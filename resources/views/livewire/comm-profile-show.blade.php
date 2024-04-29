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
                        </div>
                        <div class="text-base">
                            {{ ucwords(str_replace('_', ' ', $profile->type)) }}
                        </div>
                    </div>
                    <div>
                        @if ($profile->user)
                            <a href="card.html" class="inline-flex leading-5 text-slate-500 dark:text-slate-400 text-sm font-normal active">
                                <iconify-icon class="text-secondary-500 ltr:mr-2 rtl:ml-2 text-lg" icon="lucide:user"></iconify-icon>
                                {{ $profile->user->first_name }} {{ $profile->user->last_name }}
                            </a>
                        @endif

                    </div>

                </div>
                <div class="card-text mt-4 menu-open">
                    <p>{{ $profile->desc }}</p>
                    @can('create', \App\Models\Payments\CommProfile::class)
                    <div class="mt-4 space-x-4 rtl:space-x-reverse">
                        <button wire:click="openUpdateSec" class="btn inline-flex justify-center btn-light btn-sm">Edit
                            info</button>
                    </div>
                    @endcan
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
                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#E5F9FF] dark:bg-slate-900	 text-info-500">
                            <iconify-icon icon="tdesign:money"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                            Balance
                        </div>
                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                            <h5>{{ number_format($profile->balance, 2, '.', ',') }} EGP</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body pt-4 pb-3 px-4">
                <div class="flex space-x-3 rtl:space-x-reverse">
                    <div class="flex-none">
                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#FFEDE6] dark:bg-slate-900	 text-warning-500">
                            <iconify-icon icon="mdi:money-off"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                            Unapproved Balance
                        </div>
                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                            {{ number_format($profile->unapproved_balance, 2, '.', ',') }} EGP
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body flex flex-col col-span-2 mb-5 mt-5" wire:ignore>
        <div class="card-text h-full">
            <div>
                <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0" id="tabs-tab" role="tablist">
                    <li class="nav-item" role="presentation" wire:click="changeSection('payments')">
                        <a href="#tabs-messages-withIcon" class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'payments') active @endif dark:text-slate-300" id="tabs-messages-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-messages-withIcon" role="tab" aria-controls="tabs-messages-withIcon"
                            aria-selected="false">
                            <iconify-icon class="mr-1" icon="material-symbols:payments"></iconify-icon>
                            Payments</a>
                    </li>
                    <li class="nav-item" role="presentation" wire:click="changeSection('salescomm')">
                        <a href="#tabs-messages-withIcon" class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'salescomm') active @endif dark:text-slate-300" id="tabs-messages-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-messages-withIcon" role="tab" aria-controls="tabs-messages-withIcon"
                            aria-selected="false">
                            <iconify-icon class="mr-1" icon="mynaui:percentage-waves"></iconify-icon>
                            Sales Commission</a>
                    </li>
                    <li class="nav-item" role="presentation" wire:click="changeSection('configurations')">
                        <a href="#tabs-messages-withIcon" class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'configurations') active @endif dark:text-slate-300" id="tabs-messages-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-messages-withIcon" role="tab" aria-controls="tabs-messages-withIcon"
                            aria-selected="false">
                            <iconify-icon class="mr-1" icon="grommet-icons:configure"></iconify-icon>
                            Configurations</a>
                    </li>
                    <li class="nav-item" role="presentation" wire:click="changeSection('targets')">
                        <a href="#tabs-messages-withIcon" class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'targets') active @endif dark:text-slate-300" id="tabs-messages-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-messages-withIcon" role="tab" aria-controls="tabs-messages-withIcon"
                            aria-selected="false">
                            <iconify-icon class="mr-1" icon="lets-icons:target-fill"></iconify-icon>
                            Targets</a>
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
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="downloadPymtDoc,showPymtNote" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </label>

                        </div>

                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto ">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
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
                                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @foreach ($profile->payments as $payment)
                                                    <tr>

                                                        <td class="table-td ">
                                                            <p class=" text-lg"><b>{{ number_format($payment->amount, 0, '.', ',') }} EGP
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
                                                            <div class=" flex justify-center" wire:loading.remove wire:target="downloadPymtDoc({{ $payment->id }})">
                                                                @if ($payment->doc_url)
                                                                    <button class="toolTip onTop action-btn m-1 " data-tippy-content="Edit" wire:click="downloadPymtDoc({{ $payment->id }})" type="button">
                                                                        <iconify-icon icon="basil:document-outline"></iconify-icon>
                                                                    </button>
                                                                @endif
                                                                @if ($payment->note)
                                                                    <button class="toolTip onTop action-btn m-1 " data-tippy-content="note" wire:click="showPymtNote({{ $payment->id }})" type="button">
                                                                        <iconify-icon icon="iconamoon:comment-bold"></iconify-icon>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="downloadPymtDoc({{ $payment->id }})" icon="line-md:loading-twotone-loop"></iconify-icon>
                                                        </td>

                                                        <td class="table-td">
                                                            <div class="min-w-[170px]">
                                                                <span class="text-slate-500 dark:text-slate-400">
                                                                    <span class="block text-slate-600 dark:text-slate-300" style="display: flex; align-items: center;">
                                                                        <iconify-icon icon="mdi:user" width="1.2em" height="1.2em"></iconify-icon>&nbsp;
                                                                        <span style="vertical-align: middle;">
                                                                            Created by: <b>{{ $payment->creator->first_name . ' ' . $payment->creator->last_name }}</b>
                                                                        </span>
                                                                    </span>
                                                                    <span class="block text-slate-500 text-xs" style="display: flex; align-items: center;">
                                                                        @if ($payment->approver)
                                                                            <iconify-icon icon="mdi:approve" width="1.2em" height="1.2em"></iconify-icon>&nbsp;
                                                                            <span style="vertical-align: middle;">
                                                                                Approved by: <b>{{ $payment->approver->first_name . ' ' . $payment->approver->last_name }}</b>
                                                                            </span>
                                                                        @endif
                                                                    </span>

                                                                </span>
                                                            </div>
                                                        </td>

                                                        <td class="table-td ">
                                                            <div class="dropstart relative">
                                                                <button class="inline-flex justify-center items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                </button>
                                                                <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                    @if ($payment->is_new)
                                                                        <li>
                                                                            <a wire:click="editThisPayment({{ $payment->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon icon="fa-regular:edit"></iconify-icon>
                                                                                <span>Edit</span></a>
                                                                        </li>
                                                                    @endif

                                                                    @if (($payment->is_new && !$payment->needs_approval) || $payment->is_approved)
                                                                        <li>
                                                                            <a wire:click="setPaidSec({{ $payment->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon icon="material-symbols:paid"></iconify-icon>
                                                                                <span>Set as paid</span></a>
                                                                        </li>
                                                                    @endif
                                                                    @if ($payment->is_new)
                                                                        <li>
                                                                            <a wire:click="setCancelledSec({{ $payment->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon icon="line-md:cancel"></iconify-icon>
                                                                                <span>Set as Cancelled</span></a>
                                                                        </li>
                                                                    @endif

                                                                    @if ($payment->doc_url)
                                                                        <li>
                                                                            <a wire:click="deleteThisPymtDoc({{ $payment->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon icon="ep:document-delete"></iconify-icon>
                                                                                <span>Remove Document</span></a>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <label for="pymtDocFile" wire:click="setUploadPymtDocId({{ $payment->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon icon="fluent:document-add-24-regular"></iconify-icon>
                                                                                <span>Add Document</span></label>
                                                                            <input type="file" id="pymtDocFile" name="filename" style="display: none;" wire:model="pymtDocFile">

                                                                        </li>
                                                                    @endif

                                                                    <li>
                                                                        <a wire:click="setPymtApprove({{ $payment->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                            <iconify-icon icon="mdi:approve"></iconify-icon>
                                                                            <span>Approve</span></a>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($profile->payments->isEmpty())
                                                    <tr>
                                                        <td colspan="6" class="text-center p-5">
                                                            <div class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500">
                                                                <div class="flex items-start space-x-3 rtl:space-x-reverse">
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
                                                        <button wire:click="openNewPymtSection" class="btn inline-flex justify-center btn-light btn-sm">
                                                            Add new payment
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
                    <h4 class="card-title">
                        Sales Commission
                    </h4>
                    @can('create', \App\Models\Payments\SalesComm::class)
                    <button wire:click="toggleAddComm" class="btn btn-sm inline-flex justify-center btn-outline-dark rounded-[25px]">Add commission</button>
                    @endcan
                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="updatedCommDoc" icon="line-md:loading-twotone-loop"></iconify-icon>
                </header>
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6 ">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                @if ($profile->sales_comm->isEmpty())
                                    <p class="text-sm text-center">
                                        No sales commissions found.
                                    </p>
                                @else
                                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead class=" border-t border-slate-100 dark:border-slate-800">
                                            <tr>

                                                <th scope="col" class=" table-th ">
                                                    Title
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Amount
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    From
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Payment Date
                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Status
                                                </th>

                                                <th scope="col" class=" table-th ">

                                                </th>

                                                <th scope="col" class=" table-th ">
                                                    Action
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                            @foreach ($profile->sales_comm as $comm)
                                                <tr>


                                                    <td class="table-td ">
                                                        <div class="">
                                                            <span class="text-slate-500 dark:text-slate-400">
                                                                <span class="block text-slate-600 dark:text-slate-300">{{ $comm->title }}</span>
                                                                <span class="block text-slate-500 text-xs">
                                                                    {{-- {{ $comm->sales->first_name }} {{ $comm->sales->last_name }} --}}
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </td>

                                                    <td class="table-td ">
                                                        <div class="text-lg text-success-500">
                                                            {{ number_format($comm->amount, 0, '.', ',') }} EGP
                                                        </div>
                                                    </td>

                                                    <td class="table-td ">
                                                        {{ ucwords(str_replace('_', ' ', $comm->from)) }}
                                                    </td>

                                                    <td class="table-td ">{{ $comm->payment_date ? \Carbon\Carbon::parse($comm->payment_date)->format('D d/m/Y') : 'Not set.' }}</td>


                                                    <td class="table-td">
                                                        @if (str_contains($comm->status, 'not_confirmed'))
                                                            <span class="badge bg-warning-500 text-white h-auto">
                                                                <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $comm->status)) }}
                                                            </span>
                                                        @elseif(str_contains($comm->status, 'cancelled'))
                                                            <span class="badge bg-danger-500 text-white h-auto">
                                                                <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $comm->status)) }}
                                                            </span>
                                                        @elseif($comm->status === 'confirmed' || str_contains($comm->status, 'paid'))
                                                            <span class="badge bg-success-500 text-white h-auto">
                                                                <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $comm->status)) }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="table-td px-0">

                                                        @if ($comm->doc_url)
                                                            <iconify-icon class=" cursor-pointer" wire:loading.remove wire:target="downloadCommDoc({{ $comm->id }})" wire:click="downloadCommDoc({{ $comm->id }})" icon="pepicons-pop:file" width="1.2em" height="1.2em"></iconify-icon>
                                                            <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="downloadCommDoc({{ $comm->id }})" icon="line-md:loading-twotone-loop"></iconify-icon>
                                                        @endif
                                                        @if ($comm->note)
                                                            <iconify-icon class=" cursor-pointer" wire:click="showCommNote({{ $comm->id }})" icon="gravity-ui:comment" width="1.2em" height="1.2em"></iconify-icon>
                                                        @endif
                                                    </td>

                                                    @can('update', $comm)
                                                        <td class="table-td ">
                                                            <div class="dropstart relative">
                                                                <button class="inline-flex justify-center items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                                </button>
                                                                <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                    @if ($comm->is_new)
                                                                        <li>
                                                                            <a wire:click="setCommPaid({{ $comm->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon icon="material-symbols:paid"></iconify-icon>
                                                                                <span>Set as paid</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a wire:click="setCommCancelled({{ $comm->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon icon="line-md:cancel"></iconify-icon>
                                                                                <span>Set as Cancelled</span></a>
                                                                        </li>
                                                                    @endif

                                                                    <li>
                                                                        <a wire:click="refreshCommAmmount({{ $comm->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                            <iconify-icon icon="material-symbols:refresh"></iconify-icon>
                                                                            <span>Refresh amount</span></a>
                                                                    </li>


                                                                    @if ($comm->doc_url)
                                                                        <li>
                                                                            <a wire:click="ConfirmRemoveCommDoc({{ $comm->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon icon="lucide:file-x"></iconify-icon>
                                                                                <span>Remove document</span>
                                                                            </a>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <label for="commDoc" wire:click="setCommDoc({{ $comm->id }})" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                                <iconify-icon icon="pepicons-pop:file"></iconify-icon>
                                                                                <span>Add document</span></label>
                                                                            <input type="file" id="commDoc" name="filename" style="display: none;" wire:model="commDoc">
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
                                Configurations
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="moveup,movedown" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </label>

                        </div>

                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto ">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
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
                                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @foreach ($profile->configurations as $conf)
                                                    <tr>

                                                        <td class="table-td ">
                                                            <p class="text-success-500 text-lg"><b>{{ $conf->percentage }}%
                                                                </b></p>
                                                        </td>

                                                        <td class="table-td">
                                                            {{ ucwords(str_replace('_', ' ', $conf->from)) }}

                                                        </td>

                                                        <td class="table-td">
                                                            {{ $conf->condition_title }}
                                                        </td>

                                                        <td class="p-1">
                                                            <div class=" flex justify-center">
                                                                <button class="toolTip onTop action-btn m-1 " data-tippy-content="Edit" wire:click="editThisConf({{ $conf->id }})" type="button">
                                                                    <iconify-icon icon="iconamoon:edit-bold"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1" data-tippy-content="Move Up" type="button" wire:click="moveup({{ $conf->id }})">
                                                                    <iconify-icon icon="ion:arrow-up"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1" data-tippy-content="Move Down" type="button" wire:click="movedown({{ $conf->id }})">
                                                                    <iconify-icon icon="ion:arrow-down"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1" data-tippy-content="Delete" type="button" wire:click="confirmDeleteConf({{ $conf->id }})">
                                                                    <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($profile->configurations->isEmpty())
                                                    <tr>
                                                        <td colspan="6" class="text-center p-5">
                                                            <div class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500">
                                                                <div class="flex items-start space-x-3 rtl:space-x-reverse">
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
                                                        <button wire:click="openNewConfSection" class="btn inline-flex justify-center btn-light btn-sm">
                                                            Add new configuration
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="targetMoveup,targetMovedown" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </label>

                        </div>

                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto ">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class="">
                                                <tr>

                                                    <th scope="col" class=" table-th ">
                                                        Period
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Amount
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Extra Percentage
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Action
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @foreach ($profile->targets as $target)
                                                    <tr>

                                                        <td class="table-td">
                                                            {{ ucwords(str_replace('-', ' ', $target->period)) }}
                                                        </td>

                                                        <td class="table-td ">
                                                            <p class="text-primary-600 text-lg">
                                                                <b>{{ number_format($target->amount, 0, '.', ',') }}</b>
                                                            </p>
                                                        </td>

                                                        <td class="table-td ">
                                                            <p class="text-success-500 text-lg">
                                                                <b>{{ $target->extra_percentage }}%</b>
                                                            </p>
                                                        </td>

                                                        <td class="p-1">
                                                            <div class=" flex justify-center">
                                                                <button class="toolTip onTop action-btn m-1 " data-tippy-content="Edit" wire:click="editThisTarget({{ $target->id }})" type="button">
                                                                    <iconify-icon icon="iconamoon:edit-bold"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1" data-tippy-content="Move Up" type="button" wire:click="targetMoveup({{ $target->id }})">
                                                                    <iconify-icon icon="ion:arrow-up"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1" data-tippy-content="Move Down" type="button" wire:click="targetMovedown({{ $target->id }})">
                                                                    <iconify-icon icon="ion:arrow-down"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1" data-tippy-content="Delete" type="button" wire:click="confirmDeleteTarget({{ $target->id }})">
                                                                    <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($profile->targets->isEmpty())
                                                    <tr>
                                                        <td colspan="6" class="text-center p-5">
                                                            <div class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500">
                                                                <div class="flex items-start space-x-3 rtl:space-x-reverse">
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
                                                        <button wire:click="openNewTargetSection" class="btn inline-flex justify-center btn-light btn-sm">
                                                            Add new target
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Cycles --}}
        <div class="card mt-5">
            <div class="card-body">
                <div class="card-text h-full">
                    <div class="px-4 pt-4 pb-3">
                        <div class="flex justify-between">
                            <label class="form-label">
                                Target Cycles
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="moveup,movedown" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </label>

                        </div>

                        <div class="card-body px-6 pb-6">
                            <div class="overflow-x-auto ">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class="">
                                                <tr>

                                                    <th scope="col" class=" table-th ">
                                                        Day of month
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Each month
                                                    </th>


                                                    <th scope="col" class=" table-th ">
                                                        Action
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @foreach ($profile->target_cycles as $cycle)
                                                    <tr>

                                                        <td class="table-td ">
                                                            <p class="text-lg">
                                                                <b>Day {{ $cycle->day_of_month }}</b>
                                                            </p>
                                                        </td>

                                                        <td class="table-td ">
                                                            <p class="text-lg">
                                                                <b>Each {{ $cycle->each_month }} Month/s</b>
                                                            </p>
                                                        </td>

                                                        <td class="p-1">
                                                            <div class=" flex justify-center">
                                                                <button class="toolTip onTop action-btn m-1 " data-tippy-content="Edit" wire:click="editThisCycle({{ $cycle->id }})" type="button">
                                                                    <iconify-icon icon="iconamoon:edit-bold"></iconify-icon>
                                                                </button>
                                                                <button class="toolTip onTop action-btn m-1" data-tippy-content="Delete" type="button" wire:click="confirmDeleteCycle({{ $cycle->id }})">
                                                                    <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($profile->target_cycles->isEmpty())
                                                    <tr>
                                                        <td colspan="6" class="text-center p-5">
                                                            <div class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500">
                                                                <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                                                    <div class="flex-1">
                                                                        No targets cycles added to this profile!
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="6" class="pt-3">
                                                        <button wire:click="openNewCycleSection" class="btn inline-flex justify-center btn-light btn-sm">
                                                            Add new target cycle
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



    @if ($pymtNotePreview)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Payment Note
                            </h3>

                            <button wire:click="closePymtNote" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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

    @if ($newPymtSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Payment
                            </h3>

                            <button wire:click="closeNewPymtSection" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                                        <input type="number" name="pymtAmount" class="form-control @error('pymtAmount') !border-danger-500 @enderror !pr-32" wire:model.defer="pymtAmount">
                                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            EGP
                                        </span>
                                    </div>
                                </div>
                                @error('pymtAmount')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="pymtType" class="form-label">Payment type</label>
                                    <select name="pymtType" class="form-control w-full mt-2 @error('pymtType') !border-danger-500 @enderror" wire:model.defer="pymtType">
                                        <option value="">None</option>
                                        @foreach ($PYMT_TYPES as $PYMT_TYPE)
                                            <option value="{{ $PYMT_TYPE }}">
                                                {{ ucwords(str_replace('_', ' ', $PYMT_TYPE)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('pymtType')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="pymtDoc" class="form-label">Document</label>
                                    <input id="pymtDoc" name="pymtDoc" type="file" class="form-control @error('pymtDoc') !border-danger-500 @enderror" wire:model="pymtDoc">
                                </div>
                                @error('pymtDoc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="pymtNote" class="form-label">Note</label>
                                    <div class="relative">
                                        <input type="text" name="pymtNote" class="form-control @error('pymtNote') !border-danger-500 @enderror !pr-32" wire:model.defer="pymtNote">
                                    </div>
                                </div>
                                @error('pymtNote')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addPayment" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addPayment">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="addPayment" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($pymtId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Payment
                            </h3>

                            <button wire:click="closeEditPymtSection" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                                        <input type="number" name="pymtAmount" class="form-control @error('pymtAmount') !border-danger-500 @enderror !pr-32" wire:model.defer="pymtAmount">
                                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            EGP
                                        </span>
                                    </div>
                                </div>
                                @error('pymtAmount')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="pymtType" class="form-label">Payment type</label>
                                    <select name="pymtType" class="form-control w-full mt-2 @error('pymtType') !border-danger-500 @enderror" wire:model.defer="pymtType">
                                        <option value="">None</option>
                                        @foreach ($PYMT_TYPES as $PYMT_TYPE)
                                            <option value="{{ $PYMT_TYPE }}">
                                                {{ ucwords(str_replace('_', ' ', $PYMT_TYPE)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('pymtType')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="pymtNote" class="form-label">Note</label>
                                    <div class="relative">
                                        <input type="text" name="pymtNote" class="form-control @error('pymtNote') !border-danger-500 @enderror !pr-32" wire:model.defer="pymtNote">
                                    </div>
                                </div>
                                @error('pymtNote')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editPayment" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editPayment">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="editPayment" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($pymtPaidId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Set Paid
                            </h3>

                            <button wire:click="closeSetPaidSec" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                                        <input type="date" name="pymtPaidDate" class="form-control @error('pymtPaidDate') !border-danger-500 @enderror !pr-32" wire:model.defer="pymtPaidDate">
                                    </div>
                                </div>
                                @error('pymtPaidDate')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPymtPaid" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setPymtPaid">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="setPymtPaid" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($pymtCancelledId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Set Cancelled
                            </h3>

                            <button wire:click="closeSetCancelledSec" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                                        <input type="date" name="pymtCancelledDate" class="form-control @error('pymtCancelledDate') !border-danger-500 @enderror !pr-32" wire:model.defer="pymtCancelledDate">
                                    </div>
                                </div>
                                @error('pymtCancelledDate')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setPymtCancelled" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setPymtCancelled">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="setPymtCancelled" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newTargetSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Target
                            </h3>

                            <button wire:click="closeNewTargetSection" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="period" class="form-label">Period</label>
                                    <select name="period" class="form-control w-full mt-2 @error('period') !border-danger-500 @enderror" wire:model.defer="period">
                                        <option value="">None</option>
                                        @foreach ($PERIODS as $PERIOD)
                                            <option value="{{ $PERIOD }}">
                                                {{ ucwords(str_replace('_', ' ', $PERIOD)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('period')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="extra_percentage" class="form-label">Extra Percentage</label>
                                    <div class="relative">
                                        <input type="number" name="extra_percentage" class="form-control @error('extra_percentage') !border-danger-500 @enderror !pr-32" wire:model.defer="extra_percentage">
                                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            %
                                        </span>
                                    </div>
                                    {{-- <input id="percentage" type="number" class="form-control @error('percentage') !border-danger-500 @enderror" wire:model.defer="percentage"> --}}
                                </div>
                                @error('extra_percentage')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input id="amount" type="number" class="form-control" wire:model="amount">
                                </div>
                                @error('amount')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addTarget" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addTarget">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="addTarget" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editTargetId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Target
                            </h3>

                            <button wire:click="closeEditTargetSection" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="period" class="form-label">Period</label>
                                    <select name="period" class="form-control w-full mt-2 @error('period') !border-danger-500 @enderror" wire:model="period">
                                        <option value="">None</option>
                                        @foreach ($PERIODS as $PERIOD)
                                            <option value="{{ $PERIOD }}">
                                                {{ ucwords(str_replace('_', ' ', $PERIOD)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('period')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="extra_percentage" class="form-label">Extra Percentage</label>
                                    <div class="relative">
                                        <input type="number" name="extra_percentage" class="form-control @error('extra_percentage') !border-danger-500 @enderror !pr-32" wire:model="extra_percentage">
                                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            %
                                        </span>
                                    </div>
                                    {{-- <input id="percentage" type="number" class="form-control @error('percentage') !border-danger-500 @enderror" wire:model.defer="percentage"> --}}
                                </div>
                                @error('extra_percentage')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input id="amount" type="number" class="form-control" wire:model="amount">
                                </div>
                                @error('amount')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editarget" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editarget">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="editarget" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newConfSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Commission Configuration
                            </h3>

                            <button wire:click="closeNewConfSection" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                                        <input type="number" class="form-control @error('percentage') !border-danger-500 @enderror !pr-32" wire:model.defer="percentage">
                                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            %
                                        </span>
                                    </div>
                                    {{-- <input id="percentage" type="number" class="form-control @error('percentage') !border-danger-500 @enderror" wire:model.defer="percentage"> --}}
                                </div>
                                @error('percentage')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="from" class="form-label">From</label>
                                    <select name="from" class="form-control w-full mt-2 @error('from') !border-danger-500 @enderror" wire:model.defer="from">
                                        <option value="">None</option>
                                        @foreach ($FROMS as $FROM)
                                            <option value="{{ $FROM }}">
                                                {{ ucwords(str_replace('_', ' ', $FROM)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('from')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="line_of_business" class="form-label">Line of business</label>
                                    <select name="line_of_business" class="form-control w-full mt-2 @error('line_of_business') !border-danger-500 @enderror" wire:model="line_of_business">
                                        <option value="">None</option>
                                        @foreach ($LOBs as $LOB)
                                            <option value="{{ $LOB }}">
                                                {{ ucwords(str_replace('_', ' ', $LOB)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('line_of_business')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
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
                                            <select name="conditionType" class="form-control w-full mt-2" wire:model="conditionType">
                                                <option value="policy">Policy</option>
                                                <option value="company">Company</option>
                                            </select>
                                        </div>

                                        <div class="input-area mt-3">
                                            <label for="searchCon" class="form-label">Search
                                                {{ $conditionType }}</label>
                                            <input id="searchCon" type="text" class="form-control" wire:model="searchCon">
                                        </div>

                                        <div class="text-sm">
                                            @if ($searchlist)
                                                @if ($conditionType == 'company')
                                                    @foreach ($searchlist as $result)
                                                        <p class="mt-3"><iconify-icon icon="heroicons:building-storefront"></iconify-icon>
                                                            {{ $result->name }} <Span wire:click="selectResult({{ $result->id }})" class="cursor-pointer text-primary-500">Select
                                                                Company</Span>
                                                        </p>
                                                    @endforeach
                                                @elseif ($conditionType == 'policy')
                                                    @foreach ($searchlist as $result)
                                                        <p class="mt-3"><iconify-icon icon="material-symbols:policy-outline-rounded"></iconify-icon>
                                                            {{ $result->company->name }} | {{ $result->name }} <Span wire:click="selectResult({{ $result->id }})" class="cursor-pointer text-primary-500">Select
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
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addConf" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addConf">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="addConf" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editConfId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Commission Configuration
                            </h3>

                            <button wire:click="closeEditConf" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                                        <input type="number" class="form-control @error('percentage') !border-danger-500 @enderror !pr-32" wire:model.defer="percentage">
                                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            %
                                        </span>
                                    </div>
                                    {{-- <input id="percentage" type="number" class="form-control @error('percentage') !border-danger-500 @enderror" wire:model.defer="percentage"> --}}
                                </div>
                                @error('percentage')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="from" class="form-label">From</label>
                                    <select name="from" class="form-control w-full mt-2 @error('from') !border-danger-500 @enderror" wire:model.defer="from">
                                        <option value="">None</option>
                                        @foreach ($FROMS as $FROM)
                                            <option value="{{ $FROM }}">
                                                {{ ucwords(str_replace('_', ' ', $FROM)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('from')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror



                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editConf" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editConf">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="editConf" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($updatedCommSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Commission Profile
                            </h3>

                            <button wire:click="closeUpdateSec" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="updatedType" class="form-label">Type</label>
                                    <select name="updatedType" class="form-control w-full mt-2 @error('updatedType') !border-danger-500 @enderror" wire:model.defer="updatedType">
                                        <option>None</option>
                                        @foreach ($profileTypes as $type)
                                            <option value="{{ $type }}">
                                                {{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('updatedType')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <div class="flex items-center space-x-2">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input wire:model="updatedPerPolicy" type="checkbox" value="" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500">
                                            </div>
                                        </label>
                                        <span class="text-sm text-slate-600 font-Inter font-normal">Per Policy</span>

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
                                    <input id="updatedTitle" type="text" class="form-control @error('updatedTitle') !border-danger-500 @enderror" wire:model.defer="updatedTitle">
                                </div>

                                <div class="from-group mt-3">
                                    <label for="updatedDesc" class="form-label">Description</label>
                                    <textarea class="form-control mt-2 w-full" wire:model.defer="updatedDesc"></textarea>
                                    @error('updatedDesc')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="updateComm" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="updateComm">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="updateComm" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteConfId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Configuration
                            </h3>
                            <button wire:click="dismissDeleteConf" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteConf" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">
                                <span wire:loading.remove wire:target="deleteConf">Yes, Delete</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="deleteConf" icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteTargetId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Target
                            </h3>
                            <button wire:click="dismissDeleteTarget" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteTarget" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">
                                <span wire:loading.remove wire:target="deleteTarget">Yes, Delete</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="deleteTarget" icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($newCycleSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Target Cycle
                            </h3>

                            <button wire:click="closeNewCycleSection" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="dayOfMonth" class="form-label">DayOf Month</label>
                                    <div class="relative">
                                        <input type="number" min="1" max="31" class="form-control @error('dayOfMonth') !border-danger-500 @enderror !pr-32" wire:model.defer="dayOfMonth">
                                    </div>
                                </div>
                                @error('dayOfMonth')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="eachMonth" class="form-label">Each Month</label>
                                    <div class="relative">
                                        <input type="number" class="form-control @error('eachMonth') !border-danger-500 @enderror !pr-32" wire:model.defer="eachMonth">
                                    </div>
                                </div>
                                @error('eachMonth')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror


                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addCycle" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="addCycle">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="addCycle" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editCycleId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Target Cycle
                            </h3>

                            <button wire:click="closeEditCycle" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">

                                <div class="input-area mt-3">
                                    <label for="dayOfMonth" class="form-label">DayOf Month</label>
                                    <div class="relative">
                                        <input type="number" min="1" max="31" class="form-control @error('dayOfMonth') !border-danger-500 @enderror !pr-32" wire:model.defer="dayOfMonth">
                                    </div>
                                </div>
                                @error('dayOfMonth')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="eachMonth" class="form-label">Each Month</label>
                                    <div class="relative">
                                        <input type="number" class="form-control @error('eachMonth') !border-danger-500 @enderror !pr-32" wire:model.defer="eachMonth">
                                    </div>
                                </div>
                                @error('eachMonth')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror


                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editCycle" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="editCycle">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="editCycle" icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteCycleId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Cycle
                            </h3>
                            <button wire:click="dismissDeleteCycle" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure ! you Want to delete this Cycle ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteCycle" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">
                                <span wire:loading.remove wire:target="deleteCycle">Yes, Delete</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="deleteCycle" icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($pymtDeleteId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Payment Document
                            </h3>
                            <button wire:click="dismissDeletePymtDoc" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deletePymtDoc" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">
                                <span wire:loading.remove wire:target="deletePymtDoc">Yes, Delete</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="deletePymtDoc" icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($commNote)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Commission Note
                            </h3>
                            <button wire:click="hideCommComment" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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

    @if ($RemoveCommDocId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Remove Commission Document
                            </h3>
                            <button wire:click="DissRemoveCommDoc" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="removeCommDoc" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">Yes,
                                Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- addCommSec --}}
    @if ($addCommSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Sales Commission
                            </h3>
                            <button wire:click="toggleAddComm" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">

                            <div class="from-group">
                                <label for="commTitle" class="form-label">Title</label>
                                <input type="text" name="commTitle" class="form-control mt-2 w-full @error('commTitle') !border-danger-500 @enderror" wire:model.defer="commTitle">
                                @error('commTitle')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="from-group">
                                <label for="commFrom" class="form-label">From</label>
                                <select name="commFrom" id="commFrom" class="form-control w-full mt-2" wire:model="commFrom">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600" value="">
                                        Select option</option>
                                    @foreach ($FROMS as $FROM)
                                        <option value="{{ $FROM }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $FROM)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="from-group">
                                <label for="commPer" class="form-label">Percentage</label>
                                <input type="number" min="0" max="100" name="commPer" class="form-control mt-2 w-full @error('commPer') !border-danger-500 @enderror" wire:model.defer="commPer">
                                @error('commPer')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="from-group">
                                <label for="lastName" class="form-label">Sales Person</label>
                                <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="commUser">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="from-group">
                                <label for="lastName" class="form-label">Note</label>
                                <textarea class="form-control mt-2 w-full @error('newcommNote') !border-danger-500 @enderror" wire:model.defer="newcommNote"></textarea>
                                @error('newcommNote')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addComm" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
