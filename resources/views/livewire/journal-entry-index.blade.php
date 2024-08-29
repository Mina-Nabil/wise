<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Journal Entry
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <button wire:click="openSelectAccountModel" class="btn inline-flex justify-center btn-outline-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ion:filter"></iconify-icon>
                Account Entries
            </button>
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


    @if ($selectedAccount)
    
    <div class="card dark active mb-2">
        <div class="card-body rounded-md bg-white dark:bg-slate-800 shadow-base menu-open">
            <div class="items-center p-5">
                <h3 class="card-title text-slate-900 dark:text-white">Allianz • حساب شركه اليانز</h3>
                <p class="card-text my-5"><!-- Type Badge -->
                    <span class="badge bg-primary-500 text-white capitalize inline-flex items-center">
                        @switch($account->main_account->type)
                            @case('expense')
                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:cash"></iconify-icon>
                            @break

                            @case('revenue')
                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:currency-dollar"></iconify-icon>
                            @break

                            @case('asset')
                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:home"></iconify-icon>
                            @break

                            @case('liability')
                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:document-text"></iconify-icon>
                            @break

                            @default
                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:badge-check"></iconify-icon>
                        @endswitch
                        {{ ucfirst($account->main_account->type) }}
                    </span>

                    <!-- Nature Badge -->
                    <span class="badge bg-secondary-500 text-white capitalize inline-flex items-center">
                        @if ($account->nature === 'credit')
                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-up"></iconify-icon>
                        @elseif ($account->nature === 'debit')
                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-down"></iconify-icon>
                        @else
                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:question-mark-circle"></iconify-icon>
                        @endif
                        {{ ucfirst($account->nature) }}
                    </span>
                </p>
                <a wire:click.prevent="clearAccountFilter" class="underline btn-link active">clear filter</a>
            </div>
        </div>
    </div>
    @endif


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
                    <div class="items-end">
                        <div class="dropstart relative text-right">
                            <button class="inline-flex justify-center items-center" type="button" id="tableDropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="heroicons-outline:dots-vertical"></iconify-icon>
                            </button>
                            <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                @if (!$entry->is_reviewed)
                                    <li wire:click="reviewEntry({{ $entry->id }})">
                                        <span
                                            class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                            <iconify-icon icon="mdi:check-all"></iconify-icon>
                                            <span>Review Entry</span></span>
                                    </li>
                                @endif

                            </ul>
                        </div>
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
                    <p class="card-text">Try changing the filters or search terms for this view.
                    </p>
                    <a href="{{ url('/entries/new') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">New Entry</a>
                </div>
            </div>
        </div>
        {{-- END: empty filter result --}}
    @endforelse

    @if ($isOpenFilterAccountModal)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none" style="min-width:800px;">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Filter by accounts
                            </h3>

                            <button wire:click="toggleAddLead" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <p class="text-lg"><b>select account</b></p>

                                <div class="input-area">
                                    <label for="searchAccountText" class="form-label">Search</label>
                                    <input id="searchAccountText" placeholder="search by name..." type="text" class="form-control @error('searchAccountText') !border-danger-500 @enderror" wire:model="searchAccountText">
                                </div>

                                <div>
                                    @if ($fetched_accounts)



                                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                @foreach ($fetched_accounts as $account)
                                                    <tr>
                                                        <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700">{{ $account->name }}</td>
                                                        <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700">
                                                            <div class="text-sm text-slate-500 dark:text-slate-400 flex space-x-3">
                                                                <!-- Type Badge -->
                                                                <span class="badge bg-primary-500 text-white capitalize inline-flex items-center">
                                                                    @switch($account->main_account->type)
                                                                        @case('expense')
                                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:cash"></iconify-icon>
                                                                        @break

                                                                        @case('revenue')
                                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:currency-dollar"></iconify-icon>
                                                                        @break

                                                                        @case('asset')
                                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:home"></iconify-icon>
                                                                        @break

                                                                        @case('liability')
                                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:document-text"></iconify-icon>
                                                                        @break

                                                                        @default
                                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:badge-check"></iconify-icon>
                                                                    @endswitch
                                                                    {{ ucfirst($account->main_account->type) }}
                                                                </span>

                                                                <!-- Nature Badge -->
                                                                <span class="badge bg-secondary-500 text-white capitalize inline-flex items-center">
                                                                    @if ($account->nature === 'credit')
                                                                        <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-up"></iconify-icon>
                                                                    @elseif ($account->nature === 'debit')
                                                                        <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-down"></iconify-icon>
                                                                    @else
                                                                        <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:question-mark-circle"></iconify-icon>
                                                                    @endif
                                                                    {{ ucfirst($account->nature) }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">{{ number_format($account->balance, 2) }}</td>
                                                        <td class="table-td border border-slate-100 dark:bg-slate-800 dark:border-slate-700 ">
                                                            <button wire:click='selectAccount({{ $account->id }})' class="btn btn-sm inline-flex justify-center btn-outline-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                                                                Select account
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach


                                            </tbody>
                                        </table>




                                        {{-- @foreach ($fetched_accounts as $account)
                                            <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base mb-3">
                                                <div class="card-body flex flex-col p-6 active">
                                                    <div class="card-text h-full menu-open active">
                                                        <div class="flex justify-between mb-4">
                                                            <div>
                                                                <div class="text-xl text-slate-900 dark:text-white">
                                                                    {{ $account->name }}
                                                                </div>
                                                                <div class="text-sm text-slate-500 dark:text-slate-400 flex space-x-3">
                                                                    <!-- Type Badge -->
                                                                    <span class="badge bg-primary-500 text-white capitalize inline-flex items-center">
                                                                        @switch($account->main_account->type)
                                                                            @case('expense')
                                                                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:cash"></iconify-icon>
                                                                            @break

                                                                            @case('revenue')
                                                                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:currency-dollar"></iconify-icon>
                                                                            @break

                                                                            @case('asset')
                                                                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:home"></iconify-icon>
                                                                            @break

                                                                            @case('liability')
                                                                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:document-text"></iconify-icon>
                                                                            @break

                                                                            @default
                                                                                <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:badge-check"></iconify-icon>
                                                                        @endswitch
                                                                        {{ ucfirst($account->main_account->type) }}
                                                                    </span>

                                                                    <!-- Nature Badge -->
                                                                    <span class="badge bg-secondary-500 text-white capitalize inline-flex items-center">
                                                                        @if ($account->nature === 'credit')
                                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-up"></iconify-icon>
                                                                        @elseif ($account->nature === 'debit')
                                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-down"></iconify-icon>
                                                                        @else
                                                                            <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:question-mark-circle"></iconify-icon>
                                                                        @endif
                                                                        {{ ucfirst($account->nature) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-text mt-4 menu-open active">
                                                            <div class="mt-6 flex justify-between menu-open">
                                                                <div>
                                                                    <p>Balance</p>
                                                                    <div class="text-lg text-slate-900 dark:text-white">
                                                                        <b>
                                                                            EGP {{ number_format($account->balance, 2) }}
                                                                        </b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach --}}
                                    @endif

                                </div>


                            </div>
                            <!-- Modal footer -->
                            <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                <button wire:click="addLead" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                    Accept
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endif

</div>
