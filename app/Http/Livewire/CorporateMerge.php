<?php

namespace App\Http\Livewire;

use App\Models\Corporates\Corporate;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use App\Traits\AlertFrontEnd;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CorporateMerge extends Component
{
    use AlertFrontEnd, AuthorizesRequests;

    public $candidateIds = [];   // ids of the profiles available to merge (max 3)
    public $selectedIds = [];    // ids the admin chose to merge together
    public $survivorId;          // the profile that remains
    public $ownerId;             // who can access the survivor after merge
    public $ownerOptions = [];   // [['id' => .., 'name' => ..], ..]
    public $confirmingMerge = false;
    public $errorMessage = '';   // shown inline so failures are never silent

    public function mount($ids = null)
    {
        $this->authorize('merge', Corporate::class);

        $this->candidateIds = collect(explode(',', (string) $ids))
            ->map(fn ($v) => (int) trim($v))
            ->filter()
            ->unique()
            ->take(3)
            ->values()
            ->all();

        $candidates = Corporate::whereIn('id', $this->candidateIds)->get();

        if ($candidates->count() < 2) {
            abort(404, 'Need at least two profiles to merge.');
        }

        $this->candidateIds = $candidates->pluck('id')->all();
        $this->selectedIds = $candidates->pluck('id')->map(fn ($id) => (string) $id)->all();
        $this->survivorId = ''; // no default master — the admin picks which profile to keep
        $this->ownerId = (string) $candidates->first()->owner_id;

        $userIds = $candidates->pluck('owner_id')
            ->merge($candidates->pluck('creator_id'))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $this->ownerOptions = User::whereIn('id', $userIds)->get()
            ->map(fn ($u) => ['id' => $u->id, 'name' => $u->username])
            ->all();
    }

    // Choosing the master from the radio. A kept profile is always part of the merge.
    public function setSurvivor($id)
    {
        $this->survivorId = (string) $id;
        if ((string) $id !== '' && !in_array((string) $id, array_map('strval', $this->selectedIds), true)) {
            $this->selectedIds[] = (string) $id;
        }
    }

    // If the master gets un-included, drop the master choice so they pick again.
    public function updatedSelectedIds()
    {
        if ($this->survivorId !== '' && $this->survivorId !== null
            && !in_array((string) $this->survivorId, array_map('strval', $this->selectedIds), true)) {
            $this->survivorId = '';
        }
    }

    public function confirmMerge()
    {
        $this->errorMessage = '';
        if ($this->validateSelection() !== null) {
            $this->confirmingMerge = true;
        }
    }

    public function cancelMerge()
    {
        $this->confirmingMerge = false;
    }

    public function merge()
    {
        $this->errorMessage = '';
        $this->authorize('merge', Corporate::class);

        $data = $this->validateSelection();
        if ($data === null) {
            $this->confirmingMerge = false;
            return;
        }

        $corporate = Corporate::find($data['survivor']);
        if (!$corporate) {
            $this->fail('The profile to keep could not be found.');
            return;
        }

        try {
            if ($corporate->mergeCorporates($data['sources'], (int) $this->ownerId)) {
                return redirect()->route('reports.corporate-duplicates', ['merged' => 1]);
            }
            $this->fail('Unable to merge — you may not have permission, or the profiles were not found.');
        } catch (\Throwable $e) {
            report($e);
            AppLog::error('Corporate merge failed', desc: $e->getMessage(), loggable: $corporate);
            $this->fail('Merge failed: ' . $e->getMessage());
        }
    }

    /**
     * Validate the current selection. Returns ['survivor' => int, 'sources' => int[]]
     * when valid, or null (after recording the reason) when invalid.
     */
    protected function validateSelection(): ?array
    {
        $selected = collect($this->selectedIds)->map(fn ($v) => (int) $v)->filter()->unique()->values();
        $survivor = (int) $this->survivorId;

        if ($selected->count() < 2) {
            $this->fail('Select at least two profiles to merge.');
            return null;
        }
        if ($survivor === 0) {
            $this->fail('Choose which profile to keep (master).');
            return null;
        }
        if (!$selected->contains($survivor)) {
            $this->fail('The profile you keep must be one of the selected profiles.');
            return null;
        }
        if (!$this->ownerId) {
            $this->fail('Please choose who can access the merged profile.');
            return null;
        }

        return [
            'survivor' => $survivor,
            'sources' => $selected->reject(fn ($id) => $id === $survivor)->values()->all(),
        ];
    }

    // Surface a failure both inline (reliable) and via toast.
    protected function fail(string $message): void
    {
        $this->confirmingMerge = false;
        $this->errorMessage = $message;
        $this->alert('failed', $message);
    }

    public function render()
    {
        $candidates = Corporate::whereIn('id', $this->candidateIds)
            ->with('owner')
            ->withCount(['offers', 'soldpolicies', 'phones', 'addresses', 'contacts'])
            ->orderBy('id')
            ->get();

        return view('livewire.corporate-merge', [
            'candidates' => $candidates,
        ]);
    }
}
