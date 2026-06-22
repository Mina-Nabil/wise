<?php

namespace App\Http\Livewire;

use App\Models\Customers\Customer;
use App\Traits\AlertFrontEnd;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerDuplicatesReport extends Component
{
    use WithPagination, AlertFrontEnd;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        if (!Auth::user()?->is_admin) {
            abort(403);
        }
    }

    public function render()
    {
        $groups = Customer::duplicateGroups();

        $details = [];
        foreach ($groups as $group) {
            $details[] = [
                'id_type' => $group->id_type,
                'id_number' => $group->id_number,
                'cnt' => $group->cnt,
                'customers' => Customer::where('id_type', $group->id_type)
                    ->where('id_number', $group->id_number)
                    ->with('owner')
                    ->withCount(['offers', 'soldpolicies'])
                    ->orderBy('id')
                    ->get(),
            ];
        }

        return view('livewire.customer-duplicates-report', [
            'groups' => $groups,
            'details' => $details,
        ]);
    }
}
