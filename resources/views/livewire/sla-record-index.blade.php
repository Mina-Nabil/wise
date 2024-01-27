<div>
    <div class="card">
        <header class=" card-header noborder">
            <h4 class="card-title">
                SLA Records
            </h4>
            <input type="text" class="form-control w-auto d-inline-block cursor-pointer" style="width:auto" name="datetimes" id="reportrange" />
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
                                    <tr wire:click="showLogInfo({{ $record->id }})" class="table-td hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">

                                        <td class="table-td ">{{ $record->created_by?->username }}</td>

                                        <td class="table-td ">{{ $record->action_item }}</td>

                                        <td class="table-td ">{{ $record->action_title }}</td>

                                        <td class="table-td ">{{ $record->assigned_to?->username }}</td>

                                        <td class="table-td ">{{ $record->due }}</td>

                                        <td class="table-td ">{{ $record->reply_action }}</td>

                                        <td class="table-td ">{{ $record->reply_date }}</td>

                                        <td class="table-td ">{{ $record->is_ignore ? '<span class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">Ignored</span>' : '' }}</td>

                                        <td class="table-td ">{{ $record->reply_by?->username }}</td>

                                        
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
</div>
