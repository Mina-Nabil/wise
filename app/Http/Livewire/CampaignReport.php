<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Marketing\Campaign;
use Livewire\WithPagination;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;

class CampaignReport extends Component
{
    use WithPagination, ToggleSectionLivewire;

    public $startDateSection;
    public $endDateSection;
    public $budgetSection = false;
    public $handlerSection = false;

    public $start_from;
    public $start_to;
    public $end_from;
    public $end_to;
    public $budget_from;
    public $budget_to;
    public $handler_id;
    public $search;

    public $Estart_from;
    public $Estart_to;
    public $Eend_from;
    public $Eend_to;
    public $Ebudget_from;
    public $Ebudget_to;
    public $Ehandler_id;

    public $handlerName;

    public function toggleHandler()
    {
        $this->toggle($this->handlerSection);
        if ($this->handlerSection) {
            $this->Ehandler_id = $this->handler_id;
        }
    }

    public function setHandler()
    {
        $this->handler_id = $this->Ehandler_id;
        $this->toggle($this->handlerSection);
    }

    public function clearHandler()
    {
        $this->handler_id = null;
    }

    public function toggleBudget()
    {
        $this->toggle($this->budgetSection);
        if ($this->budgetSection) {
            $this->Ebudget_from = $this->budget_from;
            $this->Ebudget_to = $this->budget_to;
        }
    }

    public function setBudget()
    {
        $this->budget_from = $this->Ebudget_from;
        $this->budget_to = $this->Ebudget_to;
        $this->toggle($this->budgetSection);
    }

    public function clearBudget()
    {
        $this->budget_from = null;
        $this->budget_to = null;
    }

    public function toggleStartDate()
    {
        $this->toggle($this->startDateSection);
        if ($this->startDateSection) {
            $this->Estart_from = Carbon::parse($this->start_from)->toDateString();
            $this->Estart_to = Carbon::parse($this->start_to)->toDateString();
        }
    }

    public function setStartDates()
    {
        $this->start_from = Carbon::parse($this->Estart_from);
        $this->start_to = Carbon::parse($this->Estart_to);
        $this->toggle($this->startDateSection);
    }

    public function clearStartDates()
    {
        $this->start_from = null;
        $this->start_to = null;
    }

    public function toggleEndDate()
    {
        $this->toggle($this->endDateSection);
        if ($this->endDateSection) {
            $this->Eend_from = Carbon::parse($this->end_from)->toDateString();
            $this->Eend_to = Carbon::parse($this->Eend_to)->toDateString();
        }
    }

    public function setEndDates()
    {
        $this->end_from = Carbon::parse($this->Eend_from);
        $this->end_to = Carbon::parse($this->Eend_to);
        $this->toggle($this->endDateSection);
    }

    public function clearEndDates()
    {
        $this->end_from = null;
        $this->end_to = null;
    }

    public function exportReport()
    {
        if (Auth::user()->is_admin) {
            return Campaign::exportReport(
                $this->start_from,
                $this->start_to,
                $this->end_from,
                $this->end_to,
                $this->budget_from,
                $this->budget_to,
                $this->handler_id,
                $this->search
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
        if ($this->handler_id) {
            $c = User::find($this->handler_id);
            $this->handlerName = $c ? ucwords($c->first_name) . ' ' . ucwords($c->last_name) : $this->handler_id;
        }

        $users = User::all();

        $campaigns = Campaign::query()
            ->when($this->start_from, function ($query) {
                $query->where('start_date', '>=', $this->start_from);
            })
            ->when($this->start_to, function ($query) {
                $query->where('start_date', '<=', $this->start_to);
            })
            ->when($this->end_from, function ($query) {
                $query->where('end_date', '>=', $this->end_from);
            })
            ->when($this->end_to, function ($query) {
                $query->where('end_date', '<=', $this->end_to);
            })
            ->when($this->budget_from, function ($query) {
                $query->where('budget', '>=', $this->budget_from);
            })
            ->when($this->budget_to, function ($query) {
                $query->where('budget', '<=', $this->budget_to);
            })
            ->when($this->handler_id, function ($query) {
                $query->where('handler', $this->handler_id);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('marketing_channels', 'like', '%' . $this->search . '%')
                      ->orWhere('target_audience', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(30);

        return view('livewire.campaign-report', [
            'campaigns' => $campaigns,
            'users' => $users,
        ]);
    }
}
