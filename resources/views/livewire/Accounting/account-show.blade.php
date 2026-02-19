<div>

    <div class="card dark active mb-5">
        <div class="card-body rounded-md bg-white dark:bg-slate-800 shadow-base menu-open">
            <div class="flex items-start justify-between p-5">
                <div>
                    <h3 class="card-title text-slate-900 dark:text-white">{{ $account->name }}</h3>
                    @if ($account->desc)
                        <p class="card-text my-5 break-words">{{ $account->desc }}</p>
                    @endif
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ ucwords($account->nature) }}</p>
                </div>
                @can('setOpeningBalance', $account)
                <button wire:click="openOpeningBalanceModal"
                    class="btn inline-flex justify-center btn-outline-primary btn-sm">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:settings-2"></iconify-icon>
                    Set Opening Balance
                </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="card">



        <header class=" card-header noborder">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Start Balance</p>
                <h4 class="card-title">
                    {{ $entries->first()
                        ? ($account->nature == 'debit'
                            ? number_format(
                                $entries->first()->account_balance + $entries->first()->credit_amount - $entries->first()->debit_amount,
                                2,
                            )
                            : number_format(
                                $entries->first()->account_balance + $entries->first()->debit_amount - $entries->first()->credit_amount,
                                2,
                            ))
                        : number_format($account->balance, 2) }}
                </h4>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="downloadJournalEntries"
                    class="btn inline-flex justify-center btn-outline-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="line-md:download-loop"></iconify-icon>
                    Download Journal Entries
                </button>
                <input type="text" class="form-control w-auto d-inline-block cursor-pointer" style="width:auto"
                    name="datetimes" id="reportrange" />
            </div>
        </header>

        <header class="card-header noborder">
            <iconify-icon wire:loading wire:target="searchText" class="loading-icon text-lg"
                    icon="line-md:loading-twotone-loop"></iconify-icon>
                <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search..."
                    wire:model="searchText">
        </header>

        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800 no-wrap">
                                <tr>
                                    <th scope="col" class="table-th">#</th>
                                    <th scope="col" class="table-th">Date</th>
                                    <th scope="col" class="table-th">Title</th>
                                    <th scope="col" class="table-th">Comment</th>
                                    <th scope="col" class="table-th">Debit</th>
                                    <th scope="col" class="table-th">Credit</th>
                                    <th scope="col" class="table-th">Balance</th>
                                    <th scope="col" class="table-th">Debit $</th>
                                    <th scope="col" class="table-th">Credit $</th>
                                    <th scope="col" class="table-th">Balance $</th>
                                    <th scope="col" class="table-th">Creator</th>

                                </tr>
                            </thead>
                            <tbody
                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 no-wrap">

                                @foreach ($entries as $entry)
                                    <tr>
                                        <td class="table-td" 
                                        ><a href="{{ route('accounts.entries', $entry->id) }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline">{{ $entry->id }}</a></td>
                                        <td class="table-td">{{ $entry->created_at->format('d/m/Y') }}</td>
                                        <td class="table-td"><b>{{ $entry->name }}</b> {{ $entry->revert_entry_id ? ' (R)' : '' }} </td>
                                        <td class="table-td">{{ $entry->cash_title }}</td>
                                        <td class="table-td">{{ number_format($entry->debit_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->credit_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->account_balance, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->debit_foreign_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->credit_foreign_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->account_foreign_balance, 2) }}
                                        </td>
                                        <td class="table-td">{{ $entry->username }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($entries->isEmpty())
                    {{-- START: empty filter result --}}
                    <div class="card m-5 p-5">
                        <div class="card-body rounded-md bg-white dark:bg-slate-800">
                            <div class="items-center text-center p-5">
                                <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon>
                                </h2>
                                <h2 class="card-title text-slate-900 dark:text-white mb-3">
                                    No entries found!</h2>
                                <p class="card-text">Try changing the filters or search terms for this view.
                                </p>
                                <a href="{{ url('/accounts') }}"
                                    class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">
                                    View Accounts</a>
                            </div>
                        </div>
                    </div>
                    {{-- END: empty filter result --}}
                @endif
            </div>
        </div>
        @if (!$entries->isEmpty())
            <header class=" card-header noborder">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">End Balance</p>
                    <h4 class="card-title">{{ number_format($entries->last()->account_balance, 2) }}
                    </h4>
                </div>
            </header>
        @endif
    </div>


    @if ($is_open_edit)
    @endif

    {{-- Set Opening Balance Modal --}}
    @if ($isOpeningBalanceModalOpen)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="opening_balance_modal" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 500px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Set Opening Balance
                            </h3>

                            <button wire:click="closeOpeningBalanceModal" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4">
                                <div class="flex">
                                    <iconify-icon icon="lucide:info" class="text-blue-600 dark:text-blue-400 text-xl mr-2"></iconify-icon>
                                    <div>
                                        <p class="text-sm text-blue-700 dark:text-blue-300">
                                            Setting the opening balance will recalculate all entry balances for all accounts. This may take a moment.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-5">
                                <label for="openingBalance" class="form-label">Balance (EGP)</label>
                                <input type="number" step="0.01" id="openingBalance"
                                    class="form-control mt-2 w-full {{ $errors->has('openingBalance') ? '!border-danger-500' : '' }}"
                                    wire:model="openingBalance" placeholder="Enter opening balance">
                                @error('openingBalance')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-5">
                                <label for="openingForeignBalance" class="form-label">Foreign Balance (Optional)</label>
                                <input type="number" step="0.01" id="openingForeignBalance"
                                    class="form-control mt-2 w-full {{ $errors->has('openingForeignBalance') ? '!border-danger-500' : '' }}"
                                    wire:model="openingForeignBalance" placeholder="Enter foreign balance">
                                @error('openingForeignBalance')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <p class="text-sm text-slate-500 mt-1">Only needed for accounts with foreign currency</p>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeOpeningBalanceModal"
                                class="btn inline-flex justify-center btn-outline-dark">
                                Cancel
                            </button>
                            <button wire:click="setOpeningBalance"
                                class="btn inline-flex justify-center btn-primary">
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setOpeningBalance"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                <span wire:loading.remove wire:target="setOpeningBalance">Set Balance</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
