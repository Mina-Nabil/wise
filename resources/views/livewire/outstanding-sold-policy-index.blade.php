<div>
    <div>
        <div class="flex justify-between flex-wrap items-center">
            <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                    Outstanding Sold Policies
                </h4>
            </div>
            {{-- @can('viewCommission', App\Models\Business\SoldPolicy::class)
                <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                    <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                        Total Unpaid Policies: {{ $totalUnpaidPolicies }}
                    </h4>
                </div>
            @endcan --}}
        </div>

        <div class="flex justify-between items-center space-x-7 flex-wrap mb-5">
            <div class="flex gap-5">
                <div class="secondary-radio">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" class="hidden" value="all" wire:model="outstandingType">
                        <span
                            class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                        <span class="text-secondary-500 text-sm leading-6 capitalize">All</span>
                    </label>
                </div>

                <div class="secondary-radio">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" class="hidden" value="policy" wire:model="outstandingType">
                        <span
                            class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                        <span class="text-secondary-500 text-sm leading-6 capitalize">Client Payment Outstanding</span>
                    </label>
                </div>
                @can('viewCommission', App\Models\Business\SoldPolicy::class)
                    <div class="secondary-radio">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" class="hidden" value="commission" wire:model="outstandingType">
                            <span
                                class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                            <span class="text-secondary-500 text-sm leading-6 capitalize">Commission Outstanding</span>
                        </label>
                    </div>

                    <div class="secondary-radio">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" class="hidden" value="invoice" wire:model="outstandingType">
                            <span
                                class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                            <span class="text-secondary-500 text-sm leading-6 capitalize">Invoice Outstanding</span>
                        </label>
                    </div>
                @endcan
            </div>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
                @if (Auth::user()->is_admin)
                    <button wire:click="exportReport"
                        class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
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
                            <iconify-icon class="leading-none text-xl"
                                icon="ic:round-keyboard-arrow-down"></iconify-icon>
                        </span>
                    </button>
                    <ul
                        class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                        z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                        <li wire:click="toggleStartDate">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Start date ( From-To )</span>
                        </li>
                        <li wire:click="toggleCompany">
                            <span href="#"
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                                Insurance Company</span>
                        </li>
                        <li wire:click="togglePaymentDate">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Client Payment ( From-To )</span>
                        </li>
                        <li wire:click="toggleInvoicePaymentDate">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Invoice Payment ( From-To )</span>
                        </li>
                        <li wire:click="toggleHasInvoice(true)">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Has Invoice?
                            </span>
                        </li>
                        <li wire:click="toggleInvoicePaid">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                                Invoice Paid?
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>


    <div class="card-body px-6 pb-6">
        <div class="overflow-x-auto -mx-6">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden ">
                    <div class="card">
                        <header class="card-header cust-card-header noborder">

                            <iconify-icon wire:loading class="loading-icon text-lg"
                                icon="line-md:loading-twotone-loop"></iconify-icon>
                            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search"
                                wire:model="search">
                        </header>
                        <header class="card-header cust-card-header noborder">

                            @if ($start_from || $start_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleStartDate">
                                        {{ $start_from ? 'Start From: ' . \Carbon\Carbon::parse($start_from)->format('l d/m/Y') : '' }}
                                        {{ $start_from && $start_to ? '-' : '' }}
                                        {{ $start_to ? 'Start To: ' . \Carbon\Carbon::parse($start_to)->format('l d/m/Y') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearStartDates">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($company_ids)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleCompany">
                                        Company(
                                        @foreach ($company_ids as $id)
                                            @php
                                                $company = \App\Models\Insurance\Company::find($id)->name;
                                            @endphp

                                            {{ $company }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach

                                        )
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearCompany">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($payment_from || $payment_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="togglePaymentDate">
                                        {{ $payment_from ? 'Payment From: ' . \Carbon\Carbon::parse($payment_from)->format('l d/m/Y') : '' }}
                                        {{ $payment_from && $payment_to ? '-' : '' }}
                                        {{ $payment_to ? 'Payment To: ' . \Carbon\Carbon::parse($payment_to)->format('l d/m/Y') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearPaymentDates">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($invoice_payment_from || $invoice_payment_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleInvoicePaymentDate">
                                        {{ $invoice_payment_from ? 'Invoice Payment From: ' . \Carbon\Carbon::parse($invoice_payment_from)->format('l d/m/Y') : '' }}
                                        {{ $invoice_payment_from && $invoice_payment_to ? '-' : '' }}
                                        {{ $invoice_payment_to ? 'Invoice Payment To: ' . \Carbon\Carbon::parse($invoice_payment_to)->format('l d/m/Y') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearInvoicePaymentDates">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($hasInvoiceFilter !== null)
                                <button class="btn inline-flex justify-center btn-dark btn-sm"
                                    wire:click="toggleHasInvoice">
                                    <span>Has Invoice: {{ $hasInvoiceFilter ? 'Yes' : 'No' }}</span>
                                    <span wire:click="clearHasInvoice">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($invoicePaidFilter !== null)
                                <button class="btn inline-flex justify-center btn-dark btn-sm"
                                    wire:click="toggleInvoicePaid">
                                    <span>Invoice: {{ $invoicePaidFilter ? 'Paid' : 'Unpaid' }}</span>
                                    <span wire:click="clearInvoicePaid">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif
                        </header>

                        <div class="tab-content mt-6" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-list" role="tabpanel"
                                aria-labelledby="pills-list-tab">
                                <div class="tab-content">
                                    <div class="card">
                                        <div class="card-body px-6 rounded overflow-hidden pb-3">
                                            <div class="overflow-x-auto -mx-6">
                                                <div class="inline-block min-w-full align-middle">
                                                    <div class="overflow-hidden ">
                                                        <table
                                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 ">
                                                            <thead class="bg-slate-200 dark:bg-slate-700">
                                                                <tr>
                                                                    <th scope="col" class="table-th ">
                                                                        POLICY
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        GROSS
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        NET
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        START
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        END
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        PYMT
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        POLICY#
                                                                    </th>
                                                                    @can('viewCommission',
                                                                        App\Models\Business\SoldPolicy::class)
                                                                        <th scope="col" class="table-th ">
                                                                            COMM.
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            PAID
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            INVOICE
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            #
                                                                        </th>
                                                                        <th scope="col" class="table-th ">
                                                                            PYMT
                                                                        </th>
                                                                    @endcan
                                                                    <th scope="col" class="table-th ">
                                                                        CLIENT
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        STATUS
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody
                                                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                                @foreach ($soldPolicies as $policy)
                                                                    <tr
                                                                        class="even:bg-slate-50 dark:even:bg-slate-700">
                                                                        <td class="table-td">
                                                                            <div class="flex-1 text-start">
                                                                                <h4
                                                                                    class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                                                    {{ $policy->policy->company->name }}
                                                                                </h4>
                                                                                <div
                                                                                    class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                                                    {{ $policy->policy->name }}
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span
                                                                                class="block date-text">{{ number_format($policy->gross_premium, 2) }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span
                                                                                class="block date-text">{{ number_format($policy->net_premium, 2) }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span
                                                                                class="block date-text">{{ \Carbon\Carbon::parse($policy->start)->format('d-m-Y') }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span
                                                                                class="block date-text">{{ \Carbon\Carbon::parse($policy->expiry)->format('d-m-Y') }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span
                                                                                class="block date-text">{{ $policy->client_payment_date ? \Carbon\Carbon::parse($policy->client_payment_date)->format('d-m-Y') : 'N/A' }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <a href="{{ route('sold.policy.show', $policy->id) }}"
                                                                                target="_blank"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <span class="block date-text">
                                                                                    {{ $policy->policy_number }}
                                                                                </span>
                                                                            </a>
                                                                        </td>

                                                                        @can('viewCommission',
                                                                            App\Models\Business\SoldPolicy::class)
                                                                            <td class="table-td">
                                                                                <span
                                                                                    class="block date-text">{{ number_format($policy->after_tax_comm, 2) }}</span>
                                                                            </td>
                                                                            <td class="table-td">
                                                                                <span
                                                                                    class="block date-text">{{ number_format($policy->total_comp_paid, 2) }}</span>
                                                                            </td>

                                                                            <td class="table-td">
                                                                                <span
                                                                                    class="block date-text">{{ $policy->last_company_comm_payment ? \Carbon\Carbon::parse($policy->last_company_comm_payment?->created_at)->format('d-m-Y') : 'N/A' }}</span>
                                                                            </td>
                                                                            <td class="table-td">
                                                                                <span
                                                                                    class="block date-text">{{ $policy->last_company_comm_payment?->invoice->serial }}</span>
                                                                            </td>
                                                                            <td class="table-td">
                                                                                <span
                                                                                    class="block date-text">{{ $policy->last_company_comm_payment?->payment_date ? \Carbon\Carbon::parse($policy->last_company_comm_payment->payment_date)->format('d-m-Y') : 'N/A' }}</span>
                                                                            </td>
                                                                        @endcan
                                                                        <td class="table-td">
                                                                            <div
                                                                                class="flex space-x-3 items-center text-left rtl:space-x-reverse">

                                                                                <div
                                                                                    class="flex-1 font-medium text-sm leading-4 whitespace-nowrap">
                                                                                    <a class="hover:underline cursor-pointer"
                                                                                        href="{{ route($policy->client_type . 's.show', $policy->client_id) }}">

                                                                                        {{ $policy->client->name }}

                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            @if ($policy->is_valid)
                                                                                <span
                                                                                    class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Validated</span>
                                                                            @endif
                                                                        </td>

                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                @if ($soldPolicies->isEmpty())
                                                    {{-- START: empty filter result --}}
                                                    <div class="card m-5 p-5">
                                                        <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                                            <div class="items-center text-center p-5">
                                                                <h2><iconify-icon
                                                                        icon="icon-park-outline:search"></iconify-icon>
                                                                </h2>
                                                                <h2
                                                                    class="card-title text-slate-900 dark:text-white mb-3">
                                                                    No Sold Policies with the
                                                                    applied
                                                                    filters</h2>
                                                                <p class="card-text">Try changing the filters or search
                                                                    terms for this view.
                                                                </p>
                                                                <a href="{{ url('/sold-policies') }}"
                                                                    class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                                                    all Sold Policies</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- END: empty filter result --}}
                                                @endif

                                                @if ($startSection)
                                                    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                                                        tabindex="-1" aria-labelledby="vertically_center"
                                                        aria-modal="true" role="dialog" style="display: block;">
                                                        <div
                                                            class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                                                            <div
                                                                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                                                                <div
                                                                    class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                                                    <!-- Modal header -->
                                                                    <div
                                                                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                                                        <h3
                                                                            class="text-xl font-medium text-white dark:text-white capitalize">
                                                                            Start date
                                                                        </h3>
                                                                        <button wire:click="toggleStartDate"
                                                                            type="button"
                                                                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                                                            data-bs-dismiss="modal">
                                                                            <svg aria-hidden="true" class="w-5 h-5"
                                                                                fill="#ffffff" viewBox="0 0 20 20"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                            11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                                    clip-rule="evenodd"></path>
                                                                            </svg>
                                                                            <span class="sr-only">Close modal</span>
                                                                        </button>
                                                                    </div>
                                                                    <!-- Modal body -->
                                                                    <div class="p-6 space-y-4">
                                                                        <div class="from-group">
                                                                            <label for="Estart_from"
                                                                                class="form-label">Start from</label>
                                                                            <input name="Estart_from" type="date"
                                                                                class="form-control mt-2 w-full @error('Estart_from') !border-danger-500 @enderror"
                                                                                wire:model.defer="Estart_from">
                                                                            @error('Estart_from')
                                                                                <span
                                                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="from-group">
                                                                            <label for="Estart_to"
                                                                                class="form-label">Start to</label>
                                                                            <input name="Estart_to" type="date"
                                                                                class="form-control mt-2 w-full @error('Estart_to') !border-danger-500 @enderror"
                                                                                wire:model.defer="Estart_to">
                                                                            @error('Estart_to')
                                                                                <span
                                                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <!-- Modal footer -->
                                                                    <div
                                                                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                                                        <button wire:click="setStartDates"
                                                                            data-bs-dismiss="modal"
                                                                            class="btn inline-flex justify-center text-white bg-black-500">
                                                                            <span wire:loading.remove
                                                                                wire:target="setStartDates">Submit</span>
                                                                            <iconify-icon
                                                                                class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                                                                wire:loading
                                                                                wire:target="setStartDates"
                                                                                icon="line-md:loading-twotone-loop"></iconify-icon>

                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($companySection)
                                                    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                                                        tabindex="-1" aria-labelledby="vertically_center"
                                                        aria-modal="true" role="dialog" style="display: block;">
                                                        <div
                                                            class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                                                            <div
                                                                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                                                                <div
                                                                    class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                                                    <!-- Modal header -->
                                                                    <div
                                                                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                                                        <h3
                                                                            class="text-xl font-medium text-white dark:text-white capitalize">
                                                                            Insurance Company
                                                                        </h3>
                                                                        <button wire:click="toggleCompany"
                                                                            type="button"
                                                                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                                                            data-bs-dismiss="modal">
                                                                            <svg aria-hidden="true" class="w-5 h-5"
                                                                                fill="#ffffff" viewBox="0 0 20 20"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                            11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                                    clip-rule="evenodd"></path>
                                                                            </svg>
                                                                            <span class="sr-only">Close modal</span>
                                                                        </button>
                                                                    </div>
                                                                    <!-- Modal body -->
                                                                    <div class="p-6 space-y-4">
                                                                        <div>
                                                                            @if(count($Ecompany_ids) > 0)
                                                                                <div class="mb-4">
                                                                                    <h4 class="text-base font-medium mb-2">Selected Companies:</h4>
                                                                                    <div class="flex flex-wrap gap-2">
                                                                                        @foreach ($Ecompany_ids as $id)
                                                                                            @php
                                                                                                $company = \App\Models\Insurance\Company::find($id)->name;
                                                                                            @endphp
                                                                                            <div class="badge bg-slate-900 text-white capitalize rounded-3xl px-3 py-1 flex items-center">
                                                                                                <span>{{ $company }}</span>
                                                                                                <button class="ml-2" wire:click="$set('Ecompany_ids', {{ json_encode(array_values(array_filter($Ecompany_ids, function($item) use ($id) { return $item != $id; }))) }})">
                                                                                                    <iconify-icon icon="material-symbols:close" width="1em" height="1em"></iconify-icon>
                                                                                                </button>
                                                                                            </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <div class="text-center p-2 bg-slate-100 dark:bg-slate-600 rounded mb-4">
                                                                                    <span class="text-sm">No companies selected</span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="from-group">
                                                                            <label for="searchCompany"
                                                                                class="form-label">Search
                                                                                Company</label>
                                                                            <input name="searchCompany" type="text"
                                                                                class="form-control mt-2 w-full"
                                                                                wire:model="searchCompany">
                                                                        </div>
                                                                        <table
                                                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                                                            <thead
                                                                                class="bg-slate-200 dark:bg-slate-700">
                                                                                <tr>
                                                                                    <th scope="col" class="table-th" style="width: 50px">
                                                                                        Select
                                                                                    </th>
                                                                                    <th scope="col" class="table-th">
                                                                                        Company Name
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody
                                                                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                                                @foreach ($companies as $company)
                                                                                    <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                                                        <td class="table-td text-center">
                                                                                            <div class="checkbox-area">
                                                                                                <label class="inline-flex items-center cursor-pointer">
                                                                                                    <input 
                                                                                                        type="checkbox" 
                                                                                                        class="hidden" 
                                                                                                        wire:model="Ecompany_ids" 
                                                                                                        value="{{ $company->id }}"
                                                                                                    >
                                                                                                    <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td class="table-td">
                                                                                            {{ $company->name }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <!-- Modal footer -->
                                                                    <div
                                                                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                                                        <button type="button" wire:click="$set('Ecompany_ids', [])" 
                                                                            class="btn btn-outline-danger">
                                                                            Clear All
                                                                        </button>
                                                                        <button wire:click="setCompany"
                                                                            class="btn inline-flex justify-center text-white bg-black-500">
                                                                            <span wire:loading.remove
                                                                                wire:target="setCompany">Apply Selection</span>
                                                                            <iconify-icon
                                                                                class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                                                                wire:loading wire:target="setCompany"
                                                                                icon="line-md:loading-twotone-loop"></iconify-icon>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($paymentSection)
                                                    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                                                        tabindex="-1" aria-labelledby="paymentDateModal"
                                                        aria-modal="true" role="dialog" style="display: block;">
                                                        <div class="modal-dialog relative w-auto pointer-events-none">
                                                            <div
                                                                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                                                                <div
                                                                    class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                                                    <!-- Modal header -->
                                                                    <div
                                                                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                                                        <h3
                                                                            class="text-xl font-medium text-white dark:text-white">
                                                                            Select Payment Date Range
                                                                        </h3>
                                                                        <button wire:click="togglePaymentDate"
                                                                            type="button"
                                                                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                                                            <svg aria-hidden="true" class="w-5 h-5"
                                                                                fill="#ffffff" viewBox="0 0 20 20"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                                    clip-rule="evenodd"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                    <!-- Modal body -->
                                                                    <div class="p-6 space-y-4">
                                                                        <div class="grid grid-cols-2 gap-4">
                                                                            <div>
                                                                                <label class="form-label">From
                                                                                    Date</label>
                                                                                <input type="date"
                                                                                    class="form-control"
                                                                                    wire:model="Epayment_from">
                                                                            </div>
                                                                            <div>
                                                                                <label class="form-label">To
                                                                                    Date</label>
                                                                                <input type="date"
                                                                                    class="form-control"
                                                                                    wire:model="Epayment_to">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Modal footer -->
                                                                    <div
                                                                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                                                        <button wire:click="clearPaymentDates"
                                                                            type="button"
                                                                            class="btn btn-danger">Clear</button>
                                                                        <button wire:click="setPaymentDates"
                                                                            type="button"
                                                                            class="btn btn-dark">Apply</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-backdrop fade show"></div>
                                                @endif

                                                @if ($invoicePaymentSection)
                                                    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                                                        tabindex="-1" aria-labelledby="invoicePaymentDateModal"
                                                        aria-modal="true" role="dialog" style="display: block;">
                                                        <div class="modal-dialog relative w-auto pointer-events-none">
                                                            <div
                                                                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                                                                <div
                                                                    class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                                                    <!-- Modal header -->
                                                                    <div
                                                                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                                                        <h3
                                                                            class="text-xl font-medium text-white dark:text-white">
                                                                            Select Invoice Payment Date Range
                                                                        </h3>
                                                                        <button wire:click="toggleInvoicePaymentDate"
                                                                            type="button"
                                                                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                                                            <svg aria-hidden="true" class="w-5 h-5"
                                                                                fill="#ffffff" viewBox="0 0 20 20"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                                    clip-rule="evenodd"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                    <!-- Modal body -->
                                                                    <div class="p-6 space-y-4">
                                                                        <div class="grid grid-cols-2 gap-4">
                                                                            <div>
                                                                                <label class="form-label">From
                                                                                    Date</label>
                                                                                <input type="date"
                                                                                    class="form-control"
                                                                                    wire:model="Einvoice_payment_from">
                                                                            </div>
                                                                            <div>
                                                                                <label class="form-label">To
                                                                                    Date</label>
                                                                                <input type="date"
                                                                                    class="form-control"
                                                                                    wire:model="Einvoice_payment_to">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Modal footer -->
                                                                    <div
                                                                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                                                        <button wire:click="clearInvoicePaymentDates"
                                                                            type="button"
                                                                            class="btn btn-danger">Clear</button>
                                                                        <button wire:click="setInvoicePaymentDates"
                                                                            type="button"
                                                                            class="btn btn-dark">Apply</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-backdrop fade show"></div>
                                                @endif


                                            </div>
                                            {{ $soldPolicies->links('vendor.livewire.bootstrap') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
