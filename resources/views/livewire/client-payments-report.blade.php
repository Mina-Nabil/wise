<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Client Payments
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @if (Auth::user()->is_admin)
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
                    <li wire:click="toggleProfiles">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            By Sales Out</span>
                    </li>

                    <li wire:click="toggleRenewal">
                        <span href="#"
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

                    <li wire:click="togglestatuses">
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
                    <li wire:click="toggleDateFilter">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                            dark:hover:text-white cursor-pointer">
                            Payment date ( From-To )</span>
                    </li>
                    <li wire:click="toggleCollectionDateFilter">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                            dark:hover:text-white cursor-pointer">
                            Collection date ( From-To )</span>
                    </li>


                    <li wire:click="toggleCompany">
                        <span href="#"
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Insurance Company</span>
                    </li>


                </ul>
            </div>
        </div>
    </div>


    <div class="card-body px-6 pb-6">
        <div class=" -mx-6">
            <div class="inline-block min-w-full align-middle">
                <div class="card">
                    <header class="card-header cust-card-header noborder">
                        <iconify-icon wire:loading class="loading-icon text-lg"
                            icon="line-md:loading-twotone-loop"></iconify-icon>
                        <input class="form-control py-2 w-auto ml-5" type="text" wire:model="searchText"
                            placeholder="Search by policy number" />


                    </header>
                    <header class="card-header cust-card-header noborder" style="display: block;">



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

                        @if ($expiry_from || $expiry_to)
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="setExpiryDates">
                                    {{ $expiry_from ? 'Expiry From: ' . \Carbon\Carbon::parse($expiry_from)->format('l d/m/Y') : '' }}
                                    {{ $expiry_from && $expiry_to ? '-' : '' }}
                                    {{ $expiry_to ? 'Expiry To: ' . \Carbon\Carbon::parse($expiry_to)->format('l d/m/Y') : '' }}
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearExpiryDates">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif

                        @if ($issued_from || $issued_to)
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="toggleIssuedDate">
                                    {{ $issued_from ? 'Issued From: ' . \Carbon\Carbon::parse($issued_from)->format('l d/m/Y') : '' }}
                                    {{ $issued_from && $issued_to ? '-' : '' }}
                                    {{ $issued_to ? 'Issued To: ' . \Carbon\Carbon::parse($issued_to)->format('l d/m/Y') : '' }}
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearIssuedDates">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif

                        @if ($date_from || $date_to)
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="toggleDateFilter">
                                    {{ $date_from ? 'Payment From: ' . \Carbon\Carbon::parse($date_from)->format('l d/m/Y') : '' }}
                                    {{ $date_from && $date_to ? '-' : '' }}
                                    {{ $date_to ? 'Payment To: ' . \Carbon\Carbon::parse($date_to)->format('l d/m/Y') : '' }}
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearDateFilter">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif

                        @if ($collection_date_from || $collection_date_to)
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="toggleCollectionDateFilter">
                                    {{ $collection_date_from ? 'Collection From: ' . \Carbon\Carbon::parse($collection_date_from)->format('l d/m/Y') : '' }}
                                    {{ $collection_date_from && $collection_date_to ? '-' : '' }}
                                    {{ $collection_date_to ? 'Collection To: ' . \Carbon\Carbon::parse($collection_date_to)->format('l d/m/Y') : '' }}
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearCollectionDateFilter">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif

                        @if ($types)
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="toggleTypes">
                                    Types(
                                    @foreach ($types as $tt)
                                        {{ $tt }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach

                                    )
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearTypes">
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

                        @if ($sales_out_ids)
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="toggleSalesOut">
                                    Sales Outs(
                                    @foreach ($sales_out_ids as $id)
                                        @php
                                            $so = App\Models\Payments\CommProfile::find($id)->title;
                                        @endphp

                                        {{ $so }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach

                                    )
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearSalesOuts">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif

                        @if ($statuses)
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="togglestatuses">
                                    Status:&nbsp;(
                                    @foreach ($statuses as $status)
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                    )
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearstatuses">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif

                        @if (!is_null($is_renewal))
                            <button class="btn inline-flex justify-center btn-dark btn-sm">
                                <span wire:click="toggleRenewal">
                                    @if ($is_renewal)
                                        Renewal:&nbsp;Yes
                                    @else
                                        Renewal:&nbsp;No
                                    @endif
                                    &nbsp;&nbsp;
                                </span>
                                <span wire:click="clearrenewal">
                                    <iconify-icon icon="material-symbols:close" width="1.2em"
                                        height="1.2em"></iconify-icon>
                                </span>
                            </button>
                        @endif

                    </header>
                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                        <thead class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                            <tr>

                                <th scope="col" class=" table-th ">
                                    Policy#
                                </th>
                                <th scope="col" class=" table-th ">
                                    Creator
                                </th>

                                <th scope="col" class=" table-th ">
                                    Client
                                </th>

                                <th scope="col" class=" table-th cursor-pointer">
                                    <span wire:click="sortByColomn('start')" class="clickable-header">Start
                                        @if ($sortColomn === 'start')
                                            @if ($sortDirection === 'asc')
                                                <iconify-icon icon="fluent:arrow-up-12-filled"></iconify-icon>
                                            @else
                                                <iconify-icon icon="fluent:arrow-down-12-filled"></iconify-icon>
                                            @endif
                                        @endif
                                    </span>

                                </th>

                                @if (auth()->user()->is_admin || auth()->user()->is_finance)
                                    <th scope="col" class="table-th">
                                        Sales
                                    </th>
                                @endif

                                <th scope="col" class="table-th">
                                    Collection
                                </th>

                                <th scope="col" class="table-th">
                                    Amount
                                </th>

                                <th scope="col" class=" table-th ">
                                    Status
                                </th>

                                <th scope="col" class=" table-th ">
                                    Type
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
                                        {{ $payment->sold_policy->creator->username }}
                                    </td>
                                    <td class="table-td">
                                        {{ $payment->sold_policy->client->name }}
                                    </td>

                                    <td class="table-td">
                                        {{ \Carbon\Carbon::parse($payment->sold_policy->start)->format('D d/m/Y') }}
                                    </td>


                                    @if (auth()->user()->is_admin || auth()->user()->is_finance)
                                        <td class="table-td">
                                            <ul>

                                                @foreach ($payment->sold_policy->active_sales_comms as $sales_comm)
                                                    <li>
                                                        {{ $sales_comm->comm_profile->title }}:
                                                        {{ $sales_comm->amount }}
                                                        {{ $sales_comm->status }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    @endif

                                    <td class="table-td">
                                        {{ $payment->collected_date?->format('d-m-Y') ?? 'N/A' }}
                                    </td>
                                    <td class="table-td">
                                        <p><b>{{ number_format($payment->amount, 2, '.', ',') }} EGP
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
                                        @elseif($payment->status === 'prem_collected')
                                            <div
                                                class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-info-500 bg-info-500 text-xs">
                                                Prem Collected
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
                                    </td>

                                    <td class="table-td">
                                        <span
                                            class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize">{{ ucwords(str_replace('_', ' ', $payment->type)) }}
                                            @if ($payment->type == 'sales_out')
                                                - {{ $payment->sales_out?->title }}
                                            @endif

                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    @if ($payments->isEmpty())
                        {{-- START: empty filter result --}}
                        <div class="card p-5">
                            <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                <div class="items-center text-center p-5">
                                    <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                    <h2 class="card-title text-slate-900 dark:text-white mb-3">No Payments with the
                                        applied
                                        filters</h2>
                                    <p class="card-text">Try changing the filters or search terms for this view.
                                    </p>
                                    <a href="{{ url('/payments') }}"
                                        class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                        all Payments</a>
                                </div>
                            </div>
                        </div>
                        {{-- END: empty filter result --}}
                    @endif

                </div>



                {{ $payments->links('vendor.livewire.bootstrap') }}

            </div>
        </div>


        @if ($startSection)
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
                                    Start date
                                </h3>
                                <button wire:click="toggleStartDate" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                    data-bs-dismiss="modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6 space-y-4">
                                <div class="from-group">
                                    <label for="Estart_from" class="form-label">Start from</label>
                                    <input name="Estart_from" type="date"
                                        class="form-control mt-2 w-full @error('Estart_from') !border-danger-500 @enderror"
                                        wire:model.defer="Estart_from">
                                    @error('Estart_from')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="Estart_to" class="form-label">Start to</label>
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
                                <button wire:click="setStartDates" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="setStartDates">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="setStartDates"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>

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
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd">
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
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd">
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
        {{-- 
        @if ($salesOutSection)
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
                                    Sales Out
                                </h3>
                                <button wire:click="toggleSalesOut" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                    data-bs-dismiss="modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6 space-y-4">
                                <div>
                                    @foreach ($Esales_out_ids as $id)
                                        <!-- Fetch brand name based on ID -->
                                        @php
                                            $sales_out = App\Models\Payments\CommProfile::find($id)->title;
                                        @endphp

                                        <!-- Display brand name -->
                                        <span
                                            class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ $sales_out }}</span>
                                    @endforeach

                                </div>
                                <div class="from-group">
                                    <label for="searchSalesOut" class="form-label">Search Sales Out</label>
                                    <input name="searchSalesOut" type="text" class="form-control mt-2 w-full"
                                        wire:model="searchSalesOut">
                                </div>
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>

                                            <th scope="col" class=" table-th ">
                                                Name
                                            </th>

                                            <th scope="col" class=" table-th ">
                                                Action
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                        @foreach ($sales_outs as $sales_out)
                                            @if (!in_array($sales_out->id, $Esales_out_ids))
                                                <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                    <td class="table-td">{{ $sales_out->title }}</td>
                                                    <td class="table-td "><button
                                                            wire:click="pushSalesOut({{ $sales_out->id }})"
                                                            class="btn inline-flex justify-center btn-success light">Add</button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="setSalesOuts" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="setSalesOuts">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="setSalesOuts"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}

        @if ($commProfilesSection)
            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
                style="display: block;">
                <div class="modal-dialog relative w-auto pointer-events-none">
                    <div
                        class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                        <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                    Commissions Profiles
                                </h3>
                                <button wire:click="toggleProfiles" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                    data-bs-dismiss="modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6 space-y-4">
                                <div class="from-group">
                                    <label for="Eline_of_business" class="form-label">Select Profile</label>
                                    @foreach ($COMM_PROFILES as $COMM_PROFILE)
                                        <div class="checkbox-area">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden"
                                                    value="{{ json_encode(['id' => $COMM_PROFILE->id, 'title' => $COMM_PROFILE->title]) }}"
                                                    name="checkbox" wire:model.defer="Eprofiles">
                                                <span
                                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                    <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                        alt=""
                                                        class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                                <span
                                                    class="text-slate-500 dark:text-slate-400 text-sm leading-6">{{ $COMM_PROFILE->title }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="setProfiles" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="setProfiles">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="setProfiles"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- statusesSection --}}
        @if ($statusesSection)
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
                                    Statuses
                                </h3>
                                <button wire:click="togglestatuses" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                    data-bs-dismiss="modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6 space-y-4">
                                <div class="from-group">
                                    <label for="Eline_of_business" class="form-label">Select statuses</label>
                                    @foreach ($STATUSES as $STATUS)
                                        <div class="checkbox-area">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden" value="{{ $STATUS }}"
                                                    name="checkbox" wire:model="Estatuses">
                                                <span
                                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                    <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                        alt=""
                                                        class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                                <span
                                                    class="text-slate-500 dark:text-slate-400 text-sm leading-6">{{ ucwords(str_replace('_', ' ', $STATUS)) }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="setStatuses" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="setStatuses">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="setStatuses"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>

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
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                    Types
                                </h3>
                                <button wire:click="toggleTypes" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                    data-bs-dismiss="modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6 space-y-4">
                                <div>
                                    @foreach ($Etypes as $t)
                                        <span class="badge bg-slate-900 text-white capitalize rounded-3xl">
                                            {{ $t }}
                                        </span>
                                    @endforeach

                                </div>

                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>

                                            <th scope="col" class=" table-th ">
                                                Type
                                            </th>

                                            <th scope="col" class=" table-th ">
                                                Action
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                        @foreach ($Alltypes as $ttt)
                                            @if (!in_array($ttt, $Etypes))
                                                <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                    <td class="table-td">{{ $ttt }}</td>
                                                    <td class="table-td "><button
                                                            wire:click="pushType(`{{ $ttt }}`)"
                                                            class="btn inline-flex justify-center btn-success light">Add</button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="setTypes" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="setTypes">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="setTypes"
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
                                    Insurance Company
                                </h3>
                                <button wire:click="toggleCompany" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                    data-bs-dismiss="modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6 space-y-4">
                                <div>
                                    @foreach ($Ecompany_ids as $id)
                                        <!-- Fetch brand name based on ID -->
                                        @php
                                            $company = \App\Models\Insurance\Company::find($id)->name;
                                        @endphp

                                        <!-- Display brand name -->
                                        <span
                                            class="badge bg-slate-900 text-white capitalize rounded-3xl">{{ $company }}</span>
                                    @endforeach

                                </div>
                                <div class="from-group">
                                    <label for="searchCompany" class="form-label">Search Company</label>
                                    <input name="searchCompany" type="text" class="form-control mt-2 w-full"
                                        wire:model="searchCompany">
                                </div>
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>

                                            <th scope="col" class=" table-th ">
                                                Name
                                            </th>

                                            <th scope="col" class=" table-th ">
                                                Action
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                        @foreach ($companies as $company)
                                            @if (!in_array($company->id, $Ecompany_ids))
                                                <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                    <td class="table-td">{{ $company->name }}</td>
                                                    <td class="table-td "><button
                                                            wire:click="pushCompany({{ $company->id }})"
                                                            class="btn inline-flex justify-center btn-success light">Add</button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="setCompany" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="setCompany">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="setCompany"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($dateSection)
            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                tabindex="-1" aria-labelledby="dateFilterModal" aria-modal="true" role="dialog"
                style="display: block;">
                <div class="modal-dialog relative w-auto pointer-events-none">
                    <div
                        class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                        <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                <h3 class="text-xl font-medium text-white dark:text-white">
                                    Select Payment Date Range
                                </h3>
                                <button wire:click="toggleDateFilter" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
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
                                        <label class="form-label">From Date</label>
                                        <input type="date" class="form-control" wire:model="Edate_from">
                                    </div>
                                    <div>
                                        <label class="form-label">To Date</label>
                                        <input type="date" class="form-control" wire:model="Edate_to">
                                    </div>
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="setDateFilter" type="button" class="btn btn-dark">
                                    <span wire:loading.remove wire:target="setDateFilter">Apply</span>
                                    <iconify-icon class="text-xl spin-slow" wire:loading wire:target="setDateFilter"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        @endif

        @if ($collectionDateSection)
            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                tabindex="-1" aria-labelledby="dateFilterModal" aria-modal="true" role="dialog"
                style="display: block;">
                <div class="modal-dialog relative w-auto pointer-events-none">
                    <div
                        class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                        <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                <h3 class="text-xl font-medium text-white dark:text-white">
                                    Select Collection Date Range
                                </h3>
                                <button wire:click="toggleCollectionDateFilter" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
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
                                        <label class="form-label">From Date</label>
                                        <input type="date" class="form-control" wire:model="Ecollection_date_from">
                                    </div>
                                    <div>
                                        <label class="form-label">To Date</label>
                                        <input type="date" class="form-control" wire:model="Ecollection_date_to">
                                    </div>
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="setCollectionDateFilter" type="button" class="btn btn-dark">
                                    <span wire:loading.remove wire:target="setCollectionDateFilter">Apply</span>
                                    <iconify-icon class="text-xl spin-slow" wire:loading wire:target="setCollectionDateFilter"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        @endif

    </div>


    <script>
        document.addEventListener('livewire:load', function() {
            $('#demo').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "startDate": "10/20/2023",
                "endDate": "10/26/2023",
                "drops": "up"
            }, function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format(
                    'YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            });
        });
    </script>
</div>
