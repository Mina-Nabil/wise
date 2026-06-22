<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                <b>Reports:</b> Corporate Duplicates -- Groups: {{ $groups->total() }}
            </h4>
        </div>
    </div>

    @if (request('merged'))
        <div class="bg-success-500 bg-opacity-20 text-success-500 border border-success-500 rounded-md p-3 mb-4 flex items-center">
            <iconify-icon icon="ic:round-check-circle" class="text-xl mr-2"></iconify-icon>
            Profiles merged successfully.
        </div>
    @endif

    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="table-th">Commercial Record</th>
                                    <th scope="col" class="table-th">Corporate</th>
                                    <th scope="col" class="table-th">Owner</th>
                                    <th scope="col" class="table-th">Offers</th>
                                    <th scope="col" class="table-th">Policies</th>
                                    <th scope="col" class="table-th">Created</th>
                                    <th scope="col" class="table-th">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($details as $group)
                                    @foreach ($group['corporates'] as $i => $corporate)
                                        <tr class="border-t border-slate-200 dark:border-slate-700">
                                            @if ($i === 0)
                                                <td class="table-td align-top bg-slate-50 dark:bg-slate-900"
                                                    rowspan="{{ $group['corporates']->count() }}">
                                                    <div class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                                                        {{ $group['commercial_record'] }}
                                                    </div>
                                                    <span class="badge bg-warning-500 bg-opacity-30 text-warning-500 mt-2">
                                                        {{ $group['cnt'] }} duplicates
                                                    </span>
                                                </td>
                                            @endif
                                            <td class="table-td">
                                                <a href="{{ route('corporates.show', $corporate->id) }}" target="_blank"
                                                    class="text-primary-500 font-medium hover:underline">
                                                    {{ $corporate->full_name }}
                                                </a>
                                                <div class="text-xs text-slate-400">#{{ $corporate->id }}</div>
                                            </td>
                                            <td class="table-td">{{ $corporate->owner?->username ?? '-' }}</td>
                                            <td class="table-td">{{ $corporate->offers_count }}</td>
                                            <td class="table-td">{{ $corporate->soldpolicies_count }}</td>
                                            <td class="table-td">{{ $corporate->created_at?->format('Y-m-d') }}</td>
                                            @if ($i === 0)
                                                <td class="table-td align-top bg-slate-50 dark:bg-slate-900"
                                                    rowspan="{{ $group['corporates']->count() }}">
                                                    <a href="{{ route('reports.corporate-merge', ['ids' => $group['corporates']->pluck('id')->implode(',')]) }}"
                                                        class="btn inline-flex justify-center btn-dark btn-sm">
                                                        <iconify-icon icon="mdi:merge" class="mr-1"></iconify-icon> Merge
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>

                        @if ($groups->isEmpty())
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">
                                            No duplicate corporates found
                                        </h2>
                                        <p class="card-text">No two corporates share the same commercial record.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $groups->links('vendor.livewire.bootstrap') }}
            </div>
        </div>
    </div>
</div>
