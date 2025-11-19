<?php

namespace App\Http\Livewire;

use App\Models\Payments\CommProfile;
use App\Models\Payments\SalesComm;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session as FacadesSession;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Session\Session;

class SalesCommissionsReport extends Component
{
    use WithPagination;
    use ToggleSectionLivewire;
    use AuthorizesRequests;
    use AlertFrontEnd;

    public $commProfilesSection = false;
    public $profileIds = [];
    public $EprofileIds = [];
    public $searchProfile;

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

    public $clientPaymentDateSection = false;
    public $client_payment_date_from;
    public $client_payment_date_to;
    public $Eclient_payment_date_from;
    public $Eclient_payment_date_to;

    public $selectedCommissions = [];

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
            $this->EprofileIds = $this->profileIds;
        }
        if (! $this->commProfilesSection) {
            $this->searchProfile = null;
        }
    }

    public function setProfiles(): void
    {
        $this->profileIds = array_values(array_unique(array_map('intval', $this->EprofileIds)));
        $this->toggleProfiles();
    }

    public function clearProfiles(): void
    {
        $this->profileIds = [];
        $this->EprofileIds = [];
    }

    public function pushProfile(int $id): void
    {
        if (! in_array($id, $this->EprofileIds, true)) {
            $this->EprofileIds[] = $id;
        }
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

    public function toggleClientPaymentDates(): void
    {
        $this->toggle($this->clientPaymentDateSection);
        if ($this->clientPaymentDateSection) {
            $this->Eclient_payment_date_from = $this->client_payment_date_from ? Carbon::parse($this->client_payment_date_from)->toDateString() : null;
            $this->Eclient_payment_date_to = $this->client_payment_date_to ? Carbon::parse($this->client_payment_date_to)->toDateString() : null;
        }
    }

    public function setClientPaymentDates(): void
    {
        $this->client_payment_date_from = $this->Eclient_payment_date_from ? Carbon::parse($this->Eclient_payment_date_from) : null;
        $this->client_payment_date_to = $this->Eclient_payment_date_to ? Carbon::parse($this->Eclient_payment_date_to) : null;
        $this->toggleClientPaymentDates();
    }

    public function clearClientPaymentDates(): void
    {
        $this->client_payment_date_from = null;
        $this->client_payment_date_to = null;
        $this->Eclient_payment_date_from = null;
        $this->Eclient_payment_date_to = null;
    }

    public function exportReport()
    {
        return SalesComm::exportReport(
            $this->selectedProfileIds(),
            $this->asCarbon($this->start_from),
            $this->asCarbon($this->start_to),
            $this->asCarbon($this->payment_date_from),
            $this->asCarbon($this->payment_date_to),
            $this->statuses,
            $this->asCarbon($this->client_payment_date_from),
            $this->asCarbon($this->client_payment_date_to)
        );
    }

    public function generateCommProfilePayment()
    {
        $commissions = SalesComm::whereIn('id', $this->selectedCommissions)->get();
        $commProfileID = $commissions->first()->comm_profile_id;
        foreach ($commissions as $commission) {
            if ($commission->comm_profile_id !== $commProfileID) {
                $this->alert('error', 'All commissions must be for the same comm profile');
                return;
            }
        }
        $commProfile = CommProfile::find($commProfileID);
        if (!$commProfile) {
            $this->alert('error', 'Comm profile not found');
            return;
        }
        FacadesSession::put('commissions', $commissions);
        $this->dispatchBrowserEvent('openNewTab', ['url' => route('comm.profile.show', $commProfileID)]);
        return;
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
            $this->statuses,
            $this->asCarbon($this->client_payment_date_from),
            $this->asCarbon($this->client_payment_date_to)
        )->paginate(50);

        $selectedProfiles = $this->selectedProfiles();
        $modalSelectedProfiles = $this->commProfilesSection
            ? CommProfile::whereIn('id', $this->EprofileIds)->orderBy('title')->get()
            : collect();
        $commProfiles = $this->commProfilesSection
            ? CommProfile::select('id', 'title')
            ->when($this->EprofileIds, fn($q) => $q->whereNotIn('id', $this->EprofileIds))
            ->when($this->searchProfile, fn($q) => $q->where('title', 'like', '%' . $this->searchProfile . '%'))
            ->orderBy('title')
            ->take(10)
            ->get()
            : collect();

        return view('livewire.sales-commissions-report', [
            'STATUSES' => $STATUSES,
            'commissions' => $commissions,
            'selectedProfiles' => $selectedProfiles,
            'modalSelectedProfiles' => $modalSelectedProfiles,
            'commProfiles' => $commProfiles,
        ]);
    }

    protected function selectedProfiles(): Collection
    {
        if (empty($this->profileIds)) {
            return collect();
        }

        return CommProfile::whereIn('id', $this->profileIds)
            ->orderBy('title')
            ->get();
    }

    protected function selectedProfileIds(): array
    {
        return array_values(array_unique(array_map('intval', $this->profileIds)));
    }

    protected function asCarbon($value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        return $value instanceof Carbon ? $value : Carbon::parse($value);
    }
}
