<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Marketing\Campaign;
use App\Traits\AlertFrontEnd;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CampaignIndex extends Component
{
    use WithPagination, AlertFrontEnd, AuthorizesRequests;

    public $search;
    public $deleteThisCampaign;
    public $editCampaignId;
    
    // Form fields
    public $name;
    public $description;
    public $offers;
    public $goal;
    public $target_audience;
    public $marketing_channels;
    public $handler;
    public $budget;
    public $start_date;
    public $end_date;
    
    // Modal controls
    public $newCampaignSec = false;
    public $editCampaignSec = false;

    public function openCampaignSec()
    {
        $this->resetForm();
        $this->newCampaignSec = true;
    }

    public function closeCampaignSec()
    {
        $this->newCampaignSec = false;
        $this->resetForm();
    }

    public function openEditCampaign($id)
    {
        $campaign = Campaign::find($id);
        if ($campaign) {
            $this->authorize('update', $campaign);
            
            $this->editCampaignId = $id;
            $this->name = $campaign->name;
            $this->description = $campaign->description;
            $this->offers = $campaign->offers;
            $this->goal = $campaign->goal;
            $this->target_audience = $campaign->target_audience;
            $this->marketing_channels = $campaign->marketing_channels;
            $this->handler = $campaign->handler;
            $this->budget = $campaign->budget;
            $this->start_date = $campaign->start_date ? $campaign->start_date->format('Y-m-d') : null;
            $this->end_date = $campaign->end_date ? $campaign->end_date->format('Y-m-d') : null;
            $this->editCampaignSec = true;
        }
    }

    public function closeEditCampaign()
    {
        $this->editCampaignSec = false;
        $this->resetForm();
    }

    public function openDeleteCampaign($id)
    {
        $campaign = Campaign::find($id);
        if ($campaign) {
            $this->authorize('delete', $campaign);
            $this->deleteThisCampaign = $id;
        }
    }

    public function closeDeleteCampaign()
    {
        $this->deleteThisCampaign = null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->editCampaignId = null;
        $this->name = null;
        $this->description = null;
        $this->offers = null;
        $this->goal = null;
        $this->target_audience = null;
        $this->marketing_channels = null;
        $this->handler = null;
        $this->budget = null;
        $this->start_date = null;
        $this->end_date = null;
    }

    public function add()
    {
        $this->authorize('create', Campaign::class);

        $this->validate([
            'name' => 'required|string|max:255|unique:campaigns,name',
            'description' => 'nullable|string',
            'offers' => 'nullable|string',
            'goal' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'marketing_channels' => 'nullable|string',
            'handler' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $campaign = Campaign::create([
            'name' => $this->name,
            'description' => $this->description,
            'offers' => $this->offers,
            'goal' => $this->goal,
            'target_audience' => $this->target_audience,
            'marketing_channels' => $this->marketing_channels,
            'handler' => $this->handler,
            'budget' => $this->budget,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        if ($campaign) {
            $this->alert('success', 'Campaign created successfully');
            $this->closeCampaignSec();
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function update()
    {
        $campaign = Campaign::find($this->editCampaignId);
        if (!$campaign) {
            $this->alert('failed', 'Campaign not found');
            return;
        }

        $this->authorize('update', $campaign);

        $this->validate([
            'name' => 'required|string|max:255|unique:campaigns,name,' . $this->editCampaignId,
            'description' => 'nullable|string',
            'offers' => 'nullable|string',
            'goal' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'marketing_channels' => 'nullable|string',
            'handler' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $campaign->update([
            'name' => $this->name,
            'description' => $this->description,
            'offers' => $this->offers,
            'goal' => $this->goal,
            'target_audience' => $this->target_audience,
            'marketing_channels' => $this->marketing_channels,
            'handler' => $this->handler,
            'budget' => $this->budget,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $this->alert('success', 'Campaign updated successfully');
        $this->closeEditCampaign();
    }

    public function delete()
    {
        $campaign = Campaign::find($this->deleteThisCampaign);
        if (!$campaign) {
            $this->alert('failed', 'Campaign not found');
            return;
        }

        $this->authorize('delete', $campaign);

        // Check if campaign has customers or corporates attached
        if ($campaign->customers()->count() > 0 || $campaign->corporates()->count() > 0) {
            $this->alert('failed', 'Cannot delete campaign. It has customers or corporates attached.');
            $this->closeDeleteCampaign();
            return;
        }

        $campaign->delete();
        $this->alert('success', 'Campaign deleted successfully');
        $this->closeDeleteCampaign();
    }

    public function render()
    {
        $campaigns = Campaign::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('target_audience', 'like', '%' . $this->search . '%')
                  ->orWhere('handler', 'like', '%' . $this->search . '%');
        })->paginate(12);

        return view('livewire.campaign-index', [
            'campaigns' => $campaigns,
        ])->layout('layouts.app');
    }
}
