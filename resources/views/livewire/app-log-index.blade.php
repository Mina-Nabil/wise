<div>
    <div class="card">
        <header class=" card-header noborder">
            <h4 class="card-title">
                Activity Log
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
                                        Level
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Ttile
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Description
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Time
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($logs as $log)
                                    <tr>
                                        <td class="table-td">
                                            @if ($log->level === 'info')
                                                <span class="badge bg-info-500 text-info-500 bg-opacity-30 capitalize">{{ $log->level }}</span>
                                            @elseif($log->level === 'error')
                                                <span class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">{{ $log->level }}</span>
                                            @elseif($log->level === 'warning')
                                                <span class="badge bg-warning-500 text-warning-500 bg-opacity-30 capitalize">{{ $log->level }}</span>
                                            @else
                                                <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">{{ $log->level }}</span>
                                            @endif
                                        </td>

                                        <td class="table-td ">{{ $log->title }}</td>

                                        <td class="table-td @if ($log->desc != '') !toolTip onTop @endif" data-tippy-content="{{ $log->desc }}" data-tippy-theme="dark">
                                            {{ strlen($log->desc) > 50 ? substr($log->desc, 0, 50) . '...' : $log->desc }}
                                        </td>

                                        <td class="table-td ">{{ $log->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($logs->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Logs with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/logs') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all Logs</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif
                    </div>
                    {{ $logs->links('vendor.livewire.bootstrap') }}
                </div>
            </div>
        </div>
    </div>
</div>
