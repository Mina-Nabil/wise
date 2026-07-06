<?php

namespace App\Http\Livewire;

use App\Models\Business\SoldPolicy;
use App\Models\Offers\OfferOption;
use App\Models\Users\User;
use App\Traits\AlertFrontEnd;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SoldPolicyBulkShow extends Component
{
    use WithPagination, AlertFrontEnd;

    public $search;
    public $isPaidCB = 'all';

    //date filters
    public $dateRange;
    public $startDate;
    public $endDate;

    //selection
    public $selectedPolicies = [];
    public $showToUser; //selected user id

    //errors from the latest bulk show call
    public $lastErrors = [];

    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function mount()
    {
        abort_unless(Auth::user()?->is_admin, 403);
        $this->startDate = null;
        $this->endDate = null;
        $this->dateRange = $this->startDate && $this->endDate ? $this->startDate . ' to ' . $this->endDate : 'N/A';

        $preselected = request()->query('selected', []);
        if (is_array($preselected)) {
            $this->selectedPolicies = array_values(array_unique(array_map('intval', $preselected)));
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedDateRange()
    {
        if (strpos($this->dateRange, 'to') !== false) {
            [$this->startDate, $this->endDate] = explode(' to ', $this->dateRange);
        }
    }

    public function clearSelection()
    {
        $this->selectedPolicies = [];
    }

    public function clearLastErrors()
    {
        $this->lastErrors = [];
    }

    public function removeSelectedPolicy($id)
    {
        $this->selectedPolicies = array_values(array_filter($this->selectedPolicies, fn ($v) => (int) $v !== (int) $id));
    }

    public function showSelected()
    {
        abort_unless(Auth::user()?->is_admin, 403);

        $this->validate([
            'showToUser' => 'required|integer|exists:users,id',
            'selectedPolicies' => 'required|array|min:1',
            'selectedPolicies.*' => 'integer|exists:sold_policies,id',
        ], [
            'showToUser.required' => 'Please select a user to show the policies to.',
            'selectedPolicies.required' => 'Please select at least one sold policy.',
            'selectedPolicies.min' => 'Please select at least one sold policy.',
        ]);

        $policies = SoldPolicy::whereIn('id', $this->selectedPolicies)->get();
        $user = User::find($this->showToUser);

        $success = 0;
        $failures = [];
        foreach ($policies as $policy) {
            // add the user to the watchers list without detaching existing watchers
            $res = $policy->setWatchers([$this->showToUser], false);
            if ($res === true) {
                $success++;
            } else {
                $failures[] = [
                    'policy_id' => $policy->id,
                    'policy_number' => $policy->policy_number,
                    'reason' => is_string($res) ? $res : 'Server error',
                ];
            }
        }

        $this->lastErrors = [
            'at' => now()->format('Y-m-d H:i'),
            'user' => $user ? trim($user->first_name . ' ' . $user->last_name) : null,
            'total' => $policies->count(),
            'success' => $success,
            'failures' => $failures,
        ];

        if ($success) {
            $this->alert('success', "$success policy(ies) shared with the user successfully");
        }
        if (count($failures)) {
            $this->alert('warning', count($failures) . ' policy(ies) could not be shared');
        }

        $this->selectedPolicies = [];
        $this->showToUser = null;
    }

    public function render()
    {
        $users = User::active()->get();

        $soldPolicies = SoldPolicy::userData(searchText: $this->search)
            ->with('offer', 'creator')
            ->when($this->isPaidCB, function ($q, $v) {
                if ($v === 'isPaid') return $q->byPaid(1);
                elseif ($v === 'notPaid') return $q->byPaid(0);
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
                return $query->fromTo($startDate, $endDate);
            })
            ->simplePaginate(10);

        $selectedPoliciesData = count($this->selectedPolicies)
            ? SoldPolicy::whereIn('id', $this->selectedPolicies)->with('client')->get()
            : collect();

        return view('livewire.sold-policy-bulk-show', [
            'soldPolicies' => $soldPolicies,
            'users' => $users,
            'selectedPoliciesData' => $selectedPoliciesData,
        ]);
    }
}
