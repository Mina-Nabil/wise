<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Journal Entry
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @can('create', \App\Models\Accounting\JournalEntry::class)
            <a href="{{ url('/entries/new') }}">
                <button wire:click="openAddNewModal" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                    New Entry
                </button>
            </a>
            @endcan
        </div>
    </div>

    @foreach ($entries as $entry)
        <div class="card  ring-1 ring-secondary-500 mb-3 rounded-md shadow-base bg-white dark:bg-slate-800">
            <div class="card-body p-6">
                <div class="flex justify-between mb-4">
                    <!-- Journal Entry Title & Date -->
                    <div>
                        <h5 class="text-xl font-semibold text-slate-900 dark:text-white">#{{ $entry->day_serial }} - {{ $entry->entry_title->name }}</h5>
                        <p class="text-sm text-slate-500 dark:text-slate-400 flex items-center">
                            <iconify-icon icon="mingcute:time-line" class="mr-1"></iconify-icon>
                            Date: {{ $entry->created_at->format('d/m/Y') }}
                        </p>
                        @if ($entry->is_reviewed)
                            {{-- <span class="badge bg-success-500 text-white capitalize mt-2">Reviewed</span> --}}
                            <span class="badge bg-success-500 text-white capitalize inline-flex items-center mt-2">
                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="gg:check-o"></iconify-icon>
                                Reviewed</span>
                        @endif
                        @if ($entry->approver_id)
                            <span class="badge btn-outline-success text-white capitalize inline-flex items-center mt-2">
                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="gg:check-o"></iconify-icon>
                                Approved</span>
                        @endif

                    </div>
                    <a href="journal-entry/{{ $entry->id }}" class="text-sm text-slate-500 dark:text-slate-400">
                        <iconify-icon icon="heroicons-outline:calendar" class="text-secondary-500 text-lg"></iconify-icon>
                        View Details
                    </a>
                </div>

                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="p-2 bg-slate-800" style="color: white">Account Name</th>
                            <th class="p-2 bg-slate-800" style="color: white">Debit</th>
                            <th class="p-2 bg-slate-800" style="color: white">Credit</th>

                            @if ($entry->credit_doc_url || $entry->debit_doc_url)
                                <th class="p-2 bg-slate-800" style="color: white"> Doc </th>
                            @endif

                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                        <tr>
                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center">{{ $entry->debit_account->name }}</td>
                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center">
                                <p class="text-lg"><b>{{ number_format($entry->amount, 2) }}</b></p>
                            </td>
                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center"></td>
                            @if ($entry->credit_doc_url || $entry->debit_doc_url)
                                <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center">
                                    <button class="btn btn-sm inline-flex items-center justify-center btn-dark rounded-full">
                                        <span class="flex items-center">
                                            Download
                                        </span>
                                    </button>
                                </td>
                            @endif

                        </tr>
                        <tr>
                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center">{{ $entry->credit_account->name }}</td>
                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center"></td>
                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center">
                                <p class="text-lg"><b>{{ number_format($entry->amount, 2) }}</b></p>
                            </td>
                            @if ($entry->credit_doc_url || $entry->debit_doc_url)
                                <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center">
                                    <button class="btn btn-sm inline-flex items-center justify-center btn-dark rounded-full">
                                        <span class="flex items-center">
                                            Download
                                        </span>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    </tbody>
                </table>

                <!-- Amounts and Currency -->
                <div class="flex justify-between mt-4">
                    <div class="w-1/2">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Amount:</p>
                        <p class="text-lg text-slate-900 dark:text-white">EGP {{ number_format($entry->amount, 2) }}</p>
                    </div>

                    <div class="w-1/2 text-right">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Currency:</p>
                        <p class="text-lg text-slate-900 dark:text-white">{{ $entry->currency }}</p>
                    </div>
                </div>

                <!-- Comment -->
                <div class="mt-4">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Comment:</p>
                    <p class="text-lg text-slate-900 dark:text-white">{{ $entry->comment ?? 'No comments available.' }}</p>
                </div>
            </div>
        </div>
    @endforeach



    @if ($isNewJournalEntryModalOpen)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 900px;">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add New Journal Entry
                            </h3>
                            <button wire:click="closeAddNewModal" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="form-group mb-5">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" id="title" class="form-control mt-2 w-full {{ $errors->has('title') ? '!border-danger-500' : '' }}" wire:model.defer="title" max="100">
                                @error('title')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" id="amount" class="form-control mt-2 w-full {{ $errors->has('amount') ? '!border-danger-500' : '' }}" wire:model.defer="amount" step="0.01">
                                @error('amount')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="credit_id" class="form-label">Credit Account</label>
                                <select id="credit_id" class="form-control mt-2 w-full {{ $errors->has('credit_id') ? '!border-danger-500' : '' }}" wire:model.defer="credit_id">
                                    <option value="">Select Credit Account</option>
                                    @foreach ($creditAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                                @error('credit_id')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="debit_id" class="form-label">Debit Account</label>
                                <select id="debit_id" class="form-control mt-2 w-full {{ $errors->has('debit_id') ? '!border-danger-500' : '' }}" wire:model.defer="debit_id">
                                    <option value="">Select Debit Account</option>
                                    @foreach ($debitAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                                @error('debit_id')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="currency" class="form-label">Currency</label>
                                <input type="text" id="currency" class="form-control mt-2 w-full {{ $errors->has('currency') ? '!border-danger-500' : '' }}" wire:model.defer="currency" max="10">
                                @error('currency')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="currency_amount" class="form-label">Currency Amount</label>
                                <input type="number" id="currency_amount" class="form-control mt-2 w-full {{ $errors->has('currency_amount') ? '!border-danger-500' : '' }}" wire:model.defer="currency_amount" step="0.01">
                                @error('currency_amount')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="currency_rate" class="form-label">Currency Rate</label>
                                <input type="number" id="currency_rate" class="form-control mt-2 w-full {{ $errors->has('currency_rate') ? '!border-danger-500' : '' }}" wire:model.defer="currency_rate" step="0.01">
                                @error('currency_rate')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="credit_doc_url" class="form-label">Credit Document URL</label>
                                <input type="text" id="credit_doc_url" class="form-control mt-2 w-full {{ $errors->has('credit_doc_url') ? '!border-danger-500' : '' }}" wire:model.defer="credit_doc_url">
                                @error('credit_doc_url')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="debit_doc_url" class="form-label">Debit Document URL</label>
                                <input type="text" id="debit_doc_url" class="form-control mt-2 w-full {{ $errors->has('debit_doc_url') ? '!border-danger-500' : '' }}" wire:model.defer="debit_doc_url">
                                @error('debit_doc_url')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="revert_entry_id" class="form-label">Revert Entry ID</label>
                                <input type="text" id="revert_entry_id" class="form-control mt-2 w-full {{ $errors->has('revert_entry_id') ? '!border-danger-500' : '' }}" wire:model.defer="revert_entry_id">
                                @error('revert_entry_id')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="comment" class="form-label">Comment</label>
                                <textarea id="comment" class="form-control mt-2 w-full {{ $errors->has('comment') ? '!border-danger-500' : '' }}" wire:model.defer="comment"></textarea>
                                @error('comment')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="cash_entry_type" class="form-label">Cash Entry Type</label>
                                <input type="text" id="cash_entry_type" class="form-control mt-2 w-full {{ $errors->has('cash_entry_type') ? '!border-danger-500' : '' }}" wire:model.defer="cash_entry_type">
                                @error('cash_entry_type')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="receiver_name" class="form-label">Receiver Name</label>
                                <input type="text" id="receiver_name" class="form-control mt-2 w-full {{ $errors->has('receiver_name') ? '!border-danger-500' : '' }}" wire:model.defer="receiver_name">
                                @error('receiver_name')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="approver_id" class="form-label">Approver ID</label>
                                <input type="text" id="approver_id" class="form-control mt-2 w-full {{ $errors->has('approver_id') ? '!border-danger-500' : '' }}" wire:model.defer="approver_id">
                                @error('approver_id')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="approved_at" class="form-label">Approved At</label>
                                <input type="datetime-local" id="approved_at" class="form-control mt-2 w-full {{ $errors->has('approved_at') ? '!border-danger-500' : '' }}" wire:model.defer="approved_at">
                                @error('approved_at')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="user_id" class="form-label">User ID</label>
                                <input type="text" id="user_id" class="form-control mt-2 w-full {{ $errors->has('user_id') ? '!border-danger-500' : '' }}" wire:model.defer="user_id">
                                @error('user_id')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="saveJournalEntry" class="btn inline-flex justify-center text-white bg-black-500">
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="saveJournalEntry" icon="line-md:loading-twotone-loop"></iconify-icon>
                                <span wire:loading.remove="saveJournalEntry">Submit</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
