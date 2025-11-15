<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Company Commission Payments
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @if (Auth::user()->is_admin || Auth::user()->is_finance)
                <button wire:click="exportReport" class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
                    <span wire:loading.remove wire:target="exportReport">Export</span>
                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                        wire:target="exportReport" icon="line-md:loading-twotone-loop"></iconify-icon>
                </button>
            @endif
            <div class="dropdown relative ">
                <button class="btn inline-flex justify-center btn-dark items-center cursor-default relative !pr-14"
                    type="button" id="darksplitDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Add filter
                    <span
                        class="cursor-pointer absolute ltr:border-l rtl:border-r border-slate-100 h-full ltr:right-0 rtl:left-0 px-2 flex
                                items-center justify-center leading-none">
                        <iconify-icon class="leading-none text-xl" icon="ic:round-keyboard-arrow-down"></iconify-icon>
                    </span>
                </button>
                <ul
                    class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                            z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                    <li wire:click="toggleRenewal">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            is Renewal</span>
                    </li>

                    <li wire:click="toggleTypes">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Payment Types</span>
                    </li>

                    <li wire:click="toggleStatuses">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Statuses</span>
                    </li>

                    <li wire:click="toggleStartDate">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Start date ( From-To )</span>
                    </li>

                    <li wire:click="toggleExpiryDate">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Expiry date ( From-To )</span>
                    </li>

                    <li wire:click="toggleIssuedDate">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Issued date ( From-To )</span>
                    </li>

                    <li wire:click="togglePaymentDate">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                            dark:hover:text-white cursor-pointer">
                            Payment date ( From-To )</span>
                    </li>

                    <li wire:click="toggleCompany">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Insurance Company</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Search Input -->
    <div class="space-y-5">
        <div class="card">
            <div class="card-body">
                <div class="grid grid-cols-12 gap-5">
                    <div class="col-span-12 md:col-span-6 p-4">
                        <div class="input-area">
                            <input id="searchText" type="text" class="form-control" wire:model="searchText"
                                placeholder="Enter policy number">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Cards -->
    <div class="space-y-5">
        <div class="card">
            <header class="card-header">
                <h4 class="card-title">Company Commission Payments</h4>
                <div class="md:flex md:space-x-4 md:justify-end items-center rtl:space-x-reverse">
                    @if (count($statuses) > 0)
                        <div class="min-w-[200px] space-y-4 w-full">
                            @foreach ($statuses as $status)
                                <span class="badge bg-slate-900 text-white capitalize rounded-3xl">
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    @if (count($types) > 0)
                        <div class="min-w-[200px] space-y-4 w-full">
                            @foreach ($types as $type)
                                <span class="badge bg-slate-900 text-white capitalize rounded-3xl">
                                    {{ ucwords(str_replace('_', ' ', $type)) }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    @if (count($company_ids) > 0)
                        <div class="min-w-[200px] space-y-4 w-full">
                            @foreach ($company_ids as $companyId)
                                @php
                                    $company = $companies->firstWhere('id', $companyId);
                                @endphp
                                @if ($company)
                                    <span class="badge bg-slate-900 text-white capitalize rounded-3xl">
                                        {{ $company->name }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    @if ($searchText)
                        <div class="min-w-[200px] space-y-4 w-full">
                            <span class="badge bg-slate-900 text-white capitalize rounded-3xl">
                                Search: {{ $searchText }}
                            </span>
                        </div>
                    @endif
                    @if ($start_from || $start_to)
                        <div class="min-w-[200px] space-y-4 w-full">
                            <span class="badge bg-slate-900 text-white capitalize rounded-3xl">
                                Start: {{ $start_from ?? 'Any' }} - {{ $start_to ?? 'Any' }}
                            </span>
                        </div>
                    @endif
                    @if ($expiry_from || $expiry_to)
                        <div class="min-w-[200px] space-y-4 w-full">
                            <span class="badge bg-slate-900 text-white capitalize rounded-3xl">
                                Expiry: {{ $expiry_from ?? 'Any' }} - {{ $expiry_to ?? 'Any' }}
                            </span>
                        </div>
                    @endif
                    @if ($issued_from || $issued_to)
                        <div class="min-w-[200px] space-y-4 w-full">
                            <span class="badge bg-slate-900 text-white capitalize rounded-3xl">
                                Issued: {{ $issued_from ?? 'Any' }} - {{ $issued_to ?? 'Any' }}
                            </span>
                        </div>
                    @endif
                    @if ($payment_date_from || $payment_date_to)
                        <div class="min-w-[200px] space-y-4 w-full">
                            <span class="badge bg-slate-900 text-white capitalize rounded-3xl">
                                Payment: {{ $payment_date_from ?? 'Any' }} - {{ $payment_date_to ?? 'Any' }}
                            </span>
                        </div>
                    @endif
                </div>
            </header>
            <div class="card-body px-6 pb-6">
                <div class="overflow-x-auto -mx-6 dashcode-data-table">
                    <span class="col-span-8 hidden"></span>
                    <span class="col-span-4 hidden"></span>
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead
                                    class="border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                    <tr>
                                        <th scope="col" class="table-th">
                                            Policy#
                                        </th>

                                        <th scope="col" class="table-th">
                                            Client
                                        </th>

                                        <th scope="col" class="table-th cursor-pointer">
                                            <span wire:click="sortByColumn('created_at')" class="clickable-header">Issue
                                                @if ($sortColumn === 'created_at')
                                                    @if ($sortDirection === 'asc')
                                                        <iconify-icon icon="fluent:arrow-up-12-filled"></iconify-icon>
                                                    @else
                                                        <iconify-icon icon="fluent:arrow-down-12-filled"></iconify-icon>
                                                    @endif
                                                @endif
                                            </span>
                                        </th>

                                        <th scope="col" class="table-th cursor-pointer">
                                            <span wire:click="sortByColumn('payment_date')"
                                                class="clickable-header">Payment
                                                @if ($sortColumn === 'payment_date')
                                                    @if ($sortDirection === 'asc')
                                                        <iconify-icon icon="fluent:arrow-up-12-filled"></iconify-icon>
                                                    @else
                                                        <iconify-icon icon="fluent:arrow-down-12-filled"></iconify-icon>
                                                    @endif
                                                @endif
                                            </span>
                                        </th>

                                        <th scope="col" class="table-th">
                                            Net
                                        </th>

                                        <th scope="col" class="table-th cursor-pointer">
                                            <span wire:click="sortByColumn('amount')" class="clickable-header">Amount
                                                @if ($sortColumn === 'amount')
                                                    @if ($sortDirection === 'asc')
                                                        <iconify-icon icon="fluent:arrow-up-12-filled"></iconify-icon>
                                                    @else
                                                        <iconify-icon icon="fluent:arrow-down-12-filled"></iconify-icon>
                                                    @endif
                                                @endif
                                            </span>
                                        </th>

                                        <th scope="col" class="table-th">
                                            Tax
                                        </th>
                                        <th scope="col" class="table-th">
                                            Status
                                        </th>

                                        <th scope="col" class="table-th">
                                            Type
                                        </th>

                                        <th scope="col" class="table-th">
                                            Company
                                        </th>
                                        <th scope="col" class="table-th">
                                            Invoice#
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y cursor-pointer divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                    @foreach ($payments as $payment)
                                        <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">
                                            <td class="table-td">
                                                <a href="{{ route('sold.policy.show', $payment->sold_policy->id) }}"
                                                    target="_blank"
                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                    <span
                                                        class="block date-text">{{ $payment->sold_policy->policy_number }}</span>
                                                </a>
                                            </td>

                                            <td class="table-td">
                                                {{ $payment->sold_policy->client?->full_name ?? ($payment->sold_policy->client?->name ?? 'N/A') }}
                                            </td>

                                            <td class="table-td">
                                                {{ \Carbon\Carbon::parse($payment->sold_policy->created_at)->format('d/m/Y') }}
                                            </td>

                                            <td class="table-td">
                                                {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A' }}
                                            </td>

                                            <td class="table-td">
                                                <p><b>{{ number_format($payment->sold_policy->net_premium, 2, '.', ',') }}
                                                        EGP</b></p>
                                            </td>

                                            <td class="table-td">
                                                <p><b>{{ number_format($payment->amount + $payment->tax_amount, 2, '.', ',') }}
                                                        EGP</b></p>
                                            </td>
                                            <td class="table-td">
                                                <p><b>{{ number_format($payment->tax_amount, 2, '.', ',') }}</b>
                                                </p>
                                            </td>

                                            <td class="table-td">
                                                @if ($payment->status === 'new')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-primary-500 bg-primary-500 text-xs">
                                                        New
                                                    </div>
                                                @elseif($payment->status === 'paid')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500 text-xs">
                                                        Paid
                                                    </div>
                                                @elseif($payment->status === 'cancelled')
                                                    <div
                                                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-danger-500 bg-danger-500 text-xs">
                                                        Cancelled
                                                    </div>
                                                @endif
                                                @if ($payment->sold_policy->offer?->is_renewal)
                                                    <span
                                                        class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Renewal</span>
                                                @endif
                                                @if ($payment->sold_policy->cancellation_time)
                                                    <span
                                                        class="badge bg-danger-500 text-slate-800 text-danger-500 bg-opacity-30 capitalize rounded-3xl">Sold Policy Cancelled on: {{ \Carbon\Carbon::parse($payment->sold_policy->cancellation_time)->format('d/m/Y') }}</span>
                                                @endif
                                            </td>

                                            <td class="table-td">
                                                <span
                                                    class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize">
                                                    {{ ucwords(str_replace('_', ' ', $payment->type)) }}
                                                </span>
                                            </td>

                                            <td class="table-td">
                                                {{ $payment->sold_policy->policy->company->name ?? 'N/A' }}
                                            </td>

                                            <td class="table-td">
                                                {{ $payment->invoice?->serial ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if ($payments->isEmpty())
                                <div class="text-center py-8">
                                    <p class="text-slate-500 dark:text-slate-400">No company commission payments found.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $payments->links() }}
    </div>

    <!-- Filter Modals -->
    @if ($startSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Start date
                            </h3>
                            <button wire:click="toggleStartDate" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Estart_from" class="form-label">Start from</label>
                                <input name="Estart_from" type="date" class="form-control mt-2 w-full"
                                    wire:model.defer="Estart_from">
                            </div>
                            <div class="from-group">
                                <label for="Estart_to" class="form-label">Start to</label>
                                <input name="Estart_to" type="date" class="form-control mt-2 w-full"
                                    wire:model.defer="Estart_to">
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setStartDates"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($paymentDateSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Payment date
                            </h3>
                            <button wire:click="togglePaymentDate" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Epayment_date_from" class="form-label">Payment date from</label>
                                <input name="Epayment_date_from" type="date" class="form-control mt-2 w-full"
                                    wire:model.defer="Epayment_date_from">
                            </div>
                            <div class="from-group">
                                <label for="Epayment_date_to" class="form-label">Payment date to</label>
                                <input name="Epayment_date_to" type="date" class="form-control mt-2 w-full"
                                    wire:model.defer="Epayment_date_to">
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPaymentDates"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($statusesSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
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
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach ($STATUSES as $status)
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="Estatuses" value="{{ $status }}"
                                        class="form-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label class="ml-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setStatuses"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($typesSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Payment Types
                            </h3>
                            <button wire:click="toggleTypes" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach ($Alltypes as $type)
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="Etypes" value="{{ $type }}"
                                        class="form-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label class="ml-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ ucwords(str_replace('_', ' ', $type)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setTypes"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($companySection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Insurance Company
                            </h3>
                            <button wire:click="toggleCompany" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach ($companies as $company)
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="Ecompany_ids" value="{{ $company->id }}"
                                        class="form-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label class="ml-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $company->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setCompanies"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($expirySection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Expiry date
                            </h3>
                            <button wire:click="toggleExpiryDate" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd">
                                    </path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Eexpiry_from" class="form-label">Expiry from</label>
                                <input name="Eexpiry_from" type="date"
                                    class="form-control mt-2 w-full @error('Eexpiry_from') !border-danger-500 @enderror"
                                    wire:model.defer="Eexpiry_from">
                                @error('Eexpiry_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Eexpiry_to" class="form-label">Expiry to</label>
                                <input name="Eexpiry_to" type="date"
                                    class="form-control mt-2 w-full @error('Eexpiry_to') !border-danger-500 @enderror"
                                    wire:model.defer="Eexpiry_to">
                                @error('Eexpiry_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setExpiryDates" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setExpiryDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setExpiryDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($issuedSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Issued date
                            </h3>
                            <button wire:click="toggleIssuedDate" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd">
                                    </path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Eissued_from" class="form-label">Issued from</label>
                                <input name="Eissued_from" type="date"
                                    class="form-control mt-2 w-full @error('Eissued_from') !border-danger-500 @enderror"
                                    wire:model.defer="Eissued_from">
                                @error('Eissued_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Eissued_to" class="form-label">Issued to</label>
                                <input name="Eissued_to" type="date"
                                    class="form-control mt-2 w-full @error('Eissued_to') !border-danger-500 @enderror"
                                    wire:model.defer="Eissued_to">
                                @error('Eissued_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setIssuedDates" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setIssuedDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setIssuedDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
