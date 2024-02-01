<?php

namespace App\Http\Livewire;

use App\Models\Base\SlaRecord;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;

class SlaRecordIndex extends Component
{
    use WithPagination,AlertFrontEnd;
    
    public $recordInfo = null;

    public function closeRecordInfo(){
        $this->recordInfo = null;
    }

    public function showRecordsInfo($id){
        $this->recordInfo = SlaRecord::find($id);
    }

    public function setIgnored($id){
        $res = SlaRecord::find($id)->ignoreRecord();
        if($res){
            $this->alert('success' , 'record ignored');
            $this->recordInfo = null;
        }else{
            $this->alert('failed' , 'server error');
        }
    }

    
    public function render()
    {
        $records = SlaRecord::paginate(20);
        return view('livewire.sla-record-index',[
            'records' => $records
        ]);
    }
}
