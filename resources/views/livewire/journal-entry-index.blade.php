<div>


    @if (count($selectedEntries) > 0)
        <div class="grid md:grid-cols-1 select-action-btns-container gap-2">
            <button wire:click='reviewSelectedEntries' class="btn btn-sm inline-flex justify-center btn-primary block-btn">
                <span class="flex items-center">
                    <span>Review Entries</span>
                </span>
            </button>
        </div>
    @endif


    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Journal Entry
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @can('viewAny', \App\Models\Accounting\JournalEntry::class)
                <button wire:click="openSelectAccountModel" class="btn inline-flex justify-center btn-outline-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ion:filter"></iconify-icon>
                    Account Entries
                </button>
            @endcan
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
                    <div class="flex justify-between">
                        <h3 class="card-title text-slate-900 dark:text-white">{{ $selectedAccount->name }} • {{ $selectedAccount->desc }}</h3>
                        <button wire:click.prevent="clearAccountFilter" class="action-btn" type="button">
                            <iconify-icon icon="material-symbols:close"></iconify-icon>
                        </button>

                    </div>
                    <div class="flex justify-between">
                        <p class="card-text my-5"><!-- Type Badge -->
                            <span class="badge bg-primary-500 text-white capitalize inline-flex items-center">
                                @switch($selectedAccount->main_account->type)
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
                                {{ ucfirst($selectedAccount->main_account->type) }}
                            </span>

                            <!-- Nature Badge -->
                            <span class="badge bg-secondary-500 text-white capitalize inline-flex items-center">
                                @if ($selectedAccount->nature === 'credit')
                                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-up"></iconify-icon>
                                @elseif ($selectedAccount->nature === 'debit')
                                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:arrow-circle-down"></iconify-icon>
                                @else
                                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="heroicons-outline:question-mark-circle"></iconify-icon>
                                @endif
                                {{ ucfirst($selectedAccount->nature) }}
                            </span>
                        </p>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 text-right">Balance</p>
                            <h5>{{ number_format($selectedAccount->balance, 2) }}</h5>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <header class=" card-header noborder">
            <h4 class="card-title">Table Head
            </h4>
        </header>

        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700 no-wrap">
                                <tr>
                                    <th scope="col" class="table-th">

                                        <div class="checkbox-area">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" wire:model="selectAll" class="hidden" id="select-all">
                                                <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                    <img src="assets/images/icon/ck-white.svg" alt="" class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                            </label>
                                        </div>
                                    </th>

                                    <th scope="col" class=" table-th !p-1">
                                        #
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Serial
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Amount
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Credit Account
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Debit Account
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Currency
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Currency Amount
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Currency Rate
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Actions
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 no-wrap">

                                @forelse ($entries as $entry)
                                    <tr>
                                        <td class="table-td">
                                            <div class="checkbox-area">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" wire:model="selectedEntries" value="{{ $entry->id }}" class="hidden" id="select-all">
                                                    <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                        <img src="assets/images/icon/ck-white.svg" alt="" class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                                </label>
                                            </div>
                                        </td>

                                        <td class="table-td"><b>#{{ $entry->id }}</b></td>

                                        <td class="table-td"><b>{{ $entry->day_serial }}</b></td>

                                        <td class="table-td ">{{ $entry->entry_title->name }}</td>

                                        <td class="table-td "><b>{{ number_format($entry->amount, 2) }}</b></td>

                                        <td class="table-td ">{{ $entry->credit_account->name }}</td>

                                        <td class="table-td ">{{ $entry->debit_account->name }}</td>

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

                                        <td class="table-td ">
                                            <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize rounded-3xl">{{ $entry->currency }}</span>
                                        </td>

                                        <td class="table-td ">{{ number_format($entry->currency_amount, 2) }}</td>

                                        <td class="table-td ">{{ number_format($entry->currency_rate, 2) }}</td>

                                        <td class="table-td flex justify-between">
                                            <div>
                                                <button class="action-btn" type="button" wire:click="showEntry({{ $entry->id }})">
                                                    <iconify-icon icon="bi:info" class="text-lg"></iconify-icon>
                                                </button>

                                            </div>
                                            <div class="dropstart relative text-right">
                                                <button class="inline-flex justify-center items-center" type="button" id="tableDropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                </button>
                                                <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                    @can('review', $entry)
                                                        @if (!$entry->is_reviewed)
                                                            <li wire:click="reviewEntry({{ $entry->id }})">
                                                                <span
                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                    <iconify-icon icon="mdi:check-all"></iconify-icon>
                                                                    <span>Review Entry</span></span>
                                                            </li>
                                                        @endif
                                                    @endcan
                                                    @can('update', $entry)
                                                        @if (!$entry->revert_entry_id)
                                                            <li wire:click="revertEntry({{ $entry->id }})">
                                                                <span
                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                    <iconify-icon icon="grommet-icons:revert"></iconify-icon>
                                                                    <span>Revert Entry</span></span>
                                                            </li>
                                                        @endif
                                                    @endcan
                                                    @can('view', $entry)
                                                        @if ($entry->credit_doc_url)
                                                            <li wire:click="downloadCreditDoc({{ $entry->id }})">
                                                                <span
                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                    <iconify-icon icon="material-symbols:download"></iconify-icon>
                                                                    <span>Download credit document</span></span>
                                                            </li>
                                                        @endif
                                                        @if ($entry->debit_doc_url)
                                                            <li wire:click="downloadDebitDoc({{ $entry->id }})">
                                                                <span
                                                                    class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                    <iconify-icon icon="material-symbols:download"></iconify-icon>
                                                                    <span>Download debit document</span></span>
                                                            </li>
                                                        @endif
                                                    @endcan
                                                </ul>
                                            </div>
                                        </td>

                                    </tr>
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

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    @can('viewAny', \App\Models\Accounting\JournalEntry::class)
        @if ($isOpenFilterAccountModal)
            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
                <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
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
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @endif
    @endcan

    @can('viewAny', \App\Models\Accounting\JournalEntry::class)
        @if ($entryId)
            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
                <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
                    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                        <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                    #{{ $entryInfo->id }} • {{ $entryInfo->day_serial }}
                                </h3>

                                <button wire:click="closeShowInfo" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                                                                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6 space-y-4">
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Comment</p>
                                    <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                                        {{ $entryInfo->comment ?? 'No comment added.' }}
                                    </div>
                                </div>

                                @if ($entryInfo->cash_entry_type)
                                    <div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $entryInfo->cash_entry_type }}</p>
                                        <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                                            <b>{{ $entryInfo->receiver_name ?? 'No comment added.' }}</b>
                                        </div>
                                    </div>
                                @endif

                                <hr>

                                <div class="flex justify-end">
                                    <div class="mr-6">
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 text-right">Approver</p>
                                        <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                                            <b>{{ $entryInfo->approver->full_name ?? 'Not approved yet.' }}</b>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 text-right">Creator</p>
                                        <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                                            <b>{{ $entryInfo->creator->full_name }}</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @endif
    @endcan
</div>