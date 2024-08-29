<div>
    <div class="flex justify-between flex-wrap items-center mb-5">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Unapproved Journal Entry
            </h4>
        </div>
    </div>

    @forelse ($entries as $entry)
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
                        <button  wire:click="$emit('showConfirmation', 'Are you sure you want to approve this Entry?','success','approveEntry' , {{ $entry->id }})" class="btn btn-sm inline-flex justify-center btn-success mt-2">Approve Entry</button>
                    </div>
                    <div class="items-end">

                        <br>
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize rounded-3xl">{{ $entry->currency }}</span>
                    </div>

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
                                <p class="text-lg"><b>{{ number_format($entry->debit_balance, 2) }}</b></p>
                            </td>
                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center"></td>
                            @if ($entry->credit_doc_url || $entry->debit_doc_url)
                                @if ($entry->debit_doc_url)
                                    <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center">
                                        <button wire:click="downloadDebitDoc({{ $entry->id }})" class="btn btn-sm inline-flex items-center justify-center btn-dark rounded-full">
                                            <span class="flex items-center">
                                                Download
                                            </span>
                                        </button>
                                    </td>
                                @endif
                            @endif

                        </tr>
                        <tr>
                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center">{{ $entry->credit_account->name }}</td>
                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center"></td>
                            <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center">
                                <p class="text-lg"><b>{{ number_format($entry->credit_balance, 2) }}</b></p>
                            </td>
                            @if ($entry->credit_doc_url || $entry->debit_doc_url)
                                @if ($entry->credit_doc_url)
                                    <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 text-center">
                                        <button wire:click='downloadCreditDoc({{ $entry->id }})' class="btn btn-sm inline-flex items-center justify-center btn-dark rounded-full">
                                            <span class="flex items-center">
                                                Download
                                            </span>
                                        </button>
                                    </td>
                                @endif
                            @endif
                        </tr>
                    </tbody>
                </table>

                <!-- Amounts and Currency -->
                <div class="flex justify-between mt-4">
                    <div class="w-1/2">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Amount:</p>
                        <p class="text-lg text-slate-900 dark:text-white">{{ $entry->currency }} {{ number_format($entry->amount, 2) }}</p>
                    </div>


                </div>

                <!-- Comment -->
                <div class="flex justify-between flex-wrap items-center">
                    <div class="mt-4">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Comment:</p>
                        <p class="text-lg text-slate-900 dark:text-white">{{ $entry->comment ?? 'No comments available.' }}</p>
                    </div>

                    @if ($entry->approver)
                        <span>
                            <p class="text-sm font-light text-slate-600 dark:text-slate-300">
                                Approved by: <b>{{ ucwords($entry->approver->full_name) }}</b>
                            </p>
                        </span>
                    @endif

                </div>

            </div>
        </div>

    @empty
        


        {{-- START: empty filter result --}}
        <div class="card m-5 p-5">
            <div class="card-body rounded-md bg-white dark:bg-slate-800 m-5">
                <div class="items-center text-center p-5 m-5">
                    <h2><iconify-icon icon="ph:empty-bold"></iconify-icon></h2>
                    <h2 class="card-title text-slate-900 dark:text-white mb-3">No Entries Found!</h2>
                    <p class="card-text">Try check journal entries or add new entry.
                    </p>
                    <a href="{{ url('/entries/new') }}"
                        class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">New Entry</a>
                </div>
            </div>
        </div>
        {{-- END: empty filter result --}}
    @endforelse

</div>
