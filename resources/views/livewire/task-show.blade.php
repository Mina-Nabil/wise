<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div class="w-full sm:w-1/2" style="max-width: 600px">
            {{-- <div class="flex justify-between flex-wrap items-center mb-3">
                <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                    <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">

                        {{ $taskTitle }}

                    </h4>
                </div>

                <div>
                    @if ($changes)
                        <button type="submit" wire:click="save" class="btn inline-flex justify-center btn-success rounded-[25px] btn-sm mr-2">Save</button>
                    @endif
                    @can('delete', $task)
                        <button type="submit" wire:click="delete" class="btn inline-flex justify-center btn-danger rounded-[25px] btn-sm">Delete</button>
                    @endcan

                </div>


            </div> --}}
            <div class="card mb-5">
                <div class="card-body">
                    <div class="card-text h-full">
                        <div class="px-4 pt-4 pb-3">
                            <div class="from-group mb-3">
                                @if ($changeTitleDesc)
                                    <div class="input-area flex">
                                        <label for="firstName" class="form-label">Title</label>
                                        <button wire:click="saveTitleAndDesc" class="btn inline-flex justify-center btn-success btn-sm mb-2 float-right">
                                            <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="saveTitleAndDesc" icon="line-md:loading-twotone-loop"></iconify-icon>
                                            <span>Save</span>
                                        </button>


                                    </div>
                                    <input type="text" class="form-control" value="Bill" placeholder="Title" wire:model="taskTitle">
                                    <div class="input-area mb-3">
                                        <label for="name" class="form-label">Description</label>
                                        <textarea class="form-control" placeholder="Write Description" wire:model="desc" style="min-height: 150px"></textarea>
                                    </div>
                                @else
                                    <h6 class="mb-3">
                                        {{ $task->title }}
                                        <span class="float-right cursor-pointer" wire:click="toggleEditTitleDesc">
                                            <iconify-icon icon="carbon:edit"></iconify-icon>
                                        </span>
                                    </h6>
                                    <p class="text-sm mb-3">{{ $task->desc }}</p>
                                @endif
                            </div>


                            @if ($task->status === 'new')
                                <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-primary-500 bg-primary-500 text-xs">
                                    New
                                    <span class="float-right cursor-pointer ml-3" wire:click="toggleEditStatus">
                                        <iconify-icon icon="carbon:edit"></iconify-icon>
                                    </span>
                                </div>
                            @elseif($task->status === 'assigned')
                                <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-info-500 bg-info-500 text-xs">
                                    Assigned
                                    <span class="float-right cursor-pointer ml-3" wire:click="toggleEditStatus">
                                        <iconify-icon icon="carbon:edit"></iconify-icon>
                                    </span>
                                </div>
                            @elseif($task->status === 'in_progress')
                                <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-secondary-500 bg-secondary-500 text-xs">
                                    in Progress
                                    <span class="float-right cursor-pointer ml-3" wire:click="toggleEditStatus">
                                        <iconify-icon icon="carbon:edit"></iconify-icon>
                                    </span>
                                </div>
                            @elseif($task->status === 'pending')
                                <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-warning-500 bg-warning-500 text-xs">
                                    Pending
                                    <span class="float-right cursor-pointer ml-3" wire:click="toggleEditStatus">
                                        <iconify-icon icon="carbon:edit"></iconify-icon>
                                    </span>
                                </div>
                            @elseif($task->status === 'completed')
                                <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500 text-xs">
                                    Completed
                                    <span class="float-right cursor-pointer ml-3" wire:click="toggleEditStatus">
                                        <iconify-icon icon="carbon:edit"></iconify-icon>
                                    </span>
                                </div>
                            @elseif($task->status === 'closed')
                                <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-black-500 bg-black-500 text-xs">
                                    Closed
                                    <span class="float-right cursor-pointer ml-3" wire:click="toggleEditStatus">
                                        <iconify-icon icon="carbon:edit"></iconify-icon>
                                    </span>
                                </div>
                            @endif

                            @if ($changeDue)
                                <div class="from-group mt-3">
                                    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-6">
                                        <div class="input-area">
                                            <label for="firstName" class="form-label">Due Date</label>
                                            <input type="date" class="form-control" wire:model="dueDate">
                                        </div>

                                        {{-- <input type="checkbox" wire:model="noDueTime"> --}}
                                        @if ($haveDueTime)
                                            <div class="input-area">
                                                <label for="lastName" class="form-label">Time</label>
                                                <input type="time" class="form-control" wire:model="dueTime">
                                            </div>
                                        @endif
                                        <div class="checkbox-area">
                                            <label for="checkbox" class="form-label">Add time</label>
                                            <input type="checkbox" name="checkbox" wire:model="haveDueTime">
                                        </div>

                                        <div class="flex justify-between items-end space-x-6">
                                            <div class="input-area">
                                                <button wire:click="saveDue" class="inline-flex items-center justify-center h-10 w-10 bg-success-500 text-lg border rounded border-success-500
                                                          text-white rb-zeplin-focused">
                                                    <iconify-icon class="text-xl spin-slow rtl:ml-2 relative top-[1px]" wire:loading wire:target="saveDue" icon="line-md:loading-twotone-loop"></iconify-icon>
                                                    <iconify-icon icon="material-symbols:save-outline" wire:loading.remove wire:target="saveDue"></iconify-icon>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="badge bg-primary-500 text-white capitalize float-right">
                                    Due: {{ $task->due }}
                                    <span class="float-right cursor-pointer ml-3" wire:click="toggleDue">
                                        <iconify-icon icon="carbon:edit"></iconify-icon>
                                    </span>
                                </span>
                            @endif


                            @if ($changeStatus)
                                <div class="mt-2">
                                    <div class="basicRadio">
                                        <label class="flex items-center cursor-pointer">
                                            <input wire:model="editedStatus" type="radio" class="hidden" name="basicradios" value="assigned" @if ($task->status === 'assigned') checked="checked" @endif>
                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                                    duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                            <span class="text-secondary-500 text-sm leading-6 capitalize">Assigned</span>
                                        </label>
                                    </div>
                                    <div class="basicRadio">
                                        <label class="flex items-center cursor-pointer">
                                            <input wire:model="editedStatus" type="radio" class="hidden" name="basicradios" value="in_progress"@if ($task->status === 'in_progress') checked="checked" @endif>
                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                                    duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                            <span class="text-secondary-500 text-sm leading-6 capitalize">in Progress</span>
                                        </label>
                                    </div>
                                    <div class="basicRadio">
                                        <label class="flex items-center cursor-pointer">
                                            <input wire:model="editedStatus" type="radio" class="hidden" name="basicradios" value="pending" @if ($task->status === 'pending') checked="checked" @endif>
                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                                    duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                            <span class="text-secondary-500 text-sm leading-6 capitalize">Pending</span>
                                        </label>
                                    </div>
                                    <div class="basicRadio">
                                        <label class="flex items-center cursor-pointer">
                                            <input wire:model="editedStatus" type="radio" class="hidden" name="basicradios" value="completed" @if ($task->status === 'completed') checked="checked" @endif>
                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                                    duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                            <span class="text-secondary-500 text-sm leading-6 capitalize">Completed</span>
                                        </label>
                                    </div>
                                    <div class="basicRadio">
                                        <label class="flex items-center cursor-pointer">
                                            <input wire:model="editedStatus" type="radio" class="hidden" name="basicradios" value="closed" @if ($task->status === 'closed') checked="checked" @endif>
                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                                    duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                            <span class="text-secondary-500 text-sm leading-6 capitalize">Closed</span>
                                        </label>
                                    </div>
                                    <input type="text" wire:model="statusComment" placeholder="Leave a note..." class="form-control w-full">

                                    <button wire:click="saveStatuses" class="btn inline-flex justify-center btn-success btn-sm mt-2">
                                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="saveStatuses" icon="line-md:loading-twotone-loop"></iconify-icon>
                                        <span>Save Status</span>
                                    </button>

                                    {{-- <div class="mb-5">
                                        <button wire:click="saveStatuses" class="btn btn-success mt-3 float-right btn-sm">
                                            <div class="flex items-center">
                                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"  icon="line-md:loading-twotone-loop"></iconify-icon>
                                                <span>Save</span>
                                            </div>
                                        </button>
                                    </div> --}}

                                </div>
                            @endif


                            {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Title</label>
                                        <input type="text" class="form-control" value="Bill" placeholder="Title" wire:model="taskTitle">
                                    </div>
                                    <div class="input-area">
                                        <div wire:ignore>
                                            <label for="basicSelect" class="form-label">Assigned to</label>

                                            <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="assignedTo">
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}" {{ $assignedTo == $user->id ? 'selected' : '' }}>
                                                        {{ $user->first_name }} {{ $user->last_name }} <span class="text-sm">( {{ $user->type }} )</span>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}

                            {{-- <div class="input-area mb-3">
                                <label for="name" class="form-label">Description</label>
                                <textarea class="form-control" placeholder="Write Description" wire:model="desc"></textarea>
                            </div> --}}

                            {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="input-area mb-3">
                                    <label for="time-date-picker" class="form-label">Due Date</label>
                                    <input class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('dueDate') !border-danger-500 @enderror" id="default-picker" value="" type="text" wire:model.defer="dueDate" autocomplete="off">
                                    @error('dueDate')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-area mb-3">
                                    <label for="time-date-picker" class="form-label">Time </label>
                                    <input class="form-control cursor-pointer py-2 flatpickr time flatpickr-input active @error('dueTime') !border-danger-500 @enderror" id="time-picker" data-enable-time="true" value="" type="text" wire:model.defer="dueTime" autocomplete="off">
                                    @error('dueTime')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="from-group mb-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                </div>
                            </div>

                            <div class="input-area mb-3">
                                <label for="name" class="form-label">Status</label>
                                <select name="taskStatus" class="form-control w-full mt-2" wire:model="taskStatus">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                    @endforeach
                                </select>

                            </div> --}}

                        </div>

                    </div>
                </div>

            </div>




            <div class="card">
                <div class="card-body flex flex-col p-6">
                    <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                        <div class="flex-1">
                            <div class="card-title text-slate-900 dark:text-white">Files</div>
                        </div>
                    </header>
                    <iframe src='https://wiseins.s3.eu-north-1.amazonaws.com/tasks/GGxyo5OihDGEJnn6dW51XyQ2x9544vNDGBqCMMVj.pdf' height='400px' frameborder='0'></iframe>
                </div>
            </div>




        </div>
        <div class="w-full sm:w-1/2" style="max-width: 600px">

            <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base mb-5">
                <div class="card-body flex flex-col p-6 active">
                    <header class="flex mb-5 items-center">
                        <div class="flex-1">
                            <div class="card-subtitle font-Inter">
                                <iconify-icon icon="material-symbols:task"></iconify-icon>
                                Assignied to
                                @if ($changeAsignee)
                                    <button wire:click="saveAsignee" class="btn inline-flex justify-center btn-success btn-sm mb-2 float-right">
                                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="saveAsignee" icon="line-md:loading-twotone-loop"></iconify-icon>
                                        <span>Save</span>
                                    </button>
                                @else
                                    <span class="float-right cursor-pointer" wire:click="toggleEditAsignee">
                                        <iconify-icon icon="carbon:edit"></iconify-icon>
                                    </span>
                                @endif
                            </div>
                            @if ($changeAsignee)
                                <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2 @error('assignedTo') !border-danger-500 @enderror" wire:model.defer="assignedTo">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $assignedTo == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name }} {{ $user->last_name }} <span class="text-sm">( {{ $user->type }} )</span>
                                        </option>
                                    @endforeach
                                </select>
                                @error('assignedTo')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <input type="text" wire:model="assignedToComment" placeholder="Leave a note..." class="form-control w-full mt-2">
                            @else
                                <h6>{{ $task->assigned_to->first_name . ' ' . $task->assigned_to->last_name }}</h6>
                            @endif
                        </div>
                    </header>
                    <div class="card-text h-full menu-open mb-5">
                        <div class="card-subtitle font-Inter mb-1">
                            <iconify-icon icon="carbon:view-filled"></iconify-icon> Watchers
                            <span class="float-right cursor-pointer" wire:click="OpenChangeWatchers">
                                <iconify-icon icon="carbon:edit"></iconify-icon>
                            </span>
                        </div>
                        <div {{ $changeWatchers ? '' : "style=display:none;'" }}>
                            <div class="w-full">
                                <select wire:model.defer="setWatchersList" id="multiSelect" multiple aria-label="multiple select example" class="select2 form-control w-full mt-2 py-2" multiple="multiple" style="height: 250px">
                                    @foreach ($users as $user)
                                        <option {{ in_array($user->id, $watchersList->pluck('user_id')->all()) ? 'selected="selected"' : '' }} value="{{ $user->id }}" class="">
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button wire:click="saveWatchers" class="btn inline-flex justify-center btn-success mt-3 float-right btn-sm">
                                <div class="flex items-center">
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="saveWatchers" icon="line-md:loading-twotone-loop"></iconify-icon>
                                    <span>Save Watchers</span>
                                </div>
                            </button>

                            {{-- <button class="toolTip onTop action-btn m-1 h-full" data-tippy-content="saveWatchers" type="button" wire:click="saveWatchers">
                                <iconify-icon icon="material-symbols:save"></iconify-icon>
                            </button> --}}
                        </div>

                        <div {{ $changeWatchers ? "style=display:none;'" : '' }}>
                            @foreach ($watchersList as $watcher)
                                <span class="badge bg-slate-200 text-slate-900 capitalize rounded-3xl mb-1 me-1">{{ $watcher->user->first_name }} {{ $watcher->user->last_name }}</span>
                            @endforeach
                        </div>

                    </div>
                    <div class="card-text h-full menu-open">
                        <div class="card-subtitle font-Inter mb-1">
                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="material-symbols:timer-outline"></iconify-icon>
                            Temperorary Assigned:
                            <span class="float-right">
                                Till 15/11/2023 08:00PM
                            </span>
                        </div>
                        <span class="badge bg-slate-200 text-slate-900 capitalize rounded-3xl mb-1 me-1">Mina Nabil</span>
                    </div>
                </div>
            </div>


            <div>
                Timeline
            </div>
            <div class="card mb-5" style="margin-bottom:50px">
                <div class="card-body">
                    <div class="card-text h-full">
                        <div class="mt-5">
                            <div class="text-slate-600 dark:text-slate-300 block w-full px-4 py-3 text-sm mb-2 last:mb-0">
                                <div class="flex ltr:text-left rtl:text-right">
                                    <div class="flex-none ltr:mr-3 rtl:ml-3">
                                        <div class="h-8 w-8 rounded-full relative text-white bg-blue-500">

                                            <span class="block w-full h-full object-cover text-center text-lg leading-8">
                                                {{ strtoupper(substr('michael', 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" class="form-control border-0" placeholder="Leave a comment..." wire:model="newComment" wire:keydown.enter="addComment" style="border: none; box-shadow: 0 0 0px rgba(0, 0, 0, 0.5);">
                                    </div>
                                    <div class="">
                                        <button class="btn inline-flex justify-center btn-primary btn-sm" wire:click="addComment">
                                            <span class="flex items-center">
                                                <span>Post</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($comments as $comment)
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="card-text h-full">
                            <div class="mt-5">
                                <div class="text-slate-600 dark:text-slate-300 block w-full px-4 py-3 text-sm mb-2 last:mb-0">
                                    <div class="flex ltr:text-left rtl:text-right">
                                        <div class="flex-none ltr:mr-3 rtl:ml-3">
                                            <div class="h-8 w-8 rounded-full relative text-white bg-blue-500">

                                                <span class="block w-full h-full object-cover text-center text-lg leading-8">
                                                    {{ strtoupper(substr($comment->user?->username ?? 'System', 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-slate-800 dark:text-slate-300 text-sm font-medium mb-1`">
                                                {{ $comment->user?->username ?? 'System' }}
                                            </div>
                                            <div class="text-xs hover:text-[#68768A] font-normal text-slate-600 dark:text-slate-300">
                                                {{ $comment->comment }}
                                            </div>
                                        </div>
                                        <div class="">
                                            <span class="flex items-center justify-center text-sm">
                                                {{ $comment->created_at->format('Y-m-d H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach







        </div>
    </div>
</div>
