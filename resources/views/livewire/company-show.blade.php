<div>
    {{--  justify-center --}}
    {{-- sm:flex --}}
    <div>
        <div class="max-w-screen-lg">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-5 mb-5">
                <div class="flex justify-between">
                    <div>
                        <div>
                            <h4><b> {{ $company->name }} </b></h4>
                            <span class="text-sm"><iconify-icon class="ml-3" style="position: absolute" wire:loading wire:target="changeSection" icon="svg-spinners:180-ring"></iconify-icon></span>
                            @if ($company->note)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><iconify-icon icon="hugeicons:note" width="1.2em" height="1.2em"></iconify-icon> {{ $company->note }}</p>
                            @endif
                        </div>

                    </div>


                    <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
                        @can('create', \App\Models\Insurance\Company::class)
                            <button wire:click="openEditInfo" class="btn inline-flex justify-center btn-outline-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                                Edit Info
                            </button>
                        @endcan
                    </div>
                </div><br>

                <div class="card-body flex flex-col col-span-2" wire:ignore>
                    <div class="card-text h-full">
                        <div>
                            <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0" id="tabs-tab" role="tablist">
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
                                <li class="nav-item" role="presentation" wire:click="changeSection('emails')">
                                    <a href="#tabs-profile-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'emails') active @endif dark:text-slate-300"
                                        id="tabs-profile-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-profile-withIcon" role="tab" aria-controls="tabs-profile-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1" icon="material-symbols-light:stacked-email-outline"></iconify-icon>
                                        Emails</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if ($section === 'emails')
                    <div class="card">
                        <header class="card-header noborder">
                            <h4 class="card-title">Company Emails
                            </h4>
                            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
                                <button wire:click="openNewEmail" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                                    Add Email
                                </button>
                            </div>
                        </header>
                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto -mx-6">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class="bg-slate-200 dark:bg-slate-700">
                                                <tr>

                                                    <th scope="col" class=" table-th ">
                                                        Type
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Email
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Name
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Action
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">



                                                @foreach ($companyEmails as $email)
                                                    <tr>
                                                        <td class="table-td">
                                                            @if ($email->is_primary)
                                                                <span class="success-400">
                                                                    <iconify-icon icon="material-symbols:star"></iconify-icon>
                                                                </span>
                                                            @endif

                                                            {{ $email->type }}
                                                        </td>

                                                        <td class="table-td">{{ $email->email }}</td>

                                                        <td class="table-td ">
                                                            {{ $email->contact_first_name }}

                                                            {{ $email->contact_last_name }}</td>

                                                        <td class="table-td flex">
                                                            @can('edit', $company)
                                                                <button class="toolTip onTop action-btn m-1 " data-tippy-content="Edit" wire:click="editEmailRow({{ $email->id }})" type="button">
                                                                    <iconify-icon icon="iconamoon:edit-bold"></iconify-icon>
                                                                </button>

                                                                <button class="toolTip onTop action-btn m-1 " data-tippy-content="Edit" wire:click="confirmDelEmail({{ $email->id }})" type="button">
                                                                    <iconify-icon icon="heroicons-outline:trash"></iconify-icon>
                                                                </button>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        {{ $companyEmails->links('vendor.livewire.bootstrap') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                        Add Invoice
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
                                                                    <div class="dropstart relative">
                                                                        <button class="inline-flex justify-center items-center" type="button" id="tableDropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                        </button>
                                                                        <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                            <li wire:click="printInvoice({{ $invoice->id }})">
                                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#editModal"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                    <iconify-icon icon="lets-icons:print-light"></iconify-icon>
                                                                                    <span>Print</span></a>
                                                                            </li>
                                                                            <li wire:click="$emit('showConfirmation', 'Are you sure you want to delete this invoice?', 'deleteInvoice' , {{ $invoice->id }})">
                                                                                <a href="#"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                    <iconify-icon icon="fluent:delete-28-regular"></iconify-icon>
                                                                                    <span>Delete</span></a>
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


                @if ($section === 'policies')
                    <div class="card">
                        <header class="card-header cust-card-header noborder">
                            <iconify-icon wire:loading wire:target='seachAllSoldPolicies' class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
                            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search" wire:model="seachAllSoldPolicies">
                        </header>

                        <div class="tab-content mt-6" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-list" role="tabpanel" aria-labelledby="pills-list-tab">
                                <div class="tab-content">
                                    <div class="card">
                                        <div class="card-body px-6 rounded overflow-hidden pb-3">
                                            <div class="overflow-x-auto -mx-6">
                                                <div class="inline-block min-w-full align-middle">
                                                    <div class="overflow-hidden ">
                                                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 ">
                                                            <thead class="bg-slate-200 dark:bg-slate-700">
                                                                <tr>
                                                                    <th scope="col" class="table-th ">
                                                                        POLICY
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        CREATOR
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
                                                                        CLIENT
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        STATUS
                                                                    </th>
                                                                    <th scope="col" class="table-th ">
                                                                        ACTION
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                                @foreach ($soldPolicies as $policy)
                                                                    <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                                        <td class="table-td">
                                                                            <div class="flex-1 text-start">
                                                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                                                    {{ $policy->policy->name }}
                                                                                </h4>
                                                                            </div>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span class="block date-text">{{ $policy->creator_id == 10 ? 'Uploaded' : $policy->creator->username }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span class="block date-text">{{ \Carbon\Carbon::parse($policy->start)->format('d-m-Y') }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span class="block date-text">{{ \Carbon\Carbon::parse($policy->expiry)->format('d-m-Y') }}</span>
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <span class="block date-text">{{ $policy->policy_number }}</span>
                                                                        </td>
                                                                        <td class="table-td">

                                                                            <div class="flex space-x-3 items-center text-left rtl:space-x-reverse">
                                                                                <div class="flex-none">
                                                                                    <div class="h-10 w-10 rounded-full text-sm bg-[#E0EAFF] dark:bg-slate-700 flex flex-col items-center justify-center font-medium -tracking-[1px]">
                                                                                        @if ($policy->client_type === 'customer')
                                                                                            <iconify-icon icon="raphael:customer"></iconify-icon>
                                                                                        @elseif($policy->client_type === 'corporate')
                                                                                            <iconify-icon icon="mdi:company"></iconify-icon>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-1 font-medium text-sm leading-4 whitespace-nowrap">
                                                                                    <a class="hover:underline cursor-pointer" href="{{ route($policy->client_type . 's.show', $policy->client_id) }}">
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
                                                                                <span class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Validated</span>
                                                                            @endif
                                                                            @if ($policy->is_paid)
                                                                                <span class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Paid</span>
                                                                            @endif
                                                                            @if ($policy->is_renewal)
                                                                                <span class="badge bg-success-500 text-slate-800 text-success-500 bg-opacity-30 capitalize rounded-3xl">Renewal</span>
                                                                            @endif
                                                                            @if ($policy->is_expired)
                                                                                <span class="badge bg-danger-500 text-slate-800 text-danger-500 bg-opacity-30 capitalize rounded-3xl">Expired</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="table-td">
                                                                            <div class="dropstart relative">
                                                                                <button class="inline-flex justify-center items-center" type="button" id="tableDropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                                </button>
                                                                                <ul
                                                                                    class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                                    <li>
                                                                                        <a href="{{ route('sold.policy.show', $policy->id) }}"
                                                                                            class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                            <iconify-icon icon="heroicons-outline:eye"></iconify-icon>
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
                                                                <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon>
                                                                </h2>
                                                                <h2 class="card-title text-slate-900 dark:text-white mb-3">
                                                                    No Pold Policies with the
                                                                    applied
                                                                    filters</h2>
                                                                <p class="card-text">Try changing the filters or
                                                                    search terms for this view.
                                                                </p>
                                                                <a href="{{ url('/sold-policies') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
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
                @endif
            </div>
        </div>
    </div>


    @if ($newInvoiceSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 1000px;">
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
                                    <input type="number" step="0.01" wire:model="gross_total" class="form-control @error('gross_total') !border-danger-500 @enderror" disabled>
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
                                    <label for="firstName" class="form-label">Net Total</label>
                                    <input wire:model="net_total" disabled type="number" class="form-control">
                                    <!-- Hint message -->
                                    <small class="form-text text-muted">
                                        Perm is 95% of the gross total.
                                    </small>
                                </div>
                            </div>

                            <h5>Available Sold Policies</h5>
                            <small class="form-text text-muted">
                                * Only up to 5 records are displayed at a time. Use the search bar to find specific sold policies.
                            </small>
                            <div class="card-body pb-6">
                                <div class="overflow-x-auto ">
                                    <div class="inline-block min-w-full align-middle">
                                        <div class="overflow-hidden ">
                                            <input type="text" wire:model="search_sold_policy" placeholder="Search sold policy..." class="form-control">
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
                                                            Commission
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
                                                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700">{{ $policy->after_tax_comm }}</td>
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
                                                                    <button type="button" wire:click="unselectPolicy({{ $policy->id }})" class="btn btn-sm inline-flex justify-center btn-outline-success rounded-[25px]">selected</button>
                                                                @else
                                                                    <button type="button" wire:click="selectPolicy({{ $policy->id }})" class="btn btn-sm inline-flex justify-center btn-success rounded-[25px]">Select</button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    @if (empty($available_policies))
                                                        <tr>
                                                            <td colspan="5" class="text-center p-4">
                                                                <p>No policies available.</p>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                </tbody>
                                            </table>



                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5>Selected Policies</h5>
                            @if (empty($sold_policies_entries))
                                <div class="py-[18px] px-6 font-normal text-sm rounded-md bg-white text-warning-500 border border-warning-500
                                    dark:bg-slate-800">
                                    <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="flex-1 font-Inter">
                                            No sold policies have been selected yet.
                                        </div>
                                    </div>
                                </div>
                            @endif
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
                                                    <input type="number" step="0.01" wire:change="updateTotal"  wire:model.defer="sold_policies_entries.{{ $index }}.amount" class="form-control @error('sold_policies_entries.' . $index . '.amount') !border-danger-500 @enderror">

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




    @if ($editInfoSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                        rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                {{ $company->name }}
                            </h3>
                            <button wire:click="closeEditInfo" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                    dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                            11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="input-area">
                                <label for="name" class="form-label">Company Name*</label>
                                <input id="name" type="text" class="form-control @error('companyInfoName') !border-danger-500 @enderror" placeholder="Company Name" wire:model.defer="companyInfoName">
                                @error('companyInfoName')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-area">
                                <label for="name" class="form-label">Note</label>
                                <textarea id="name" type="text" class="form-control @error('companyInfoNote') !border-danger-500 @enderror" placeholder="Leave a note..." wire:model.defer="companyInfoNote"></textarea>
                                @error('companyInfoNote')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <button wire:click="saveChanges" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500 btn-sm">Save
                                Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($newEmailSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Email
                            </h3>
                            <button wire:click="closeNewEmail" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="input-area">
                                <label for="type" class="form-label">Type</label>
                                <select wire:model="type" class="form-control @error('type') !border-danger-500 @enderror">
                                    <option value="" disabled>Select type</option>
                                    @foreach ($Emailtypes as $typeOption)
                                        <option value="{{ $typeOption }}">{{ $typeOption }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" wire:model="email" class="form-control @error('email') !border-danger-500 @enderror">
                                @error('email')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="is_primary" class="form-label">Primary</label>
                                <input type="checkbox" wire:model="is_primary" class="form-checkbox">
                            </div>
                            <div class="input-area">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" wire:model="first_name" class="form-control @error('first_name') !border-danger-500 @enderror">
                                @error('first_name')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" wire:model="last_name" class="form-control @error('last_name') !border-danger-500 @enderror">
                                @error('last_name')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="note" class="form-label">Note</label>
                                <textarea wire:model="note" class="form-control @error('note') !border-danger-500 @enderror"></textarea>
                                @error('note')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addEmail" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



</div>
