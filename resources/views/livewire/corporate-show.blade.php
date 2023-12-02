<div>
    <div class="flex justify-center">
        <div class="max-w-screen-lg">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                <div class="flex-1 rounded-md overlay max-w-[520px] min-w-\[var\(500px\)\]" style="min-width: 400px;">

                    <div class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <b>{{ $corporate->name }}</b>
                    </div>

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
                                        <button wire:click="deleteThisAddress({{ $corporate->id }})" class="action-btn float-right" type="button">
                                            <iconify-icon icon="heroicons:trash"></iconify-icon>
                                        </button>
                                        <button wire:click="editThisAddress({{ $corporate->id }})" class="action-btn float-right mr-1" type="button">
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

                            <button wire:click="toggleAddContact" class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add Bank Account</button>

                        </div>
                    </div>

                </div>

                <div class="flex-1 rounded-md overlay  max-w-[400px] min-w-[310px]">
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
</div>
