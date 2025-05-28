<div>

    <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base">
        <div class="card-body flex flex-col p-6 active">
            <div class="order-2 card-text h-full menu-open active">
                <div class="flex justify-between mb-4">
                    <div>
                        <div class="text-xl text-slate-900 dark:text-white text-wrap">
                            <b>{{ str_replace('_', ' ', $profile->title) }}</b>
                            @if ($profile->per_policy)
                                <span class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize">Per
                                    Policy</span>
                            @endif

                            @if ($profile->select_available)
                                <span class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize">Available
                                    for Selection</span>
                            @endif
                        </div>
                        <div class="text-base">
                            {{ ucwords(str_replace('_', ' ', $profile->type)) }}
                        </div>
                    </div>
                    <div>
                        @if ($profile->user)
                            <a href="card.html"
                                class="inline-flex leading-5 text-slate-500 dark:text-slate-400 text-sm font-normal active">
                                <iconify-icon class="text-secondary-500 ltr:mr-2 rtl:ml-2 text-lg"
                                    icon="lucide:user"></iconify-icon>
                                {{ $profile->user->first_name }} {{ $profile->user->last_name }}
                            </a>
                        @endif

                    </div>

                </div>
                <div class="card-text mt-4 menu-open">
                    <p>{{ $profile->desc }}</p>
                    <div class="mt-4 space-x-4 rtl:space-x-reverse">
                        @can('create', \App\Models\Payments\CommProfile::class)
                            <button wire:click="openUpdateSec" class="btn inline-flex justify-center btn-light btn-sm">Edit
                                info</button>

                            <button
                                wire:click="$emit('showConfirmation', 'Are you sure you want to delete this profile?','danger','deleteProfile')"
                                class="btn inline-flex justify-center btn-outline-danger btn-sm"> Delete profile</button>
                        @endcan
                        @can('viewAny', $profile)
                            <button wire:click="openDownloadAccountStatement"
                                class="btn inline-flex justify-center btn-outline-light btn-sm">Download Account
                                Statement</button>
                        @endcan

                        @can('manage', $profile)
                            <button
                                wire:click="$emit('showConfirmation', 'Are you sure you want to refresh the profile balances?','black','refreshBalances')"
                                class="btn inline-flex justify-center btn-outline-light btn-sm"> Refresh Balance</button>
                            <button wire:click="openStartTargetRunSec"
                                class="btn inline-flex justify-center btn-outline-light btn-sm">Start Target run</button>
                        @endcan
                    </div>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 float-right">Created
                    {{ \Carbon\Carbon::parse($profile->created_at)->format('l d/m/Y') }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 float-right">Updated
                    {{ \Carbon\Carbon::parse($profile->updated_at)->format('l d/m/Y h:m') }} - &nbsp;</p>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 grid-cols-1 gap-4 mt-5">

        <!-- BEGIN: Group Chart -->


        <div class="card">
            <div class="card-body pt-4 pb-3 px-4">
                <div class="flex space-x-3 rtl:space-x-reverse">
                    <div class="flex-none">
                        <div
                            class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#E5F9FF] dark:bg-slate-900	 text-info-500">
                            <iconify-icon icon="tdesign:money"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                            Balance
                        </div>
                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                            <h5>{{ number_format($balance, 2, '.', ',') }} EGP</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body pt-4 pb-3 px-4">
                <div class="flex space-x-3 rtl:space-x-reverse">
                    <div class="flex-none">
                        <div
                            class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#FFEDE6] dark:bg-slate-900	 text-warning-500">
                            <iconify-icon icon="mdi:money-off"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                            Unapproved Balance
                        </div>
                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                            {{ number_format($profile->balance + $profile->unapproved_balance, 2, '.', ',') }} EGP
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body flex flex-col col-span-2 mb-5 mt-5">
        <div class="card-text h-full">
            <div>
                <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0" id="tabsss-tab"
                    role="tablist">
                    <li class="nav-item" role="presentation" wire:click='changeSection("payments")'>
                        <a href="#tabs-messages-withIcon"
                            class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'payments') active @endif dark:text-slate-300"
                            id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                            data-bs-target="#tabs-messages-withIcon" role="tab"
                            aria-controls="tabs-messages-withIcon" aria-selected="false">
                            <iconify-icon class="mr-1" icon="material-symbols:payments"></iconify-icon>
                            Payments</a>
                    </li>
                    <li class="nav-item" role="presentation" wire:click='changeSection("salescomm")'>
                        <a href="#tabs-messages-withIcon"
                            class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'salescomm') active @endif dark:text-slate-300"
                            id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                            data-bs-target="#tabs-messages-withIcon" role="tab"
                            aria-controls="tabs-messages-withIcon" aria-selected="false">
                            <iconify-icon class="mr-1" icon="mynaui:percentage-waves"></iconify-icon>
                            Sales Commission</a>
                    </li>
                    @if ($profile->is_sales_out)
                        <li class="nav-item" role="presentation" wire:click='changeSection("clientpayments")'>
                            <a href="#tabs-messages-withIcon"
                                class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'clientpayments') active @endif dark:text-slate-300"
                                id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                                data-bs-target="#tabs-messages-withIcon" role="tab"
                                aria-controls="tabs-messages-withIcon" aria-selected="false">
                                <iconify-icon class="mr-1" icon="mynaui:percentage-waves"></iconify-icon>
                                Collected Client Payments</a>
                        </li>
                    @endif
                    <li class="nav-item" role="presentation" wire:click='changeSection("targets")'>
                        <a href="#tabs-messages-withIcon"
                            class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'targets') active @endif dark:text-slate-300"
                            id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                            data-bs-target="#tabs-messages-withIcon" role="tab"
                            aria-controls="tabs-messages-withIcon" aria-selected="false">
                            <iconify-icon class="mr-1" icon="lets-icons:target-fill"></iconify-icon>
                            Targets</a>
                    </li>
                    <li class="nav-item" role="presentation" wire:click='changeSection("configurations")'>
                        <a href="#tabs-messages-withIcon"
                            class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'configurations') active @endif dark:text-slate-300"
                            id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                            data-bs-target="#tabs-messages-withIcon" role="tab"
                            aria-controls="tabs-messages-withIcon" aria-selected="false">
                            <iconify-icon class="mr-1" icon="grommet-icons:configure"></iconify-icon>
                            Direct Configurations</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Payments --}}
    @if ($section === 'payments')
        <div class="card mt-5">
            <div class="card-body">
                <div class="card-text h-full">
                    <div class="px-4 pt-4 pb-3">
                        <div class="flex justify-between">
                            <label class="form-label">
                                Payments
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="downloadPymtDoc,showPymtNote"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </label>
                            <button wire:click="changeSortDirection"
                                class="btn inline-flex justify-center btn-outline-light btn-sm">
                                @if ($isSortLatest)
                                    Sort:<b>&nbsp;Newest</b>
                                @else
                                    Sort:<b>&nbsp;Oldest</b>
                                @endif
                            </button>
                        </div>

                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto ">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class="">
                                                <tr>

                                                    <th scope="col" class=" table-th ">
                                                        Amount
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Type
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Status
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Payment Date
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Document/Note
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Details
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Action
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @foreach ($payments as $payment)
                                                    <tr>

                                                        <td class="table-td ">
                                                            <p class=" text-lg">
                                                                <b>{{ number_format($payment->amount, 2, '.', ',') }}
                                                                    EGP
                                                        </td>

                                                        <td class="table-td">
                                                            {{ ucwords(str_replace('_', ' ', $payment->type)) }}
                                                        </td>

                                                        <td class="table-td">
                                                            @if ($payment->status === 'new')
                                                                <span class="badge bg-info-500 h-auto text-white">
                                                                    {{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                                </span>
                                                            @elseif(str_contains($payment->status, 'declined') || str_contains($payment->status, 'cancelled'))
                                                                <span class="badge bg-danger-500 h-auto text-white">
                                                                    {{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                                </span>
                                                            @elseif($payment->status === 'paid' || ($payment->status = 'approved'))
                                                                <span class="badge bg-success-500 h-auto text-white">
                                                                    {{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                                </span>
                                                            @endif
                                                        </td>

                                                        <td class="table-td">
                                                            @if ($payment->payment_date)
                                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('l d/m/Y') }}
                                                            @else
                                                                Not paid yet.
                                                            @endif
                                                        </td>

                                                        <td class="table-td">
                                                            <div class=" flex justify-center" wire:loading.remove
                                                                wire:target="downloadPymtDoc({{ $payment->id }})">
                                                                @if ($payment->doc_url)
                                                                    <button class="toolTip onTop action-btn m-1 "
                                                                        data-tippy-content="Edit"
                                                                        wire:click="downloadPymtDoc({{ $payment->id }})"
                                                                        type="button">
                                                                        <iconify-icon
                                                                            icon="basil:document-outline"></iconify-icon>
                                                                    </button>
                                                                @endif
                                                                @if ($payment->note)
                                                                    <button class="toolTip onTop action-btn m-1 "
                                                                        data-tippy-content="note"
                                                                        wire:click="showPymtNote({{ $payment->id }})"
                                                                        type="button">
                                                                        <iconify-icon
                                                                            icon="iconamoon:comment-bold"></iconify-icon>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            <iconify-icon
                                                                class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                                                wire:loading
                                                                wire:target="downloadPymtDoc({{ $payment->id }})"
                                                                icon="line-md:loading-twotone-loop"></iconify-icon>
                                                        </td>

                                                        <td class="table-td">
                                                            <div class="min-w-[170px]">
                                                                <span class="text-slate-500 dark:text-slate-400">
                                                                    <span
                                                                        class="block text-slate-600 dark:text-slate-300"
                                                                        style="display: flex; align-items: center;">
                                                                        <iconify-icon icon="mdi:user" width="1.2em"
                                                                            height="1.2em"></iconify-icon>&nbsp;
                                                                        <span style="vertical-align: middle;">
                                                                            Created by:
                                                                            <b>{{ $payment->creator->first_name . ' ' . $payment->creator->last_name }}</b>
                                                                        </span>
                                                                    </span>
                                                                    <span class="block text-slate-500 text-xs"
                                                                        style="display: flex; align-items: center;">
                                                                        @if ($payment->approver)
                                                                            <iconify-icon icon="mdi:approve"
                                                                                width="1.2em"
                                                                                height="1.2em"></iconify-icon>&nbsp;
                                                                            <span style="vertical-align: middle;">
                                                                                Approved by:
                                                                                <b>{{ $payment->approver->first_name . ' ' . $payment->approver->last_name }}</b>
                                                                            </span>
                                                                        @endif
                                                                    </span>

                                                                </span>
                                                            </div>
                                                        </td>

                                                        <td class="table-td ">
                                                            <div class="dropstart relative">
                                                                <button class="inline-flex justify-center items-center"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                                                        icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                </button>
                                                                <ul
                                                                    class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                    @if ($payment->journal_entry_id)
                                                                        <li>
                                                                            <a href="{{ route('accounts.entries', $payment->journal_entry_id) }}"
                                                                                target="_blank"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="hugeicons:view"></iconify-icon>
                                                                                <span>Show linked journal
                                                                                    entry</span></a>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <a wire:click="openCreateMainJournalEntryModal({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="material-symbols:add-circle-outline"></iconify-icon>
                                                                                <span>Create journal entry</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a wire:click="openCreateSalesJournalEntryModal({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="mdi:account-plus"></iconify-icon>
                                                                                <span>Create sales journal entry</span>
                                                                            </a>
                                                                        </li>
                                                                    @endif

                                                                    @if ($payment->is_new)
                                                                        <li>
                                                                            <a wire:click="editThisPayment({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="fa-regular:edit"></iconify-icon>
                                                                                <span>Edit</span></a>
                                                                        </li>
                                                                    @endif

                                                                    @if ($payment->sales_commissions->isNotEmpty())
                                                                        <li>
                                                                            <a wire:click="openLinkedSalesComm({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="hugeicons:view"></iconify-icon>
                                                                                <span>Show linked commissions</span></a>
                                                                        </li>
                                                                    @endif

                                                                    {{-- @if (($payment->is_new && !$payment->needs_approval) || $payment->is_approved) --}}

                                                                    @if ($payment->is_new || $payment->is_approved)
                                                                        <li>
                                                                            <a wire:click="setPaidSec({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="material-symbols:paid"></iconify-icon>
                                                                                <span>Set as paid</span></a>
                                                                        </li>
                                                                    @endif
                                                                    @if ($payment->is_new)
                                                                        <li>
                                                                            <a wire:click="setCancelledSec({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="line-md:cancel"></iconify-icon>
                                                                                <span>Set as Cancelled</span></a>
                                                                        </li>
                                                                    @endif

                                                                    @if ($payment->doc_url)
                                                                        <li>
                                                                            <a wire:click="deleteThisPymtDoc({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="ep:document-delete"></iconify-icon>
                                                                                <span>Remove Document</span></a>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <label for="pymtDocFile"
                                                                                wire:click="setUploadPymtDocId({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="fluent:document-add-24-regular"></iconify-icon>
                                                                                <span>Add Document</span></label>
                                                                            <input type="file" id="pymtDocFile"
                                                                                name="filename" style="display: none;"
                                                                                wire:model="pymtDocFile">

                                                                        </li>
                                                                    @endif
                                                                    @can('approve', $payment)
                                                                        <li>
                                                                            <a wire:click="setPymtApprove({{ $payment->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="mdi:approve"></iconify-icon>
                                                                                <span>Approve</span></a>
                                                                        </li>
                                                                    @endcan
                                                                    <li>
                                                                        <a wire:click="downloadPaymentDetails({{ $payment->id }})"
                                                                            class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                            <iconify-icon
                                                                                icon="ic:baseline-download"></iconify-icon>
                                                                            <span>Download details</span></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($payments->isEmpty())
                                                    <tr>
                                                        <td colspan="6" class="text-center p-5">
                                                            <div
                                                                class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500">
                                                                <div
                                                                    class="flex items-start space-x-3 rtl:space-x-reverse">
                                                                    <div class="flex-1">
                                                                        No payments added to this profile!
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="6" class="pt-3">
                                                        <button wire:click="openNewPymtSection"
                                                            class="btn inline-flex justify-center btn-light btn-sm">
                                                            Add new payment
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    {{ $payments->links('vendor.livewire.bootstrap') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($section === 'salescomm')
        <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mt-5">
            <div class="card-body flex flex-col p-6 active justify-center">
                <header class="card-header noborder flex justify-between">
                    <div>
                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                            wire:target="updatedCommDoc" icon="line-md:loading-twotone-loop"></iconify-icon>
                        <h4 class="card-title flex justify-between">
                            Sales Commission

                        </h4>
                    </div>

                    <div class="flex gap-2">
                        <div class="dropdown relative">
                            <button class="btn inline-flex justify-center btn-light items-center btn-sm"
                                type="button" id="lightDropdownMenuButton" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ $salesCommStatus ? str_replace('_', ' ', $salesCommStatus) : 'All' }}
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                    icon="ic:round-keyboard-arrow-down"></iconify-icon>
                            </button>
                            <ul
                                class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                                    z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                <li wire:click='setSalesCommStatus("")'
                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                                    All
                                </li>
                                @foreach ($SALES_COMM_STATUSES as $SALES_COMM_STATUS)
                                    <li wire:click='setSalesCommStatus("{{ $SALES_COMM_STATUS }}")'
                                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                            dark:hover:text-white cursor-pointer">
                                        {{ ucwords(str_replace('_', ' ', $SALES_COMM_STATUS)) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <button wire:click="changeSortDirection"
                                class="btn inline-flex justify-center btn-outline-light btn-sm">
                                @if ($isSortLatest)
                                    Sort:<b>&nbsp;Newest</b>
                                @else
                                    Sort:<b>&nbsp;Oldest</b>
                                @endif
                            </button>
                        </div>

                    </div>

                </header>
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6 ">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                @if ($sales_comm->isEmpty())
                                    <p class="text-sm text-center">
                                        No sales commissions found.
                                    </p>
                                @else
                                    <table
                                        class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead class=" border-t border-slate-100 dark:border-slate-800">
                                            <tr>

                                                <th scope="col" class=" table-th ">
                                                    Policy#
                                                </th>
                                                <th scope="col" class=" table-th ">
                                                    Policy
                                                </th>
                                                <th scope="col" class=" table-th ">
                                                    Client
                                                </th>
                                                <th scope="col" class=" table-th ">
                                                    Gross/Net
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Amount
                                                </th>
                                                <th scope="col" class=" table-th ">
                                                    Status
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Issue
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Client
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Sales-Out
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Sum-Insured
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    %
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Action
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                            @foreach ($sales_comm as $comm)
                                                @if (!$comm->sold_policy_id)
                                                    @continue
                                                @endif
                                                <tr>


                                                    <td class="table-td ">
                                                        <a href="{{ route('sold.policy.show', $comm->sold_policy?->id) }}"
                                                            target="_blank"
                                                            class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">

                                                            <span
                                                                class="block text-slate-600 dark:text-slate-300">{{ $comm->sold_policy?->policy_number }}</span>
                                                            <span class="block text-slate-500 text-xs">
                                                                {{-- {{ $comm->sales->first_name }} {{ $comm->sales->last_name }} --}}
                                                            </span>
                                                        </a>

                                                    </td>
                                                    <td class="table-td ">
                                                        <div class="">
                                                            <span class="text-slate-500 dark:text-slate-400">
                                                                <span
                                                                    class="block text-slate-600 dark:text-slate-300">{{ $comm->sold_policy?->policy?->company?->name }}
                                                                    {{ $comm->sold_policy?->policy?->name }}</span>
                                                                <span class="block text-slate-500 text-xs">
                                                                    {{-- {{ $comm->sales->first_name }} {{ $comm->sales->last_name }} --}}
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </td>

                                                    <td class="table-td ">
                                                        <div class="text-lg">
                                                            {{ $comm->sold_policy?->gross_premium }} /
                                                            {{ $comm->sold_policy?->net_premium }}
                                                        </div>
                                                    </td>
                                                    <td class="table-td ">
                                                        <div class="text-lg">
                                                            {{ $comm->sold_policy?->client?->name }}
                                                        </div>
                                                    </td>

                                                    <td class="table-td ">
                                                        <div class="text-lg text-success-500">
                                                            {{ number_format($comm->amount, 2, '.', ',') }}
                                                        </div>
                                                    </td>

                                                    <td class="table-td">
                                                        @if (str_contains($comm->status, 'not_confirmed'))
                                                            <span class="badge bg-warning-500 text-white h-auto">
                                                                <iconify-icon
                                                                    icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $comm->status)) }}
                                                            </span>
                                                        @elseif(str_contains($comm->status, 'cancelled'))
                                                            <span class="badge bg-danger-500 text-white h-auto">
                                                                <iconify-icon
                                                                    icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $comm->status)) }}
                                                            </span>
                                                        @elseif($comm->status === 'confirmed' || str_contains($comm->status, 'paid'))
                                                            <span class="badge bg-success-500 text-white h-auto">
                                                                <iconify-icon
                                                                    icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $comm->status)) }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="table-td ">
                                                        {{ $comm->sold_policy?->created_at ? \Carbon\Carbon::parse($comm->sold_policy?->created_at)->format('D d/m/Y') : 'Not set.' }}
                                                    </td>

                                                    <td class="table-td ">
                                                        {{ $comm->sold_policy?->client_payment_date ? \Carbon\Carbon::parse($comm->sold_policy?->client_payment_date)->format('D d/m/Y') : 'Not set.' }}
                                                    </td>


                                                    <td class="table-td">
                                                        {{ number_format($comm->sold_policy?->sales_out_comm, 2, '.', ',') }}
                                                    </td>

                                                    <td class="table-td px-0">
                                                        {{ number_format($comm->sold_policy?->insured_value, 2, '.', ',') }}
                                                    </td>

                                                    <td class="table-td px-0">
                                                        {{ number_format($comm->comm_percentage, 2, '.', ',') }}
                                                    </td>

                                                    <td class="table-td ">
                                                        <div class="dropstart relative">
                                                            <button class="inline-flex justify-center items-center"
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                                                    icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                            </button>

                                                            <ul
                                                                class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                <li>
                                                                    <a wire:click="refreshCommAmmount({{ $comm->id }})"
                                                                        class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                        <iconify-icon
                                                                            icon="material-symbols:refresh"></iconify-icon>
                                                                        <span>Refresh amount</span></a>
                                                                </li>
                                                                @can('update', $comm)
                                                                    @if (!$comm->invoice_id)
                                                                        <li>
                                                                            <a wire:click="addToInvoice({{ $comm->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="material-symbols:invoice"></iconify-icon>
                                                                                <span>Add to Payment</span></a>
                                                                        </li>
                                                                    @endif
                                                                    @if ($comm->is_new)
                                                                        <li>
                                                                            <a wire:click="setCommPaid({{ $comm->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="material-symbols:paid"></iconify-icon>
                                                                                <span>Set as paid</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a wire:click="setCommCancelled({{ $comm->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="line-md:cancel"></iconify-icon>
                                                                                <span>Set as Cancelled</span></a>
                                                                        </li>
                                                                    @endif




                                                                    @if ($comm->doc_url)
                                                                        <li>
                                                                            <a wire:click="ConfirmRemoveCommDoc({{ $comm->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="lucide:file-x"></iconify-icon>
                                                                                <span>Remove document</span>
                                                                            </a>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <label for="commDoc"
                                                                                wire:click="setCommDoc({{ $comm->id }})"
                                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon
                                                                                    icon="pepicons-pop:file"></iconify-icon>
                                                                                <span>Add document</span></label>
                                                                            <input type="file" id="commDoc"
                                                                                name="filename" style="display: none;"
                                                                                wire:model="commDoc">
                                                                        </li>
                                                                    @endif
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
                                                    @endcan
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                @endif
                                {{ $sales_comm->links('vendor.livewire.bootstrap') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($section === 'clientpayments')
        <div class="card rounded-md bg-white dark:bg-slate-800  shadow-base mt-5">
            <div class="card-body flex flex-col p-6 active justify-center">
                <header class="card-header noborder flex justify-between">
                    <div>
                        <h4 class="card-title">
                            Client Payments
                        </h4>
                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                            wire:target="updatedCommDoc" icon="line-md:loading-twotone-loop"></iconify-icon>
                    </div>

                    {{-- @can('create', \App\Models\Payments\SalesComm::class)
                        <button wire:click="toggleAddComm" class="btn btn-sm inline-flex justify-center btn-outline-dark rounded-[25px]">Add commission</button>
                    @endcan --}}

                    <button wire:click="changeSortDirection"
                        class="btn inline-flex justify-center btn-outline-light btn-sm">
                        @if ($isSortLatest)
                            Sort:<b>&nbsp;Newest</b>
                        @else
                            Sort:<b>&nbsp;Oldest</b>
                        @endif
                    </button>
                </header>
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6 ">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                @if ($client_payments->isEmpty())
                                    <p class="text-sm text-center">
                                        No collected payments found.
                                    </p>
                                @else
                                    <table
                                        class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead class=" border-t border-slate-100 dark:border-slate-800">
                                            <tr>

                                                <th scope="col" class=" table-th ">
                                                    Due
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Policy
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Client
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Amount
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Type
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Status
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Payment date
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Assigned to
                                                </th>

                                                <th scope="col" class=" table-th ">

                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                            @foreach ($client_payments as $payment)
                                                <tr>

                                                    <td class="table-td ">
                                                        {{ $payment->due ? \Carbon\Carbon::parse($payment->due)->format('D d/m/Y') : 'Not set.' }}
                                                    </td>

                                                    <td class="table-td ">
                                                        <a href="{{ route('sold.policy.show', $payment->sold_policy?->id) }}"
                                                            target="_blank"
                                                            class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">

                                                            <span
                                                                class="block text-slate-600 dark:text-slate-300">{{ $payment->sold_policy?->policy_number }}</span>
                                                        </a>

                                                    </td>
                                                    <td class="table-td ">
                                                        <div class="">
                                                            <span class="text-slate-500 dark:text-slate-400">
                                                                <span
                                                                    class="block text-slate-600 dark:text-slate-300">{{ $payment->sold_policy?->client?->name }}</span>
                                                            </span>
                                                        </div>
                                                    </td>

                                                    <td class="table-td ">
                                                        <div class="text-lg text-success-500">
                                                            {{ number_format($payment->amount, 2, '.', ',') }} EGP
                                                        </div>
                                                    </td>

                                                    <td class="table-td">
                                                        <span
                                                            class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize">{{ ucwords(str_replace('_', ' ', $payment->type)) }}</span>
                                                    </td>


                                                    <td class="table-td">
                                                        @if (str_contains($payment->status, 'new'))
                                                            <span class="badge bg-warning-500 text-white h-auto">
                                                                <iconify-icon
                                                                    icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                            </span>
                                                        @elseif(str_contains($payment->status, 'cancelled'))
                                                            <span class="badge bg-danger-500 text-white h-auto">
                                                                <iconify-icon
                                                                    icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                            </span>
                                                        @elseif(str_contains($payment->status, 'prem_collected'))
                                                            <span class="badge bg-info-500 text-white h-auto">
                                                                <iconify-icon
                                                                    icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                            </span>
                                                        @elseif($payment->status === 'confirmed' || str_contains($payment->status, 'paid'))
                                                            <span class="badge bg-success-500 text-white h-auto">
                                                                <iconify-icon
                                                                    icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $payment->status)) }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="table-td ">
                                                        {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('D d/m/Y') : 'Not set.' }}
                                                    </td>

                                                    <td class="table-td ">{{ $payment->assigned->first_name }}
                                                        {{ $payment->assigned->last_name }}</td>


                                                    <td class="table-td px-0">

                                                        @if ($payment->doc_url)
                                                            <iconify-icon class=" cursor-pointer" wire:loading.remove
                                                                wire:target="downloadPaymentDoc({{ $payment->id }})"
                                                                wire:click="downloadPaymentDoc({{ $payment->id }})"
                                                                icon="pepicons-pop:file" width="1.2em"
                                                                height="1.2em"></iconify-icon>
                                                            <iconify-icon
                                                                class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                                                wire:loading
                                                                wire:target="downloadPaymentDoc({{ $payment->id }})"
                                                                icon="line-md:loading-twotone-loop"></iconify-icon>
                                                        @endif
                                                        @if ($payment->note)
                                                            <iconify-icon class=" cursor-pointer"
                                                                wire:click="showPaymentNote({{ $payment->id }})"
                                                                icon="gravity-ui:comment" width="1.2em"
                                                                height="1.2em"></iconify-icon>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            {{ $client_payments->links('vendor.livewire.bootstrap') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Targets --}}
    @if ($section === 'targets')
        <div class="card mt-5">
            <div class="card-body">
                <div class="card-text h-full">
                    <div class="px-4 pt-4 pb-3">
                        <div class="flex justify-between">
                            <label class="form-label">
                                Targets
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="targetMoveup,targetMovedown"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </label>

                        </div>

                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto ">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class="">
                                                <tr>
                                                    <th scope="col" class=" table-th ">
                                                        %
                                                    </th>
                                                    <th scope="col" class=" table-th ">
                                                        Prem Target
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Each - Next Run
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Income Target
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Payment / Balance
                                                    </th>
                                                    <th scope="col" class=" table-th ">
                                                        Base?
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Action
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @foreach ($targets as $target)
                                                    <tr>
                                                        <td class="table-td ">
                                                            <p class="text-success-600 text-lg">
                                                                <b>{{ $target->comm_percentage }}%</b>
                                                            </p>
                                                        </td>
                                                        <td class="table-td ">
                                                            <p class="text-success-600 text-lg">
                                                                <b>{{ number_format($target->prem_target, 0, '.', ',') }}</b>
                                                            </p>
                                                        </td>

                                                        <td class="table-td ">
                                                            {{ $target->day_of_month }} / {{ $target->each_month }} -
                                                            {{ $target->calculated_next_run_date->format('Y-m-d') }}
                                                        </td>

                                                        <td class="table-td ">

                                                            {{ number_format($target->min_income_target, 0, '.', ',') . '->' . ($target->max_income_target ? number_format($target->max_income_target, 0, '.', ',') : '∞') }}

                                                        </td>


                                                        <td class="table-td ">
                                                            {{ $target->add_as_payment }}% /
                                                            {{ $target->add_to_balance }}%
                                                        </td>
                                                        <td class="table-td ">
                                                            {{ $target->base_payment ?? 'N/A' }}
                                                        </td>

                                                        <td class="p-1">
                                                            <div class=" flex justify-center">
                                                                <button class="toolTip onTop action-btn m-1 "
                                                                    data-tippy-content="Show Runs"
                                                                    wire:click="showTagetRuns({{ $target->id }})"
                                                                    type="button">
                                                                    <iconify-icon icon="hugeicons:view"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1 "
                                                                    data-tippy-content="Edit"
                                                                    wire:click="editThisTarget({{ $target->id }})"
                                                                    type="button">
                                                                    <iconify-icon
                                                                        icon="iconamoon:edit-bold"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1"
                                                                    data-tippy-content="Move Up" type="button"
                                                                    wire:click="targetMoveup({{ $target->id }})">
                                                                    <iconify-icon icon="ion:arrow-up"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1"
                                                                    data-tippy-content="Move Down" type="button"
                                                                    wire:click="targetMovedown({{ $target->id }})">
                                                                    <iconify-icon icon="ion:arrow-down"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1"
                                                                    data-tippy-content="Delete" type="button"
                                                                    wire:click="confirmDeleteTarget({{ $target->id }})">
                                                                    <iconify-icon
                                                                        icon="heroicons:trash"></iconify-icon>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($targets->isEmpty())
                                                    <tr>
                                                        <td colspan="6" class="text-center p-5">
                                                            <div
                                                                class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500">
                                                                <div
                                                                    class="flex items-start space-x-3 rtl:space-x-reverse">
                                                                    <div class="flex-1">
                                                                        No targets added to this profile!
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="6" class="pt-3">
                                                        <button wire:click="openNewTargetSection"
                                                            class="btn inline-flex justify-center btn-light btn-sm">
                                                            Add new target
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    {{ $targets->links('vendor.livewire.bootstrap') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

    {{-- Configurations --}}
    @if ($section === 'configurations')
        <div class="card mt-5">
            <div class="card-body">
                <div class="card-text h-full">
                    <div class="px-4 pt-4 pb-3">
                        <div class="flex justify-between">
                            <label class="form-label">
                                Direct Configurations
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="moveup,movedown"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </label>

                        </div>

                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto ">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class="">
                                                <tr>

                                                    <th scope="col" class=" table-th ">
                                                        Percentage
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        From
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Condition
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Action
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @foreach ($configurations as $conf)
                                                    <tr>

                                                        <td class="table-td ">
                                                            <p class="text-success-500 text-lg">
                                                                <b>{{ $conf->percentage }}%
                                                                </b>
                                                            </p>
                                                        </td>

                                                        <td class="table-td">
                                                            {{ ucwords(str_replace('_', ' ', $conf->from)) }}

                                                        </td>

                                                        <td class="table-td">
                                                            {{ $conf->condition_title }}
                                                        </td>

                                                        <td class="p-1">
                                                            <div class=" flex justify-center">
                                                                <button class="toolTip onTop action-btn m-1 "
                                                                    data-tippy-content="Edit"
                                                                    wire:click="editThisConf({{ $conf->id }})"
                                                                    type="button">
                                                                    <iconify-icon
                                                                        icon="iconamoon:edit-bold"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1"
                                                                    data-tippy-content="Move Up" type="button"
                                                                    wire:click="moveup({{ $conf->id }})">
                                                                    <iconify-icon icon="ion:arrow-up"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1"
                                                                    data-tippy-content="Move Down" type="button"
                                                                    wire:click="movedown({{ $conf->id }})">
                                                                    <iconify-icon icon="ion:arrow-down"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1"
                                                                    data-tippy-content="Delete" type="button"
                                                                    wire:click="confirmDeleteConf({{ $conf->id }})">
                                                                    <iconify-icon
                                                                        icon="heroicons:trash"></iconify-icon>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($configurations->isEmpty())
                                                    <tr>
                                                        <td colspan="6" class="text-center p-5">
                                                            <div
                                                                class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500">
                                                                <div
                                                                    class="flex items-start space-x-3 rtl:space-x-reverse">
                                                                    <div class="flex-1">
                                                                        No configrations added to this profile!
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="6" class="pt-3">
                                                        <button wire:click="openNewConfSection"
                                                            class="btn inline-flex justify-center btn-light btn-sm">
                                                            Add new configuration
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    {{ $configurations->links('vendor.livewire.bootstrap') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($runs)

        <div class="card">
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
                                    Target Runs
                                </h3>

                                <button wire:click="closeTargetRuns" type="button"
                                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                    data-bs-dismiss="modal">
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
                                <div class="card-body px-6 pb-6">
                                    <div class="overflow-x-auto -mx-6">
                                        <div class="inline-block min-w-full align-middle">
                                            <div class="overflow-hidden ">
                                                <table
                                                    class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                                        <tr>
                                                            <th scope="col" class="table-th">
                                                                Added to Balance
                                                            </th>
                                                            <th scope="col" class="table-th">
                                                                Added to Payment
                                                            </th>
                                                            <th scope="col" class="table-th">
                                                                Execution time
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody
                                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                        @forelse ($runs as $run)
                                                            <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                                                <td class="table-td">{{ $run->added_to_balance }}
                                                                </td>
                                                                <td class="table-td">{{ $run->added_to_payments }}
                                                                </td>
                                                                <td class="table-td">
                                                                    {{ \Carbon\Carbon::parse($run->created_at)->format('D d/m/Y H:i:s') }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3" class="text-center py-4">
                                                                    No records found.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal footer -->
                        </div>
                    </div>
                </div>
            </div>

        </div>

    @endif

    @if ($downloadAccountStatementSec)
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
                                Download Account Statement
                            </h3>

                            <button wire:click="closeDownloadAccountStatementSec" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="downloadAccountStartDate" class="form-label">Start date</label>
                                    <input type="date" name="downloadAccountStartDate"
                                        class="form-control @error('downloadAccountStartDate') !border-danger-500 @enderror !pr-32"
                                        wire:model.defer="downloadAccountStartDate">
                                </div>
                                @error('downloadAccountStartDate')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="downloadAccountEndDate" class="form-label">End date</label>
                                    <input type="date" name="downloadAccountEndDate"
                                        class="form-control @error('downloadAccountEndDate') !border-danger-500 @enderror !pr-32"
                                        wire:model.defer="downloadAccountEndDate">
                                </div>
                                @error('downloadAccountEndDate')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="downloadAccountStatement" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="downloadAccountStatement">Download</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="downloadAccountStatement"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($pymtNotePreview)
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
                                Payment Note
                            </h3>

                            <button wire:click="closePymtNote" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            {{ $pymtNotePreview }}
                        </div>
                        <!-- Modal footer -->
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($paymentNoteSec)
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
                                Payment Note
                            </h3>
                            <button wire:click="hidePaymentComment" type="button"
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
                            {{ $paymentNoteSec }}
                        </div>
                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newPymtSec)
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
                                Add Payment
                            </h3>

                            <button wire:click="closeNewPymtSection" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="mb-4">
                                <div class="from-group">
                                    <label for="lastName" class="form-label">Amount</label>
                                    <input type="number" name="pymtAmount" class="form-control mt-2 w-full"
                                        wire:model.defer="pymtAmount" min="1">
                                    @error('pymtAmount')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="from-group">
                                    <label for="pymtType" class="form-label">Payment Type</label>
                                    <select wire:model.live="pymtType"
                                        class="form-control w-full py-2 @error('pymtType') !border-danger-500 @enderror">
                                        <option value="">Select a payment type...</option>
                                        @foreach ($PYMT_TYPES as $PYMT_TYPE)
                                            <option value="{{ $PYMT_TYPE }}">{{ ucfirst($PYMT_TYPE) }}</option>
                                        @endforeach
                                    </select>
                                    @error('pymtType')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-base font-medium mb-3">Link Sales Commissions</h4>

                                <!-- Search Bar for Sales Commissions -->
                                <div class="mb-3">
                                    <label for="salesCommSearch" class="form-label mb-2">Search by Policy
                                        Number</label>
                                    <div class="relative">
                                        <input type="text" wire:model.debounce.300ms="salesCommSearch"
                                            wire:keyup="searchSalesComm" class="form-control w-full"
                                            placeholder="Type to search...">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <iconify-icon wire:loading wire:target="searchSalesComm"
                                                class="loading-icon text-lg"
                                                icon="line-md:loading-twotone-loop"></iconify-icon>
                                        </div>
                                    </div>

                                    <!-- Search Results -->
                                    @if (count($salesCommSearchResults) > 0)
                                        <div
                                            class="mt-1 bg-white dark:bg-slate-600 shadow-lg rounded-md border border-slate-200 dark:border-slate-700 absolute z-50 w-full max-h-60 overflow-y-auto">
                                            @foreach ($salesCommSearchResults as $result)
                                                <div wire:click="addToInvoice({{ $result->id }})"
                                                    class="p-3 hover:bg-slate-100 dark:hover:bg-slate-700 cursor-pointer border-b border-slate-200 dark:border-slate-700">
                                                    <div class="flex justify-between">
                                                        <div>
                                                            <p class="font-medium">
                                                                {{ $result->sold_policy->policy_number }}</p>
                                                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                                                {{ $result->sold_policy->client->name }}</p>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="font-medium">
                                                                {{ number_format($result->amount, 2) }}</p>
                                                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                                                {{ $result->sold_policy->policy->company->name }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>


                            </div>

                            <div class="mb-4">
                                <div class="from-group">
                                    <label for="lastName" class="form-label">Document</label>
                                    <input type="file" name="pymtDoc" class="form-control mt-2 w-full"
                                        wire:model.defer="pymtDoc">
                                    @error('pymtDoc')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="from-group">
                                    <label for="lastName" class="form-label">Note</label>
                                    <textarea name="pymtNote" class="form-control mt-2 w-full" wire:model.defer="pymtNote"></textarea>
                                    @error('pymtNote')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <h4 class="text-base font-medium">Selected Sales Commissions</h4>
                            <div class="space-y-3 mb-4">
                                @foreach ($salesCommArray as $index => $item)
                                    <div
                                        class="p-3 bg-white dark:bg-slate-700 rounded-md border border-slate-200 dark:border-slate-700 shadow-sm">
                                        <div class="flex justify-between">
                                            <div>
                                                <p class="font-medium">{{ $item['policy_number'] ?? 'N/A' }}</p>
                                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                                    {{ $item['client_name'] ?? 'N/A' }}</p>
                                            </div>
                                            <div class="text-right">
                                                <button type="button"
                                                    wire:click="removeSalesComm({{ $index }})"
                                                    class="text-danger-500">
                                                    <iconify-icon icon="material-symbols:delete-outline"
                                                        width="1.2em" height="1.2em"></iconify-icon>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Percentage and Amount in another line -->
                                        <div class="flex space-x-4 mt-3">
                                            <div class="w-1/2">
                                                <label class="form-label text-xs">Percentage</label>
                                                <input type="number"
                                                    wire:model.defer="salesCommArray.{{ $index }}.paid_percentage"
                                                    placeholder="Percentage"
                                                    class="form-control @error('salesCommArray.' . $index . '.paid_percentage') !border-danger-500 @enderror">
                                                @error('salesCommArray.' . $index . '.paid_percentage')
                                                    <span
                                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="w-1/2">
                                                <label class="form-label text-xs">Amount</label>
                                                <input type="number"
                                                    wire:model.defer="salesCommArray.{{ $index }}.amount"
                                                    placeholder="Amount"
                                                    class="form-control @error('salesCommArray.' . $index . '.amount') !border-danger-500 @enderror">
                                                @error('salesCommArray.' . $index . '.amount')
                                                    <span
                                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if (count($salesCommArray) === 0 && !$selectedSalesComm)
                                    <div class="text-center p-4 bg-slate-50 dark:bg-slate-600 rounded-md">
                                        <p class="text-slate-500 dark:text-slate-400">No commissions selected yet</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addPayment" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addPayment">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="addPayment"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @if ($showLinkedSalesComm)
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
                                Payment Linked Sales Commissions
                            </h3>

                            <button wire:click="closeLinkedSalesComm" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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

                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>

                                        <th scope="col" class=" table-th ">
                                            Title
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Amount
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Percentage
                                        </th>

                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                    @foreach ($linkedSalesComm as $linkedSC)
                                        <tr class="even:bg-slate-50 dark:even:bg-slate-700">
                                            <td class="table-td">{{ $linkedSC->sold_policy->policy_number }}</td>
                                            <td class="table-td"><b>{{ number_format($linkedSC->pivot?->amount) }}
                                                    EGP</b>
                                            </td>
                                            <td class="table-td "><b>{{ $linkedSC->comm_percentage }}%</b></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-between p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">

                            <button wire:click="downloadPaymentDetails({{ $showLinkedSalesComm }})"
                                data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-success-500 btn-sm">
                                <span wire:loading.remove
                                    wire:target="downloadPaymentDetails({{ $showLinkedSalesComm }})">Download Payment
                                    Details</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="downloadPaymentDetails({{ $showLinkedSalesComm }})"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>

                            <button wire:click="closeLinkedSalesComm" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500 btn-sm">
                                <span wire:loading.remove wire:target="closeLinkedSalesComm">close</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="closeLinkedSalesComm"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($pymtId)
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
                                Edit Payment
                            </h3>

                            <button wire:click="closeEditPymtSection" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="pymtAmount" class="form-label">Amount</label>
                                    <div class="relative">
                                        <input type="number" name="pymtAmount"
                                            class="form-control @error('pymtAmount') !border-danger-500 @enderror !pr-32"
                                            wire:model.defer="pymtAmount">
                                        <span
                                            class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            EGP
                                        </span>
                                    </div>
                                </div>
                                @error('pymtAmount')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="pymtType" class="form-label">Payment type</label>
                                    <select name="pymtType"
                                        class="form-control w-full mt-2 @error('pymtType') !border-danger-500 @enderror"
                                        wire:model.defer="pymtType">
                                        <option value="">None</option>
                                        @foreach ($PYMT_TYPES as $PYMT_TYPE)
                                            <option value="{{ $PYMT_TYPE }}">
                                                {{ ucwords(str_replace('_', ' ', $PYMT_TYPE)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('pymtType')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="pymtNote" class="form-label">Note</label>
                                    <div class="relative">
                                        <input type="text" name="pymtNote"
                                            class="form-control @error('pymtNote') !border-danger-500 @enderror !pr-32"
                                            wire:model.defer="pymtNote">
                                    </div>
                                </div>
                                @error('pymtNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editPayment" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editPayment">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="editPayment"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($pymtPaidId)
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
                                Set Paid
                            </h3>

                            <button wire:click="closeSetPaidSec" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="pymtPaidDate" class="form-label">Payment date</label>
                                    <div class="relative">
                                        <input type="date" name="pymtPaidDate"
                                            class="form-control @error('pymtPaidDate') !border-danger-500 @enderror !pr-32"
                                            wire:model.defer="pymtPaidDate">
                                    </div>
                                </div>
                                @error('pymtPaidDate')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPymtPaid" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setPymtPaid">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setPymtPaid"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($pymtCancelledId)
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
                                Set Cancelled
                            </h3>

                            <button wire:click="closeSetCancelledSec" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="pymtCancelledDate" class="form-label">Cancellation date</label>
                                    <div class="relative">
                                        <input type="date" name="pymtCancelledDate"
                                            class="form-control @error('pymtCancelledDate') !border-danger-500 @enderror !pr-32"
                                            wire:model.defer="pymtCancelledDate">
                                    </div>
                                </div>
                                @error('pymtCancelledDate')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPymtCancelled" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setPymtCancelled">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setPymtCancelled"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newTargetSec)
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
                                Add Target
                            </h3>

                            <button wire:click="closeNewTargetSection" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="from-group">
                                <div class="flex justify-between items-start space-x-6">
                                    <div class="input-area mt-3 w-full">
                                        <label for="dayOfMonth" class="form-label">Day of month</label>
                                        <input id="dayOfMonth" type="number" class="form-control"
                                            wire:model="dayOfMonth" @disabled($isEndOfMonth)>
                                        @error('dayOfMonth')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area mt-3 flex flex-col">
                                        <label for="checkBox" class="form-label no-wrap">Last Day?</label>
                                        <div class="checkbox-area mt-2">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden row-checkbox"
                                                    wire:model="isEndOfMonth">
                                                <span
                                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                    <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                        alt=""
                                                        class="h-[10px] w-[10px] block m-auto opacity-0">
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="input-area mt-3">
                                    <label for="eachMonth" class="form-label">Each month</label>
                                    <input id="eachMonth" type="number" class="form-control"
                                        wire:model="eachMonth">
                                </div>
                                @error('eachMonth')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Next Run Date</label>
                                    <input type="date" class="form-control" wire:model.defer="nextRunDate">
                                </div>

                                <div class="flex justify-between items-start space-x-6">
                                    <div class="input-area mt-3">
                                        <label for="commPercentage" class="form-label">Comm. Percentage</label>
                                        <div class="relative">
                                            <input type="number" name="commPercentage"
                                                class="form-control @error('commPercentage') !border-danger-500 @enderror !pr-32"
                                                wire:model.defer="commPercentage">
                                            <span
                                                class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                                %
                                            </span>
                                        </div>
                                    </div>

                                    <div class="input-area mt-3 flex flex-col">
                                        <label for="checkBox" class="form-label no-wrap">Full Amount?</label>
                                        <div class="checkbox-area mt-2">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden row-checkbox"
                                                    wire:model="isFullAmount">
                                                <span
                                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                    <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                        alt=""
                                                        class="h-[10px] w-[10px] block m-auto opacity-0">
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('commPercentage')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="prem_target" class="form-label">Yearly Premium Target</label>
                                    <input id="premTarget" type="number" class="form-control"
                                        wire:model="premTarget">
                                    <small class=caption>For info only. Not used for calculations</small>
                                </div>
                                @error('premTarget')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="minIncomeTarget" class="form-label">Min Income Target</label>
                                    <input id="minIncomeTarget" type="number" class="form-control"
                                        wire:model="minIncomeTarget">
                                    <small class=caption>Used for calculations. Min. Target per 'Each Month'
                                        period</small>
                                </div>
                                @error('minIncomeTarget')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="maxIncomeTarget" class="form-label">Max Income Target</label>
                                    <input id="maxIncomeTarget" type="number" class="form-control"
                                        wire:model="maxIncomeTarget">
                                    <small class=caption>Used for calculations. Max. Target per 'Each Month' period.
                                        Leave empty if there is no max limit</small>
                                </div>
                                @error('maxIncomeTarget')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="addToBalance" class="form-label">Add To Balance %</label>
                                    <input id="addToBalance" type="number" class="form-control"
                                        wire:model="addToBalance">
                                </div>
                                @error('addToBalance')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="addAsPayment" class="form-label">Add as Payment %</label>
                                    <input id="addAsPayment" type="number" class="form-control"
                                        wire:model="addAsPayment">
                                </div>
                                @error('addAsPayment')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="basePayment" class="form-label">Base Payment</label>
                                    <input id="basePayment" type="number" class="form-control"
                                        wire:model="basePayment">
                                </div>
                                @error('basePayment')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addTarget" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addTarget">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="addTarget"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editTargetId)
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
                                Edit Target
                            </h3>

                            <button wire:click="closeEditTargetSection" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="from-group">
                                <div class="flex justify-between items-start space-x-6">
                                    <div class="input-area mt-3 w-full">
                                        <label for="dayOfMonth" class="form-label">Day of month</label>
                                        <input id="dayOfMonth" type="number" class="form-control"
                                            wire:model="dayOfMonth" @disabled($isEndOfMonth)>
                                        @error('dayOfMonth')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area mt-3 flex flex-col">
                                        <label for="checkBox" class="form-label no-wrap">Last Day?</label>
                                        <div class="checkbox-area mt-2">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden row-checkbox"
                                                    wire:model="isEndOfMonth">
                                                <span
                                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                    <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                        alt=""
                                                        class="h-[10px] w-[10px] block m-auto opacity-0">
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="input-area mt-3">
                                    <label for="eachMonth" class="form-label">Each month</label>
                                    <input id="eachMonth" type="number" class="form-control"
                                        wire:model="eachMonth">
                                </div>
                                @error('eachMonth')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="flex justify-between items-start space-x-6">
                                    <div class="input-area mt-3">
                                        <label for="commPercentage" class="form-label">Comm. Percentage</label>
                                        <div class="relative">
                                            <input type="number" name="commPercentage"
                                                class="form-control @error('commPercentage') !border-danger-500 @enderror !pr-32"
                                                wire:model.defer="commPercentage">
                                            <span
                                                class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                                %
                                            </span>
                                        </div>
                                    </div>

                                    <div class="input-area mt-3 flex flex-col">
                                        <label for="checkBox" class="form-label no-wrap">Full Amount?</label>
                                        <div class="checkbox-area mt-2">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden row-checkbox"
                                                    wire:model="isFullAmount">
                                                <span
                                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                    <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                        alt=""
                                                        class="h-[10px] w-[10px] block m-auto opacity-0">
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                @error('commPercentage')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="prem_target" class="form-label">Yearly Premium Target</label>
                                    <input id="premTarget" type="number" class="form-control"
                                        wire:model="premTarget">
                                    <small class=caption>For info only. Not used for calculations</small>
                                </div>
                                @error('premTarget')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="minIncomeTarget" class="form-label">Min Income Target</label>
                                    <input id="minIncomeTarget" type="number" class="form-control"
                                        wire:model="minIncomeTarget">
                                    <small class=caption>Used for calculations. Min. Target per 'Each Month'
                                        period</small>
                                </div>
                                @error('minIncomeTarget')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="maxIncomeTarget" class="form-label">Max Income Target</label>
                                    <input id="maxIncomeTarget" type="number" class="form-control"
                                        wire:model="maxIncomeTarget">
                                    <small class=caption>Used for calculations. Max. Target per 'Each Month' period.
                                        Leave empty if there is no max limit</small>
                                </div>
                                @error('maxIncomeTarget')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="addToBalance" class="form-label">Add To Balance %</label>
                                    <input id="addToBalance" type="number" class="form-control"
                                        wire:model="addToBalance">
                                </div>
                                @error('addToBalance')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="addAsPayment" class="form-label">Add as Payment %</label>
                                    <input id="addAsPayment" type="number" class="form-control"
                                        wire:model="addAsPayment">
                                </div>
                                @error('addAsPayment')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="basePayment" class="form-label">Base Payment</label>
                                    <input id="basePayment" type="number" class="form-control"
                                        wire:model="basePayment">
                                </div>
                                @error('basePayment')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editarget" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-black bg-white-500">
                                <span wire:target="closeEditTargetSection">Close</span>
                            </button>
                            <button wire:click="editarget" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editarget">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="editarget"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newConfSec)
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
                                Add Commission Configuration
                            </h3>

                            <button wire:click="closeNewConfSection" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="percentage" class="form-label">Percentage</label>
                                    <div class="relative">
                                        <input type="number"
                                            class="form-control @error('percentage') !border-danger-500 @enderror !pr-32"
                                            wire:model.defer="percentage">
                                        <span
                                            class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            %
                                        </span>
                                    </div>
                                    {{-- <input id="percentage" type="number" class="form-control @error('percentage') !border-danger-500 @enderror" wire:model.defer="percentage"> --}}
                                </div>
                                @error('percentage')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="from" class="form-label">From</label>
                                    <select name="from"
                                        class="form-control w-full mt-2 @error('from') !border-danger-500 @enderror"
                                        wire:model.defer="from">
                                        <option value="">None</option>
                                        @foreach ($FROMS as $FROM)
                                            <option value="{{ $FROM }}">
                                                {{ ucwords(str_replace('_', ' ', $FROM)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="line_of_business" class="form-label">Line of business</label>
                                    <select name="line_of_business"
                                        class="form-control w-full mt-2 @error('line_of_business') !border-danger-500 @enderror"
                                        wire:model="line_of_business">
                                        <option value="">None</option>
                                        @foreach ($LOBs as $LOB)
                                            <option value="{{ $LOB }}">
                                                {{ ucwords(str_replace('_', ' ', $LOB)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('line_of_business')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                @if (!$line_of_business)

                                    @if ($condition)
                                        <p class="mt-3"><iconify-icon icon="mdi:shield-tick"></iconify-icon>
                                            {{ $condition->company ? $condition->company->name . ' | ' : '' }}
                                            {{ $condition->name }}
                                        </p>
                                    @else
                                        <div class="input-area mt-3">
                                            <label for="conditionType" class="form-label">Condition Type</label>
                                            <select name="conditionType" class="form-control w-full mt-2"
                                                wire:model="conditionType">
                                                <option value="policy">Policy</option>
                                                <option value="company">Company</option>
                                            </select>
                                        </div>

                                        <div class="input-area mt-3">
                                            <label for="searchCon" class="form-label">Search
                                                {{ $conditionType }}</label>
                                            <input id="searchCon" type="text" class="form-control"
                                                wire:model="searchCon">
                                        </div>

                                        <div class="text-sm">
                                            @if ($searchlist)
                                                @if ($conditionType == 'company')
                                                    @foreach ($searchlist as $result)
                                                        <p class="mt-3"><iconify-icon
                                                                icon="heroicons:building-storefront"></iconify-icon>
                                                            {{ $result->name }} <Span
                                                                wire:click="selectResult({{ $result->id }})"
                                                                class="cursor-pointer text-primary-500">Select
                                                                Company</Span>
                                                        </p>
                                                    @endforeach
                                                @elseif ($conditionType == 'policy')
                                                    @foreach ($searchlist as $result)
                                                        <p class="mt-3"><iconify-icon
                                                                icon="material-symbols:policy-outline-rounded"></iconify-icon>
                                                            {{ $result->company->name }} | {{ $result->name }} <Span
                                                                wire:click="selectResult({{ $result->id }})"
                                                                class="cursor-pointer text-primary-500">Select
                                                                Policy</Span>
                                                        </p>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </div>

                                    @endif
                                @endif

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addConf" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addConf">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="addConf"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editConfId)
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
                                Edit Commission Configuration
                            </h3>

                            <button wire:click="closeEditConf" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="percentage" class="form-label">Percentage</label>
                                    <div class="relative">
                                        <input type="number"
                                            class="form-control @error('percentage') !border-danger-500 @enderror !pr-32"
                                            wire:model.defer="percentage">
                                        <span
                                            class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            %
                                        </span>
                                    </div>
                                    {{-- <input id="percentage" type="number" class="form-control @error('percentage') !border-danger-500 @enderror" wire:model.defer="percentage"> --}}
                                </div>
                                @error('percentage')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="from" class="form-label">From</label>
                                    <select name="from"
                                        class="form-control w-full mt-2 @error('from') !border-danger-500 @enderror"
                                        wire:model.defer="from">
                                        <option value="">None</option>
                                        @foreach ($FROMS as $FROM)
                                            <option value="{{ $FROM }}">
                                                {{ ucwords(str_replace('_', ' ', $FROM)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror



                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editConf" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editConf">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="editConf"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($updatedCommSec)
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
                                Edit Commission Profile
                            </h3>

                            <button wire:click="closeUpdateSec" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="updatedType" class="form-label">Type</label>
                                    <select name="updatedType"
                                        class="form-control w-full mt-2 @error('updatedType') !border-danger-500 @enderror"
                                        wire:model.defer="updatedType">
                                        <option>None</option>
                                        @foreach ($profileTypes as $type)
                                            <option value="{{ $type }}">
                                                {{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('updatedType')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <div class="flex items-center space-x-2">
                                        <label
                                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input wire:model="updatedPerPolicy" type="checkbox" value=""
                                                class="sr-only peer">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500">
                                            </div>
                                        </label>
                                        <span class="text-sm text-slate-600 font-Inter font-normal">Per Policy</span>

                                    </div>

                                    <div class="flex items-center space-x-2 mt-3">
                                        <label
                                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input wire:model="updatedSelectAvailable" type="checkbox"
                                                value="" class="sr-only peer">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500">
                                            </div>
                                        </label>
                                        <span class="text-sm text-slate-600 font-Inter font-normal">Available for
                                            Selection</span>

                                    </div>
                                </div>

                                {{-- <div class="input-area mt-3">
                                        <label for="updatedUserId" class="form-label">User</label>
                                        <select name="updatedUserId" id="updatedUserId" class="form-control w-full mt-2 @error('updatedUserId') !border-danger-500 @enderror" wire:model="updatedUserId">
                                            <option value="">None</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('updatedUserId')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror --}}

                                <div class="input-area mt-3">
                                    <label for="updatedTitle" class="form-label">Title</label>
                                    <input id="updatedTitle" type="text"
                                        class="form-control @error('updatedTitle') !border-danger-500 @enderror"
                                        wire:model.defer="updatedTitle">
                                </div>

                                <div class="input-area mt-3">
                                    <label for="updatedAvailableForId" class="form-label">Available For User</label>
                                    <select name="updatedAvailableForId" class="form-control w-full mt-2 "
                                        wire:model.defer="updatedAvailableForId">
                                        <option>None</option>
                                        @foreach ($users as $u)
                                            <option value="{{ $u->id }}">
                                                {{ $u->username }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-area mt-3">
                                    <label for="updatedAutomaticOverrideId" class="form-label">Automatic
                                        Override</label>
                                    <select name="updatedAutomaticOverrideId" class="form-control w-full mt-2 "
                                        wire:model.defer="updatedAutomaticOverrideId">
                                        <option>None</option>
                                        @foreach ($overrides as $o)
                                            <option value="{{ $o->id }}">
                                                {{ $o->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="from-group mt-3">
                                    <label for="updatedDesc" class="form-label">Description</label>
                                    <textarea class="form-control mt-2 w-full" wire:model.defer="updatedDesc"></textarea>
                                    @error('updatedDesc')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="from-group mt-3">
                                    <label for="account" class="form-label">Account</label>
                                    @inject('helper', 'App\Helpers\Helpers')
                                    <select id="account"
                                        class="form-control @error('updatedAccountId') !border-danger-500 @enderror"
                                        wire:model.defer="updatedAccountId">
                                        <option value="">Select Account</option>
                                        @php
                                            $printed_arr = [];
                                        @endphp
                                        @foreach ($accounts_list as $account)
                                            {{ $helper->printAccountChildren('', $account, $printed_arr) }}
                                        @endforeach
                                    </select>
                                    @error('updatedAccountId')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="updateComm" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="updateComm">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="updateComm"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteConfId)
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
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Configuration
                            </h3>
                            <button wire:click="dismissDeleteConf" type="button"
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
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure ! you Want to delete this Configuration ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteConf" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">
                                <span wire:loading.remove wire:target="deleteConf">Yes, Delete</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="deleteConf"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteTargetId)
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
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Target
                            </h3>
                            <button wire:click="dismissDeleteTarget" type="button"
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
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure ! you Want to delete this Target ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteTarget" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">
                                <span wire:loading.remove wire:target="deleteTarget">Yes, Delete</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="deleteTarget"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($pymtDeleteId)
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
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Payment Document
                            </h3>
                            <button wire:click="dismissDeletePymtDoc" type="button"
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
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure ! you Want to delete this payment document ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deletePymtDoc" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">
                                <span wire:loading.remove wire:target="deletePymtDoc">Yes, Delete</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="deletePymtDoc"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($commNote)
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
                                Commission Note
                            </h3>
                            <button wire:click="hideCommComment" type="button"
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
                            {{ $commNote }}
                        </div>
                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($startTargetRunSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                <iconify-icon icon="radix-icons:rocket" width="1.2em"
                                    height="1.2em"></iconify-icon>
                                Start Target Run
                            </h3>
                            <button wire:click="closeStartTargetRunSec" type="button"
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

                                <div class="input-area mt-3">
                                    <label for="startTargetRunEndDate" class="form-label">End Date</label>
                                    <div class="relative">
                                        <input type="date" name="startTargetRunEndDate"
                                            class="form-control @error('startTargetRunEndDate') !border-danger-500 @enderror !pr-32"
                                            wire:model.defer="startTargetRunEndDate">
                                    </div>
                                </div>
                                @error('startTargetRunEndDate')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="startManualTargetsRun " data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500">
                                    <span wire:loading.remove wire:target="startManualTargetsRun ">Submit</span>
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                        wire:loading wire:target="startManualTargetsRun "
                                        icon="line-md:loading-twotone-loop"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endif

    @if ($RemoveCommDocId)
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
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Remove Commission Document
                            </h3>
                            <button wire:click="DissRemoveCommDoc" type="button"
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
                                Are you sure ! you Want to remove commission Document ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="removeCommDoc" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes,
                                Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- addCommSec --}}
    @if ($addCommSec)
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
                                Add Sales Commission
                            </h3>
                            <button wire:click="toggleAddComm" type="button"
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
                                <label for="commTitle" class="form-label">Title</label>
                                <input type="text" name="commTitle"
                                    class="form-control mt-2 w-full @error('commTitle') !border-danger-500 @enderror"
                                    wire:model.defer="commTitle">
                                @error('commTitle')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="from-group">
                                <label for="commFrom" class="form-label">From</label>
                                <select name="commFrom" id="commFrom" class="form-control w-full mt-2"
                                    wire:model="commFrom">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                        value="">
                                        Select option</option>
                                    @foreach ($FROMS as $FROM)
                                        <option value="{{ $FROM }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $FROM)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="from-group">
                                <label for="commPer" class="form-label">Percentage</label>
                                <input type="number" min="0" max="100" name="commPer"
                                    class="form-control mt-2 w-full @error('commPer') !border-danger-500 @enderror"
                                    wire:model.defer="commPer">
                                @error('commPer')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="from-group">
                                <label for="lastName" class="form-label">Sales Person</label>
                                <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2"
                                    wire:model="commUser">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="from-group">
                                <label for="lastName" class="form-label">Note</label>
                                <textarea class="form-control mt-2 w-full @error('newcommNote') !border-danger-500 @enderror"
                                    wire:model.defer="newcommNote"></textarea>
                                @error('newcommNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addComm" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal for Creating Main Journal Entry -->
    @if ($createMainJournalEntryId)
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
                            <h3 class="text-xl font-medium text-white dark:text-white">
                                Create Main Journal Entry
                            </h3>
                            <button wire:click="closeCreateMainJournalEntryModal" type="button"
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
                        <div class="p-6">
                            <p class="mb-4">Select an entry title for this journal entry:</p>

                            <div class="grid gap-4">
                                <div class="input-area">
                                    <label for="entryTitleSearch" class="form-label">Search Entry Title</label>
                                    <input id="entryTitleSearch" type="text"
                                        wire:model.debounce.300ms="entryTitleSearch" class="form-control"
                                        placeholder="Search by ID or name">
                                </div>

                                <div class="input-area">
                                    <label for="selectedEntryTitleId" class="form-label">Entry Title</label>
                                    <select id="selectedEntryTitleId" wire:model="selectedEntryTitleId"
                                        class="form-control">
                                        <option value="">-- Select Entry Title --</option>
                                        @foreach ($entryTitles as $entryTitle)
                                            <option value="{{ $entryTitle->id }}">{{ $entryTitle->id }} -
                                                {{ $entryTitle->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedEntryTitleId')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeCreateMainJournalEntryModal" type="button"
                                class="btn btn-outline-secondary">
                                Cancel
                            </button>
                            <button wire:click="createMainJournalEntry" type="button"
                                class="btn inline-flex justify-center btn-dark">
                                <span wire:loading.remove wire:target="createMainJournalEntry">Create Journal
                                    Entry</span>
                                <span wire:loading wire:target="createMainJournalEntry">
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

    <!-- Modal for Creating Sales Journal Entry -->
    @if ($createSalesJournalEntryId)
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
                            <h3 class="text-xl font-medium text-white dark:text-white">
                                Create Sales Journal Entry
                            </h3>
                            <button wire:click="closeCreateSalesJournalEntryModal" type="button"
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
                        <div class="p-6">
                            <p class="mb-4">Select an entry title for this journal entry:</p>

                            <div class="grid gap-4">
                                <div class="input-area">
                                    <label for="entryTitleSearchSales" class="form-label">Search Entry Title</label>
                                    <input id="entryTitleSearchSales" type="text"
                                        wire:model.debounce.300ms="entryTitleSearch" class="form-control"
                                        placeholder="Search by ID or name">
                                </div>

                                <div class="input-area">
                                    <label for="selectedEntryTitleIdSales" class="form-label">Entry Title</label>
                                    <select id="selectedEntryTitleIdSales" wire:model="selectedEntryTitleId"
                                        class="form-control">
                                        <option value="">-- Select Entry Title --</option>
                                        @foreach ($entryTitles as $entryTitle)
                                            <option value="{{ $entryTitle->id }}">{{ $entryTitle->id }} -
                                                {{ $entryTitle->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedEntryTitleId')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeCreateSalesJournalEntryModal" type="button"
                                class="btn btn-outline-secondary">
                                Cancel
                            </button>
                            <button wire:click="createSalesJournalEntry" type="button"
                                class="btn inline-flex justify-center btn-dark">
                                <span wire:loading.remove wire:target="createSalesJournalEntry">Create Journal
                                    Entry</span>
                                <span wire:loading wire:target="createSalesJournalEntry">
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

</div>

</div>
