<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Sales Commissions Totals
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <div class="dropdown relative ">
                <button class="btn inline-flex justify-center btn-dark items-center cursor-default relative !pr-14"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Add filter
                    <span
                        class="cursor-pointer absolute ltr:border-l rtl:border-r border-slate-100 h-full ltr:right-0 rtl:left-0 px-2 flex items-center justify-center leading-none">
                        <iconify-icon class="leading-none text-xl" icon="ic:round-keyboard-arrow-down"></iconify-icon>
                    </span>
                </button>
                <ul
                    class="dropdown-menu min-w-max absolute text-sm text-slate-700 hidden bg-white shadow
                        z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none dark:text-white dark:bg-slate-700">
                    <li wire:click="toggleProfiles">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 cursor-pointer">
                            Commission Profiles</span>
                    </li>
                    <li wire:click="toggleStartDate">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 cursor-pointer">
                            Policy Start (From-To)</span>
                    </li>
                    <li wire:click="togglePaymentDates">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 cursor-pointer">
                            Payment Date (From-To)</span>
                    </li>
                    <li wire:click="toggleClientPaymentDates">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 cursor-pointer">
                            Client Payment Date (From-To)</span>
                    </li>
                    <li wire:click="toggleStatuses">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 cursor-pointer">
                            Status</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card-body px-6 pb-6">
        <div class="-mx-6">
            <div class="inline-block min-w-full align-middle">
                <div class="card">
                    <header class="card-header cust-card-header noborder" style="display: block;">
                        @if ($selectedProfiles->count())
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="toggleProfiles">
                                    Profiles:
                                    {{ $selectedProfiles->pluck('title')->join(', ') }}
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearProfiles">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif

                        @if ($start_from || $start_to)
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="toggleStartDate">
                                    {{ $start_from ? 'Policy Start From: ' . \Carbon\Carbon::parse($start_from)->format('d/m/Y') : '' }}
                                    {{ $start_from && $start_to ? '-' : '' }}
                                    {{ $start_to ? 'Policy Start To: ' . \Carbon\Carbon::parse($start_to)->format('d/m/Y') : '' }}
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearStartDates">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif

                        @if ($payment_date_from || $payment_date_to)
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="togglePaymentDates">
                                    {{ $payment_date_from ? 'Payment From: ' . \Carbon\Carbon::parse($payment_date_from)->format('d/m/Y') : '' }}
                                    {{ $payment_date_from && $payment_date_to ? '-' : '' }}
                                    {{ $payment_date_to ? 'Payment To: ' . \Carbon\Carbon::parse($payment_date_to)->format('d/m/Y') : '' }}
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearPaymentDates">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif

                        @if ($client_payment_date_from || $client_payment_date_to)
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="toggleClientPaymentDates">
                                    {{ $client_payment_date_from ? 'Client Payment From: ' . \Carbon\Carbon::parse($client_payment_date_from)->format('d/m/Y') : '' }}
                                    {{ $client_payment_date_from && $client_payment_date_to ? '-' : '' }}
                                    {{ $client_payment_date_to ? 'Client Payment To: ' . \Carbon\Carbon::parse($client_payment_date_to)->format('d/m/Y') : '' }}
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearClientPaymentDates">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif

                        @if ($statuses)
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="toggleStatuses">
                                    Statuses:
                                    {{ collect($statuses)->map(fn($status) => ucwords(str_replace('_', ' ', $status)))->join(', ') }}
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearStatuses">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif
                    </header>

                    <div class="card-body px-6 pb-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="table-th">Commission Profile</th>
                                        <th scope="col" class="table-th text-right">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    @forelse ($totals as $row)
                                        <tr>
                                            <td class="table-td">
                                                {{ $row->profile_title ?? 'N/A' }}
                                            </td>
                                            <td class="table-td text-right">
                                                {{ number_format((float) $row->total_amount, 2, '.', ',') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="table-td text-center">
                                                No sales commission totals found for the selected filters.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pt-6">
                            {{ $totals->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($commProfilesSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white capitalize">
                                Commission Profiles
                            </h3>
                            <button wire:click="toggleProfiles" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                @foreach ($modalSelectedProfiles as $profile)
                                    <span class="badge bg-slate-900 text-white capitalize rounded-3xl">
                                        {{ $profile->title }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="from-group">
                                <label for="searchProfileTotals" class="form-label">Search Commission Profile</label>
                                <input id="searchProfileTotals" name="searchProfileTotals" type="text"
                                    class="form-control mt-2 w-full" wire:model="searchProfile">
                            </div>

                            <div class="max-h-[45vh] overflow-y-auto">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>
                                            <th scope="col" class="table-th">Title</th>
                                            <th scope="col" class="table-th">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                        @forelse ($commProfiles as $profile)
                                            @if (!in_array($profile->id, $EprofileIds ?? [], true))
                                                <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                    <td class="table-td">{{ $profile->title }}</td>
                                                    <td class="table-td">
                                                        <button wire:click="pushProfile({{ $profile->id }})"
                                                            class="btn inline-flex justify-center btn-success light">
                                                            Add
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="2" class="table-td text-center">
                                                    No commission profiles found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setProfiles" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setProfiles">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setProfiles"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                            <button wire:click="clearProfiles" class="btn inline-flex justify-center btn-outline-dark">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($statusesSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white capitalize">
                                Statuses
                            </h3>
                            <button wire:click="toggleStatuses" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            @foreach ($STATUSES as $status)
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden" value="{{ $status }}"
                                            wire:model.defer="Estatuses">
                                        <span
                                            class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="{{ asset('assets/images/icon/ck-white.svg') }}" alt=""
                                                class="h-[10px] w-[10px] block m-auto opacity-0">
                                        </span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                            {{ ucwords(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setStatuses" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setStatuses">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setStatuses"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                            <button wire:click="clearStatuses" class="btn inline-flex justify-center btn-outline-dark">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($startSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white capitalize">
                                Policy Start Range
                            </h3>
                            <button wire:click="toggleStartDate" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">From</label>
                                    <input type="date" class="form-control" wire:model.defer="Estart_from">
                                </div>
                                <div>
                                    <label class="form-label">To</label>
                                    <input type="date" class="form-control" wire:model.defer="Estart_to">
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setStartDates" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setStartDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setStartDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                            <button wire:click="clearStartDates" class="btn inline-flex justify-center btn-outline-dark">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($paymentDateSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white capitalize">
                                Payment Date Range
                            </h3>
                            <button wire:click="togglePaymentDates" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover;text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">From</label>
                                    <input type="date" class="form-control" wire:model.defer="Epayment_date_from">
                                </div>
                                <div>
                                    <label class="form-label">To</label>
                                    <input type="date" class="form-control" wire:model.defer="Epayment_date_to">
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPaymentDates" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setPaymentDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setPaymentDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                            <button wire:click="clearPaymentDates" class="btn inline-flex justify-center btn-outline-dark">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($clientPaymentDateSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white capitalize">
                                Client Payment Date Range
                            </h3>
                            <button wire:click="toggleClientPaymentDates" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">From</label>
                                    <input type="date" class="form-control"
                                        wire:model.defer="Eclient_payment_date_from">
                                </div>
                                <div>
                                    <label class="form-label">To</label>
                                    <input type="date" class="form-control"
                                        wire:model.defer="Eclient_payment_date_to">
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setClientPaymentDates"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setClientPaymentDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setClientPaymentDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                            <button wire:click="clearClientPaymentDates"
                                class="btn inline-flex justify-center btn-outline-dark">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
