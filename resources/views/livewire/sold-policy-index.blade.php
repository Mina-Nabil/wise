<div>
    <div>
        <div class="flex justify-between flex-wrap items-center">
            <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                    Sold Policies
                </h4>
            </div>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
                @can('create', \App\Models\Business\SoldPolicy::class)
                    <button wire:click="openNewPolicySection"
                        class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                        Add Policy
                    </button>
                @endcan
            </div>
        </div>
        <div class="card-body px-6 pb-6">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">

                        <div class="flex items-center space-x-7 flex-wrap h-[30px]">
                            <input class="form-control py-2 flatpickr flatpickr-input active w-auto ml-5 mb-5" style="width:300px"
                            id="range-picker" data-mode="range" value="" type="text" readonly="readonly"
                            wire:model="dateRange">

                            <div class="secondary-radio">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" class="hidden" value="all" wire:model="isPaidCB">
                                    <span
                                        class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                                              duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-secondary-500 text-sm leading-6 capitalize">All</span>
                                </label>
                            </div>

                            <div class="secondary-radio">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" class="hidden" value="isPaid" wire:model="isPaidCB">
                                    <span
                                        class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                                              duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-secondary-500 text-sm leading-6 capitalize">Paid</span>
                                </label>
                            </div>

                            <div class="secondary-radio">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" class="hidden" value="notPaid" wire:model="isPaidCB">
                                    <span
                                        class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                                              duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-secondary-500 text-sm leading-6 capitalize">Not Paid</span>
                                </label>
                            </div>
                        </div>


                    </div>
                    <div class="card">
                        <header class="card-header cust-card-header noborder">
                            <iconify-icon wire:loading class="loading-icon text-lg"
                                icon="line-md:loading-twotone-loop"></iconify-icon>
                            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search"
                                wire:model="search">
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
                                                                        START DATE
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        END DATE
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        POLICY NUMBER
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        CLIENT NAME
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        STATUS
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        ACTION
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
                                                                            <span
                                                                                class="block date-text">{{ $policy->policy_number }}</span>
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
                                                                            @if ($policy->is_paid)
                                                                                <span
                                                                                    class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Paid</span>
                                                                            @endif
                                                                            @if ($policy->is_renewal)
                                                                                <span
                                                                                    class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Renewal</span>
                                                                            @endif
                                                                            @if ($policy->is_expired)
                                                                                <span
                                                                                    class="badge bg-danger-500 text-slate-800 text-danger-500 bg-opacity-30 capitalize rounded-3xl">Expired</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <div class="dropstart relative">
                                                                                <button
                                                                                    class="inline-flex justify-center items-center"
                                                                                    type="button"
                                                                                    id="tableDropdownMenuButton2"
                                                                                    data-bs-toggle="dropdown"
                                                                                    aria-expanded="false">
                                                                                    <iconify-icon
                                                                                        class="text-xl ltr:ml-2 rtl:mr-2"
                                                                                        icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                                </button>
                                                                                <ul
                                                                                    class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                                    <li>
                                                                                        <a href="{{ route('sold.policy.show', $policy->id) }}"
                                                                                            class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                            <iconify-icon
                                                                                                icon="heroicons-outline:eye"></iconify-icon>
                                                                                            <span>View</span></a>
                                                                                    </li>
                                                                                    {{-- <li>
                                                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal"
                                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                                <iconify-icon icon="clarity:note-edit-line"></iconify-icon>
                                                                                                <span>Edit</span></a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a href="#"
                                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                                <iconify-icon icon="fluent:delete-28-regular"></iconify-icon>
                                                                                                <span>Delete</span></a>
                                                                                        </li> --}}
                                                                                </ul>
                                                                            </div>
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
                                                                    No Pold Policies with the
                                                                    applied
                                                                    filters</h2>
                                                                <p class="card-text">Try changing the filters or
                                                                    search terms for this view.
                                                                </p>
                                                                <a href="{{ url('/sold-policies') }}"
                                                                    class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                                                    all Sold Policies</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- END: empty filter result --}}
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

    @if ($newPolicySection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Create Sold Policy
                            </h3>

                            <button wire:click="closeNewPolicySection" type="button"
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
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="from-group">
                                <p class="text-lg"><b>Select client</b></p>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Client Type</label>
                                        @if ($clientStatus)
                                            {{ $clientType }}
                                        @else
                                            <select
                                                class="form-control w-full mt-2 @error('clientType') !border-danger-500 @enderror"
                                                wire:model="clientType">
                                                <option value="Customer"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    Customer</option>
                                                <option value="Corporate"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    Corporate</option>
                                            </select>
                                        @endif

                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">
                                            @if ($clientStatus)
                                                Selected client
                                            @else
                                                Search client <iconify-icon wire:loading wire:target="searchClient"
                                                    class="loading-icon text-lg"
                                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                            @endif

                                        </label>
                                        @if ($clientStatus)
                                            {{ $selectedClientName }}
                                        @else
                                            <input placeholder="Search..." type="text" class="form-control"
                                                wire:model="searchClient">
                                        @endif

                                    </div>
                                </div>
                                @error('clientType')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="text-sm">
                                @if ($clientNames)
                                    @foreach ($clientNames as $client)
                                        <p><iconify-icon icon="material-symbols:person"></iconify-icon>
                                            {{ $client->name }} | {{ $client->email ?? 'N/A' }} | <Span
                                                wire:click="selectClient({{ $client->id }})"
                                                class="cursor-pointer text-primary-500">Select Client</Span></p>
                                    @endforeach

                                @endif
                            </div>

                            <div class="from-group">
                                <p class="text-lg"><b>Select Policy</b></p>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">
                                        @if ($policyStatus)
                                            Selected Policy
                                        @else
                                            Search Policy <iconify-icon wire:loading wire:target="searchPolicy"
                                                class="loading-icon text-lg"
                                                icon="line-md:loading-twotone-loop"></iconify-icon>
                                        @endif
                                    </label>
                                    @if ($policyStatus)
                                        {{ $selectedPolicyName }}
                                    @else
                                        <input placeholder="Search..." type="text" class="form-control"
                                            wire:model="searchPolicy">
                                    @endif
                                </div>
                            </div>
                            <div class="text-sm">
                                @if ($policyData)
                                    @foreach ($policyData as $policyInfo)
                                        <p><iconify-icon icon="material-symbols:person"></iconify-icon>
                                            {{ $policyInfo->company->name }} | {{ $policyInfo->name ?? 'N/A' }} |
                                            {{ str_replace('_', ' ', ucwords($policyInfo->business)) }} | <Span
                                                wire:click="selectPolicy({{ $policyInfo->id }})"
                                                class="cursor-pointer text-primary-500">Select Policy</Span></p>
                                    @endforeach

                                @endif
                            </div>


                            {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                            @if ($type === 'personal_motor' && $clientType === 'Customer')
                            @else
                                <div class="from-group">
                                    <label for="lastName" class="form-label">Item title</label>
                                    <input type="text" class="form-control mt-2 w-full" wire:model.defer="item_title">
                                    @error('item_title')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            <div class="from-group">
                                <label for="lastName" class="form-label">Is Renewal</label>
                                <div class="flex items-center mr-2 sm:mr-4 mt-2 space-x-2">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer" wire:model="isRenewal">
                                        <div
                                            class="w-14 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:z-10 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500">
                                        </div>
                                        <span class="absolute left-1 z-20 text-xs text-white font-Inter font-normal opacity-0 peer-checked:opacity-100">On</span>
                                        <span class="absolute right-1 z-20 text-xs text-white font-Inter font-normal opacity-100 peer-checked:opacity-0">Off</span>
                                    </label>
                                </div>
                            </div>

                        </div> --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                                <div class="from-group">
                                    <label for="policy_number" class="form-label">Policy Number</label>
                                    <input name="policy_number" class="form-control mt-2 w-full"
                                        wire:model.defer="policy_number">
                                    @error('policy_number')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="insured_value" class="form-label">Insured Value</label>
                                    <input type="number" name="insured_value" class="form-control mt-2 w-full"
                                        wire:model.defer="insured_value">
                                    @error('insured_value')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-2">
                                <div class="from-group">
                                    <label for="net_rate" class="form-label">Net Rate</label>
                                    <input type="number" name="net_rate" class="form-control mt-2 w-full"
                                        wire:model.defer="net_rate" max=100>
                                    @error('net_rate')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="net_premium" class="form-label">Net Premium</label>
                                    <input type="number" name="net_premium" class="form-control mt-2 w-full"
                                        wire:model.defer="net_premium">
                                    @error('net_premium')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="gross_premium" class="form-label">Gross Premium</label>
                                    <input type="number" name="gross_premium" class="form-control mt-2 w-full"
                                        wire:model.defer="gross_premium">
                                    @error('gross_premium')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                                <div class="from-group">
                                    <label for="installments_count" class="form-label">Installments Count</label>
                                    <input type="number" name="installments_count" class="form-control mt-2 w-full"
                                        wire:model.defer="installments_count">
                                    @error('installments_count')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="sold_payment_frequency" class="form-label">Payment
                                        Frequency</label>
                                    <select name="payment_frequency" id="basicSelect"
                                        class="form-control w-full mt-2  @error('payment_frequency') !border-danger-500 @enderror"
                                        wire:model="payment_frequency">
                                        <option
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select an option...</option>
                                        @foreach ($PAYMENT_FREQS as $freqs)
                                            <option value="{{ $freqs }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords($freqs) }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('payment_frequency')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                                <div class="from-group">
                                    <label for="issuing_date" class="form-label">Issuing</label>
                                    <input type="date" name="issuing_date" class="form-control mt-2 w-full"
                                        wire:model.defer="issuing_date">
                                    @error('issuing_date')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                                <div class="from-group">
                                    <label for="start" class="form-label">start</label>
                                    <input type="date" name="start" class="form-control mt-2 w-full"
                                        wire:model.defer="start">
                                    @error('start')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="expiry" class="form-label">Expiry</label>
                                    <input type="date" name="expiry" class="form-control mt-2 w-full"
                                        wire:model.defer="expiry">
                                    @error('expiry')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="from-group">
                                <label for="discount" class="form-label">Discount</label>
                                <input type="number" name="discount" class="form-control mt-2 w-full"
                                    wire:model.defer="discount">
                                @error('discount')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-2">
                                <div class="from-group">
                                    <label for="car_chassis" class="form-label">Car Chassis</label>
                                    <input type="text" name="car_chassis" class="form-control mt-2 w-full"
                                        wire:model.defer="car_chassis">
                                    @error('car_chassis')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="car_plate_no" class="form-label">Car Plate No.</label>
                                    <input type="text" name="car_plate_no" class="form-control mt-2 w-full"
                                        wire:model.defer="car_plate_no">
                                    @error('car_plate_no')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="from-group">
                                    <label for="car_engine" class="form-label">Car Engine</label>
                                    <input type="text" name="car_engine" class="form-control mt-2 w-full"
                                        wire:model.defer="car_engine">
                                    @error('car_engine')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="from-group">
                                <label for="note" class="form-label">Note</label>
                                <input type="text" name="note" class="form-control mt-2 w-full"
                                    wire:model.defer="note">
                                @error('note')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area mb-3">
                                <label for="inFavorTo" class="form-label">In Favor To</label>
                                <input name="inFavorTo"
                                    class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('inFavorTo') !border-danger-500 @enderror"
                                    id="default-picker" type="text" wire:model.defer="inFavorTo"
                                    autocomplete="off">
                                @error('inFavorTo')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area mb-3">
                                <label for="policyDoc" class="form-label">Policy Document</label>
                                <input name="policyDoc"
                                    class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('policyDoc') !border-danger-500 @enderror"
                                    id="default-picker" type="file" wire:model.defer="policyDoc">
                                @error('policyDoc')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="from-group">
                            <label for="lastName" class="form-label">Item Description</label>
                            <textarea class="form-control mt-2 w-full" wire:model.defer="item_desc"></textarea>
                            @error('item_desc')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="from-group">
                            <label for="lastName" class="form-label">Note</label>
                            <textarea class="form-control mt-2 w-full" wire:model.defer="note"></textarea>
                            @error('note')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area mb-3">
                            <label for="inFavorTo" class="form-label">In Favor To</label>
                            <input name="inFavorTo" class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('inFavorTo') !border-danger-500 @enderror" id="default-picker" type="text" wire:model.defer="inFavorTo" autocomplete="off">
                            @error('inFavorTo')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                            <div class="input-area mb-3">
                                <label for="time-date-picker" class="form-label">Due Date</label>
                                <input class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('dueDate') !border-danger-500 @enderror" id="default-picker" value="" type="date" wire:model.defer="dueDate" autocomplete="off">
                                @error('dueDate')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area mb-3">
                                <label for="time-date-picker" class="form-label">Time </label>
                                <input type="time" class="form-control  @error('dueTime') !border-danger-500 @enderror" id="appt" name="appt" min="09:00" max="18:00" wire:model.defer="dueTime" autocomplete="off" />
                                @error('dueTime')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>
                        </div> --}}
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addSoldPolicy" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>
