<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Corporates
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <button wire:click="toggleAddLead" class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Lead
            </button>
            <button wire:click="toggleAddCorporate" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Corporate
            </button>
        </div>
    </div>


    <div class="card">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search by name, email or phone number" wire:model="search">
        </header>

        <div class="card-body px-6 pb-6">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Corporate Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Type
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Phone
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Email
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Commercial Record
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($corporates as $corporate)
                                    <tr wire:click="redirectToShowPage({{ $corporate->id }})" class="hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">

                                        <td class="table-td ">
                                            <b>{{ $corporate->name }}</b>
                                        </td>

                                        <td class="table-td ">
                                            {{ $corporate->type }}
                                        </td>

                                        <td class="table-td ">
                                            @foreach ($corporate->phones->take(1) as $phones)
                                                {{ $phones->number }}
                                            @endforeach

                                        </td>

                                        <td class="table-td ">
                                            {{ $corporate->email ?? 'N/A' }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $corporate->commercial_record }}
                                        </td>


                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @if ($corporates->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Corporates with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/corporates') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all corporates</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif

                    </div>



                    {{ $corporates->links('vendor.livewire.bootstrap') }}

                </div>
            </div>
        </div>
    </div>




    @if ($addCorporateSection)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
        <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Corporate
                            </h3>
                            <button wire:click="toggleAddCorporate" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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

                                <div class="input-area">
                                    <label for="note" class="form-label mt-3">Note</label>
                                    <input id="note" type="text" class="form-control @error('note') !border-danger-500 @enderror" wire:model.defer="note">
                                </div>
                                @error('note')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="ownerId" class="form-label">Owner</label>
                                    <select name="ownerId" id="ownerId" class="form-control w-full mt-2 @error('ownerId') !border-danger-500 @enderror" wire:model.defer="ownerId">
                                        <option>None</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('ownerId')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <hr class="mt-5">
                            <div class="from-group">
                                <p class="text-lg mt-3"><b>Followup</b></p>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">

                                    <div class="input-area">
                                        <label for="followupCallDateTime" class="form-label">Call Date Time</label>
                                        <input id="followupCallDateTime" type="datetime-local" class="form-control @error('followupCallDateTime') !border-danger-500 @enderror" wire:model.defer="followupCallDateTime">
                                    </div>




                                    @error('followupCallDateTime')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror




                                </div>
                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addCorporate" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($addLeadSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Corporate
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

                                <div class="input-area">
                                    <label for="name" class="form-label">Name</label>
                                    <input id="name" type="text" class="form-control @error('name') !border-danger-500 @enderror" wire:model.defer="name">
                                </div>
                                @error('name')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area">
                                    <label for="LeadNote" class="form-label">Note</label>
                                    <input id="LeadNote" type="text" class="form-control @error('LeadNote') !border-danger-500 @enderror" wire:model.defer="LeadNote">
                                </div>
                                @error('LeadNote')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="ownerId" class="form-label">Owner</label>
                                    <select name="ownerId" id="ownerId" class="form-control w-full mt-2 @error('ownerId') !border-danger-500 @enderror" wire:model.defer="ownerId">
                                        <option>None</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('ownerId')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror


                                <hr class="mt-5">
                                <div class="from-group">
                                    <p class="text-lg mt-3"><b>Followup</b></p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">

                                        <div class="input-area">
                                            <label for="followupCallDateTime" class="form-label">Call Date Time</label>
                                            <input id="followupCallDateTime" type="datetime-local" class="form-control @error('followupCallDateTime') !border-danger-500 @enderror" wire:model.defer="followupCallDateTime">
                                        </div>
                                        @error('followupCallDateTime')
                                            <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                {{-- @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach --}}

                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addLead" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


</div>
