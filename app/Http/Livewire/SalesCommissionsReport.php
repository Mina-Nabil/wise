<?php

namespace App\Http\Livewire;

use App\Models\Payments\CommProfile;
use App\Models\Payments\SalesComm;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class SalesCommissionsReport extends Component
{
    use WithPagination;
    use ToggleSectionLivewire;
    use AuthorizesRequests;

    public $commProfilesSection = false;
    public $profiles = [];
    public $Eprofiles = [];

    public $statusesSection = false;
    public $statuses = [];
    public $Estatuses = [];

    public $startSection = false;
    public $start_from;
    public $start_to;
    public $Estart_from;
    public $Estart_to;

    public $paymentDateSection = false;
    public $payment_date_from;
    public $payment_date_to;
    public $Epayment_date_from;
    public $Epayment_date_to;

    protected $paginationTheme = 'bootstrap';

    public function mount(): void
    {
        $this->authorize('viewAny', SalesComm::class);
    }

    public function updating($name, $value): void
    {
        if ($name !== 'page') {
            $this->resetPage();
        }
    }

    public function toggleProfiles(): void
    {
        $this->toggle($this->commProfilesSection);
        if ($this->commProfilesSection) {
            $this->Eprofiles = $this->profiles;
        }
    }

    public function setProfiles(): void
    {
        $this->profiles = array_values(array_unique($this->Eprofiles));
        $this->toggleProfiles();
    }

    public function clearProfiles(): void
    {
        $this->profiles = [];
        $this->Eprofiles = [];
    }

    public function toggleStatuses(): void
    {
        $this->toggle($this->statusesSection);
        if ($this->statusesSection) {
            $this->Estatuses = $this->statuses;
        }
    }

    public function setStatuses(): void
    {
        $this->statuses = array_values(array_unique($this->Estatuses));
        $this->toggleStatuses();
    }

    public function clearStatuses(): void
    {
        $this->statuses = [];
        $this->Estatuses = [];
    }

    public function toggleStartDate(): void
    {
        $this->toggle($this->startSection);
        if ($this->startSection) {
            $this->Estart_from = $this->start_from ? Carbon::parse($this->start_from)->toDateString() : null;
            $this->Estart_to = $this->start_to ? Carbon::parse($this->start_to)->toDateString() : null;
        }
    }

    public function setStartDates(): void
    {
        $this->start_from = $this->Estart_from ? Carbon::parse($this->Estart_from) : null;
        $this->start_to = $this->Estart_to ? Carbon::parse($this->Estart_to) : null;
        $this->toggleStartDate();
    }

    public function clearStartDates(): void
    {
        $this->start_from = null;
        $this->start_to = null;
    }

    public function togglePaymentDates(): void
    {
        $this->toggle($this->paymentDateSection);
        if ($this->paymentDateSection) {
            $this->Epayment_date_from = $this->payment_date_from ? Carbon::parse($this->payment_date_from)->toDateString() : null;
            $this->Epayment_date_to = $this->payment_date_to ? Carbon::parse($this->payment_date_to)->toDateString() : null;
        }
    }

    public function setPaymentDates(): void
    {
        $this->payment_date_from = $this->Epayment_date_from ? Carbon::parse($this->Epayment_date_from) : null;
        $this->payment_date_to = $this->Epayment_date_to ? Carbon::parse($this->Epayment_date_to) : null;
        $this->togglePaymentDates();
    }

    public function clearPaymentDates(): void
    {
        $this->payment_date_from = null;
        $this->payment_date_to = null;
    }

    public function exportReport()
    {
        return SalesComm::exportReport(
            $this->selectedProfileIds(),
            $this->asCarbon($this->start_from),
            $this->asCarbon($this->start_to),
            $this->asCarbon($this->payment_date_from),
            $this->asCarbon($this->payment_date_to),
            $this->statuses
        );
    }

    public function render()
    {
        $STATUSES = SalesComm::PYMT_STATES;

        $commissions = SalesComm::report(
            $this->selectedProfileIds(),
            $this->asCarbon($this->start_from),
            $this->asCarbon($this->start_to),
            $this->asCarbon($this->payment_date_from),
            $this->asCarbon($this->payment_date_to),
            $this->statuses
        )->paginate(50);

        $selectedProfiles = $this->selectedProfiles();
        $commProfiles = $this->commProfilesSection
            ? CommProfile::select('id', 'title')->orderBy('title')->get()
            : collect();

        return view('livewire.sales-commissions-report', [
            'STATUSES' => $STATUSES,
            'commissions' => $commissions,
            'selectedProfiles' => $selectedProfiles,
            'commProfiles' => $commProfiles,
        ]);
    }

    protected function selectedProfiles(): Collection
    {
        return collect($this->profiles)
            ->map(fn($profile) => json_decode($profile, true))
            ->filter(fn($profile) => isset($profile['id'], $profile['title']));
    }

    protected function selectedProfileIds(): array
    {
        return $this->selectedProfiles()
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    protected function asCarbon($value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        return $value instanceof Carbon ? $value : Carbon::parse($value);
    }
}


