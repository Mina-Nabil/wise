<div>
    <div class="flex justify-between flex-wrap items-center mb-5">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Unapproved Journal Entry
            </h4>
        </div>
    </div>

    <div class="card">
        {{-- <header class=" card-header noborder">
            <h4 class="card-title">Table Head
            </h4>
        </header> --}}

        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700 no-wrap">
                                <tr>

                                    <th scope="col" class="table-th"></th>

                                    <th scope="col" class=" table-th !p-1">
                                        #
                                    </th>

                                    <th scope="col" class=" table-th !p-1">
                                        User
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Date
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Amnt
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Blnc
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Curr. Rate
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Actions
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 no-wrap">

                                @forelse ($entries as $entry)
                                    <tr>

                                        <td class="table-td"></td>

                                        <td class="table-td"><b>#{{ $entry->id }}</b></td>

                                        <td class="table-td"><b>{{ $entry->creator?->username }}</b></td>

                                        <td class="table-td ">{{ \Carbon\Carbon::parse($entry->created_at)->format('Y-m-d H:i') }}</td>

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

                                        <td class="table-td ">-</td>

                                        <td class="table-td ">-</td>

                                        <td class="table-td ">-</td>

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

                                            <div>
                                                <button class="action-btn" type="button" wire:click="showEntry({{ $entry->id }})">
                                                    <iconify-icon icon="bi:info" class="text-lg"></iconify-icon>
                                                </button>

                                            </div>
                                            <div class="dropstart relative text-right">
                                                <button class="inline-flex justify-center items-center" type="button"   data-bs-toggle="dropdown" aria-expanded="false">
                                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                </button>
                                                <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                    @can('approve', \App\Models\Accounting\JournalEntry::class)
                                                        <li wire:click="$emit('showConfirmation', 'Are you sure you want to Approve this entry?','black','approveEntry' , {{ $entry->id }})">
                                                            <span
                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                <iconify-icon icon="mdi:check-all"></iconify-icon>
                                                                <span>Approve</span></span>
                                                        </li>
                                                    @endcan
                                                    @if ($entry->credit_doc_url)
                                                        <li wire:click="downloadCreditDoc({{ $entry->id }})">
                                                            <span
                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                <iconify-icon
                                                                    icon="material-symbols:download"></iconify-icon>
                                                                <span>Download credit document</span></span>
                                                        </li>
                                                    @endif
                                                    @if ($entry->debit_doc_url)
                                                        <li wire:click="downloadDebitDoc({{ $entry->id }})">
                                                            <span
                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                <iconify-icon
                                                                    icon="material-symbols:download"></iconify-icon>
                                                                <span>Download debit document</span></span>
                                                        </li>
                                                    @endif
                                                    <li wire:click="$emit('showConfirmation', 'Are you sure you want to delete this entry?','danger','deleteEntry' , {{ $entry->id }})">
                                                        <span
                                                            class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                            <iconify-icon icon="material-symbols:delete-outline"></iconify-icon>
                                                            <span>Delete</span></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>

                                    </tr>

                                    @if (in_array($entry->id, $showChildAccounts))
                                        @foreach ($entry->accounts as $childAccount)
                                            <tr class="bg-slate-50 dark:bg-slate-700">
                                                <td class="table-td"></td>
                                                <td class="table-td" colspan="3"><b>{{ $childAccount->main_account->name }} • {{ $childAccount->name }}</b></td>
                                                <td class="table-td"></td>

                                                <td class="table-td">
                                                    <span class="badge bg-black-500 text-white capitalize inline-flex items-center">
                                                        @if ($childAccount->pivot->nature === 'credit')
                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-up"></iconify-icon>
                                                        @elseif ($childAccount->pivot->nature === 'debit')
                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-down"></iconify-icon>
                                                        @else
                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:question-mark-circle"></iconify-icon>
                                                        @endif
                                                        {{ ucfirst($childAccount->pivot->nature) }}
                                                    </span>
                                                </td>

                                                <td class="table-td"><b>{{ number_format($childAccount->pivot->amount, 2) }}{{ $childAccount->pivot->currency_amount ? ' (' . number_format($childAccount->pivot->currency_amount, 2) . ')' : '' }}</b></td>
                                                <td class="table-td">{{ number_format($childAccount->pivot->account_balance, 2) }}{{ $childAccount->pivot->account_foreign_balance ? ' (' . number_format($childAccount->pivot->account_foreign_balance, 2) . ')' : '' }}</td>
                                                <td class="table-td">{{ $childAccount->pivot->currency_rate }}</td>
                                                <td class="table-td">
                                                    @if ($childAccount->pivot->doc_url)
                                                        <button
                                                            wire:click='downloadAccountDoc({{ $entry->id }} , {{ $childAccount->id }})'
                                                            class="btn inline-flex justify-center btn-outline-light btn-sm">
                                                            <span wire:loading.remove
                                                                wire:target="downloadAccountDoc({{ $entry->id }} , {{ $childAccount->id }})">Download</span>
                                                            <iconify-icon
                                                                class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                                                wire:loading
                                                                wire:target="downloadAccountDoc({{ $entry->id }} , {{ $childAccount->id }})"
                                                                icon="line-md:loading-twotone-loop"></iconify-icon>
                                                        </button>
                                                    @endif
                                                    <label
                                                        class="btn inline-flex justify-center btn-outline-primary btn-sm cursor-pointer">
                                                        <span wire:loading.remove
                                                            wire:target="uploadAccountDoc({{ $entry->id }}, {{ $childAccount->id }})">Upload
                                                            File</span>
                                                        <iconify-icon
                                                            class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                                            wire:loading
                                                            wire:target="uploadAccountDoc({{ $entry->id }}, {{ $childAccount->id }})"
                                                            icon="line-md:loading-twotone-loop"></iconify-icon>
                                                        <input type="file" class="hidden"
                                                            wire:model="accountDoc"
                                                            wire:change="uploadAccountDoc({{ $entry->id }}, {{ $childAccount->id }})">
                                                    </label>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                @empty
                                    <tr>
                                        <td colspan="11">
                                            {{-- START: empty filter result --}}
                                            <div class="card m-5 p-5">
                                                <div class="card-body rounded-md bg-white dark:bg-slate-800 m-5">
                                                    <div class="items-center text-center p-5 m-5">
                                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Entries Found!</h2>
                                                        <p class="card-text">Try changing the filters or search terms for this view.</p>
                                                        <a href="{{ url('/accounts/entries/new') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">New Entry</a>
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

    @if ($entryId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                #{{ $entryInfo->id }}
                            </h3>
                            <button wire:click="closeShowInfo" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Comment</p>
                                <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                                    {{ $entryInfo->comment ?? 'No comment added.' }}
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Extra Note</p>
                                <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                                    {{ $entryInfo->extra_note ?? 'No extra note added.' }}
                                </div>
                            </div>
                            @if ($entryInfo->cash_entry_type)
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $entryInfo->cash_entry_type }}</p>
                                    <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                                        <b>{{ $entryInfo->receiver_name ?? 'No receiver name.' }}</b>
                                    </div>
                                </div>
                            @endif
                            <hr>
                            <div class="flex justify-end">
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 text-right">Creator</p>
                                    <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                                        <b>{{ $entryInfo->creator?->full_name }}</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
