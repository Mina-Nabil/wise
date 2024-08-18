<div>
    {{--  justify-center --}}
    {{-- sm:flex --}}
    <div>
        <div class="max-w-screen-lg">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-5 mb-5">
                <h4>
                    <b> {{ $company->name }} </b><iconify-icon class="ml-3" style="position: absolute" wire:loading wire:target="changeSection" icon="svg-spinners:180-ring"></iconify-icon>


                </h4>
                <div class="card-body flex flex-col col-span-2" wire:ignore>
                    <div class="card-text h-full">
                        <div>
                            <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0" id="tabs-tab" role="tablist">
                                <li class="nav-item" role="presentation" wire:click="changeSection('info')">
                                    <a href="#tabs-profile-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'info') active @endif dark:text-slate-300"
                                        id="tabs-profile-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-profile-withIcon" role="tab" aria-controls="tabs-profile-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1" icon="material-symbols:info-outline"></iconify-icon>
                                        Info</a>
                                </li>
                                <li class="nav-item" role="presentation" wire:click="changeSection('invoices')">
                                    <a href="#tabs-messages-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'invoices') active @endif dark:text-slate-300"
                                        id="tabs-messages-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-messages-withIcon" role="tab" aria-controls="tabs-messages-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1" icon="basil:invoice-outline"></iconify-icon>
                                        Invoices</a>
                                </li>
                                <li class="nav-item" role="presentation" wire:click="changeSection('policies')">
                                    <a href="#tabs-messages-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'policies') active @endif dark:text-slate-300"
                                        id="tabs-messages-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-messages-withIcon" role="tab" aria-controls="tabs-messages-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1" icon="iconoir:privacy-policy"></iconify-icon>
                                        Sold Policies</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if ($section === 'info')
                @endif

                @if ($section === 'invoices')
                    <div class="card">
                        <header class="card-header noborder">
                            <h4 class="card-title">All Invoices
                            </h4>
                            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
                                @can('create', \App\Models\Business\SoldPolicy::class)
                                    <button wire:click="openNewInvoiceSec" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                                        Add Invoive
                                    </button>
                                @endcan
                            </div>
                        </header>
                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto -mx-6 ">
                                <span class=" col-span-8  hidden"></span>
                                <span class="  col-span-4 hidden"></span>
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                                <tr>

                                                    <th scope="col" class=" table-th ">
                                                        Serial
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Gross total
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Tax total
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Net total
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Author
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Action
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @forelse ($company->invoices as $invoice)
                                                    <tr>
                                                        <td class="table-td">
                                                            <b>{{ $invoice->serial }}</b>
                                                        </td>
                                                        <td class="table-td ">{{ 'EGP ' . number_format($invoice->gross_total, 2) }}</td>
                                                        <td class="table-td ">{{ 'EGP ' . number_format($invoice->tax_total, 2) }}</td>
                                                        <td class="table-td ">{{ 'EGP ' . number_format($invoice->net_total, 2) }}</td>
                                                        <td class="table-td ">{{ $invoice->creator->full_name }}</td>
                                                        <td class="table-td ">
                                                            <div>
                                                                <div class="relative">
                                                                    <div class="dropdown relative">
                                                                        <button class="text-xl text-center block w-full " type="button" id="transactionDropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                        </button>
                                                                        <ul
                                                                            class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                            <li>
                                                                                <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                                    View</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                                    Edit</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                                    Delete</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                @empty
                                                    <td class="text-center p-5" colspan="6">
                                                        No invoices found.
                                                    </td>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


    @if ($newInvoiceSection)


        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Create Invoice
                            </h3>
                            <button wire:click="closeNewInvoiceSec" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            {{-- @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif --}}
                            <div class="input-area">
                                <label for="firstName" class="form-label">Serial</label>
                                <input type="number" step="0.01" wire:model="serial" class="form-control @error('serial') !border-danger-500 @enderror">
                                @error('serial')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Amount</label>
                                    <input type="number" step="0.01" wire:model="gross_total" class="form-control @error('gross_total') !border-danger-500 @enderror">
                                    @error('gross_total')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Tax</label>
                                    <input wire:model="tax_total" disabled type="number" class="form-control">
                                    <!-- Hint message -->
                                    <small class="form-text text-muted">
                                        Tax is 5% of the gross total.
                                    </small>
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Payment Perm</label>
                                    <input wire:model="net_total" disabled type="number" class="form-control">
                                    <!-- Hint message -->
                                    <small class="form-text text-muted">
                                        Perm is 95% of the gross total.
                                    </small>
                                </div>
                            </div>

                            <h5>Available Sold Policies</h5>

                            <div class="card-body pb-6">
                                <div class="overflow-x-auto ">
                                    <div class="inline-block min-w-full align-middle">
                                        <div class="overflow-hidden ">
                                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                                <thead class="">
                                                    <tr>

                                                        <th scope="col" class=" table-th border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                            #
                                                        </th>

                                                        <th scope="col" class=" table-th border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                            Policy Name
                                                        </th>

                                                        <th scope="col" class=" table-th border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                            Client
                                                        </th>

                                                        <th scope="col" class=" table-th border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                            Action
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                    @foreach ($available_policies as $policy)
                                                        <tr>
                                                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700">{{ $policy->policy_number }}</td>
                                                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700">{{ $policy->policy->name }}</td>
                                                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                                <div class="flex-1 text-start">

                                                                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                                        {{ ucwords($policy->client_type) }}
                                                                    </div>
                                                                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                                        @if ($policy->client_type === 'customer')
                                                                            {{ $policy->client->first_name . ' ' . $policy->client->middle_name . ' ' . $policy->client->last_name }}
                                                                        @elseif($policy->client_type === 'corporate')
                                                                            {{ $policy->client->name }}
                                                                        @endif
                                                                    </h4>
                                                                </div>
                                                            </td>
                                                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700">
                                                                @if (in_array($policy->id, array_column($sold_policies_entries, 'id')))
                                                                    <button type="button" disabled class="btn btn-sm inline-flex justify-center btn-outline-success rounded-[25px]">selected</button>
                                                                @else
                                                                    <button type="button" wire:click="selectPolicy({{ $policy->id }})" class="btn btn-sm inline-flex justify-center btn-success rounded-[25px]">Select</button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5>Selected Policies</h5>
                            @foreach ($sold_policies_entries as $index => $entry)
                                @php
                                    $policy = \App\Models\Business\SoldPolicy::find($entry['id']);
                                @endphp

                                <div class="card ring-1 ring-secondary-500">
                                    <div class="card-body p-6">
                                        <div class="flex-1 items-center">
                                            <div class="card-title mb-5">#{{ $policy->policy_number }} • @if ($policy->client_type === 'customer')
                                                    {{ $policy->client->first_name . ' ' . $policy->client->middle_name . ' ' . $policy->client->last_name }}
                                                @elseif($policy->client_type === 'corporate')
                                                    {{ $policy->client->name }}
                                                @endif

                                                <button wire:click="removeEntry({{ $index }})" class="btn btn-sm inline-flex float-right h-12 w-12 items-center justify-center btn-outline-danger  rounded-full">
                                                    <span class="flex items-center">
                                                        <iconify-icon class="text-xl" icon="uiw:delete"></iconify-icon>
                                                    </span>
                                                </button>
                                            </div>
                                            <p class="card-text">
                                            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">

                                            </h4>
                                            </p>

                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                                <div class="input-area">
                                                    <label for="firstName" class="form-label">Amount</label>
                                                    <input type="number" step="0.01" wire:model.defer="sold_policies_entries.{{ $index }}.amount" class="form-control @error('sold_policies_entries.' . $index . '.amount') !border-danger-500 @enderror">

                                                    @error('sold_policies_entries.' . $index . '.amount')
                                                        <span class="font-Inter text-sm text-danger-500 inline-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="input-area">
                                                    <label for="firstName" class="form-label">Payment Perm</label>
                                                    <input type="number" step="0.01" wire:model.defer="sold_policies_entries.{{ $index }}.pymnt_perm" class="form-control @error('sold_policies_entries.' . $index . '.pymnt_perm') !border-danger-500 @enderror">

                                                    @error('sold_policies_entries.' . $index . '.pymnt_perm')
                                                        <span class="font-Inter text-sm text-danger-500 inline-block">{{ $message }}</span>
                                                    @enderror
                                                </div>



                                            </div>


                                            {{-- <button type="button" wire:click="removeEntry({{ $index }})">Remove</button> --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach




                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addInvoice" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>




                    </div>
                </div>
            </div>
        </div>


    @endif
</div>
