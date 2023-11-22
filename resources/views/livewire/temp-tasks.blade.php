<div>
    <div class="card">
        <header class=" card-header noborder">
            <h4 class="card-title">
                Temp Assigned Tasks
            </h4>
        </header>
        <div class="card-body px-6 pb-6">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Task Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Temp Assignee
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Note
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Temp Due Date
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Action
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($taskTempAssignee as $taskTemp)
                                    <tr>
                                        <td class="table-td ">{{ $taskTemp->task->title }}</td>

                                        <td class="table-td">
                                            {{ $taskTemp->user->first_name . ' ' . $taskTemp->user->last_name }}</td>

                                        <td class="table-td ">
                                            @if ($taskTemp->status === 'new')
                                                <button wire:click="accept({{ $taskTemp->id }})"
                                                    class="btn inline-flex justify-center btn-success rounded-[50px] btn-sm">Accept</button>
                                                <button wire:click="reject({{ $taskTemp->id }})"
                                                    class="btn inline-flex justify-center btn-danger rounded-[50px] btn-sm">Reject</button>
                                            @else
                                                {{ $taskTemp->status }}
                                            @endif

                                        </td>

                                        <td class="table-td ">{{ $taskTemp->note }}</td>

                                        <td class="table-td">{{ $taskTemp->end_date }}</td>

                                        <td class="table-td"><button wire:ignore class="toolTip onTop action-btn m-1"
                                                data-tippy-content="Delete" type="button"
                                                wire:click="delete({{ $taskTemp->id }})">
                                                <iconify-icon icon="heroicons:trash"></iconify-icon>
                                            </button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($taskTempAssignee->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No temp tasks
                                            assigned with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/temp-tasks') }}"
                                            class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all Temp Tasks</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif
                    </div>
                    {{ $taskTempAssignee->links('vendor.livewire.bootstrap') }}
                </div>
            </div>
        </div>
    </div>
</div>
