<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Accounts
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            {{-- @can('create', \App\Models\Accounting\AccountType::class) --}}
            <button wire:click="openAddNewModal" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                New Account
            </button>
            {{-- @endcan --}}
        </div>
    </div>

    <div class="flex">
        <div class="dropdown relative mb-3 mr-2">
            <button class="btn btn-sm inline-flex justify-center btn-dark items-center" type="button" id="darkDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Nature: {{ $account_nature ?? 'All' }}
                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
            </button>
            <ul class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                        z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                <li wire:click="updateNature(null)">
                    <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                            dark:hover:text-white">
                        All
                    </a>
                </li>
                @foreach ($natures as $nature)
                    <li wire:click="updateNature('{{ $nature }}')">
                        <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white">
                            {{ ucwords($nature) }}
                        </a>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>

    <div class="mb-5">
        <iconify-icon wire:loading wire:target="searchText" class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
        <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search..." wire:model="searchText">
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
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class=" table-th ">
                                        Code
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Desc
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Balance
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Foreign Balance
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Nature
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Main Account
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Default Currency
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 no-wrap">

                                @foreach ($accounts as $account)
                                    @include('livewire.components.account-row', ['account' => $account, 'level' => 0])
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @foreach ($accounts as $account)
        <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base mb-3">
            <div class="card-body flex flex-col p-6 active">
                <div class="card-text h-full menu-open active">
                    <div class="flex justify-between mb-4">
                        <div>
                            <h6 class="text-slate-900 dark:text-white mb-3">
                                <b>{{ $account->name }}</b>
                            </h6>
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
                        <div>
                            <p class="text-right">Balance</p>
                            <h5 class="text-slate-900 dark:text-white">
                                <b>
                                    {{ number_format($account->balance, 2) }}
                                </b>
                            </h5>
                        </div>

                    </div>
                    <div class="card-text mt-4 menu-open active">
                        <p>
                            {{ $account->desc ?? 'No description available.' }}
                        </p>
                        <div class="mt-6 flex justify-between menu-open">

                            <div class="flex space-x-4 rtl:space-x-reverse">
                                <a href="/entries?AccountId={{ $account->id }}">
                                    <button class="btn btn-sm inline-flex items-center justify-center btn-outline-dark">
                                        <iconify-icon class="nav-icon" icon="hugeicons:view"></iconify-icon>
                                        <span>&nbsp;View Entries</span>
                                    </button>
                                </a>
                                <button wire:click="openEditModal({{ $account->id }})" class="btn btn-sm inline-flex items-center justify-center btn-outline-dark">
                                    <iconify-icon class="nav-icon" icon="lucide:edit"></iconify-icon>
                                    <span>&nbsp;Edit info</span>
                                </button>
                            </div>

                            <a href="account/{{ $account->id }}" class="inline-flex leading-5 text-slate-500 dark:text-slate-400 text-sm font-normal active">
                                <iconify-icon class="text-secondary-500 ltr:mr-2 rtl:ml-2 text-lg" icon="heroicons-outline:calendar"></iconify-icon>
                                Created at: {{ $account->created_at->format('d/m/Y') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}


    @if ($isAddNewModalOpen || $accountID)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                @if ($isAddNewModalOpen)
                                    Add New Account
                                @else
                                    Edit Account
                                @endif

                            </h3>

                            <button @if ($isAddNewModalOpen) wire:click="closeAddNewModal" @else wire:click="closeEditModal" @endif type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="form-group mb-5">
                                <label for="acc_code" class="form-label">Code</label>
                                <input type="text" id="acc_code" class="form-control mt-2 w-full {{ $errors->has('acc_code') ? '!border-danger-500' : '' }}" wire:model.defer="acc_code" max="100">
                                @error('acc_code')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="acc_name" class="form-label">Name</label>
                                <input type="text" id="acc_name" class="form-control mt-2 w-full {{ $errors->has('acc_name') ? '!border-danger-500' : '' }}" wire:model.defer="acc_name" max="100">
                                @error('acc_name')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="acc_desc" class="form-label">Description</label>
                                <textarea id="acc_desc" class="form-control mt-2 w-full {{ $errors->has('acc_desc') ? '!border-danger-500' : '' }}" wire:model.defer="acc_desc"></textarea>
                                @error('acc_desc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="nature" class="form-label">Nature</label>
                                <select id="nature" class="form-control mt-2 w-full {{ $errors->has('nature') ? '!border-danger-500' : '' }}" wire:model.defer="nature">
                                    <option value="">Select Nature</option>
                                    @foreach ($natures as $nature)
                                        <option value="{{ $nature }}">{{ ucwords($nature) }}</option>
                                    @endforeach
                                </select>
                                @error('nature')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-5">
                                <label for="mainAccountId" class="form-label">Main Account</label>
                                <select id="mainAccountId" class="form-control mt-2 w-full {{ $errors->has('mainAccountId') ? '!border-danger-500' : '' }}" wire:model="mainAccountId">
                                    <option value="">Select Type</option>
                                    @foreach ($main_accounts as $main_account)
                                        <option value="{{ $main_account->id }}">{{ ucwords($main_account->name) . ' • ' . ucwords($main_account->type) }}</option>
                                    @endforeach
                                </select>
                                @error('mainAccountId')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            @inject('helper', 'App\Helpers\Helpers')
                            @php
                                $accounts_printed_arr = [];
                            @endphp
                            @if ($mainAccountId)
                                <div class="form-group mb-5">
                                    <label for="parent_account_id" class="form-label">Parent Account</label>
                                    <select class="form-control mt-2 w-full {{ $errors->has('parent_account_id') ? '!border-danger-500' : '' }}" wire:model.defer="parent_account_id">
                                        <option value="">Select Type</option>
                                        {{-- @foreach ($filteredAccounts as $filteredAccount)
                                            <option value="{{ $filteredAccount->id }}">{{ ucwords($filteredAccount->name) . ucwords($filteredAccount->desc ? ' • ' . $filteredAccount->desc : '') }}</option>
                                        @endforeach --}}
                                        @if ($filteredAccounts)
                                            @foreach ($filteredAccounts as $account)
                                                {{ $helper->printAccountChildren('', $account, $accounts_printed_arr) }}
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('parent_account_id')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            <div class="form-group mb-5">
                                <label for="acc_desc" class="form-label">Desciption</label>
                                <input type="number" id="acc_desc" class="form-control mt-2 w-full {{ $errors->has('acc_desc') ? '!border-danger-500' : '' }}" wire:model.defer="acc_desc" step="0.01">
                                @error('acc_desc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            @if ($isAddNewModalOpen)
                                <button wire:click="save" class="btn inline-flex justify-center text-white bg-black-500">
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="save" icon="line-md:loading-twotone-loop"></iconify-icon>
                                    <span wire:loading.remove="save">Submit</span>
                                </button>
                            @else
                                <button wire:click="saveEdit" class="btn inline-flex justify-center text-white bg-black-500">
                                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="saveEdit" icon="line-md:loading-twotone-loop"></iconify-icon>
                                    <span wire:loading.remove="saveEdit">Submit</span>
                                </button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


</div>
