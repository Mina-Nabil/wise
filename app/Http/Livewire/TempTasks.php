<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Tasks\TaskTempAssignee;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;

class TempTasks extends Component
{
    use WithPagination,AlertFrontEnd;

    public function accept($id){
        $taskAssigned = TaskTempAssignee::find($id);
        $t = $taskAssigned->approveRequest();
        if($t){
            $this->alert('success', 'Request Accepted!');
        }else{
            $this->alert('failed', 'Server Error');
        }
    }

    public function reject($id){
        $taskAssigned = TaskTempAssignee::find($id);
        $t = $taskAssigned->declineRequest();
        if($t){
            $this->alert('success', 'Request Rejected!');
        }else{
            $this->alert('failed', 'Server Error');
        }
    }

    public function delete($id){
        $taskAssigned = TaskTempAssignee::find($id);
        $t = $taskAssigned->deleteRequest();
        if($t){
            $this->alert('success', 'Request Deleted!');
        }else{
            $this->alert('failed', 'Server Error');
        }
    }



    public function render()
    {
        $taskTempAssignee = TaskTempAssignee::paginate(20);
        return view('livewire.temp-tasks',[
            'taskTempAssignee' => $taskTempAssignee,
        ]);
    }
}
