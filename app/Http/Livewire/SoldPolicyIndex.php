<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Business\SoldPolicy;
use Livewire\WithPagination;

class SoldPolicyIndex extends Component
{
    use WithPagination;

    public $search;

    public function render()
    {
        $soldPolicies = SoldPolicy::userData(searchText:$this->search)->paginate(20);
        return view('livewire.sold-policy-index',[
            'soldPolicies' => $soldPolicies,
            ]
        );
    }
}
