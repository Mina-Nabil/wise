<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Insurance\Policy;
use Livewire\WithPagination;

class PolicyIndex extends Component
{
    use WithPagination;

    public $search;
    public $deleteThisPolicy;

    public function openDeletePolicy($id){
        $this->deleteThisPolicy = $id;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $policies = Policy::tableData()
                        ->SearchBy($this->search)
                        ->paginate(12);


        return view('livewire.policy-index',[
            'policies' => $policies,
    ]);
    }
}
