<div>
    <div class="flex justify-center">
        <div class="max-w-screen-lg">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                <div class="flex-1 rounded-md overlay max-w-[520px] min-w-\[var\(500px\)\]" style="min-width: 400px;">

                    {{-- Name section --}}
                    <div class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <b>{{ $corporate->name }}</b>
                    </div>

                    {{-- addresses section --}}
                    <div class="card-body flex flex-col justify-center bg-cover card p-4 mt-5">
                        <div class="card-text flex flex-col justify-between  menu-open">
                            <p>
                                <b>Addresses</b>
                            </p>
                            <br>
                            @if ($corporate->addresses->isEmpty())
                                <p class="text-center m-5 text-primary">No addresses added to this corporate.</p>
                            @else
                                @foreach ($corporate->addresses as $address)
                                    <p><b>Address {{ $loop->index + 1 }}</b>
                                        <button wire:click="deleteThisAddress({{ $address->id }})" class="action-btn float-right" type="button">
                                            <iconify-icon icon="heroicons:trash"></iconify-icon>
                                        </button>
                                        <button wire:click="editThisAddress({{ $address->id }})" class="action-btn float-right mr-1" type="button">
                                            <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                        </button>
                                    </p>
                                    <p>{{ $address->line_1 ?? 'N/A' }}</p>
                                    <p>{{ $address->line_2 ?? 'N/A' }}</p>
                                    <p>Flat: {{ $address->flat ?? 'N/A' }}, Building: {{ $address->building ?? 'N/A' }}</p>
                                    <p>{{ $address->city ?? 'N/A' }} , {{ $address->country ?? 'N/A' }}</p>
                                    <br>
                                @endforeach
                            @endif
                            <button wire:click="toggleAddAddress" class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add address</button>
                        </div>
                    </div>

                    {{-- bank accounts section --}}
                    <div class="card-body flex flex-col justify-center mt-5  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p class="mb-2">
                                <b>Bank Accounts</b>
                            </p>

                            @if ($corporate->bank_accounts->isEmpty())
                                <p class="text-center m-5 text-primary">No bank accounts added to this corporate.</p>
                            @else
                                @foreach ($corporate->bank_accounts as $account)
                                    <p><b> {{ $account->bank_name }}</b>
                                        <button wire:click="deleteThisBankAccount({{ $account->id }})" class="action-btn float-right" type="button">
                                            <iconify-icon icon="heroicons:trash"></iconify-icon>
                                        </button>
                                        <button wire:click="editThisBankAccount({{ $account->id }})" class="action-btn float-right mr-1" type="button">
                                            <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                        </button>
                                    </p>

                                    <p>{{ $account->type }}</p>
                                    <p>{{ $account->account_number }}</p>
                                    <p>{{ $account->owner_name }}</p>
                                    <p>{{ $account->bank_branch }}</p>
                                    <p>{{ $account->iban }}</p>
                                    <p>{{ $account->evidence_doc }}</p>
                                    <br>
                                @endforeach
                            @endif

                            <button wire:click="toggleAddBankAccount" class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add Bank Account</button>

                        </div>
                    </div>

                    {{-- contacts section --}}
                    <div class="card-body flex flex-col justify-center mt-5  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p class="mb-2">
                                <b>Contact</b>
                            </p>

                            @if ($corporate->contacts->isEmpty())
                                <p class="text-center m-5 text-primary">No bank accounts added to this corporate.</p>
                            @else
                                @foreach ($corporate->contacts as $contact)
                                    <p><b> {{ $contact->name }}</b>
                                        <button wire:click="deleteThisContact({{ $contact->id }})" class="action-btn float-right" type="button">
                                            <iconify-icon icon="heroicons:trash"></iconify-icon>
                                        </button>
                                        <button wire:click="editThisContact({{ $contact->id }})" class="action-btn float-right mr-1" type="button">
                                            <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                        </button>
                                    </p>

                                    <p>{{ $contact->phone ?? 'No Phone for this contact.' }}</p>
                                    <p>{{ $contact->job_title ?? 'No job.' }} | {{ $contact->email ?? 'N/A' }}</p>

                                    <br>
                                @endforeach
                            @endif

                            <button wire:click="toggleAddContact" class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add Contact</button>

                        </div>
                    </div>

                </div>

                <div class="flex-1 rounded-md overlay  max-w-[400px] min-w-[310px]">

                    {{-- Corporate info section --}}
                    <div class="card-body  flex flex-col justify-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p>
                                <b>Corporate</b>
                                <span class="float-right cursor-pointer text-slate-500" wire:click="toggleEditCorporate">
                                    <iconify-icon icon="material-symbols:edit-outline"></iconify-icon>
                                </span>
                            </p>
                            <br>

                            <p><b>Name</b></p>
                            <p>{{ $corporate->name ?? 'N/A' }} | {{ $corporate->arabic_name ?? 'N/A' }}</p>
                            <br>

                            <p><b>Email</b></p>
                            <a>{{ $corporate->email ?? 'N/A' }}</a>
                            <br>

                            <p><b>Commercial Record</b></p>
                            <p>{{ $corporate->commercial_record ?? 'N/A' }} | {{ $corporate->commercial_record_doc ?? 'N/A' }}</p>
                            <br>

                            <p><b>Tax</b></p>
                            <p>{{ $corporate->tax_id ?? 'N/A' }} | {{ $corporate->tax_id_doc ?? 'N/A' }}</p>
                            <br>

                            <p><b>KYC</b></p>
                            <p>{{ $corporate->kyc ?? 'N/A' }} | {{ $corporate->kyc_doc ?? 'N/A' }}</p>
                            <br>

                            <p><b>Contract Doc</b></p>
                            <p>{{ $corporate->contract_doc ?? 'N/A' }}</p>
                            <br>

                            <p><b>Main Bank Bvidence</b></p>
                            <p>{{ $corporate->main_bank_evidence ?? 'N/A' }}</p>
                            <br>

                        </div>
                    </div>

                    {{-- Phones Section --}}
                    <div class="card-body flex flex-col justify-center bg-cover card p-4 mt-5">
                        <div class="card-text flex flex-col justify-between  menu-open">
                            <p>
                                <b>Phones</b>
                            </p>
                            <br>

                            @if ($corporate->phones->isEmpty())
                                <p class="text-center m-5 text-primary">No cars Phones to this customer.</p>
                            @else
                                @foreach ($corporate->phones as $phone)
                                    <div class="flex items-center ">
                                        @if ($phone->is_default)
                                            <iconify-icon class="text-primary" icon="material-symbols:star"></iconify-icon>
                                        @endif
                                        <b class="mr-auto">{{ ucfirst($phone->type) }}</b>


                                        <div class="ml-auto">
                                            <div class="relative">
                                                <div class="dropdown relative">
                                                    <button class="text-xl text-center block w-full " type="button" id="tableDropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                    </button>
                                                    <ul
                                                        class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700
                                    shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">

                                                        <li>
                                                            <button wire:click="setPhoneAsDefault({{ $phone->id }})"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                        dark:hover:text-white">
                                                                Set as primary</button>
                                                        </li>
                                                        <li>
                                                            <button wire:click="editThisPhone({{ $phone->id }})"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white">
                                                                Edit</button>
                                                        </li>
                                                        <li>
                                                            <button wire:click="deleteThisPhone({{ $phone->id }})"
                                                                class="text-slate-600 dark:text-white block font-Inter text-left font-normal w-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                        dark:hover:text-white">
                                                                Delete</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <p>{{ $phone->number }}</p>
                                    <br>
                                @endforeach
                            @endif


                        </div>
                        <button wire:click="toggleAddPhone" class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add Phone</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($deleteAddressId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Address
                            </h3>
                            <button wire:click="closeDeleteAddress" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure ! you Want to delete this addres ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteAddress" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deletePhoneId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Phone
                            </h3>
                            <button wire:click="closeDeletePhone" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure ! you Want to delete this phone ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deletePhone" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteContactId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Contact
                            </h3>
                            <button wire:click="closeDeleteContact" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure ! you Want to delete this contact ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteContact" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if ($deleteBankAccountId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Bank Account
                            </h3>
                            <button wire:click="closeDeleteBankAccount" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure ! you Want to delete this bank account ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteBankAccount" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editAddressId)
        {{-- add address section --}}
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Address
                            </h3>
                            <button wire:click="closeEditAddress" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <p class="text-lg"><b>Address info</b></p>
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Address Type</label>
                                    <select name="basicSelect" class="form-control w-full mt-2 @error('type') !border-danger-500 @enderror" wire:model="type">
                                        @foreach ($addressTypes as $type)
                                            <option value="{{ $type }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('type')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Line 1</label>
                                    <input id="lastName" type="text" class="form-control @error('line1') !border-danger-500 @enderror" wire:model="line1">
                                </div>
                                @error('line1')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Line 2</label>
                                    <input id="lastName" type="text" class="form-control @error('line2') !border-danger-500 @enderror" wire:model="line2">
                                </div>
                                @error('line2')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Flat</label>
                                        <input id="lastName" type="text" class="form-control @error('flat') !border-danger-500 @enderror" wire:model="flat">
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Building</label>
                                        <input id="lastName" type="text" class="form-control @error('building') !border-danger-500 @enderror" wire:model="building">
                                    </div>

                                    <div class="input-area">
                                        <label for="lastName" class="form-label">City</label>
                                        <input id="lastName" type="text" class="form-control @error('city') !border-danger-500 @enderror" wire:model="city">
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Country</label>
                                        <input id="lastName" type="text" class="form-control @error('country') !border-danger-500 @enderror" wire:model="country">
                                    </div>
                                </div>
                                @error('flat')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('building')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('city')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('country')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editAddress" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if ($addAddressSection)
        {{-- add address section --}}
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Address
                            </h3>
                            <button wire:click="toggleAddAddress" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <p class="text-lg"><b>Address info</b></p>
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Address Type</label>
                                    <select name="basicSelect" class="form-control w-full mt-2 @error('type') !border-danger-500 @enderror" wire:model="type">
                                        @foreach ($addressTypes as $type)
                                            <option value="{{ $type }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('type')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Line 1</label>
                                    <input id="lastName" type="text" class="form-control @error('line1') !border-danger-500 @enderror" wire:model="line1">
                                </div>
                                @error('line1')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Line 2</label>
                                    <input id="lastName" type="text" class="form-control @error('line2') !border-danger-500 @enderror" wire:model="line2">
                                </div>
                                @error('line2')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Flat</label>
                                        <input id="lastName" type="text" class="form-control @error('flat') !border-danger-500 @enderror" wire:model="flat">
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Building</label>
                                        <input id="lastName" type="text" class="form-control @error('building') !border-danger-500 @enderror" wire:model="building">
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">City</label>
                                        <input id="lastName" type="text" class="form-control @error('city') !border-danger-500 @enderror" wire:model="city">
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Country</label>
                                        <input id="lastName" type="text" class="form-control @error('country') !border-danger-500 @enderror" wire:model="country">
                                    </div>
                                </div>
                                @error('flat')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('building')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('city')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('country')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addAddress" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($bankAccountId)
        {{-- add address section --}}
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Bank Account
                            </h3>
                            <button wire:click="closeEditBankAccount" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <p class="text-lg"><b>Account info</b></p>
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Account Type</label>
                                    <select name="basicSelect" class="form-control w-full mt-2 @error('accountType') !border-danger-500 @enderror" wire:model="accountType">
                                        @foreach ($bankAccTypes as $type)
                                            <option value="{{ $type }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('accountType')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Bank Name</label>
                                    <input id="lastName" type="text" class="form-control @error('bankName') !border-danger-500 @enderror" wire:model="bankName">
                                </div>
                                @error('bankName')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Account Number</label>
                                    <input id="lastName" type="text" class="form-control @error('accountNumber') !border-danger-500 @enderror" wire:model="accountNumber">
                                </div>
                                @error('accountNumber')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Owner Name</label>
                                        <input id="lastName" type="text" class="form-control @error('ownerName') !border-danger-500 @enderror" wire:model="ownerName">
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Evidence Doc</label>
                                        <input id="lastName" type="text" class="form-control @error('evidenceDoc') !border-danger-500 @enderror" wire:model="evidenceDoc">
                                    </div>

                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Iban</label>
                                        <input id="lastName" type="text" class="form-control @error('iban') !border-danger-500 @enderror" wire:model="iban">
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Bank Branch</label>
                                        <input id="lastName" type="text" class="form-control @error('bankBranch') !border-danger-500 @enderror" wire:model="bankBranch">
                                    </div>
                                </div>
                                @error('ownerName')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('evidenceDoc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('iban')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('bankBranch')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editBankAccount" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($addBankAccountSection)
        {{-- add address section --}}
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Bank Account
                            </h3>
                            <button wire:click="toggleAddBankAccount" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <p class="text-lg"><b>Account info</b></p>
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Account Type</label>
                                    <select name="basicSelect" class="form-control w-full mt-2 @error('accountType') !border-danger-500 @enderror" wire:model="accountType">
                                        @foreach ($bankAccTypes as $type)
                                            <option value="{{ $type }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('accountType')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Bank Name</label>
                                    <input id="lastName" type="text" class="form-control @error('bankName') !border-danger-500 @enderror" wire:model="bankName">
                                </div>
                                @error('bankName')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Account Number</label>
                                    <input id="lastName" type="text" class="form-control @error('accountNumber') !border-danger-500 @enderror" wire:model="accountNumber">
                                </div>
                                @error('accountNumber')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Owner Name</label>
                                        <input id="lastName" type="text" class="form-control @error('ownerName') !border-danger-500 @enderror" wire:model="ownerName">
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Evidence Doc</label>
                                        <input id="lastName" type="text" class="form-control @error('evidenceDoc') !border-danger-500 @enderror" wire:model="evidenceDoc">
                                    </div>

                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Iban</label>
                                        <input id="lastName" type="text" class="form-control @error('iban') !border-danger-500 @enderror" wire:model="iban">
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Bank Branch</label>
                                        <input id="lastName" type="text" class="form-control @error('bankBranch') !border-danger-500 @enderror" wire:model="bankBranch">
                                    </div>
                                </div>
                                @error('ownerName')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('evidenceDoc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('iban')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('bankBranch')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addBankAccount" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($contactId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Contact
                            </h3>
                            <button wire:click="closeEditContact" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Full Name</label>
                                    <input id="lastName" type="text" class="form-control @error('contactName') !border-danger-500 @enderror" wire:model="contactName">
                                </div>
                                @error('contactName')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Job Title</label>
                                    <input id="lastName" type="text" class="form-control @error('jobTitle') !border-danger-500 @enderror" wire:model="jobTitle">
                                </div>
                                @error('jobTitle')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Email</label>
                                        <input id="lastName" type="text" class="form-control @error('contactEmail') !border-danger-500 @enderror" wire:model="contactEmail">
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Phone</label>
                                        <input id="lastName" type="text" class="form-control @error('contactPhone') !border-danger-500 @enderror" wire:model="contactPhone">
                                    </div>
                                </div>
                                @error('contactEmail')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('contactPhone')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editContact" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($addContactSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Contact
                            </h3>
                            <button wire:click="toggleAddContact" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Full Name</label>
                                    <input id="lastName" type="text" class="form-control @error('contactName') !border-danger-500 @enderror" wire:model="contactName">
                                </div>
                                @error('contactName')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Job Title</label>
                                    <input id="lastName" type="text" class="form-control @error('jobTitle') !border-danger-500 @enderror" wire:model="jobTitle">
                                </div>
                                @error('jobTitle')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Email</label>
                                        <input id="lastName" type="text" class="form-control @error('contactEmail') !border-danger-500 @enderror" wire:model="contactEmail">
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Phone</label>
                                        <input id="lastName" type="text" class="form-control @error('contactPhone') !border-danger-500 @enderror" wire:model="contactPhone">
                                    </div>
                                </div>
                                @error('contactEmail')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('contactPhone')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addContact" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($addPhoneSection)
        {{-- add address section --}}
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Phone
                            </h3>
                            <button wire:click="toggleAddPhone" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Phone type</label>
                                        <select name="basicSelect" class="form-control w-full mt-2 @error('phoneType') !border-danger-500 @enderror" wire:model.defer="phoneType">
                                            <option selected>Select an option</option>
                                            @foreach ($phoneTypes as $type)
                                                <option value="{{ $type }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Number</label>
                                        <input id="lastName" type="text" class="form-control @error('number') !border-danger-500 @enderror" wire:model.defer="number">
                                    </div>
                                </div>
                                @error('phoneType')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('number')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addPhone" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($PhoneId)
        {{-- add address section --}}
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Phone
                            </h3>
                            <button wire:click="closeEditPhone" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Phone type</label>
                                        <select name="basicSelect" class="form-control w-full mt-2 @error('phoneType') !border-danger-500 @enderror" wire:model.defer="phoneType">
                                            @foreach ($phoneTypes as $type)
                                                <option value="{{ $type }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Number</label>
                                        <input id="lastName" type="text" class="form-control @error('number') !border-danger-500 @enderror" wire:model.defer="number">
                                    </div>
                                </div>
                                @error('phoneType')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('number')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editPhone" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editCorporateSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Corporate
                            </h3>
                            <button wire:click="toggleEditCorporate" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Name</label>
                                        <input id="lastName" type="text" class="form-control @error('name') !border-danger-500 @enderror" wire:model.defer="name">
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Arabic Name</label>
                                        <input id="lastName" type="text" class="form-control @error('arabicName') !border-danger-500 @enderror" wire:model.defer="arabicName">
                                    </div>
                                </div>
                                @error('name')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('arabicName')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Email</label>
                                    <input id="lastName" type="text" class="form-control @error('email') !border-danger-500 @enderror" wire:model.defer="email">
                                </div>
                                @error('email')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Commercial Record</label>
                                        <input id="lastName" type="text" class="form-control @error('commercialRecord') !border-danger-500 @enderror" wire:model.defer="commercialRecord">
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Commercial Record Doc.</label>
                                        <input id="lastName" type="text" class="form-control @error('commercialRecordDoc') !border-danger-500 @enderror" wire:model.defer="commercialRecordDoc">
                                    </div>
                                </div>
                                @error('commercialRecord')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('commercialRecordDoc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Tax ID</label>
                                        <input id="lastName" type="text" class="form-control @error('taxId') !border-danger-500 @enderror" wire:model.defer="taxId">
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Tax ID Doc.</label>
                                        <input id="lastName" type="text" class="form-control @error('taxIdDoc') !border-danger-500 @enderror" wire:model.defer="taxIdDoc">
                                    </div>
                                </div>
                                @error('taxId')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('taxIdDoc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">KYC</label>
                                        <input id="lastName" type="text" class="form-control @error('kyc') !border-danger-500 @enderror" wire:model.defer="kyc">
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">KYC Doc.</label>
                                        <input id="lastName" type="text" class="form-control @error('kycDoc') !border-danger-500 @enderror" wire:model.defer="kycDoc">
                                    </div>
                                </div>
                                @error('kyc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('kycDoc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Contract Doc.</label>
                                    <input id="lastName" type="text" class="form-control @error('contractDoc') !border-danger-500 @enderror" wire:model.defer="contractDoc">
                                </div>
                                @error('contractDoc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Main Bank Evidence</label>
                                    <input id="lastName" type="text" class="form-control @error('mainBandEvidence') !border-danger-500 @enderror" wire:model.defer="mainBandEvidence">
                                </div>
                                @error('mainBandEvidence')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editCorporate" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
