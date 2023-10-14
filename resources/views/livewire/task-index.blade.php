<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Tasks
            </h4>
            <!---->
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">

            <button data-bs-toggle="modal" data-bs-target="#successModal"
                class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Task
            </button>

        </div>
    </div>

    <div class="flex mb-">
        <div class="dropdown relative">
            <button class="btn inline-flex justify-center btn-dark items-center" type="button"
                id="darkDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                @if ($filteredStatus)
                    Status: {{ $filteredStatus[0] }}
                @else
                    Select Status
                @endif

                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
            </button>
            <ul
                class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                <li wire:click="resetStatusFilter">
                    <a href="#"
                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                        All
                    </a>
                </li>
                @foreach ($statuses as $status)
                    <li wire:click="filterByStatus('{{ $status }}')">
                        <a href="#"
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </a>
                    </li>
                @endforeach
            </ul>



        </div>
        <input class="form-control py-2 flatpickr flatpickr-input active w-auto" style="width:auto" id="range-picker"
            data-mode="range" value="" type="text" readonly="readonly" wire:model="dateRange">

        <div class="flex items-center mr-2 sm:mr-4 mt-2 space-x-2 justify-end">
            <label
                class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                <input type="checkbox" value="" checked class="sr-only peer" wire:model="myTasks">
                <div
                    class="w-14 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:z-10 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500">
                </div>
                <span
                    class="absolute left-1 z-20 text-xs text-white font-Inter font-normal opacity-0 peer-checked:opacity-100">On</span>
                <span
                    class="absolute right-1 z-20 text-xs text-white font-Inter font-normal opacity-100 peer-checked:opacity-0">Off</span>
            </label>
            <span class="text-sm text-primary-600 font-Inter font-normal capitalize">My Tasks</span>
        </div>
    </div>

    <div class="card-body px-6 pb-6">
        <div class=" -mx-6">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden ">
                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                        <thead class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                            <tr>



                                <th scope="col" class="table-th">
                                    Date
                                </th>

                                <th scope="col" class="table-th">
                                    Assigned to
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
                                    Due
                                </th>

                                <th scope="col" class=" table-th ">
                                    Opened By
                                </th>

                            </tr>
                        </thead>
                        <tbody
                            class="bg-white divide-y cursor-pointer divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                            @foreach ($tasks as $task)
                                <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">

                                    <td class="table-td">
                                        {{ $task->created_at->format('D d/m/Y') }}
                                    </td>

                                    <td class="table-td">
                                        <b>{{ $task->assigned_to->first_name }}
                                            {{ $task->assigned_to->last_name }}</b>
                                    </td>

                                    <td class="table-td scale" data-tippy-content="{{ $task->desc }}">
                                        <a href="{{ route('tasks.show', ['id' => $task->id]) }}">
                                            {{ $task->title }}
                                        </a>
                                    </td>

                                    <td class="table-td ">
                                        {{ $task->taskable_type }}
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

                                    <td class="table-td" style="vertical-align: middle;">
                                        @if (\Carbon\Carbon::parse($task->due)->isPast())
                                            <span
                                                class="h-[6px] w-[6px] bg-danger-500 rounded-full inline-block ring-4 ring-opacity-30 ring-danger-500"
                                                style="vertical-align: middle;"></span>
                                        @endif
                                        &nbsp;
                                        {{ \Carbon\Carbon::parse($task->due)->diffForHumans() }}
                                    </td>

                                    <td class="table-td">
                                        {{ $task->open_by->first_name }}
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
