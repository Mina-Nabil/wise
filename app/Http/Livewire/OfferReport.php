<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Offers\Offer;
use Livewire\WithPagination;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use App\Models\Insurance\Policy;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;


class OfferReport extends Component
{
    use WithPagination, ToggleSectionLivewire;

    public $dateSection;
    public $lobSection;
    public $creatorSection = false;
    public $assigneeSection = false;
    public $valueSection = false;
    public $closerSection = false;
    public $statusesSection = false;
    public $creatorName;
    public $assigneeName;
    public $closerName;

    public $from;
    public $to;
    public $statuses = [];
    public $creator_id;
    public $assignee_id;
    public $closed_by_id;
    public $line_of_business;
    public $value_from;
    public $value_to;
    public $search;

    public $Efrom;
    public $Eto;
    public $Estatuses = [];
    public $Ecreator_id;
    public $Eassignee_id;
    public $Eclosed_by_id;
    public $Eline_of_business;
    public $Evalue_from;
    public $Evalue_to;

    public function togglestatuses()
    {
        $this->toggle($this->statusesSection);
        if ($this->statusesSection) {
            $this->Estatuses = $this->statuses;
        }
    }

    public function clearstatuses()
    {
        $this->statuses = [];
    }

    public function setStatuses()
    {
        $this->statuses = $this->Estatuses;
        $this->toggle($this->statusesSection);
    }

    public function toggleCloser()
    {
        $this->toggle($this->closerSection);
        if ($this->closerSection) {
            $this->Eclosed_by_id = $this->closed_by_id;
        }
    }

    public function setCloser()
    {
        $this->closed_by_id = $this->Eclosed_by_id;
        $this->toggle($this->closerSection);
    }

    public function clearCloser()
    {
        $this->closed_by_id = null;
    }

    public function toggleAssignee()
    {
        $this->toggle($this->assigneeSection);
        if ($this->assigneeSection) {
            $this->Eassignee_id = $this->assignee_id;
        }
    }

    public function setAssignee()
    {
        $this->assignee_id = $this->Eassignee_id;
        $this->toggle($this->assigneeSection);
    }

    public function clearAssignee()
    {
        $this->assignee_id = null;
    }

    public function toggleValues()
    {
        $this->toggle($this->valueSection);
        if ($this->valueSection) {
            $this->Evalue_from = $this->value_from;
            $this->Evalue_to = $this->value_to;
        }
    }

    public function setValues()
    {
        $this->value_from = $this->Evalue_from;
        $this->value_to = $this->Evalue_to;
        $this->toggle($this->valueSection);
    }

    public function clearValues()
    {
        $this->value_from = null;
        $this->value_to = null;
    }

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
        $this->toggle($this->creatorSection);
    }

    public function clearCreator()
    {
        $this->creator_id = null;
    }

    public function toggleLob()
    {
        $this->toggle($this->lobSection);
        if ($this->lobSection) {
            $this->Eline_of_business = $this->line_of_business;
        }
    }

    public function setLob()
    {
        $this->line_of_business = $this->Eline_of_business;
        $this->toggle($this->lobSection);
    }

    public function clearLob()
    {
        $this->line_of_business = null;
    }


    public function toggleDate()
    {
        $this->toggle($this->dateSection);
        if ($this->dateSection) {
            $this->Efrom = Carbon::parse($this->from)->toDateString();
            $this->Eto = Carbon::parse($this->to)->toDateString();
        }
    }

    public function setDates()
    {
        $this->from = Carbon::parse($this->Efrom);
        $this->to = Carbon::parse($this->Eto);
        $this->toggle($this->dateSection);
    }

    public function clearDates()
    {
        $this->from = null;
        $this->to = null;
    }

    public function redirectToShowPage($id)
    {
        $this->dispatchBrowserEvent('openNewTab', ['url' => route('offers.show', $id)]);
    }

    public function exportReport()
    {
        if (Auth::user()->is_admin) {
            return Offer::exportReport(
                $this->from,
                $this->to,
                $this->statuses,
                $this->creator_id,
                $this->assignee_id,
                $this->closed_by_id,
                $this->line_of_business,
                $this->value_from,
                $this->value_to,
                $this->search
            );
        }
    }

    //reseting page while searching
    public function updatingSearchText()
    {
        $this->resetPage();
    }


    public function render()
    {
        $STATUSES = Offer::STATUSES;
        $LINES_OF_BUSINESS = Policy::LINES_OF_BUSINESS;
        $users = User::all();

        if ($this->creator_id) {
            $c = User::find($this->creator_id);
            $this->creatorName = ucwords($c->first_name) . ' ' . ucwords($c->last_name);
        }

        if ($this->assignee_id) {
            $c = User::find($this->assignee_id);
            $this->assigneeName = ucwords($c->first_name) . ' ' . ucwords($c->last_name);
        }

        if ($this->closed_by_id) {
            $c = User::find($this->closed_by_id);
            $this->closerName = ucwords($c->first_name) . ' ' . ucwords($c->last_name);
        }

        $offers = Offer::report(
            $this->from,
            $this->to,
            $this->statuses,
            $this->creator_id,
            $this->assignee_id,
            $this->closed_by_id,
            $this->line_of_business,
            $this->value_from,
            $this->value_to,
            $this->search
        )->paginate(30);
        return view('livewire.offer-report', [
            'offers' => $offers,
            'STATUSES' => $STATUSES,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS,
            'users' => $users
        ]);
    }
}
