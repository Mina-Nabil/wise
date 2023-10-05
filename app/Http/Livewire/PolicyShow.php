<?php

namespace App\Http\Livewire;

use App\Models\Insurance\Policy;

use Livewire\Component;

class PolicyShow extends Component
{
    public $policyId;

    public function render()
    {
        $policy = Policy::find($this->policyId);
        // dd($policy);

        return view('livewire.policy-show', [
            'policy' => $policy,
        ]);
    }
}
