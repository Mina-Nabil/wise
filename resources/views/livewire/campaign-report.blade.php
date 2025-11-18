<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                <b>Reports:</b> Campaigns -- Total: {{ $campaigns->total() }}
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
                    <li wire:click="toggleStartDate">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Start Date ( From-To )</span>
                    </li>
                    <li wire:click="toggleEndDate">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            End Date ( From-To )</span>
                    </li>
                    <li wire:click="toggleBudget">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Budget ( From-To )</span>
                    </li>
                    <li wire:click="toggleHandler">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Handler</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card mt-5 pt-5">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4"
                placeholder="Search by campaign name, channels or target audience" wire:model="search">
        </header>

        <header class="card-header cust-card-header noborder" style="display: block;">

            @if ($start_from || $start_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleStartDate">
                        {{ $start_from ? 'Start Date From: ' . \Carbon\Carbon::parse($start_from)->format('l d/m/Y') : '' }}
                        {{ $start_from && $start_to ? '-' : '' }}
                        {{ $start_to ? 'To: ' . \Carbon\Carbon::parse($start_to)->format('l d/m/Y') : '' }}
                        &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearStartDates">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($end_from || $end_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleEndDate">
                        {{ $end_from ? 'End Date From: ' . \Carbon\Carbon::parse($end_from)->format('l d/m/Y') : '' }}
                        {{ $end_from && $end_to ? '-' : '' }}
                        {{ $end_to ? 'To: ' . \Carbon\Carbon::parse($end_to)->format('l d/m/Y') : '' }}
                        &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearEndDates">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($budget_from || $budget_to)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleBudget">
                        {{ $budget_from ? 'Budget From: ' . number_format($budget_from, 0, '.', ',') : '' }}
                        {{ $budget_from && $budget_to ? '-' : '' }}
                        {{ $budget_to ? 'To: ' . number_format($budget_to, 0, '.', ',') : '' }}
                        &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearBudget">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

            @if ($handler_id)
                <button class="btn inline-flex justify-center btn-dark btn-sm">
                    <span wire:click="toggleHandler">
                        {{ $handler_id ? 'Handler: ' . $handlerName : '' }}
                        &nbsp;&nbsp;
                    </span>
                    <span wire:click="clearHandler">
                        <iconify-icon icon="material-symbols:close" width="1.2em" height="1.2em"></iconify-icon>
                    </span>
                </button>
            @endif

        </header>

        <div class="card-body px-6 pb-6">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead
                                class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        #
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Description
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Target Audience
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Marketing Channels
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Budget
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Client Price
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Handler
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Start Date
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        End Date
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($campaigns as $campaign)
                                    <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">

                                        <td class="table-td ">
                                            {{ $campaign->id }}
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ $campaign->name }}</b>
                                        </td>

                                        <td class="table-td ">
                                            {{ \Illuminate\Support\Str::limit($campaign->description, 50) }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $campaign->target_audience }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $campaign->marketing_channels }}
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ $campaign->budget ? number_format($campaign->budget, 0, '.', ',') : 'N/A' }}</b>
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ number_format($campaign->client_price, 2, '.', ',') }}</b>
                                        </td>

                                        <td class="table-td ">
                                            @if($campaign->handler && is_numeric($campaign->handler))
                                                @php $handler = App\Models\Users\User::find($campaign->handler); @endphp
                                                {{ $handler ? $handler->first_name . ' ' . $handler->last_name : 'N/A' }}
                                            @else
                                                {{ $campaign->handler ?? 'N/A' }}
                                            @endif
                                        </td>

                                        <td class="table-td ">
                                            {{ $campaign->start_date ? $campaign->start_date->format('Y-m-d') : 'N/A' }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $campaign->end_date ? $campaign->end_date->format('Y-m-d') : 'N/A' }}
                                        </td>

                                        <td class="table-td ">
                                            @if ($campaign->is_active)
                                                <span class="badge bg-success-500 h-auto">
                                                    <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;Active
                                                </span>
                                            @else
                                                <span class="badge bg-warning-500 h-auto">
                                                    <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;Inactive
                                                </span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @if ($campaigns->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No campaigns with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/campaigns') }}"
                                            class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all campaigns</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif

                    </div>

                    {{ $campaigns->links('vendor.livewire.bootstrap') }}

                </div>
            </div>
        </div>
    </div>

    @if ($startDateSection)
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
                                Start Date
                            </h3>
                            <button wire:click="toggleStartDate" type="button"
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
                                <label for="Estart_from" class="form-label">Start Date from</label>
                                <input name="Estart_from" type="date"
                                    class="form-control mt-2 w-full @error('Estart_from') !border-danger-500 @enderror"
                                    wire:model.defer="Estart_from">
                                @error('Estart_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Estart_to" class="form-label">Start Date to</label>
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

    @if ($endDateSection)
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
                                End Date
                            </h3>
                            <button wire:click="toggleEndDate" type="button"
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
                                <label for="Eend_from" class="form-label">End Date from</label>
                                <input name="Eend_from" type="date"
                                    class="form-control mt-2 w-full @error('Eend_from') !border-danger-500 @enderror"
                                    wire:model.defer="Eend_from">
                                @error('Eend_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Eend_to" class="form-label">End Date to</label>
                                <input name="Eend_to" type="date"
                                    class="form-control mt-2 w-full @error('Eend_to') !border-danger-500 @enderror"
                                    wire:model.defer="Eend_to">
                                @error('Eend_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setEndDates" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setEndDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setEndDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($budgetSection)
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
                                Budget Range
                            </h3>
                            <button wire:click="toggleBudget" type="button"
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
                                <label for="Ebudget_from" class="form-label">Budget from</label>
                                <input name="Ebudget_from" type="number"
                                    class="form-control mt-2 w-full @error('Ebudget_from') !border-danger-500 @enderror"
                                    wire:model.defer="Ebudget_from">
                                @error('Ebudget_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Ebudget_to" class="form-label">Budget to</label>
                                <input name="Ebudget_to" type="number"
                                    class="form-control mt-2 w-full @error('Ebudget_to') !border-danger-500 @enderror"
                                    wire:model.defer="Ebudget_to">
                                @error('Ebudget_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setBudget" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setBudget">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setBudget"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($handlerSection)
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
                                Handler
                            </h3>
                            <button wire:click="toggleHandler" type="button"
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
                                <label for="Ehandler_id" class="form-label">Handler</label>
                                <select name="Ehandler_id" id="Ehandler_id" class="form-control w-full mt-2"
                                    wire:model.defer="Ehandler_id">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                        value="">
                                        Select user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setHandler" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setHandler">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setHandler"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
