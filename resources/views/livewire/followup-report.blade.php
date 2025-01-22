<div>
    <div>
        <div class="flex justify-between flex-wrap items-center">
            <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                    <b>Reports:</b> Followups
                </h4>
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
                        <li wire:click="toggleCallTime">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Call Time ( From-To )</span>
                        </li>

                        <li wire:click="toggleLob">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Line of business</span>
                        </li>

                        <li wire:click="toggleSales">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Sales</span>
                        </li>

                        <li wire:click="toggleCalled">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Client</span>
                        </li>

                        <li wire:click="toggleIsMeeting">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Meeting</span>
                        </li>

                    </ul>
                </div>
            </div>

        </div>

        <div class="card-body px-6 pb-6  overflow-x-auto">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="card">
                     

                        <header class="card-header cust-card-header noborder" style="display: block;">

                            @if ($callTime_from || $callTime_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleCallTime">
                                        {{ $callTime_from ? 'Start From: ' . \Carbon\Carbon::parse($callTime_from)->format('l d/m/Y') : '' }}
                                        {{ $callTime_from && $callTime_to ? '-' : '' }}
                                        {{ $callTime_to ? 'Start To: ' . \Carbon\Carbon::parse($callTime_to)->format('l d/m/Y') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearCallTime">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($line_of_business)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleLob">
                                        {{ $line_of_business ? 'LOB: ' . ucwords(str_replace('_', ' ', $line_of_business)) : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearLob">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if (!is_null($isMeeting))
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleIsMeeting">
                                        {{ $isMeeting ? 'Meeting: Yes' : 'Meeting: No' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearMeeting">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($salesId)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleSales">
                                        {{ $salesId ? 'Sales: ' . $SalesName : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearSales">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($calledId)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleCalled">
                                        {{ $calledType ? $calledType . ': ' . $clientName : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearCalled">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                        </header>

                        <div class="card-body px-6 pb-6">
                            <div class=" -mx-6">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-x-hidden">
                                        <table
                                            class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 no-wrap">
                                            <thead
                                                class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                                <tr>
                                                    <th scope="col" class="table-th">
                                                        #
                                                    </th>
                                                    <th scope="col" class="table-th">
                                                        Sales
                                                    </th>
                                                    
                                                    <th scope="col" class="table-th">
                                                        Client
                                                    </th>

                                                    <th scope="col" class="table-th">
                                                        Call Time
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Status
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Title
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        LOB
                                                    </th>

                                                    <th scope="col" class=" table-th ">
                                                        Description
                                                    </th>



                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y cursor-pointer divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                                @foreach ($followups as $followup)
                                                    <tr class="hover:bg-slate-200 dark:hover:bg-slate-700"
                                                        wire:click="">

                                                        <td class="table-td">
                                                            {{ $followup->id }}
                                                        </td>
                                                        <td class="table-td">
                                                            {{ $followup->creator->username }}
                                                        </td>

                                                        <td class="table-td">

                                                            <div
                                                                class="flex space-x-3 items-center text-left rtl:space-x-reverse">
                                                                <div class="flex-none">
                                                                    <div
                                                                        class="h-10 w-10 rounded-full text-sm bg-[#E0EAFF] dark:bg-slate-700 flex flex-col items-center justify-center font-medium -tracking-[1px]">
                                                                        @if ($followup->called_type === 'customer')
                                                                            <iconify-icon
                                                                                icon="raphael:customer"></iconify-icon>
                                                                        @elseif($followup->called_type === 'corporate')
                                                                            <iconify-icon
                                                                                icon="mdi:company"></iconify-icon>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="flex-1 font-medium text-sm leading-4 whitespace-nowrap">
                                                                    <a class="hover:underline cursor-pointer"
                                                                        href="{{ route($followup->called_type . 's.show', $followup->called_id.'?section=followups') }}">
                                                                        @if ($followup->called_type === 'customer')
                                                                            {{ $followup->called->first_name . ' ' . $followup->called->middle_name . ' ' . $followup->called->last_name }}
                                                                        @elseif($followup->called_type === 'corporate')
                                                                            {{ $followup->called->name }}
                                                                        @endif
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td class="table-td">
                                                            {{ \Carbon\Carbon::parse($followup->call_time)->format('D d/m') }}
                                                        </td>

                                                        <td class="table-td">
                                                            @if ($followup->status === 'new')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-primary-500 bg-primary-500 text-xs">
                                                                    New
                                                                </div>
                                                            @elseif($followup->status === 'called')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500 text-xs">
                                                                    Called
                                                                </div>
                                                            @elseif($followup->status === 'canceled')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-black-500 bg-black-500 text-xs">
                                                                    Cancelled
                                                                </div>
                                                            @endif
                                                        </td>

                                                        <td class="table-td">
                                                            {{ $followup->title }}
                                                        </td>

                                                        <td class="table-td">
                                                            @if ($followup->line_of_business)
                                                                {{ ucwords(str_replace('_', ' ', $followup->line_of_business)) }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>

                                                        <td class="table-td">
                                                            <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap"
                                                                style="max-width: 350px">
                                                                <div
                                                                    class="text-sm text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap">
                                                                    {{ $followup->desc }}
                                                                </div>
                                                            </div>

                                                        </td>


                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>

                                        @if ($followups->isEmpty())
                                            {{-- START: empty filter result --}}
                                            <div class="card p-5">
                                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                                    <div class="items-center text-center p-5">
                                                        <h2><iconify-icon
                                                                icon="icon-park-outline:search"></iconify-icon></h2>
                                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No
                                                            Followups with the
                                                            applied
                                                            filters</h2>
                                                        <p class="card-text">Try changing the filters or search terms
                                                            for this view.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- END: empty filter result --}}
                                        @endif

                                    </div>



                                    {{ $followups->links('vendor.livewire.bootstrap') }}

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>



    </div>

    @if ($calledSection)
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
                                Client
                            </h3>
                            <button wire:click="toggleCalled" type="button"
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
                                <label for="EcalledType" class="form-label">Client type</label>
                                <select name="EcalledType" id="EcalledType" class="form-control w-full mt-2"
                                    wire:model="EcalledType">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                        value="customer">Customer</option>
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                        value="corporate">Corporate</option>
                                    </optgroup>
                                </select>
                            </div>


                            <div class="from-group">
                                <input name="EcallTime_from" placeholder="Search client..." type="text"
                                    class="form-control mt-2 w-full" wire:model="searchClientText">
                            </div>

                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>

                                            <th scope="col" class=" table-th ">
                                                name
                                            </th>

                                            <th scope="col" class=" table-th ">

                                            </th>

                                            <th scope="col" class=" table-th ">

                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                        @foreach ($clients as $client)
                                            @if ($EcalledType === 'customer')
                                                <tr wire:click='setCalled({{ $client->id }})'
                                                    class="hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">
                                                    <td class="table-td">
                                                        {{ $client->first_name . ' ' . $client->middle_name . ' ' . $client->last_name }}
                                                    </td>
                                                    <td class="table-td"></td>
                                                    <td class="table-td "></td>
                                                </tr>
                                            @elseif($EcalledType === 'corporate')
                                                <tr wire:click='setCalled({{ $client->id }})'
                                                    class="hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">
                                                    <td class="table-td">{{ $client->name }}</td>
                                                    <td class="table-td"></td>
                                                    <td class="table-td "></td>
                                                </tr>
                                            @endif
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($salesSection)
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
                                Sales
                            </h3>
                            <button wire:click="toggleSales" type="button"
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
                                <label for="ESalesId" class="form-label">Sales</label>
                                <select name="ESalesId" id="ESalesId" class="form-control w-full mt-2"
                                    wire:model.defer="ESalesId">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                        value="">
                                        Select user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                        </option>
                                    @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setSales" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setSales">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setSales"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($lobSection)
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
                                Line of business
                            </h3>
                            <button wire:click="toggleLob" type="button"
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
                                <label for="Eline_of_business" class="form-label">Line of business</label>
                                <select name="Eline_of_business" id="Eline_of_business"
                                    class="form-control w-full mt-2" wire:model.defer="Eline_of_business">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                        value="">
                                        Select LOB </option>
                                    @foreach ($LINES_OF_BUSINESS as $LOB)
                                        <option value="{{ $LOB }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $LOB)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setLob" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setLob">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setLob"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($callTimeSection)
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
                                Call Time
                            </h3>
                            <button wire:click="toggleCallTime" type="button"
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
                                <label for="EcallTime_from" class="form-label">From</label>
                                <input name="EcallTime_from" type="date"
                                    class="form-control mt-2 w-full @error('EcallTime_from') !border-danger-500 @enderror"
                                    wire:model.defer="EcallTime_from">
                                @error('EcallTime_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="EcallTime_to" class="form-label">To</label>
                                <input name="EcallTime_to" type="date"
                                    class="form-control mt-2 w-full @error('EcallTime_to') !border-danger-500 @enderror"
                                    wire:model.defer="EcallTime_to">
                                @error('EcallTime_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setCallTime" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setCallTime">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setCallTime"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
