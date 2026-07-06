<?php

namespace App\Http\Livewire;

use App\Models\Insurance\Policy;
use App\Models\Offers\Offer;
use App\Models\Users\User;
use App\Traits\AlertFrontEnd;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OfferBulkAssign extends Component
{
    use WithPagination, AlertFrontEnd;

    public $search;
    public $filteredStatus = ['active'];
    public $lineOfBusiness = '';
    public $isRenewalCB = 'all';

    //date filters
    public $dateRange;
    public $startDate;
    public $endDate;

    //selection
    public $selectedOffers = [];
    public $assignTo; //selected user id

    //errors from the latest bulk assign call
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
            $this->selectedOffers = array_values(array_unique(array_map('intval', $preselected)));
        }
    }

    public function filterByStatus($status)
    {
        $this->resetPage();
        $this->filteredStatus = [$status];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLineOfBusiness()
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
        $this->selectedOffers = [];
    }

    public function clearLastErrors()
    {
        $this->lastErrors = [];
    }

    public function removeSelectedOffer($id)
    {
        $this->selectedOffers = array_values(array_filter($this->selectedOffers, fn ($v) => (int) $v !== (int) $id));
    }

    public function redirectToShowPage($id)
    {
        $this->dispatchBrowserEvent('openNewTab', ['url' => route('offers.show', $id)]);
    }

    public function assignSelected()
    {
        abort_unless(Auth::user()?->is_admin, 403);

        $this->validate([
            'assignTo' => 'required|integer|exists:users,id',
            'selectedOffers' => 'required|array|min:1',
            'selectedOffers.*' => 'integer|exists:offers,id',
        ], [
            'assignTo.required' => 'Please select a user to assign to.',
            'selectedOffers.required' => 'Please select at least one offer.',
            'selectedOffers.min' => 'Please select at least one offer.',
        ]);

        $offers = Offer::whereIn('id', $this->selectedOffers)->get();
        $assignee = User::find($this->assignTo);

        $success = 0;
        $failures = [];
        foreach ($offers as $offer) {
            $res = $offer->assignTo($this->assignTo);
            if ($res === true) {
                $success++;
            } else {
                $failures[] = [
                    'offer_id' => $offer->id,
                    'reason' => is_string($res) ? $res : 'Server error',
                ];
            }
        }

        $this->lastErrors = [
            'at' => now()->format('Y-m-d H:i'),
            'assignee' => $assignee ? trim($assignee->first_name . ' ' . $assignee->last_name) : null,
            'total' => $offers->count(),
            'success' => $success,
            'failures' => $failures,
        ];

        if ($success) {
            $this->alert('success', "$success offer(s) assigned successfully");
        }
        if (count($failures)) {
            $this->alert('warning', count($failures) . ' offer(s) could not be assigned');
        }

        $this->selectedOffers = [];
        $this->assignTo = null;
    }

    public function render()
    {
        $LINES_OF_BUSINESS = Policy::LINES_OF_BUSINESS;
        $statuses = Offer::STATUSES;
        $users = User::active()->get();

        $offers = Offer::userData($this->search)
            ->when($this->isRenewalCB, function ($q, $v) {
                if ($v === 'isRenewal') {
                    return $q->byRenewal(1);
                } elseif ($v === 'notRenewal') {
                    return $q->byRenewal(0);
                }
            })
            ->when($this->filteredStatus, function ($query) {
                return $query->byStates($this->filteredStatus);
            })
            ->when($this->filteredStatus == null, function ($query) {
                return $query->byStates(['active']);
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
                return $query->fromTo($startDate, $endDate);
            })
            ->when($this->lineOfBusiness, function ($query) {
                return $query->where('offers.type', $this->lineOfBusiness);
            })
            ->orderBy('due', 'asc')
            ->with('selected_option.policy', 'comm_profiles')
            ->paginate(10);

        $selectedOffersData = count($this->selectedOffers)
            ? Offer::whereIn('id', $this->selectedOffers)->with('client')->get()
            : collect();

        return view('livewire.offer-bulk-assign', [
            'offers' => $offers,
            'statuses' => $statuses,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS,
            'filteredStatus' => $this->filteredStatus,
            'users' => $users,
            'selectedOffersData' => $selectedOffersData,
        ]);
    }
}
