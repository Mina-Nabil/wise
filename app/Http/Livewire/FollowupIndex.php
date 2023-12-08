<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customers\Followup;
use App\Traits\AlertFrontEnd;
use Livewire\WithPagination;
use App\Traits\ToggleSectionLivewire;

class FollowupIndex extends Component
{
    use WithPagination, AlertFrontEnd, ToggleSectionLivewire;

    public $search;

    public function redirectToShowPage($id)
    {
        $followup = Followup::findOrFail($id);
        return redirect(route($followup->called_type.'s.show',  $followup->called_id));
    }

    public function render()
    {
        $followups = Followup::paginate(10);
        return view('livewire.followup-index',[
            'followups' => $followups,
        ]);
    }
}
