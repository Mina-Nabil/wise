<div>
    <div class="flex">
        <div class="max-w-screen-lg">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">

                {{-- Name section --}}
                <div>
                    <b>{{ $corporate->name }} </b><iconify-icon class="ml-3" style="position: absolute" wire:loading wire:target="changeSection" icon="svg-spinners:180-ring"></iconify-icon>
                </div>

                <div class="card-body flex flex-col col-span-2" wire:ignore>
                    <div class="card-text h-full">
                        <div>
                            <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0" id="tabs-tab" role="tablist">
                                <li class="nav-item" role="presentation" wire:click="changeSection('profile')">
                                    <a href="#tabs-profile-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'profile') active @endif dark:text-slate-300"
                                        id="tabs-profile-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-profile-withIcon" role="tab" aria-controls="tabs-profile-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1" icon="heroicons-outline:user"></iconify-icon>
                                        Profile</a>
                                </li>
                                <li class="nav-item" role="presentation" wire:click="changeSection('followups')">
                                    <a href="#tabs-messages-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'followups') active @endif dark:text-slate-300"
                                        id="tabs-messages-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-messages-withIcon" role="tab" aria-controls="tabs-messages-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1" icon="icon-park-outline:cycle-arrow"></iconify-icon>
                                        Follow Ups</a>
                                </li>
                                <li class="nav-item" role="presentation" wire:click="changeSection('offers')">
                                    <a href="#tabs-messages-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'offers') active @endif dark:text-slate-300"
                                        id="tabs-messages-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-messages-withIcon" role="tab" aria-controls="tabs-messages-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1" icon="ic:outline-local-offer"></iconify-icon>
                                        Offers</a>
                                </li>
                                <li class="nav-item" role="presentation" wire:click="changeSection('tasks')">
                                    <a href="#tabs-messages-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'tasks') active @endif dark:text-slate-300"
                                        id="tabs-messages-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-messages-withIcon" role="tab" aria-controls="tabs-messages-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1" icon="ic:round-add-task"></iconify-icon>
                                        Tasks</a>
                                </li>
                                {{-- <li class="nav-item" role="presentation" wire:click="changeSection('claims')">
                                    <a href="#tabs-messages-withIcon" class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'claims') active @endif dark:text-slate-300" id="tabs-messages-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-messages-withIcon" role="tab" aria-controls="tabs-messages-withIcon"
                                        aria-selected="false">
                                        <iconify-icon class="mr-1" icon="academicons:acclaim"></iconify-icon>
                                        Claims</a>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>

                @if ($section === 'profile')
                    <div class="flex-1 rounded-md overlay max-w-[520px] min-w-\[var\(500px\)\]" style="min-width: 400px;">
                        {{-- Phones Section --}}
                        <div class="card-body flex flex-col justify-center bg-cover card p-4">
                            <div class="card-text flex flex-col justify-between  menu-open">
                                <p>
                                    <b>Phones</b>
                                </p>
                                <br>

                                @if ($corporate->phones->isEmpty())
                                    <p class="text-center m-5 text-primary">No Phones to this customer.</p>
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
                                        <p>{{ $address->area ?? 'N/A' }}, {{ $address->city ?? 'N/A' }}, {{ $address->country ?? 'N/A' }}</p>
                                        <br>
                                    @endforeach
                                @endif
                                <button wire:click="toggleAddAddress" class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add address</button>
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
                                <p>{{ $corporate->commercial_record ?? 'N/A' }}
                                    @if ($corporate->commercial_record_doc)
                                        <span wire:click="downloadDoc('{{ $corporate->commercial_record_doc }}' , 'commercial_record')" class="text-primary-500 cursor-pointer">download document</span>
                                    @endif
                                </p>
                                <br>

                                <p><b>Tax</b></p>
                                <p>{{ $corporate->tax_id ?? 'N/A' }}
                                    @if ($corporate->tax_id_doc)
                                        <span wire:click="downloadDoc('{{ $corporate->tax_id_doc }}' , 'tax_id_doc')" class="text-primary-500 cursor-pointer">download document</span>
                                    @endif
                                </p>
                                <br>

                                <p><b>KYC</b></p>
                                <p>{{ $corporate->kyc ?? 'N/A' }}
                                    @if ($corporate->kyc_doc)
                                        <span wire:click="downloadDoc('{{ $corporate->kyc_doc }}' , 'kyc_doc')" class="text-primary-500 cursor-pointer">download document</span>
                                    @endif
                                </p>
                                <br>

                                <p><b>Contract Doc</b></p>
                                <p>
                                    @if ($corporate->contract_doc)
                                        <span wire:click="downloadDoc('{{ $corporate->contract_doc }}' , 'contract_doc')" class="text-primary-500 cursor-pointer">download document</span>
                                    @endif
                                </p>
                                <br>

                                <p><b>Main Bank Bvidence</b></p>
                                <p>
                                    @if ($corporate->main_bank_evidence)
                                        <span wire:click="downloadDoc('{{ $corporate->main_bank_evidence }}' , 'main_bank_evidence')" class="text-primary-500 cursor-pointer">download document</span>
                                    @endif
                                </p>
                                <br>

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

                        @if ($corporate->owner)
                        <div class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active  mt-5">
                            <div class="card-text flex flex-col justify-between h-full menu-open">
                                <p>
                                    Owned by
                                </p>
                                <p class="text-wrap"><b>{{ $corporate->owner?->first_name }} {{ $corporate->owner?->last_name }}</b></p>
                            </div>
                        </div>
                        @endif


                    </div>
                @endif

                @if ($section === 'followups')
                    {{-- followups --}}
                    <div class="card-body flex flex-col justify-center bg-cover card p-4 mt-5  col-span-2">
                        <div class="card-text flex flex-col justify-between  menu-open">
                            <p>
                                <b>Followups</b>
                            </p>
                            <br>

                            @if ($corporate->followups->isEmpty())
                                <p class="text-center m-5 text-primary">No Followups for this corporate.</p>
                            @else
                                @foreach ($corporate->followups as $followup)
                                    <div class="flex items-center ">
                                        <b class="mr-auto">{{ ucfirst($followup->title) }}</b>

                                        <div class="ml-auto">
                                            <div class="relative flex">
                                                <span class="badge bg-slate-900 text-slate-900 dark:text-slate-200 bg-opacity-30 capitalize ml-auto h-auto">{{ $followup->status }}</span>

                                                <div class="dropdown relative">
                                                    <button class="text-xl text-center block w-full " type="button" id="tableDropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                    </button>
                                                    <ul class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">

                                                        @if ($followup->status === 'new')
                                                            <li>
                                                                <button wire:click="editThisFollowup({{ $followup->id }})" class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Edit</button>
                                                            </li>
                                                            <li>
                                                                <button wire:click="toggleCallerNote('called',{{ $followup->id }})" class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Set as called</button>
                                                            </li>
                                                            <li>
                                                                <button wire:click="toggleCallerNote('cancelled',{{ $followup->id }})" class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Set as cancelled</button>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <button wire:click="deleteThisFollowup({{ $followup->id }})" class="text-slate-600 dark:text-white block font-Inter text-left font-normal w-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                Delete</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p><b>Desc:</b> {{ $followup->desc }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 text-right">{{ $followup->call_time }}</p>
                                    <br>
                                @endforeach
                            @endif
                        </div>


                        <button wire:click="OpenAddFollowupSection" class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add Followup</button>
                    </div>
                @endif

                @if ($section === 'tasks')
                    <div class="flex-1 rounded-md col-span-2">
                        <div class="card-body flex flex-col justify-center bg-cover card  col-span-2">
                            <div class="card-header">
                                <h4 class="card-title">Tasks</h4>
                            </div>
                            <div class="card-body p-6">

                                <!-- BEGIN: Message -->

                                <div>
                                    <ul class="divide-y divide-slate-100 dark:divide-slate-700 -mx-6 -mb-6">
                                        @foreach ($tasks as $task)
                                            <li>
                                                <a class=" text-slate-600 dark:text-slate-300 block w-full px-4 py-3 text-sm mb-2 last:mb-0">
                                                    <div class="flex ltr:text-left rtl:text-right">
                                                        <div class="flex-none ltr:mr-3 rtl:ml-3">
                                                            <div class="h-8 w-8 bg-white dark:bg-slate-700 rounded-full relative">
                                                                <span class="block w-full h-full object-cover text-center text-lg leading-8">
                                                                    {{ strtoupper(substr($task->open_by->first_name, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="text-slate-800 dark:text-slate-300 text-sm font-medium mb-1`">
                                                                {{ $task->open_by->first_name . ' ' . $task->open_by->last_name }}
                                                            </div>
                                                            <div class="text-xs hover:text-[#68768A] font-normal text-slate-600 dark:text-slate-300">
                                                                <b>
                                                                    {{ $task->title }}
                                                                </b>
                                                                @if ($task->status === 'new')
                                                                    <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-primary-500 text-xs">
                                                                        New
                                                                    </div>
                                                                @elseif($task->status === 'assigned')
                                                                    <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-info-500 text-xs">
                                                                        Assigned
                                                                    </div>
                                                                @elseif($task->status === 'in_progress')
                                                                    <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-secondary-500 text-xs">
                                                                        in Progress
                                                                    </div>
                                                                @elseif($task->status === 'pending')
                                                                    <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-warning-500 text-xs">
                                                                        Pending
                                                                    </div>
                                                                @elseif($task->status === 'completed')
                                                                    <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-success-500 text-xs">
                                                                        Completed
                                                                    </div>
                                                                @elseif($task->status === 'closed')
                                                                    <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-black-500 text-xs">
                                                                        Closed
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="text-xs hover:text-[#68768A] font-normal text-slate-600 dark:text-slate-300 text-break">
                                                                {{ $task->desc }}
                                                            </div>
                                                            <div class="text-slate-400 dark:text-slate-400 text-xs mt-1">
                                                                {{ $task->created_at }}
                                                                <span class="float-right"> {{ $task->comments->count() }} <iconify-icon icon="fa6-regular:comment"></iconify-icon></span>
                                                                <span class="float-right mr-3"> {{ $task->files->count() }} <iconify-icon icon="ph:files-bold"></iconify-icon></iconify-icon></span>
                                                            </div>
                                                        </div>



                                                        <div class="flex-0">
                                                            <button wire:click="redirectToTask({{ $task->id }})" class="btn btn-sm inline-flex justify-center btn-light light">view</button>
                                                        </div>

                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                        @if ($tasks->isEmpty())
                                            <li class="p-2">
                                                <div class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500">
                                                    <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                                        <div class="flex-1">
                                                            No tasks related to this customer!
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <!-- END: Message  -->
                            </div>
                        </div>
                    </div>
                    <!-- end message -->
                @endif

                @if ($section === 'offers')
                    @foreach ($offers as $offer)
                        <div class="card">
                            <div class="card-body">
                                <div class="card-text h-full">
                                    <header class="border-b px-4 pt-4 pb-3 flex justify-between border-success-500">
                                        <div class="flex">
                                            <iconify-icon class="text-3xl inline-block ltr:mr-2 rtl:ml-2 text-success-500" icon="ic:outline-local-offer"></iconify-icon>
                                            <h3 class="card-title mb-0 text-success-500">
                                                <ul class="m-0 p-0 list-none">
                                                    <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                        {{ $offer->item->car->car_model->brand->name }}
                                                        <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                    </li>
                                                    <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                        {{ $offer->item->car->car_model->name }}
                                                        <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                    </li>
                                                    <li class="inline-block relative text-sm top-[3px] text-slate-500 font-Inter dark:text-white mr-5">
                                                        {{ $offer->item->car->category }}
                                                    </li>
                                                </ul>
                                            </h3>
                                        </div>

                                        <div>
                                            <button wire:click="redirectToOffer({{ $offer->id }})" class="btn btn-sm inline-flex justify-center btn-light light">view</button>
                                        </div>
                                    </header>
                                    <div class="py-3 px-5">
                                        {{-- <h5 class="card-subtitle">Card Subtitle</h5> --}}
                                        <div class="grid grid-cols-4 mb-4">
                                            <div class="border-r ml-5 col-span-2">
                                                <p><b>Item Details</b></p>
                                                <p class="text-wrap pr-3">{{ $offer->item_title }}312312331231331231231213</p>
                                                <h6>{{ $offer->item_value }} EGP</h6>
                                            </div>
                                            <div class="border-r ml-5">
                                                <p><b>Options</b></p>
                                                <h6>{{ $offer->options->count() }}</h6>
                                            </div>
                                            <div class="ml-5">
                                                <p><b>Documents</b></p>
                                                <p>{{ $offer->files->count() }}</p>
                                            </div>
                                        </div>
                                        <div class="flex justify-between">
                                            <div>
                                                @if ($offer->due)
                                                    <p class="card-text mt-3 text-wrap"><b>Due: </b> {{ $offer->due }}</p>
                                                @endif
                                            </div>

                                            <div class="float-right">
                                                @if ($offer->status === 'new')
                                                    <span class="badge bg-info-500 h-auto">
                                                        <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                    </span>
                                                @elseif(str_contains($offer->status, 'pending'))
                                                    <span class="badge bg-warning-500 h-auto">
                                                        <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                    </span>
                                                @elseif(str_contains($offer->status, 'declined') || str_contains($offer->status, 'cancelled'))
                                                    <span class="badge bg-danger-500 h-auto">
                                                        <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                    </span>
                                                @elseif($offer->status === 'approved')
                                                    <span class="badge bg-success-500 h-auto">
                                                        <iconify-icon icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                    </span>
                                                @endif
                                                <span class="badge bg-secondary-500 h-auto">
                                                    <iconify-icon icon="mdi:category"></iconify-icon>&nbsp;
                                                    {{ ucwords(str_replace('_', ' ', $offer->type)) }}
                                                </span>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($offers->isEmpty())
                        <div class="col-span-2 py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] w-full text-warning-500">
                            <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                <div class="flex-1">
                                    No Offers for this customer!
                                </div>
                            </div>
                        </div>
                    @endif
                @endif









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
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                                        <label for="lastName" class="form-label">Area</label>
                                        <input list="areas" type="text" class="form-control @error('area') !border-danger-500 @enderror" wire:model="area">
                                        <datalist id="areas">
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->name }}"> {{ $area->name }}</option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">City</label>
                                        <input list="cities" type="text" class="form-control @error('city') !border-danger-500 @enderror" wire:model="city">
                                        <datalist id="cities">
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->name }}"> {{ $city->name }}</option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Country</label>
                                        <input list="countries" type="text" class="form-control @error('country') !border-danger-500 @enderror" wire:model="country">
                                        <datalist id="countries">
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->name }}"> {{ $country->name }}</option>
                                            @endforeach
                                        </datalist>
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
                                        <label for="lastName" class="form-label">Area</label>
                                        <input list="areas" type="text" class="form-control @error('area') !border-danger-500 @enderror" wire:model="area">
                                        <datalist id="areas">
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->name }}"> {{ $area->name }}</option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">City</label>
                                        <input list="cities" type="text" class="form-control @error('city') !border-danger-500 @enderror" wire:model="city">
                                        <datalist id="cities">
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->name }}"> {{ $city->name }}</option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Country</label>
                                        <input list="countries" type="text" class="form-control @error('country') !border-danger-500 @enderror" wire:model="country">
                                        <datalist id="countries">
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->name }}"> {{ $country->name }}</option>
                                            @endforeach
                                        </datalist>
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
                                        <label for="lastName" class="form-label">Commercial record document</label>
                                        @if (!$commercialRecordDoc)
                                            <input wire:model.defer="commercialRecordDoc" type="file" class="form-control w-full " name="basic" />
                                        @else
                                            <span class="block min-w-[140px] text-left">
                                                <span class="inline-block text-center text-sm mx-auto py-1">
                                                    <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                        <span class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                        <span>
                                                            Document added
                                                            <span wire:click="clearCommercialRecordDoc" class="text-xs text-slate-500 dark:text-slate-400 mt-1 cursor-pointer">| remove</span>
                                                        </span>
                                                    </span>
                                                </span>
                                            </span>
                                        @endif
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
                                        <label for="lastName" class="form-label">Tax ID document</label>
                                        @if (!$taxIdDoc)
                                            <input wire:model.defer="taxIdDoc" type="file" class="form-control w-full " name="basic" />
                                        @else
                                            <span class="block min-w-[140px] text-left">
                                                <span class="inline-block text-center text-sm mx-auto py-1">
                                                    <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                        <span class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                        <span>
                                                            Document added
                                                            <span wire:click="clearTaxIdDoc" class="text-xs text-slate-500 dark:text-slate-400 mt-1 cursor-pointer">| remove</span>
                                                        </span>
                                                    </span>
                                                </span>
                                            </span>
                                        @endif
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
                                        <label for="lastName" class="form-label">KYC document</label>
                                        @if (!$kycDoc)
                                            <input wire:model.defer="kycDoc" type="file" class="form-control w-full " name="basic" />
                                        @else
                                            <span class="block min-w-[140px] text-left">
                                                <span class="inline-block text-center text-sm mx-auto py-1">
                                                    <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                        <span class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                        <span>
                                                            Document added
                                                            <span wire:click="clearKycDoc" class="text-xs text-slate-500 dark:text-slate-400 mt-1 cursor-pointer">| remove</span>
                                                        </span>
                                                    </span>
                                                </span>
                                            </span>
                                        @endif
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
                                    @if (!$contractDoc)
                                        <input wire:model="contractDoc" type="file" class="form-control w-full " name="basic" />
                                    @else
                                        <span class="block min-w-[140px] text-left">
                                            <span class="inline-block text-center text-sm mx-auto py-1">
                                                <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                    <span class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                    <span>
                                                        Document added
                                                        <span wire:click="clearContractDoc" class="text-xs text-slate-500 dark:text-slate-400 mt-1 cursor-pointer">| remove</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    @endif

                                </div>
                                @error('contractDoc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Main Bank Evidence</label>
                                    @if (!$mainBandEvidence)
                                        <input wire:model.defer="mainBandEvidence" type="file" class="form-control w-full " name="basic" />
                                    @else
                                        <span class="block min-w-[140px] text-left">
                                            <span class="inline-block text-center text-sm mx-auto py-1">
                                                <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                    <span class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                    <span>
                                                        Document added
                                                        <span wire:click="clearMainBandEvidence" class="text-xs text-slate-500 dark:text-slate-400 mt-1 cursor-pointer">| remove</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    @endif
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

    @if ($addFollowupSection)
        {{-- add address section --}}
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Follow up
                            </h3>
                            <button wire:click="closeFollowupSection" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Title</label>
                                    <input id="lastName" type="text" class="form-control @error('followupTitle') !border-danger-500 @enderror" wire:model.defer="followupTitle">
                                </div>
                                @error('followupTitle')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Call Date</label>
                                        <input id="lastName" type="date" class="form-control @error('followupCallDate') !border-danger-500 @enderror" wire:model.defer="followupCallDate">
                                    </div>
                                    @error('followupCallDate')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                    <div class="input-area">
                                        <label for="firstName" class="form-label"> Time</label>
                                        <input id="lastName" type="time" class="form-control @error('followupCallTime') !border-danger-500 @enderror" wire:model.defer="followupCallTime">
                                    </div>
                                    @error('followupCallTime')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Description</label>
                                    <input id="lastName" type="text" class="form-control @error('followupDesc') !border-danger-500 @enderror" wire:model.defer="followupDesc">
                                </div>
                                @error('followupDesc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addFollowup" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($followupId)
        {{-- add address section --}}
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Follow up
                            </h3>
                            <button wire:click="closeEditFollowup" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Title</label>
                                    <input id="lastName" type="text" class="form-control @error('followupTitle') !border-danger-500 @enderror" wire:model.defer="followupTitle">
                                </div>
                                @error('followupTitle')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Call Date</label>
                                        <input id="lastName" type="date" class="form-control @error('followupCallDate') !border-danger-500 @enderror" wire:model.defer="followupCallDate">
                                    </div>
                                    @error('followupCallDate')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                    <div class="input-area">
                                        <label for="firstName" class="form-label"> Time</label>
                                        <input id="lastName" type="time" class="form-control @error('followupCallTime') !border-danger-500 @enderror" wire:model.defer="followupCallTime">
                                    </div>
                                    @error('followupCallTime')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Description</label>
                                    <input id="lastName" type="text" class="form-control @error('followupDesc') !border-danger-500 @enderror" wire:model.defer="followupDesc">
                                </div>
                                @error('followupDesc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="editFollowup" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($deleteFollowupId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Followup
                            </h3>
                            <button wire:click="dismissDeleteFollowup" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
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
                                Are you sure ! you Want to delete this followup ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="deleteFollowup" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($callerNoteSec)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Caller Note
                            </h3>
                            <button wire:click="toggleCallerNote" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Leave a note...</label>
                                    <input id="lastName" type="text" class="form-control @error('followupTitle') !border-danger-500 @enderror" wire:model.defer="note">
                                </div>
                                @error('note')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="submitCallerNote" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
