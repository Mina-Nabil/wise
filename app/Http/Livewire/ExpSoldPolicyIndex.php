<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\models\Business\SoldPolicy;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExpSoldPolicyIndex extends Component
{
    use WithPagination,AlertFrontEnd, AuthorizesRequests;

    public $search;
    public $isCancelledFilter = null;

    public function toggleIsCancelled()
    {
        if ($this->isCancelledFilter === null) {
            $this->isCancelledFilter = true;
        } elseif ($this->isCancelledFilter === true) {
            $this->isCancelledFilter = false;
        } else {
            $this->isCancelledFilter = null;
        }
    }

    public function clearIsCancelled()
    {
        $this->isCancelledFilter = null;
    }

    public function render()
    {
        $this->authorize('viewReports', SoldPolicy::class);
        $query = SoldPolicy::userData(searchText:$this->search,is_expiring:true);
        
        if ($this->isCancelledFilter === true) {
            $query->cancelled();
        } elseif ($this->isCancelledFilter === false) {
            $query->notCancelled();
        }
        
        $soldPolicies = $query->paginate(20);
        return view('livewire.exp-sold-policy-index',[
            'soldPolicies' => $soldPolicies,
            ]);
    }
}
