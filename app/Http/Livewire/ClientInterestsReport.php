<?php

namespace App\Http\Livewire;

use App\Models\Customers\Customer;
use App\Models\Insurance\Policy;
use App\Models\Users\User;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ClientInterestsReport extends Component
{
    use WithPagination, ToggleSectionLivewire;
    public $searchText;

    // START: line of businesses filter
    public $lobsSection = false;
    public $lobs = [];
    public $Elobs = [];

    public function togglelobs()
    {
        $this->toggle($this->lobsSection);
        if ($this->lobsSection) {
            $this->Elobs = $this->lobs;
        }
    }

    public function clearlobs()
    {
        $this->lobs = [];
    }

    public function setLobs()
    {
        $this->lobs = $this->Elobs;
        $this->toggle($this->lobsSection);
    }
    // END: line of businesses filter

    // START: creator filter
    public $creatorSection = false;
    public $creatorName;
    public $creator_id;
    public $Ecreator_id;
    
    public function toggleCreator()
    {
        $this->toggle($this->creatorSection);
        if ($this->creatorSection) {
            $this->Ecreator_id = $this->creator_id;
        }
    }

    public function setCreator()
    {
        $this->creator_id = $this->Ecreator_id;
        $this->creatorName = User::find($this->creator_id)->full_name;
        $this->toggle($this->creatorSection);
    }

    public function clearCreator()
    {
        $this->creator_id = null;
        $this->creatorName = null;
    }
    // END: creator filter

    // START: creation date filter
    public $creationSection = false;
    public $creation_from;
    public $creation_to;
    public $Ecreation_from;
    public $Ecreation_to;

    public function toggleCreationDate()
    {
        $this->toggle($this->creationSection);
        if ($this->creationSection) {
            $this->Ecreation_from = Carbon::parse($this->creation_from)->toDateString();
            $this->Ecreation_to = Carbon::parse($this->creation_to)->toDateString();
        }
    }

    public function setCreationDates()
    {
        $this->creation_from = Carbon::parse($this->Ecreation_from);
        $this->creation_to = Carbon::parse($this->Ecreation_to);
        $this->toggle($this->creationSection);
    }

    public function clearCreationDates()
    {
        $this->creation_from = null;
        $this->creation_to = null;
    }
    // END: creation date filter

    // START: welcomed filter
    public $isWelcomed;
    public function toggleIsWelcomed()
    {
        $this->toggle($this->isWelcomed);
    }

    public function clearWelcomed()
    {
        $this->isWelcomed = null;
    }
    // END: welcomed filter


    public function render()
    {
        $customers = Customer::InterestReport($this->creation_from,$this->creation_to,$this->lobs,$this->creator_id,$this->isWelcomed)->paginate(50);
        $users = User::all();
        $all_lobs = Policy::LINES_OF_BUSINESS;
        return view('livewire.client-interests-report',[
            'all_lobs' => $all_lobs,
            'users' => $users,
            'customers' => $customers
        ]);
    }
}
