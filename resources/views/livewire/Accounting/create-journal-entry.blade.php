<div>
    <div class="card">
        <div class="card-body">
            <div class="card-text h-full">
                <header class="border-b px-4 pt-4 pb-3 flex items-center border-primary-500">
                    <iconify-icon class="text-3xl inline-block ltr:mr-2 rtl:ml-2 text-primary-500" icon="fluent:quiz-new-20-regular"></iconify-icon>
                    <h3 class="card-title mb-0 text-primary-500">New Journal Entry</h3>
                </header>
                <div class="container-fluid  p-6">

                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- First Column: Title and Amount -->
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Info</h3>
                                <div class="mb-4">
                                    <label for="title" class="block text-gray-700 dark:text-gray-300">Title</label>
                                    <input type="text" id="title" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('title') ? '!border-danger-500' : 'border-gray-300' }}" wire:model="title" maxlength="100">
                                    @error('title')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="text-sm mb-4">
                                    @if ($entry_titles)
                                        @foreach ($entry_titles as $entry_title)
                                            <p><iconify-icon icon="material-symbols:person"></iconify-icon>
                                                {{ $entry_title->name }} <Span wire:click="selectTitle('{{ $entry_title->name }}')" class="cursor-pointer text-primary-500">Select title</Span>
                                            </p>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="mb-4">
                                    <label for="amount" class="block text-gray-700 dark:text-gray-300">Amount</label>
                                    <input type="number" id="amount" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('amount') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="amount" step="0.01">
                                    @error('amount')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="receiver_name" class="block text-gray-700 dark:text-gray-300">Reciever Name</label>
                                    <input type="text" id="receiver_name" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('receiver_name') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="receiver_name" step="0.01">
                                    @error('receiver_name')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Second Column: Debit and Credit Accounts with Documents -->
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Accounts</h3>

                                <h4 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Debit</h4>
                                <div class="mb-4">
                                    <label for="debit_id" class="block text-gray-700 dark:text-gray-300">Account</label>
                                    <select id="debit_id" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('debit_id') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="debit_id">
                                        <option value="">Select Debit Account</option>
                                        @foreach ($debitAccounts as $account)
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

                                <hr class="m-2"><br>

                                <h4 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Credit</h4>
                                <div class="mb-4">
                                    <label for="credit_id" class="block text-gray-700 dark:text-gray-300">Account</label>
                                    <select id="credit_id" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('credit_id') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="credit_id">
                                        <option value="">Select Credit Account</option>
                                        @foreach ($creditAccounts as $account)
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

                            <!-- Third Column: Currency Data and Notes -->
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Currency Info</h3>
                                <div class="mb-4">
                                    <label for="currency" class="block text-gray-700 dark:text-gray-300">Currency</label>
                                    <select id="currency" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('currency') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="currency">
                                        <option value="">Select Currency</option>
                                        @foreach ($CURRENCIES as $CURRENCY)
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

                                <div class="mb-4">
                                    <label for="notes" class="block text-gray-700 dark:text-gray-300">Notes</label>
                                    <textarea id="notes" class="mt-1 block w-full p-2 border rounded-md {{ $errors->has('notes') ? '!border-danger-500' : 'border-gray-300' }}" wire:model.defer="notes" rows="6"></textarea>
                                    @error('notes')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" class="bg-black-500 hover:bg-black-600 text-white font-semibold py-2 px-4 rounded-md">
                                Submit Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



</div>
