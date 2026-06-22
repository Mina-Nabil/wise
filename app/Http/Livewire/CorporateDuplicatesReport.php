<?php

namespace App\Http\Livewire;

use App\Models\Corporates\Corporate;
use App\Traits\AlertFrontEnd;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CorporateDuplicatesReport extends Component
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
        $groups = Corporate::duplicateGroups();

        $details = [];
        foreach ($groups as $group) {
            $details[] = [
                'commercial_record' => $group->commercial_record,
                'cnt' => $group->cnt,
                'corporates' => Corporate::where('commercial_record', $group->commercial_record)
                    ->with('owner')
                    ->withCount(['offers', 'soldpolicies'])
                    ->orderBy('id')
                    ->get(),
            ];
        }

        return view('livewire.corporate-duplicates-report', [
            'groups' => $groups,
            'details' => $details,
        ]);
    }
}
