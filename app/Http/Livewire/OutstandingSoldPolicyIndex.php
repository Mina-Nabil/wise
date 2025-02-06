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
    public $outstandingType = 'all';


    public function render()
    {
        if($this->outstandingType === 'all'){
            $client_outstanding = true;
            $commission_outstanding = true;
        }elseif($this->outstandingType === 'policy'){
            $client_outstanding = true;
            $commission_outstanding = false;
        }
        elseif($this->outstandingType === 'commission'){
            $client_outstanding = false;
            $commission_outstanding = true;
        }

        $soldPolicies = SoldPolicy::userData(searchText:$this->search,is_commission_outstanding:$commission_outstanding , is_client_outstanding:$client_outstanding)
        ->with('last_company_comm_payment')
        ->paginate(20);

        return view('livewire.outstanding-sold-policy-index',[
            'soldPolicies' => $soldPolicies,
            ]);
    }
}
