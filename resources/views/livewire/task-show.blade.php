<div>
    <div class="flex justify-center">
        <div class="w-full sm:w-1/2" style="max-width: 600px">
            <div class="flex justify-between flex-wrap items-center mb-3">
                <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
                    <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                        {{ $taskTitle }}

                    </h4>

                    <!---->

                </div>
                @if ($changes)
                    <button type="submit" wire:click="save"
                        class="btn inline-flex justify-center btn-success rounded-[25px] btn-sm">Save</button>
                @endif
                @can('delete', $task)
                    <button type="submit" wire:click="delete"
                        class="btn inline-flex justify-center btn-danger rounded-[25px] btn-sm">Delete</button>
                @endcan
            </div>
            @if (session()->has('success'))
                <div
                    class="py-[18px] px-6 font-normal text-sm rounded-md bg-success-500 text-white animate-\[fade-out_350ms_ease-in-out\] alert mb-2">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <p class="flex-1 font-Inter">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            @elseif (session()->has('failed'))
                <div class="py-[18px] px-6 font-normal text-sm rounded-md bg-danger-500 text-white mb-2">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <p class="flex-1 font-Inter">
                            {{ session('failed') }}
                        </p>
                    </div>
                </div>
            @endif
            <div class="card mb-5">
                <div class="card-body">
                    <div class="card-text h-full">
                        <div class="px-4 pt-4 pb-3">
                            <div class="from-group mb-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Title</label>
                                        <input type="text" class="form-control" value="Bill" placeholder="Title"
                                            wire:model="taskTitle">
                                    </div>
                                    <div class="input-area">



                                        <div wire:ignore>
                                            <label for="basicSelect" class="form-label">Assigned to</label>

                                            <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2"
                                                wire:model="assignedTo">
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ $assignedTo == $user->id ? 'selected' : '' }}>
                                                        {{ $user->first_name }} {{ $user->last_name }} <span
                                                            class="text-sm">( {{ $user->type }} )</span>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>



                                    </div>
                                </div>
                            </div>
                            <div class="input-area mb-3">
                                <label for="name" class="form-label">Description</label>
                                <textarea class="form-control" placeholder="Write Description" wire:model="desc"></textarea>
                            </div>

                            <div class="from-group mb-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                    {{-- <div class="input-area">
                                        <div wire:ignore>
                                            <label for="basicSelect" class="form-label">Task Type</label>

                                            <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="taskableType">
                                                <option value="" {{ $taskableType === null ? 'selected' : '' }}>None</option>
                                                <option value="Car" {{ $taskableType === 'Car' ? 'selected' : '' }}>Car</option>
                                                <option value="Policy" {{ $taskableType === 'Policy' ? 'selected' : '' }}>Policy</option>
                                            </select>

                                        </div>
                                    </div> --}}
                                    {{-- <div class="input-area">
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
                                    </div> --}}
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

                            </div>

                            <div class="input-area mb-3">
                                <label for="time-date-picker" class="form-label">Due</label>
                                <input class="form-control py-2 flatpickr flatpickr-input active" id="time-date-picker"
                                    data-enable-time="true" value="" type="text" wire:model="due">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-5">
                <div class="card-body">
                    <div class="card-text h-full">
                        <div class="mt-5">
                            <div
                                class="text-slate-600 dark:text-slate-300 block w-full px-4 py-3 text-sm mb-2 last:mb-0">
                                <div class="flex ltr:text-left rtl:text-right">
                                    <div class="flex-none ltr:mr-3 rtl:ml-3">
                                        <div class="h-8 w-8 rounded-full relative text-white bg-blue-500">

                                            <span
                                                class="block w-full h-full object-cover text-center text-lg leading-8">
                                                {{ strtoupper(substr('michael', 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" class="form-control border-0"
                                            placeholder="Leave a comment..." wire:model="newComment"
                                            wire:keydown.enter="addComment"
                                            style="border: none; box-shadow: 0 0 0px rgba(0, 0, 0, 0.5);">
                                    </div>
                                    <div class="">
                                        <button class="btn inline-flex justify-center btn-primary btn-sm"
                                            wire:click="addComment">
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
                                <div
                                    class="text-slate-600 dark:text-slate-300 block w-full px-4 py-3 text-sm mb-2 last:mb-0">
                                    <div class="flex ltr:text-left rtl:text-right">
                                        <div class="flex-none ltr:mr-3 rtl:ml-3">
                                            <div class="h-8 w-8 rounded-full relative text-white bg-blue-500">

                                                <span
                                                    class="block w-full h-full object-cover text-center text-lg leading-8">
                                                    {{ strtoupper(substr($comment->user?->username ?? 'System', 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-slate-800 dark:text-slate-300 text-sm font-medium mb-1`">
                                                {{ $comment->user?->username ?? 'System' }}
                                            </div>
                                            <div
                                                class="text-xs hover:text-[#68768A] font-normal text-slate-600 dark:text-slate-300">
                                                {{ $comment->comment }}
                                            </div>
                                        </div>
                                        <div class="">
                                            <span class="flex items-center justify-center text-sm">
                                                {{ $comment->created_at->diffForHumans() }}
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
