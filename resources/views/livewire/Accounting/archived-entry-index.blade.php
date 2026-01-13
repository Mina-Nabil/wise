<div>
    <div class="flex justify-between flex-wrap items-center mb-5">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Archived Journal Entries
            </h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700 no-wrap">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        View
                                    </th>

                                    <th scope="col" class=" table-th !p-1">
                                        #
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Created At
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Amnt
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Blnc
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        $ Balance
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Curr.
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Curr. Amount
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Curr. Rate
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Archived At
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Archived By
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 no-wrap">

                                @forelse ($entries as $entry)
                                    <tr>

                                        <td class="table-td flex justify-between">
                                            <div>
                                                @if ($entry->accounts->isNotEmpty())
                                                    @if (in_array($entry->id, $showChildAccounts))
                                                        <button class="action-btn mr-2" type="button" wire:click="hideThisChildAccount({{ $entry->id }})">
                                                            <iconify-icon icon="mingcute:up-fill"></iconify-icon>
                                                        </button>
                                                    @else
                                                        <button class="action-btn mr-2" type="button" wire:click="showThisChildAccount({{ $entry->id }})">
                                                            <iconify-icon icon="mingcute:down-fill"></iconify-icon>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>

                                        <td class="table-td"><b>#{{ $entry->id }}</b></td>

                                        <td class="table-td ">{{ $entry->entry_title->name }}</td>

                                        <td class="table-td ">
                                            @if ($entry->is_reviewed)
                                                <span class="badge bg-success-500 text-white capitalize inline-flex items-center mt-2">
                                                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="gg:check-o"></iconify-icon>
                                                    Reviewed</span>
                                            @endif
                                            @if ($entry->approver_id)
                                                <span class="badge btn-outline-success text-white capitalize inline-flex items-center mt-2">
                                                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="gg:check-o"></iconify-icon>
                                                    Approved</span>
                                            @endif
                                        </td>

                                        <td class="table-td ">{{ \Carbon\Carbon::parse($entry->created_at)->format('d/m/Y H:i') }}</td>

                                        <td class="table-td ">-</td>

                                        <td class="table-td ">-</td>

                                        <td class="table-td ">-</td>

                                        <td class="table-td ">-</td>

                                        <td class="table-td ">-</td>

                                        <td class="table-td ">-</td>

                                        <td class="table-td ">{{ \Carbon\Carbon::parse($entry->archived_at)->format('d/m/Y H:i') }}</td>

                                        <td class="table-td ">{{ $entry->archivedBy->username ?? '-' }}</td>

                                    </tr>

                                    @if (in_array($entry->id, $showChildAccounts))
                                        @foreach ($entry->accounts as $childAccount)
                                            <tr class="bg-slate-50 dark:bg-slate-700">
                                                <td class="table-td" colspan="3"><b>{{ $childAccount->main_account->name }} • {{ $childAccount->name }}</b></td>

                                                <td class="table-td" ></td>

                                                <td class="table-td">
                                                    <span class="badge bg-black-500 text-white capitalize inline-flex items-center">
                                                        @if ($childAccount->nature === 'credit')
                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-up"></iconify-icon>
                                                        @elseif ($childAccount->nature === 'debit')
                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-down"></iconify-icon>
                                                        @else
                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:question-mark-circle"></iconify-icon>
                                                        @endif
                                                        {{ ucfirst($childAccount->nature) }}
                                                    </span>
                                                </td>

                                                <td class="table-td"><b>{{ number_format($childAccount->pivot->amount, 2) }}</b></td>
                                                <td class="table-td">{{ number_format($childAccount->pivot->account_balance, 2) }}</td>
                                                <td class="table-td">{{ number_format($childAccount->pivot->account_foreign_balance, 2) }}</td>
                                                <td class="table-td">{{ $childAccount->pivot->currency }}</td>
                                                <td class="table-td">{{ number_format($childAccount->pivot->currency_amount, 2) }}</td>
                                                <td class="table-td">{{ $childAccount->pivot->currency_rate }}</td>
                                                <td class="table-td" colspan="3"></td>
                                            </tr>
                                        @endforeach
                                    @endif

                                @empty
                                    <tr>
                                        <td colspan="12">
                                            {{-- START: empty filter result --}}
                                            <div class="card m-5 p-5">
                                                <div class="card-body rounded-md bg-white dark:bg-slate-800 m-5">
                                                    <div class="items-center text-center p-5 m-5">
                                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Entries Found!</h2>
                                                        <p class="card-text">No archived entries found.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- END: empty filter result --}}
                                        </td>
                                    </tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                    {{ $entries->links('vendor.livewire.bootstrap') }}
                </div>
            </div>
        </div>
    </div>

</div>

