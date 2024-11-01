<div>
    <div>
        <div class="flex justify-between flex-wrap items-center">
            <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                    <b>Reports:</b> Tasks
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
                        <li wire:click="toggleStartDate">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Start date ( From-To )</span>
                        </li>

                        <li wire:click="toggleDueDate">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Due date ( From-To )</span>
                        </li>

                        <li wire:click="toggleIsExpired">
                            <span
                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white cursor-pointer">
                                Expired</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="card-body px-6 pb-6  overflow-x-auto">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="card">
                        <header class="card-header cust-card-header noborder">
                            <iconify-icon wire:loading class="loading-icon text-lg"
                                icon="line-md:loading-twotone-loop"></iconify-icon>
                            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search"
                                wire:model="search">


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

                            @if ($due_from || $due_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="setDueDates">
                                        {{ $due_from ? 'Due From: ' . \Carbon\Carbon::parse($due_from)->format('l d/m/Y') : '' }}
                                        {{ $due_from && $due_to ? '-' : '' }}
                                        {{ $due_to ? 'Due To: ' . \Carbon\Carbon::parse($due_to)->format('l d/m/Y') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearDueDates">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if (!is_null($isExpired))
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleIsExpired">
                                        {{ $isExpired ? 'Expired: Yes' : 'Expired: No' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearExpired">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                        </header>

                        <div class="card-body px-6 pb-6">
                            <div class=" -mx-6">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <thead class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                                <tr>
                                                    <th scope="col" class="table-th">
                                                        Created
                                                    </th>
                    
                                                    <th scope="col" class="table-th">
                                                        Due
                                                    </th>
                    
                                                    <th scope="col" class="table-th">
                                                        Assignee
                                                    </th>
                    
                                                    <th scope="col" class=" table-th ">
                                                        Title
                                                    </th>
                    
                                                    <th scope="col" class=" table-th ">
                                                        Type
                                                    </th>
                    
                                                    <th scope="col" class=" table-th ">
                                                        Status
                                                    </th>
                    
                                                    <th scope="col" class=" table-th ">
                                                        Creator
                                                    </th>
                    
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y cursor-pointer divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                    
                                                @foreach ($tasks as $task)
                                                    <tr class="hover:bg-slate-200 dark:hover:bg-slate-700"
                                                        wire:click="redirectToShowPage({{ $task->id }})">
                    
                                                        <td class="table-td">
                                                            {{ $task->created_at->format('D d/m') }}
                                                        </td>
                    
                                                        <td class="table-td" style="vertical-align: middle;">
                                                            @if ($task->due && \Carbon\Carbon::parse($task->due)->isPast())
                                                                <span
                                                                    class="h-[6px] w-[6px] bg-danger-500 rounded-full inline-block ring-4 ring-opacity-30 ring-danger-500"
                                                                    style="vertical-align: middle;"></span>
                                                            @endif
                                                            &nbsp;
                                                            {{ $task->due ? \Carbon\Carbon::parse($task->due)->format('D d/M H:i') : 'N/A' }}
                    
                                                            @php
                                                                $currentDate = now();
                                                                $startDate = $task->created_at;
                                                                $dueDate = $task->due;
                                                                $totalDuration = $startDate->diffInSeconds($dueDate);
                                                                $elapsedDuration = $startDate->diffInSeconds($currentDate);
                                                                $percentagePassed = ($elapsedDuration / $totalDuration) * 100;
                                                            @endphp
                    
                                                            <div class="w-full bg-slate-200 h-2 m-1 rounded-xl overflow-hidden">
                                                                <div class="@if ($percentagePassed >= 0 && $percentagePassed < 30) bg-success-500 @elseif($percentagePassed >= 30 && $percentagePassed < 70) bg-warning-500 @else bg-danger-500 @endif h-full rounded-xl"
                                                                    style="width: {{ number_format($percentagePassed, 0) }}%"></div>
                                                            </div>
                                                        </td>
                    
                                                        <td class="table-td">
                                                            @if ($task->assigned_to_id)
                                                                <b>{{ $task->assigned_to?->first_name }}
                                                                    {{ $task->assigned_to?->last_name }}</b>
                                                            @elseif($task->assigned_to_type)
                                                                <b>{{ $task->assigned_to_type }}</b>
                                                            @else
                                                                <b> - </b>
                                                            @endif
                                                        </td>
                    
                                                        <td class="table-td" >
                                                            {{ $task->title }}
                                                        </td>
                    
                                                        <td class="table-td ">
                                                            {{ $task->taskable_type }}
                                                            @if ($task->file_url)
                                                                <iconify-icon icon="bx:file"></iconify-icon>
                                                            @endif
                                                        </td>
                    
                                                        <td class="table-td">
                                                            @if ($task->status === 'new')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-primary-500 bg-primary-500 text-xs">
                                                                    New
                                                                </div>
                                                            @elseif($task->status === 'assigned')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-info-500 bg-info-500 text-xs">
                                                                    Assigned
                                                                </div>
                                                            @elseif($task->status === 'in_progress')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-secondary-500 bg-secondary-500 text-xs">
                                                                    in Progress
                                                                </div>
                                                            @elseif($task->status === 'pending')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-warning-500 bg-warning-500 text-xs">
                                                                    Pending
                                                                </div>
                                                            @elseif($task->status === 'completed')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500 text-xs">
                                                                    Completed
                                                                </div>
                                                            @elseif($task->status === 'closed')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-black-500 bg-black-500 text-xs">
                                                                    Closed
                                                                </div>
                                                            @endif
                    
                                                        </td>
                    
                                                        <td class="table-td">
                                                            {{ $task->open_by?->first_name }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                    
                                            </tbody>
                                        </table>
                    
                                        @if ($tasks->isEmpty())
                                            {{-- START: empty filter result --}}
                                            <div class="card p-5">
                                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                                    <div class="items-center text-center p-5">
                                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Tasks with the
                                                            applied
                                                            filters</h2>
                                                        <p class="card-text">Try changing the filters or search terms for this view.
                                                        </p>
                                                        <a href="{{ url('/tasks') }}"
                                                            class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                                            all Tasks</a>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- END: empty filter result --}}
                                        @endif
                    
                                    </div>
                    
                    
                    
                                    {{ $tasks->links('vendor.livewire.bootstrap') }}
                    
                                </div>
                            </div>
                        </div>
                        

                    </div>
                </div>
            </div>
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
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
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

    @if ($dueSection)
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
                                Due date
                            </h3>
                            <button wire:click="toggleDueDate" type="button"
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
                                <label for="Edue_from" class="form-label">Due from</label>
                                <input name="Edue_from" type="date"
                                    class="form-control mt-2 w-full @error('Edue_from') !border-danger-500 @enderror"
                                    wire:model.defer="Edue_from">
                                @error('Edue_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Edue_to" class="form-label">Due to</label>
                                <input name="Edue_to" type="date"
                                    class="form-control mt-2 w-full @error('Edue_to') !border-danger-500 @enderror"
                                    wire:model.defer="Edue_to">
                                @error('Edue_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setDueDates" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setDueDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setDueDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
