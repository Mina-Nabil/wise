<div>
    <div class="card">
        <div class="card-body">
            <div class="card-text h-full">
                <header class="border-b px-4 pt-4 pb-3 flex items-center border-primary-500">
                    <iconify-icon class="text-3xl inline-block ltr:mr-2 rtl:ml-2 text-primary-500" icon="fluent:quiz-new-20-regular"></iconify-icon>
                    <h3 class="card-title mb-0 text-primary-500">New Journal Entry</h3>
                </header>
                <div class="container-fluid  p-6">
                    <div class="from-group">
                        <h4 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">

                            <div class="input-area">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" id="title" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('title') ? '!border-danger-500' : 'border-gray-300' }}" wire:model="title" maxlength="100">
                                @error('title')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-area">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" id="amount" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('amount') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="amount" step="0.01">
                                @error('amount')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            @if ($entry_titles)
                                <div class="text-sm mb-4 col-span-2">
                                    @foreach ($entry_titles as $entry_title)
                                        <p class="flex items-center">
                                            <iconify-icon icon="fluent:rename-24-filled" class="mr-2"></iconify-icon>
                                            {{ $entry_title->name }}
                                            <span wire:click="selectTitle('{{ $entry_title->name }}')" class="cursor-pointer text-primary-500 ml-2">Select title</span>
                                        </p>
                                    @endforeach
                                </div>
                            @endif

                            <div class="input-area">
                                <label for="cash_entry_type" class="form-label">Cash entry type</label>
                                <select id="cash_entry_type" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('cash_entry_type') ? '!border-danger-500' : 'border-gray-300' }}" wire:model="cash_entry_type">
                                    <option value="">N/A</option>
                                    @foreach ($CASH_ENTRY_TYPES as $CASH_ENTRY_TYPE)
                                        <option value="{{ $CASH_ENTRY_TYPE }}">{{ ucwords($CASH_ENTRY_TYPE) }}</option>
                                    @endforeach
                                </select>
                                @error('cash_entry_type')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-between items-end space-x-6">
                                <div class="input-area w-full">
                                    <label for="receiver_name" class="form-label">Receiver name</label>
                                    <input type="text" id="receiver_name" @if (!$cash_entry_type) disabled @endif class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('receiver_name') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="receiver_name" step="0.01">
                                    @error('receiver_name')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>



                    <!-- Second Column: Debit and Credit Accounts with Documents -->
                    <div class="my-5">
                        <div class="">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class=" card-body rounded-md bg-[#E5F9FF] dark:bg-slate-800 shadow-base menu-open p-5">
                                    <h4 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Debit Account</h4>
                                    <div class="mb-4">
                                        <label for="debit_id" class="block text-gray-700 dark:text-gray-300">Account</label>
                                        <select id="debit_id" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('debit_id') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="debit_id">
                                            <option value="">Select Debit Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('debit_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="debit_doc_url" class="block text-gray-700 dark:text-gray-300">Document</label>
                                        <input type="file" id="debit_doc_url" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('debit_doc_url') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="debit_doc_url">
                                        @error('debit_doc_url')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>



                                <div class=" card-body rounded-md bg-[#E5F9FF] dark:bg-slate-800 shadow-base menu-open p-5">
                                    <h4 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Credit Account</h4>
                                    <div class="mb-4">
                                        <label for="credit_id" class="block text-gray-700 dark:text-gray-300">Account</label>
                                        <select id="credit_id" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('credit_id') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="credit_id">
                                            <option value="">Select Credit Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('credit_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="credit_doc_url" class="block text-gray-700 dark:text-gray-300">Document</label>
                                        <input type="file" id="credit_doc_url" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('credit_doc_url') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="credit_doc_url">
                                        @error('credit_doc_url')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="from-group mt-5">
                        <h4 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Currency</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Third Column: Currency Data and Notes -->
                            <div class="mb-4">
                                <label for="currency" class="block text-gray-700 dark:text-gray-300">Currency</label>
                                <select id="currency" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('currency') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="currency">
                                    <option value="">Select Currency</option>
                                    @foreach ($CURRENCIES as $CURRENCY)
                                        <iconify-icon icon="openmoji:flag-egypt" width="1.2em" height="1.2em"></iconify-icon>
                                        <option value="{{ $CURRENCY }}">{{ ucwords($CURRENCY) }}</option>
                                    @endforeach
                                </select>
                                @error('currency')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="currency_amount" class="block text-gray-700 dark:text-gray-300">Currency Amount</label>
                                <input type="number" id="currency_amount" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('currency_amount') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="currency_amount" step="0.01">
                                @error('currency_amount')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="currency_rate" class="block text-gray-700 dark:text-gray-300">Currency Rate</label>
                                <input type="number" id="currency_rate" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('currency_rate') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="currency_rate" step="0.01">
                                @error('currency_rate')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="notes" class="block text-gray-700 dark:text-gray-300">Notes</label>
                            <textarea id="notes" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('notes') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="notes" rows="6"></textarea>
                            @error('notes')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6">
                        <button wire:click="save" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                            <span wire:loading.remove wire:target="save">Submit</span>
                            <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading wire:target="save" icon="line-md:loading-twotone-loop"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
