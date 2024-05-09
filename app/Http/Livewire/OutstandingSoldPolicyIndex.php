<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Business\SoldPolicy;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;

class OutstandingSoldPolicyIndex extends Component
{

    use WithPagination,AlertFrontEnd;

    public $search;

    public function render()
    {
        $soldPolicies = SoldPolicy::userData(searchText:$this->search,is_client_outstanding:true)->paginate(20);
        return view('livewire.outstanding-sold-policy-index',[
            'soldPolicies' => $soldPolicies,
            ]);
    }
}
