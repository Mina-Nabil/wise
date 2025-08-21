<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Business\SoldPolicy;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;

class ExpSoldPolicyIndex extends Component
{
    use WithPagination,AlertFrontEnd;

    public $search;

    public function render()
    {
        $this->authorize('viewReports', SoldPolicy::class);
        $soldPolicies = SoldPolicy::userData(searchText:$this->search,is_expiring:true)->paginate(20);
        return view('livewire.exp-sold-policy-index',[
            'soldPolicies' => $soldPolicies,
            ]);
    }
}
