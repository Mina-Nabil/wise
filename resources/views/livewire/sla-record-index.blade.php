<div>
    <div class="card">
        <header class=" card-header noborder">
            <h4 class="card-title">
                SLA Records
            </h4>
            {{-- <input type="text" class="form-control w-auto d-inline-block cursor-pointer" style="width:auto" name="datetimes" id="reportrange" /> --}}
        </header>
        <div class="card-body px-6 pb-6">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class=" table-th ">
                                        Created By
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Type
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Assigned to
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        due
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Reply Action
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Reply Date
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Reply By
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($records as $record)
                                    <tr wire:click="showRecordsInfo({{ $record->id }})" class="table-td hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">

                                        <td class="table-td ">{{ $record->creator?->username }}</td>

                                        <td class="table-td ">{{ $record->action_item->first_name }}</td>

                                        <td class="table-td ">{{ $record->action_title }}</td>

                                        <td class="table-td ">{{ $record->assigned_to?->username }}</td>

                                        <td class="table-td ">{{ $record->due }}</td>

                                        <td class="table-td ">{{ $record->reply_action }}</td>

                                        <td class="table-td ">{{ $record->reply_date }}</td>

                                        <td class="table-td ">@if($record->is_ignore)<span class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">Ignored</span>@endif</td>

                                        <td class="table-td ">{{ $record->replier?->username }}</td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($records->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Records with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/slarecords') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all Logs</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif
                    </div>
                    {{ $records->links('vendor.livewire.bootstrap') }}
                </div>
            </div>
        </div>
    </div>
    @if ($recordInfo)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Record Info
                            </h3>

                            <button wire:click="closeRecordInfo" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6">

                            <div class="text-sm"><b>Created by</b></div>
                            <div class="text-sm text-slate-600 dark:text-slate-300 mb-3">{{ $recordInfo->creator?->username }}</div>

                            <div class="text-sm"><b>Assigned to</b></div>
                            <div class="text-sm text-slate-600 dark:text-slate-300 mb-3">{{ $recordInfo->assigned_to?->username }} {{ $recordInfo->assigned_to_team ?? '' }}</div>

                            <div class="text-sm"><b>Action Title</b></div>
                            <div class="text-sm text-slate-600 dark:text-slate-300 mb-3">{{ $recordInfo->action_title }}</div>

                            <div class="text-sm"><b>Due </b></div>
                            <div class="text-sm text-slate-600 dark:text-slate-300 mb-3">{{ $recordInfo->due }}</div>

                            <div class="text-sm"><b>Replied by </b></div>
                            <div class="text-sm text-slate-600 dark:text-slate-300 mb-3">{{ $recordInfo->replier?->username }}</div>

                            <div class="text-sm"><b>Reply action </b></div>
                            <div class="text-sm text-slate-600 dark:text-slate-300 mb-3">{{ $recordInfo->reply_action }}</div>

                            <div class="text-sm"><b>Reply date </b></div>
                            <div class="text-sm text-slate-600 dark:text-slate-300 mb-3">{{ $recordInfo->reply_date }}</div>

                            @if ($recordInfo->is_ignore)
                                <span class="badge bg-danger-500 text-white capitalize inline-flex items-center">
                                    Ignored
                                </span>
                            @else
                                <button wire:click="setIgnored({{ $recordInfo->id }})" class="btn inline-flex justify-center btn-danger light btn-sm float-right mb-3">set as ignored</button>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
