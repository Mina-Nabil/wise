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
                            <span class="text-sm"><iconify-icon class="ml-3" style="position: absolute" wire:loading
                                    wire:target="changeSection" icon="svg-spinners:180-ring"></iconify-icon></span>
                            @if ($company->note)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><iconify-icon
                                        icon="hugeicons:note" width="1.2em" height="1.2em"></iconify-icon>
                                    {{ $company->note }}</p>
                            @endif
                        </div>

                    </div>


                    <div
                        class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
                        @can('create', \App\Models\Insurance\Company::class)
                            <button wire:click="openEditInfo"
                                class="btn inline-flex justify-center btn-outline-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                                Edit Info
                            </button>
                        @endcan
                    </div>
                </div><br>

                <div class="card-body flex flex-col col-span-2" wire:ignore>
                    <div class="card-text h-full">
                        <div>
                            <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0"
                                id="tabs-tab" role="tablist">
                                @can('create', \App\Models\Insurance\Company::class)
                                    <li class="nav-item" role="presentation" wire:click="changeSection('invoices')">
                                        <a href="#tabs-messages-withIcon"
                                            class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'invoices') active @endif dark:text-slate-300"
                                            id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                                            data-bs-target="#tabs-messages-withIcon" role="tab"
                                            aria-controls="tabs-messages-withIcon" aria-selected="false">
                                            <iconify-icon class="mr-1" icon="basil:invoice-outline"></iconify-icon>
                                            Invoices</a>
                                    </li>
                                @endcan

                                <li class="nav-item" role="presentation" wire:click="changeSection('emails')">
                                    <a href="#tabs-profile-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'emails') active @endif dark:text-slate-300"
                                        id="tabs-profile-withIcon-tab" data-bs-toggle="pill"
                                        data-bs-target="#tabs-profile-withIcon" role="tab"
                                        aria-controls="tabs-profile-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1"
                                            icon="material-symbols-light:stacked-email-outline"></iconify-icon>
                                        Emails</a>
                                </li>

                                @can('create', \App\Models\Insurance\Company::class)
                                    <li class="nav-item" role="presentation" wire:click="changeSection('extras')">
                                        <a href="#tabs-profile-withIcon"
                                            class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'extras') active @endif dark:text-slate-300"
                                            id="tabs-profile-withIcon-tab" data-bs-toggle="pill"
                                            data-bs-target="#tabs-profile-withIcon" role="tab"
                                            aria-controls="tabs-profile-withIcon" aria-selected="false">
                                            <iconify-icon class="mr-1" icon="ph:plus-bold"></iconify-icon>
                                            Extras</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                </div>

                @if ($section === 'emails')
                    <div class="card">
                        <header class="card-header noborder">
                            <h4 class="card-title">Company Contacts
                            </h4>
                            <div
                                class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
                                <button wire:click="openNewEmail"
                                    class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                                    Add Contact
                                </button>
                            </div>
                        </header>
                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto -mx-6">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class="bg-slate-200 dark:bg-slate-700">
                                                <tr>

                                                    <th scope="col" class=" table-th ">
                                                        Type
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Email/Phone
                                                    </th>

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



                                                @foreach ($companyEmails as $email)
                                                    <tr>
                                                        <td class="table-td">
                                                            @if ($email->is_primary)
                                                                <span class="success-400">
                                                                    <iconify-icon
                                                                        icon="material-symbols:star"></iconify-icon>
                                                                </span>
                                                            @endif

                                                            {{ $email->type }}
                                                        </td>

                                                        <td class="table-td">
                                                            <div>{{ $email->email }}</div>
                                                            @if($email->phone)
                                                                <div class="text-sm text-slate-500">{{ $email->phone }}</div>
                                                            @endif
                                                        </td>

                                                        <td class="table-td ">
                                                            {{ $email->contact_first_name }}

                                                            {{ $email->contact_last_name }}</td>

                                                        <td class="table-td flex">
                                                            @can('edit', $company)
                                                                <button class="toolTip onTop action-btn m-1 "
                                                                    data-tippy-content="Edit"
                                                                    wire:click="editEmailRow({{ $email->id }})"
                                                                    type="button">
                                                                    <iconify-icon icon="iconamoon:edit-bold"></iconify-icon>
                                                                </button>

                                                                <button class="toolTip onTop action-btn m-1 "
                                                                    data-tippy-content="Edit"
                                                                    wire:click="confirmDelEmail({{ $email->id }})"
                                                                    type="button">
                                                                    <iconify-icon
                                                                        icon="heroicons-outline:trash"></iconify-icon>
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
                            <div
                                class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
                                @can('create', \App\Models\Business\SoldPolicy::class)
                                    <button wire:click="openNewInvoiceSec"
                                        class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2"
                                            icon="ph:plus-bold"></iconify-icon>
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
                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                                <tr>

                                                    <th scope="col" class=" table-th ">
                                                        Serial
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Created at
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Confirmation at
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
                                                        Confirmation
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Action
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @forelse ($company->invoices as $invoice)
                                                    <tr>
                                                        <td class="table-td">
                                                            <b>{{ $invoice->serial }}</b>
                                                        </td>
                                                        <td class="table-td ">
                                                            {{ $invoice->created_at->format('d/m/Y') }}
                                                        </td>
                                                        <td class="table-td ">
                                                            {{ $invoice->commissions->first()->payment_date ? $invoice->commissions->first()->payment_date : 'N/A' }}
                                                        </td>
                                                        <td class="table-td ">
                                                            {{ 'EGP ' . number_format($invoice->gross_total, 2) }}</td>
                                                        <td class="table-td ">
                                                            {{ 'EGP ' . number_format($invoice->tax_total, 2) }}</td>
                                                        <td class="table-td ">
                                                            {{ 'EGP ' . number_format($invoice->net_total, 2) }}</td>
                                                        <td class="table-td ">{{ $invoice->creator->full_name }}</td>
                                                        <td class="table-td ">
                                                            @if ($invoice->isconfirmed)
                                                                <span
                                                                    class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize rounded-3xl">Confirmed</span>
                                                            @endif
                                                        </td>
                                                        <td class="table-td ">
                                                            <div>
                                                                <div class="relative">
                                                                    <div class="dropstart relative">
                                                                        <button
                                                                            class="inline-flex justify-center items-center"
                                                                            type="button" data-bs-toggle="dropdown"
                                                                            aria-expanded="false">
                                                                            <iconify-icon
                                                                                class="text-xl ltr:ml-2 rtl:mr-2"
                                                                                icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                        </button>
                                                                        <ul
                                                                            class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding    border-none">
                                                                            @if (!$invoice->paid_journal_entry_id && $invoice->created_journal_entry_id)
                                                                                <li
                                                                                    wire:click='openCreatePaidJournalEntryModal({{ $invoice->id }})'>
                                                                                    <a href="#"
                                                                                        class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                        <iconify-icon
                                                                                            icon="icon-park-outline:journal"></iconify-icon>
                                                                                        <span>Add Paid Journal
                                                                                            Entry</span></a>
                                                                                </li>
                                                                            @elseif($invoice->paid_journal_entry_id)
                                                                                <li>
                                                                                    <a href="{{ route('accounts.entries', $invoice->paid_journal_entry_id) }}"
                                                                                        target="_blank"
                                                                                        class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                        <iconify-icon
                                                                                            icon="icon-park-outline:journal"></iconify-icon>
                                                                                        <span>View Paid Journal
                                                                                            Entry</span></a>
                                                                                </li>
                                                                            @endif

                                                                            @if (!$invoice->created_journal_entry_id)
                                                                                <li
                                                                                    wire:click='openCreateJournalEntryModal({{ $invoice->id }})'>
                                                                                    <a href="#"
                                                                                        class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                        <iconify-icon
                                                                                            icon="icon-park-outline:journal"></iconify-icon>
                                                                                        <span>Add Created Journal
                                                                                            Entry</span></a>
                                                                                </li>
                                                                            @else
                                                                                <li>
                                                                                    <a href="{{ route('accounts.entries', $invoice->created_journal_entry_id) }}"
                                                                                        target="_blank"
                                                                                        class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                        <iconify-icon
                                                                                            icon="icon-park-outline:journal"></iconify-icon>
                                                                                        <span>View Created Journal
                                                                                            Entry</span></a>
                                                                                </li>
                                                                            @endif
                                                                            <li
                                                                                wire:click='openConfirmInvoice({{ $invoice->id }})'>
                                                                                <a href="#"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                    <iconify-icon
                                                                                        icon="game-icons:confirmed"></iconify-icon>
                                                                                    <span>Confirm Invoice</span></a>
                                                                            </li>
                                                                            <li
                                                                                wire:click="printInvoice({{ $invoice->id }})">
                                                                                <a href="#"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#editModal"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                    <iconify-icon
                                                                                        icon="lets-icons:print-light"></iconify-icon>
                                                                                    <span>Print</span></a>
                                                                            </li>
                                                                            <li
                                                                                wire:click="$emit('showConfirmation', 'Are you sure you want to delete this invoice?','danger','deleteInvoice' , {{ $invoice->id }})">
                                                                                <a href="#"
                                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                                                    <iconify-icon
                                                                                        icon="fluent:delete-28-regular"></iconify-icon>
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

                @if ($section === 'extras')
                    <div class="card">
                        <header class="card-header noborder">
                            <h4 class="card-title">Invoice Extras</h4>
                            <button wire:click="openNewExtraSection" class="btn inline-flex justify-center btn-dark">
                                <span class="flex items-center">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2"
                                        icon="ph:plus-bold"></iconify-icon>
                                    <span>Add Extra</span>
                                </span>
                            </button>
                        </header>

                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto -mx-6">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden">
                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class="bg-slate-200 dark:bg-slate-700">
                                                <tr>
                                                    <th scope="col" class="table-th">
                                                        Title
                                                    </th>
                                                    <th scope="col" class="table-th">
                                                        Amount
                                                    </th>
                                                    <th scope="col" class="table-th">
                                                        Note
                                                    </th>
                                                    <th scope="col" class="table-th">
                                                        Invoice
                                                    </th>
                                                    <th scope="col" class="table-th">
                                                        Created At
                                                    </th>
                                                    <th scope="col" class="table-th">
                                                        Actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                @foreach ($invoiceExtras as $extra)
                                                    <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                        <td class="table-td">{{ $extra->title }}</td>
                                                        <td class="table-td">{{ number_format($extra->amount, 2) }}
                                                        </td>
                                                        <td class="table-td">{{ $extra->note ?: 'N/A' }}</td>
                                                        <td class="table-td">
                                                            {{ $extra->invoice?->serial ?? 'N/A' }}
                                                        </td>
                                                        <td class="table-td">{{ $extra->created_at->format('d M Y') }}
                                                        </td>
                                                        <td class="table-td">
                                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                                <button
                                                                    wire:click="openEditExtraSection({{ $extra->id }})"
                                                                    class="action-btn" type="button">
                                                                    <iconify-icon
                                                                        icon="heroicons:pencil-square"></iconify-icon>
                                                                </button>
                                                                @if (!$extra->invoice_id)
                                                                    <button
                                                                        wire:click="confirmDeleteExtra({{ $extra->id }})"
                                                                        class="action-btn" type="button">
                                                                        <iconify-icon
                                                                            icon="heroicons:trash"></iconify-icon>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if ($invoiceExtras->isEmpty())
                                        <div class="text-center p-5">
                                            <div class="text-slate-500 dark:text-slate-400">
                                                No extras found for this company
                                            </div>
                                        </div>
                                    @endif
                                    <div class="mt-6">
                                        {{ $invoiceExtras->links() }}
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
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 1000px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Create Invoice
                            </h3>
                            <button wire:click="closeNewInvoiceSec" type="button"
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
                                <input type="number" step="0.01" wire:model="serial"
                                    class="form-control @error('serial') !border-danger-500 @enderror">
                                @error('serial')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Gross Total</label>
                                    <input type="number" step="0.01" wire:model="gross_total"
                                        class="form-control @error('gross_total') !border-danger-500 @enderror"
                                        disabled>
                                    @error('gross_total')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
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

                            <h5>Available Sold Policies <small>({{ $available_policies->total() }})</small></h5>
                            <div class="card-body pb-6">
                                <div class="overflow-x-auto ">
                                    <div class="inline-block min-w-full align-middle">
                                        <div class="overflow-hidden ">


                                            <div class="card-text h-full space-y-4">
                                                <div class="flex items-center space-x-7 flex-wrap">
                                                    <div class="basicRadio">
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="radio" class="hidden" name="basicradios"
                                                                wire:model="availableSoldPolicies_isNotPaid"
                                                                value="0" checked="checked">
                                                            <span
                                                                class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                            <span
                                                                class="text-secondary-500 text-sm leading-6 capitalize">All</span>
                                                        </label>
                                                    </div>
                                                    <div class="basicRadio">
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="radio" class="hidden" name="basicradios"
                                                                wire:model="availableSoldPolicies_isNotPaid"
                                                                value="1">
                                                            <span
                                                                class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                            <span
                                                                class="text-secondary-500 text-sm leading-6 capitalize">Not
                                                                Paid</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>




                                            <input type="text" wire:model="seachAvailablePoliciesText"
                                                placeholder="Search sold policies by policy number"
                                                class="form-control">
                                            <table
                                                class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                                <thead class="">
                                                    <tr>

                                                        <th scope="col"
                                                            class=" table-th border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                            #
                                                        </th>
                                                        <th scope="col"
                                                            class=" table-th border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                            Issue
                                                        </th>

                                                        <th scope="col"
                                                            class=" table-th border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                            Policy
                                                        </th>
                                                        <th scope="col"
                                                            class=" table-th border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                            Comm.
                                                        </th>

                                                        <th scope="col"
                                                            class=" table-th border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                            Client
                                                        </th>

                                                        <th scope="col"
                                                            class=" table-th border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                            Action
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                    @forelse ($available_policies as $policy)
                                                        <tr>
                                                            <td
                                                                class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700">
                                                                {{ $policy->policy_number }}</td>
                                                            <td
                                                                class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700">
                                                                {{ $policy->created_at->format('d/m/Y') }}</td>
                                                            <td
                                                                class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700">
                                                                {{ $policy->policy->name }}</td>
                                                            <td
                                                                class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700">
                                                                {{ $policy->commission_left }}</td>
                                                            <td
                                                                class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                                <div class="flex-1 text-start">

                                                                    <div
                                                                        class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                                        {{ ucwords($policy->client_type) }}
                                                                    </div>
                                                                    <h4
                                                                        class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                                        @if ($policy->client_type === 'customer')
                                                                            {{ $policy->client?->first_name . ' ' . $policy->client?->middle_name . ' ' . $policy->client?->last_name }}
                                                                        @elseif($policy->client_type === 'corporate')
                                                                            {{ $policy->client?->name }}
                                                                        @endif
                                                                    </h4>
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700">
                                                                @if (in_array($policy->id, array_column($sold_policies_entries, 'id')))
                                                                    <button type="button"
                                                                        wire:click="unselectPolicy({{ $policy->id }})"
                                                                        class="btn btn-sm inline-flex justify-center btn-outline-success rounded-[25px]">selected</button>
                                                                @else
                                                                    <button type="button"
                                                                        wire:click="selectPolicy({{ $policy->id }})"
                                                                        class="btn btn-sm inline-flex justify-center btn-success rounded-[25px]">Select</button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5"
                                                                class=" table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center">
                                                                <p>No policies available.</p>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                            <div class="text-center m-3">
                                                <small>
                                                    Showing items
                                                    {{ $available_policies->firstItem() }}-{{ $available_policies->lastItem() }}
                                                    of {{ $available_policies->total() }}.
                                                </small>
                                            </div>
                                            {{ $available_policies->links('vendor.livewire.bootstrap') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5>Selected Policies
                                @if ($sold_policies_entries)
                                    <small>
                                        ({{ count($sold_policies_entries) }})
                                    </small>
                                @endif
                            </h5>
                            @if (empty($sold_policies_entries))
                                <div
                                    class="py-[18px] px-6 font-normal text-sm rounded-md bg-white text-warning-500 border border-warning-500
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
                                                    {{ $policy->client?->first_name . ' ' . $policy->client?->middle_name . ' ' . $policy->client?->last_name }}
                                                @elseif($policy->client_type === 'corporate')
                                                    {{ $policy->client?->name }}
                                                @endif

                                                <button wire:click="removeEntry({{ $index }})"
                                                    class="btn btn-sm inline-flex float-right h-12 w-12 items-center justify-center btn-outline-danger  rounded-full">
                                                    <span class="flex items-center">
                                                        <iconify-icon class="text-xl"
                                                            icon="uiw:delete"></iconify-icon>
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
                                                    <input type="number" step="0.01" wire:change="updateTotal"
                                                        wire:model.defer="sold_policies_entries.{{ $index }}.amount"
                                                        class="form-control @error('sold_policies_entries.' . $index . '.amount') !border-danger-500 @enderror">

                                                    @error('sold_policies_entries.' . $index . '.amount')
                                                        <span
                                                            class="font-Inter text-sm text-danger-500 inline-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="input-area">
                                                    <label for="firstName" class="form-label">اذن صرف عموله</label>
                                                    <input type="number" step="0.01"
                                                        wire:model.defer="sold_policies_entries.{{ $index }}.pymnt_perm"
                                                        class="form-control @error('sold_policies_entries.' . $index . '.pymnt_perm') !border-danger-500 @enderror">

                                                    @error('sold_policies_entries.' . $index . '.pymnt_perm')
                                                        <span
                                                            class="font-Inter text-sm text-danger-500 inline-block">{{ $message }}</span>
                                                    @enderror
                                                </div>



                                            </div>


                                            {{-- <button type="button" wire:click="removeEntry({{ $index }})">Remove</button> --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Unlinked Extras Section -->
                            <h5 class="mt-6">Unlinked Extras
                                @php
                                    $unlinkedExtras = $company->invoiceExtras()->whereNull('invoice_id')->get();
                                @endphp
                                @if ($unlinkedExtras->count() > 0)
                                    <small>({{ $unlinkedExtras->count() }})</small>
                                @endif
                            </h5>

                            @if ($unlinkedExtras->isEmpty())
                                <div
                                    class="py-[18px] px-6 font-normal text-sm rounded-md bg-white text-slate-500 border border-slate-200 dark:bg-slate-800 dark:border-slate-700">
                                    <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="flex-1 font-Inter">
                                            No unlinked extras found for this company.
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="overflow-x-auto mt-2">
                                    <table
                                        class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead class="bg-slate-200 dark:bg-slate-700">
                                            <tr>
                                                <th scope="col" class="table-th">
                                                    Select
                                                </th>
                                                <th scope="col" class="table-th">
                                                    Title
                                                </th>
                                                <th scope="col" class="table-th">
                                                    Amount
                                                </th>
                                                <th scope="col" class="table-th">
                                                    Note
                                                </th>
                                                <th scope="col" class="table-th">
                                                    Created At
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                            @foreach ($unlinkedExtras as $extra)
                                                <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                    <td class="table-td text-center">
                                                        <input type="checkbox" wire:model="selectedExtras"
                                                            value="{{ $extra->id }}"
                                                            class="form-checkbox h-5 w-5 text-primary-500 rounded">
                                                    </td>
                                                    <td class="table-td">{{ $extra->title }}</td>
                                                    <td class="table-td">{{ number_format($extra->amount, 2) }}</td>
                                                    <td class="table-td">{{ $extra->note ?: 'N/A' }}</td>
                                                    <td class="table-td">{{ $extra->created_at->format('d M Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addInvoice" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>




                    </div>
                </div>
            </div>
        </div>


    @endif

    @if ($editInfoSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                        rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                {{ $company->name }}
                            </h3>
                            <button wire:click="closeEditInfo" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                    dark:hover:bg-slate-600 dark:hover:text-white"
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
                            <div class="input-area">
                                <label for="name" class="form-label">Company Name*</label>
                                <input id="name" type="text"
                                    class="form-control @error('companyInfoName') !border-danger-500 @enderror"
                                    placeholder="Company Name" wire:model.defer="companyInfoName">
                                @error('companyInfoName')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-area">
                                <label for="name" class="form-label">Note</label>
                                <textarea id="name" type="text" class="form-control @error('companyInfoNote') !border-danger-500 @enderror"
                                    placeholder="Leave a note..." wire:model.defer="companyInfoNote"></textarea>
                                @error('companyInfoNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-area">
                                <label for="account" class="form-label">Account</label>
                                @inject('helper', 'App\Helpers\Helpers')
                                <select id="account"
                                    class="form-control @error('companyInfoAccountId') !border-danger-500 @enderror"
                                    wire:model.defer="companyInfoAccountId">
                                    <option value="">Select Account</option>
                                    @php
                                        $printed_arr = [];
                                    @endphp
                                    @foreach ($accounts_list as $account)
                                        {{ $helper->printAccountChildren('', $account, $printed_arr) }}
                                    @endforeach
                                </select>
                                @error('companyInfoAccountId')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <button wire:click="saveChanges" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500 btn-sm">Save
                                Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($newEmailSec)
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
                                Add Email
                            </h3>
                            <button wire:click="closeNewEmail" type="button"
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
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="input-area">
                                <label for="type" class="form-label">Type</label>
                                <select wire:model="type"
                                    class="form-control @error('type') !border-danger-500 @enderror">
                                    <option value="" disabled>Select type</option>
                                    @foreach ($Emailtypes as $typeOption)
                                        <option value="{{ $typeOption }}">{{ $typeOption }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" wire:model="email"
                                    class="form-control @error('email') !border-danger-500 @enderror">
                                @error('email')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" wire:model="phone"
                                    class="form-control @error('phone') !border-danger-500 @enderror">
                                @error('phone')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="is_primary" class="form-label">Primary</label>
                                <input type="checkbox" wire:model="is_primary" class="form-checkbox">
                            </div>
                            <div class="input-area">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" wire:model="first_name"
                                    class="form-control @error('first_name') !border-danger-500 @enderror">
                                @error('first_name')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" wire:model="last_name"
                                    class="form-control @error('last_name') !border-danger-500 @enderror">
                                @error('last_name')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area">
                                <label for="note" class="form-label">Note</label>
                                <textarea wire:model="note" class="form-control @error('note') !border-danger-500 @enderror"></textarea>
                                @error('note')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addEmail"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($confirmInvoiceId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Confirm Invoice
                            </h3>
                            <button wire:click="closeConfirmInvoice" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
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
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure you want to confirm this invoice?
                            </h6>

                            <div class="input-area">
                                <label for="confirmDate" class="form-label">Date*</label>
                                <input id="confirmDate" type="date"
                                    class="form-control @error('confirmDate') !border-danger-500 @enderror"
                                    wire:model.defer="confirmDate">
                                @error('confirmDate')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="confirmInvoice" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">Yes,
                                Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newExtraSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="createExtraModal" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white">
                                Add New Extra
                            </h3>
                            <button wire:click="closeNewExtraSection" type="button"
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
                            <div class="from-group">
                                <label for="extraTitle" class="form-label">Title</label>
                                <input type="text" class="form-control mt-2 w-full" wire:model.defer="extraTitle">
                                @error('extraTitle')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="extraAmount" class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control mt-2 w-full"
                                    wire:model.defer="extraAmount">
                                @error('extraAmount')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="extraNote" class="form-label">Note (Optional)</label>
                                <textarea class="form-control mt-2 w-full" wire:model.defer="extraNote" rows="3"></textarea>
                                @error('extraNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeNewExtraSection" type="button" class="btn btn-outline-danger">
                                Cancel
                            </button>
                            <button wire:click="addExtra" type="button" class="btn btn-dark">
                                <span wire:loading.remove wire:target="addExtra">Add Extra</span>
                                <span wire:loading wire:target="addExtra">
                                    <iconify-icon class="text-xl spin-slow"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @if ($editExtraSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="editExtraModal" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white">
                                Edit Extra
                            </h3>
                            <button wire:click="closeEditExtraSection" type="button"
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
                            <div class="from-group">
                                <label for="extraTitle" class="form-label">Title</label>
                                <input type="text" class="form-control mt-2 w-full" wire:model.defer="extraTitle">
                                @error('extraTitle')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="extraAmount" class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control mt-2 w-full"
                                    wire:model.defer="extraAmount">
                                @error('extraAmount')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="extraNote" class="form-label">Note (Optional)</label>
                                <textarea class="form-control mt-2 w-full" wire:model.defer="extraNote" rows="3"></textarea>
                                @error('extraNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeEditExtraSection" type="button" class="btn btn-outline-danger">
                                Cancel
                            </button>
                            <button wire:click="updateExtra" type="button" class="btn btn-dark">
                                <span wire:loading.remove wire:target="updateExtra">Update Extra</span>
                                <span wire:loading wire:target="updateExtra">
                                    <iconify-icon class="text-xl spin-slow"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @if ($confirmDeleteExtraId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="deleteExtraModal" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white">
                                Confirm Delete
                            </h3>
                            <button wire:click="cancelDeleteExtra" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                        11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6">
                            <p class="text-center text-lg">Are you sure you want to delete this extra?</p>
                            <p class="text-center text-sm text-slate-500 mt-2">This action cannot be undone.</p>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="cancelDeleteExtra" type="button" class="btn btn-outline-secondary">
                                Cancel
                            </button>
                            <button wire:click="deleteExtra" type="button" class="btn btn-danger">
                                <span wire:loading.remove wire:target="deleteExtra">Delete</span>
                                <span wire:loading wire:target="deleteExtra">
                                    <iconify-icon class="text-xl spin-slow"
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @if ($createJournalEntryId)
        <div class="fixed z-40 inset-0 overflow-y-auto flex items-center justify-center">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl p-6 relative mx-auto w-3/4 md:w-1/2 my-8">
                <h1 class="font-bold text-xl mb-6 text-center">Create Invoice Journal Entry</h1>
                <p class="mb-4">Are you sure you want to create a journal entry for this invoice?</p>
                <p class="mb-4 text-sm text-gray-600">This will create accounting entries for the invoice creation.</p>

                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="closeCreateJournalEntryModal()"
                        class="px-4 py-2 text-black rounded text-xs font-medium bg-red-400 hover:bg-red-500 focus:outline-none">Cancel</button>
                    <button type="button" wire:click="createJournalEntry()"
                        class="px-4 py-2 text-white rounded text-xs font-medium bg-blue-500 hover:bg-blue-700 focus:outline-none">
                        <span wire:loading.remove wire:target="createJournalEntry">Create Journal Entry</span>
                        <span wire:loading wire:target="createJournalEntry">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($createPaidJournalEntryId)
        <div class="fixed z-40 inset-0 overflow-y-auto flex items-center justify-center">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl p-6 relative mx-auto w-3/4 md:w-1/2 my-8">
                <h1 class="font-bold text-xl mb-6 text-center">Create Invoice Paid Journal Entry</h1>
                <p class="mb-4">Please provide the details for the paid journal entry:</p>

                <div class="grid gap-4">
                    <div>
                        <label class="font-medium block">Bank Account</label>
                        @inject('helper', 'App\Helpers\Helpers')
                        <select id="account"
                            class="form-control @error('bankAccountId') !border-danger-500 @enderror"
                            wire:model.defer="bankAccountId">
                            <option value="">Select Account</option>
                            @php
                                $printed_arr = [];
                            @endphp
                            @foreach ($bankAccountsParent as $account)
                                {{ $helper->printAccountChildren('', $account, $printed_arr) }}
                            @endforeach
                        </select>
                        @error('bankAccountId')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="font-medium block">Transaction Fees</label>
                        <input type="number" step="0.01" wire:model.defer="transFees"
                            class="block bg-gray-200 w-full h-10 rounded-lg px-4 border border-transparent focus:border-blue-300 focus:ring-0">
                        @error('transFees')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="closeCreatePaidJournalEntryModal()"
                        class="px-4 py-2 text-black rounded text-xs font-medium bg-red-400 hover:bg-red-500 focus:outline-none">Cancel</button>
                    <button type="button" wire:click="createPaidJournalEntry()"
                        class="px-4 py-2 text-white rounded text-xs font-medium bg-blue-500 hover:bg-blue-700 focus:outline-none">
                        <span wire:loading.remove wire:target="createPaidJournalEntry">Create Paid Journal Entry</span>
                        <span wire:loading wire:target="createPaidJournalEntry">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
