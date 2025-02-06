<div>
    <div>
        <div class="flex justify-between flex-wrap items-center">
            <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                    Outstanding Sold Policies
                </h4>
            </div>
        </div>

        <div class="flex justify-between items-center space-x-7 flex-wrap h-[30px]">
            <div class="flex">
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
                        <span class="text-secondary-500 text-sm leading-6 capitalize">Policy Outstanding</span>
                    </label>
                </div>

                <div class="secondary-radio">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" class="hidden" value="commission" wire:model="outstandingType">
                        <span
                            class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                        <span class="text-secondary-500 text-sm leading-6 capitalize">Commission Outstanding</span>
                    </label>
                </div>
            </div>

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
                    <li wire:click="toggleStartDate">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                dark:hover:text-white cursor-pointer">
                            Start date ( From-To )</span>
                    </li>

                </ul>
            </div>
        </div>



    </div>


    <div class="card-body px-6 pb-6">
        <div class=" -mx-6">
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
                                                                        START
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        END
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        POLICY#
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        INVOICE
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        INVOICE PYMT
                                                                    </th>
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
                                                                    <tr class="even:bg-slate-50 dark:even:bg-slate-700">
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
                                                                                class="block date-text">{{ \Carbon\Carbon::parse($policy->start)->format('d-m-Y') }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span
                                                                                class="block date-text">{{ \Carbon\Carbon::parse($policy->expiry)->format('d-m-Y') }}</span>
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
                                                                        <td class="table-td">
                                                                            <span
                                                                                class="block date-text">{{ $policy->last_company_comm_payment ? \Carbon\Carbon::parse($policy->last_company_comm_payment?->created_at)->format('d-m-Y') : 'N/A' }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span
                                                                                class="block date-text">{{ $policy->last_company_comm_payment?->payment_date ? \Carbon\Carbon::parse($policy->last_company_comm_payment->payment_date)->format('d-m-Y') : 'N/A' }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <div
                                                                                class="flex space-x-3 items-center text-left rtl:space-x-reverse">
                                                                                <div class="flex-none">
                                                                                    <div
                                                                                        class="h-10 w-10 rounded-full text-sm bg-[#E0EAFF] dark:bg-slate-700 flex flex-col items-center justify-center font-medium -tracking-[1px]">
                                                                                        @if ($policy->client_type === 'customer')
                                                                                            <iconify-icon
                                                                                                icon="raphael:customer"></iconify-icon>
                                                                                        @elseif($policy->client_type === 'corporate')
                                                                                            <iconify-icon
                                                                                                icon="mdi:company"></iconify-icon>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                    class="flex-1 font-medium text-sm leading-4 whitespace-nowrap">
                                                                                    <a class="hover:underline cursor-pointer"
                                                                                        href="{{ route($policy->client_type . 's.show', $policy->client_id) }}">
                                                                                        @if ($policy->client_type === 'customer')
                                                                                            {{ $policy->client->first_name . ' ' . $policy->client->middle_name . ' ' . $policy->client->last_name }}
                                                                                        @elseif($policy->client_type === 'corporate')
                                                                                            {{ $policy->client->name }}
                                                                                        @endif
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
