<?php

namespace App\Http\Livewire;

use App\Models\Base\SlaRecord;
use Livewire\Component;
use Livewire\WithPagination;

class SlaRecordIndex extends Component
{
    use WithPagination;
    
    public function render()
    {
        $records = SlaRecord::paginate(20);
        return view('livewire.sla-record-index',[
            'records' => $records
        ]);
    }
}
