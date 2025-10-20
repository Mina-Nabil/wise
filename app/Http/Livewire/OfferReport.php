<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Offers\Offer;
use Livewire\WithPagination;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use App\Models\Insurance\Policy;
use App\Models\Payments\CommProfile;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;


class OfferReport extends Component
{
    use WithPagination, ToggleSectionLivewire;

    public $dateSection;
    public $expirySection;
    public $lobSection;
    public $assigneeSection = false;
    public $valueSection = false;
    public $closerSection = false;
    public $statusesSection = false;
    public $assigneeName;
    public $closerName;

    public $from;
    public $to;
    public $expiryFrom;
    public $expiryTo;
    public $statuses = [];
    public $assignee_id;
    public $closed_by_id;
    public $line_of_business;
    public $value_from;
    public $value_to;
    public $search;
    public $is_renewal;

    public $Efrom;
    public $Eto;
    public $EExpiryfrom;
    public $EExpiryto;
    public $Estatuses = [];
    public $Eassignee_id;
    public $Eclosed_by_id;
    public $Eline_of_business;
    public $Evalue_from;
    public $Evalue_to;
    public $Eis_renewal;

    public $FilteredCreators = [];
    public $selectedCreators = [];
    public $creatorSection = false;
    public $usersSearchText;

    public $commProfilesSection;
    public $Eprofiles = [];
    public $profiles = [];

    public function toggleProfiles()
    {
        $this->toggle($this->commProfilesSection);
        if ($this->commProfilesSection) {
            $this->Eprofiles = $this->profiles;
        }
    }

    public function clearProfiles()
    {
        $this->profiles = [];
    }

    public function setProfiles()
    {
        $this->profiles = $this->Eprofiles;
        $this->toggle($this->commProfilesSection);
    }

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

    public function clearrenewal()
    {
        $this->is_renewal = null;
    }
    public function toggleRenewal()
    {
        $this->toggle($this->is_renewal);
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

    public function openCreatorSection()
    {
        if (!empty($this->FilteredCreators)) {
            $this->selectedCreators = $this->FilteredCreators->pluck('id')->toArray();
        }
        $this->creatorSection = true;
    }

    public function closeCreatorSection()
    {
        $this->creatorSection = false;
        $this->selectedCreators = [];
        $this->usersSearchText = null;
    }

    public function clearCreator(){
        $this->FilteredCreators = [];
    }

    public function setCtreators(){
        if (empty($this->selectedCreators)) {
            $this->FilteredCreators = [];
        } else {
            $this->FilteredCreators = User::whereIn('id', $this->selectedCreators)->get();
        }
        $this->closeCreatorSection();
        // $this->resetPage();
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

    public function setExpiry()
    {
        $this->expiryFrom = Carbon::parse($this->EExpiryfrom);
        $this->expiryTo = Carbon::parse($this->EExpiryto);
        $this->toggle($this->expirySection);
    }

    public function clearExpiry()
    {
        $this->expiryFrom = null;
        $this->expiryTo = null;
    }

    public function toggleExpiry()
    {
        $this->toggle($this->expirySection);
        if ($this->expirySection) {
            $this->EExpiryfrom = Carbon::parse($this->expiryFrom)->toDateString();
            $this->EExpiryto = Carbon::parse($this->expiryTo)->toDateString();
        }
    }

    public function redirectToShowPage($id)
    {
        $this->dispatchBrowserEvent('openNewTab', ['url' => route('offers.show', $id)]);
    }

    public function exportReport()
    {
        if (!empty($this->FilteredCreators)) {
            $creators_ids = array_map(function($creator) {
                return $creator['id'];
            }, $this->FilteredCreators->toArray());
        } else {
            $creators_ids = [];
        }

        if (Auth::user()->is_admin) {
            return Offer::exportReport(
                $this->from,
                $this->to,
                $this->statuses,
                $creators_ids,
                $this->assignee_id,
                $this->closed_by_id,
                $this->line_of_business,
                $this->value_from,
                $this->value_to,
                $this->search,
                $this->is_renewal,
                $this->profiles,
                $this->expiryFrom,
                $this->expiryTo,
            );
        }
    }

    //reseting page while searching
    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        $STATUSES = Offer::STATUSES;
        $LINES_OF_BUSINESS = Policy::LINES_OF_BUSINESS;
        $COMM_PROFILES = CommProfile::select('title', 'id')->get();

        if ($this->assignee_id) {
            $c = User::find($this->assignee_id);
            $this->assigneeName = $c ? ucwords($c->first_name) . ' ' . ucwords($c->last_name) : $this->assignee_id;
        }

        if ($this->closed_by_id) {
            $c = User::find($this->closed_by_id);
            $this->closerName = ucwords($c->first_name) . ' ' . ucwords($c->last_name);
        }

        if (!empty($this->FilteredCreators)) {
            $creators_ids = array_map(function($creator) {
                return $creator['id'];
            }, $this->FilteredCreators->toArray());
        } else {
            $creators_ids = [];
        }

        if ($this->creatorSection) {
            $users = User::search($this->usersSearchText)->take(5)->get();
        } else {        
            $users = User::all();
        }

        $offers = Offer::report(
            $this->from,
            $this->to,
            $this->statuses,
            $creators_ids,
            $this->assignee_id,
            $this->closed_by_id,
            $this->line_of_business,
            $this->value_from,
            $this->value_to,
            $this->search,
            $this->is_renewal,
            collect($this->profiles)->map(fn($profile) => json_decode($profile, true)['id'])->all() ,
            $this->expiryFrom,
            $this->expiryTo,
        )->with('sold_policy')->paginate(30);
        return view('livewire.offer-report', [
            'offers' => $offers,
            'STATUSES' => $STATUSES,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS,
            'users' => $users,
            'types' => User::TYPES,
            'COMM_PROFILES' => $COMM_PROFILES
        ]);
    }
}
