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
            <button wire:click="toggleAddCorporate"
                class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Corporate
            </button>
        </div>
    </div>


    <div class="card">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4"
                placeholder="Search by name, email or phone number" wire:model="search">
        </header>

        <div class="card-body px-6 pb-6">

            <div class="flex items-center space-x-7 flex-wrap h-[30px]">
                <div class="secondary-radio">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="statusFilter" class="hidden" value="all" wire:model="statusFilter">
                        <span
                            class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                        <span class="text-secondary-500 text-sm leading-6 capitalize">All</span>
                    </label>
                </div>
            </div>
            @foreach ($customerStatus as $cs)
                <div class="flex items-center space-x-7 flex-wrap h-[30px]">
                    <div class="secondary-radio">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="statusFilter"  class="hidden" value="{{ $cs }}" wire:model="statusFilter">
                            <span
                                class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                            <span class="text-secondary-500 text-sm leading-6 capitalize">{{ $cs }}</span>
                        </label>
                    </div>
                </div>
            @endforeach

            <div class="flex items-center space-x-7 flex-wrap h-[30px] mt-4">
                <div class="input-area">
                    <label class="form-label text-sm">Campaign:</label>
                    <select class="form-control form-control-sm" wire:model="campaignFilter">
                        <option value="">All Campaigns</option>
                        @foreach ($campaigns as $campaign)
                            <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead
                                class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Corporate Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Type
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Campaign
                                    </th>

                                    {{-- <th scope="col" class=" table-th ">
                                        Phone
                                    </th> --}}

                                    <th scope="col" class=" table-th ">
                                        Email
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Commercial Record
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Actions
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($corporates as $corporate)
                                    <tr>

                                        <td class="table-td hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer"
                                            wire:click="redirectToShowPage({{ $corporate->id }})">
                                            <b>{{ $corporate->name }}</b>
                                        </td>

                                        <td class="table-td hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer"
                                            wire:click="openChangeStatus({{ $corporate->id }})">
                                            {{ $corporate->type }} - {{ $corporate->status?->status }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $corporate->campaign->name ?? 'N/A' }}
                                        </td>

                                        {{-- <td class="table-td ">
                                            @foreach ($corporate->phones->take(1) as $phones)
                                                {{ $phones->number }}
                                            @endforeach

                                        </td> --}}

                                        <td class="table-td ">
                                            {{ $corporate->email ?? 'N/A' }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $corporate->commercial_record }}
                                        </td>

                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                <button class="action-btn" type="button" 
                                                    wire:click="openInterestManagement({{ $corporate->id }})">
                                                    <iconify-icon icon="heroicons:eye"></iconify-icon>
                                                </button>
                                                <button class="action-btn" type="button" 
                                                    wire:click="openChangeStatus({{ $corporate->id }})">
                                                    <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                                </button>
                                            </div>
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
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Corporates with
                                            the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/corporates') }}"
                                            class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
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

    @if ($changeStatusId)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Update Status
                            </h3>
                            <button wire:click="closeChangeStatus" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">

                            <div class="input-area mb-3">
                                <label for="lastName" class="form-label">Status</label>
                                <select name="basicSelect" id="basicSelect"
                                    class="form-control w-full mt-2 @error('status') !border-danger-500 @enderror"
                                    wire:model="status">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select an option</option>
                                    @foreach ($customerStatus as $s)
                                        <option value="{{ $s }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input-area mb-3">
                                <label class="form-label">Reason</label>
                                <input class="form-control py-2 @error('statusReason') !border-danger-500 @enderror"
                                    id="default-picker" type="text" wire:model.defer="statusReason"
                                    autocomplete="off">
                                @error('statusReason')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area mb-3">
                                <label class="form-label">Note</label>
                                <input class="form-control py-2 @error('statusNote') !border-danger-500 @enderror"
                                    id="default-picker" type="text" wire:model.defer="statusNote" autocomplete="off">
                                @error('statusNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="changeStatus" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($addCorporateSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Corporate
                            </h3>
                            <button wire:click="toggleAddCorporate" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
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
                                        <input id="lastName" type="text"
                                            class="form-control @error('name') !border-danger-500 @enderror"
                                            wire:model.defer="name">
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Arabic Name</label>
                                        <input id="lastName" type="text"
                                            class="form-control @error('arabicName') !border-danger-500 @enderror"
                                            wire:model.defer="arabicName">
                                    </div>
                                </div>
                                @error('name')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('arabicName')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Email</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('email') !border-danger-500 @enderror"
                                        wire:model.defer="email">
                                </div>
                                @error('email')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Commercial Record</label>
                                        <input id="lastName" type="text"
                                            class="form-control @error('commercialRecord') !border-danger-500 @enderror"
                                            wire:model.defer="commercialRecord">
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Commercial Record Doc.</label>
                                        <input id="lastName" type="text"
                                            class="form-control @error('commercialRecordDoc') !border-danger-500 @enderror"
                                            wire:model.defer="commercialRecordDoc">
                                    </div>
                                </div>
                                @error('commercialRecord')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('commercialRecordDoc')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Tax ID</label>
                                        <input id="lastName" type="text"
                                            class="form-control @error('taxId') !border-danger-500 @enderror"
                                            wire:model.defer="taxId">
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">Tax ID Doc.</label>
                                        <input id="lastName" type="text"
                                            class="form-control @error('taxIdDoc') !border-danger-500 @enderror"
                                            wire:model.defer="taxIdDoc">
                                    </div>
                                </div>
                                @error('taxId')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('taxIdDoc')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">KYC</label>
                                        <input id="lastName" type="text"
                                            class="form-control @error('kyc') !border-danger-500 @enderror"
                                            wire:model.defer="kyc">
                                    </div>
                                    <div class="input-area">
                                        <label for="firstName" class="form-label">KYC Doc.</label>
                                        <input id="lastName" type="text"
                                            class="form-control @error('kycDoc') !border-danger-500 @enderror"
                                            wire:model.defer="kycDoc">
                                    </div>
                                </div>
                                @error('kyc')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                @error('kycDoc')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Contract Doc.</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('contractDoc') !border-danger-500 @enderror"
                                        wire:model.defer="contractDoc">
                                </div>
                                @error('contractDoc')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="firstName" class="form-label">Main Bank Evidence</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('mainBandEvidence') !border-danger-500 @enderror"
                                        wire:model.defer="mainBandEvidence">
                                </div>
                                @error('mainBandEvidence')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area">
                                    <label for="note" class="form-label mt-3">Note</label>
                                    <input id="note" type="text"
                                        class="form-control @error('note') !border-danger-500 @enderror"
                                        wire:model.defer="note">
                                </div>
                                @error('note')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="ownerId" class="form-label">Owner</label>
                                    <select name="ownerId" id="ownerId"
                                        class="form-control w-full mt-2 @error('ownerId') !border-danger-500 @enderror"
                                        wire:model.defer="ownerId">
                                        <option>None</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }}
                                                {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('ownerId')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="campaignId" class="form-label">Campaign</label>
                                    <select name="campaignId" id="campaignId"
                                        class="form-control w-full mt-2 @error('campaignId') !border-danger-500 @enderror"
                                        wire:model.defer="campaignId">
                                        <option value="">None</option>
                                        @foreach ($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('campaignId')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="channel" class="form-label">Channel</label>
                                    <input id="channel" type="text"
                                        class="form-control w-full mt-2 @error('channel') !border-danger-500 @enderror"
                                        wire:model.defer="channel" placeholder="Enter channel">
                                </div>
                                @error('channel')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <hr class="mt-5">
                            <div class="from-group">
                                <p class="text-lg mt-3"><b>Followup</b></p>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">

                                    <div class="input-area">
                                        <label for="followupCallDateTime" class="form-label">Call Date Time</label>
                                        <input id="followupCallDateTime" type="datetime-local"
                                            class="form-control @error('followupCallDateTime') !border-danger-500 @enderror"
                                            wire:model.defer="followupCallDateTime">
                                    </div>




                                    @error('followupCallDateTime')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror




                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addCorporate" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($addLeadSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Corporate
                            </h3>
                            <button wire:click="toggleAddLead" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">

                                <div class="input-area">
                                    <label for="name" class="form-label">Name</label>
                                    <input id="name" type="text"
                                        class="form-control @error('name') !border-danger-500 @enderror"
                                        wire:model.defer="name">
                                </div>
                                @error('name')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area">
                                    <label for="LeadNote" class="form-label">Note</label>
                                    <input id="LeadNote" type="text"
                                        class="form-control @error('LeadNote') !border-danger-500 @enderror"
                                        wire:model.defer="LeadNote">
                                </div>
                                @error('LeadNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="ownerId" class="form-label">Owner</label>
                                    <select name="ownerId" id="ownerId"
                                        class="form-control w-full mt-2 @error('ownerId') !border-danger-500 @enderror"
                                        wire:model.defer="ownerId">
                                        <option>None</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }}
                                                {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('ownerId')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="leadCampaignId" class="form-label">Campaign</label>
                                    <select name="leadCampaignId" id="leadCampaignId"
                                        class="form-control w-full mt-2 @error('leadCampaignId') !border-danger-500 @enderror"
                                        wire:model.defer="leadCampaignId">
                                        <option value="">None</option>
                                        @foreach ($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('leadCampaignId')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <div class="input-area mt-3">
                                    <label for="leadChannel" class="form-label">Channel</label>
                                    <input id="leadChannel" type="text"
                                        class="form-control w-full mt-2 @error('leadChannel') !border-danger-500 @enderror"
                                        wire:model.defer="leadChannel" placeholder="Enter channel">
                                </div>
                                @error('leadChannel')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror

                                <hr class="mt-5">
                                <div class="from-group">
                                    <p class="text-lg mt-3"><b>Followup</b></p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">

                                        <div class="input-area">
                                            <label for="followupCallDateTime" class="form-label">Call Date
                                                Time</label>
                                            <input id="followupCallDateTime" type="datetime-local"
                                                class="form-control @error('followupCallDateTime') !border-danger-500 @enderror"
                                                wire:model.defer="followupCallDateTime">
                                        </div>
                                        @error('followupCallDateTime')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                {{-- @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach --}}

                            </div>


                            @can('exportAndImport', App\Models\Corporates\Corporate::class)
                                <hr />
                                <p class="text-lg mt-3"><b>Import/Export</b></p>
                                <div class="input-area mt-3">
                                    <input wire:model="leadsImportFile" type="file" class="form-control w-full "
                                        name="basic" />
                                    @error('leadsImportFile')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div
                                    class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                    <button wire:click="downloadTemplate" data-bs-dismiss="modal"
                                        class="btn inline-flex justify-center text-black bg-white-500">
                                        <span wire:loading.remove wire:target="downloadTemplate">Download Template</span>
                                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                            wire:loading wire:target="downloadTemplate"
                                            icon="line-md:loading-twotone-loop"></iconify-icon>
                                    </button>
                                    <button wire:click="importLeads" data-bs-dismiss="modal"
                                        class="btn inline-flex justify-center text-white bg-black-500">
                                        <span wire:loading.remove wire:target="importLeads">Import Leads</span>
                                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                            wire:loading wire:target="importLeads"
                                            icon="line-md:loading-twotone-loop"></iconify-icon>
                                    </button>
                                </div>
                                <div class="input-area mt-3">
                                    <label for="ownerId" class="form-label">Download User Leads</label>
                                    <select name="ownerId" id="ownerId"
                                        class="form-control w-full mt-2 @error('ownerId') !border-danger-500 @enderror"
                                        wire:model.defer="downloadUserLeadsID">
                                        <option value=null>All</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }}
                                                {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div
                                    class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                    <button wire:click="downloadLeadsFile" data-bs-dismiss="modal"
                                        class="btn inline-flex justify-center text-white bg-black-500">
                                        <span wire:loading.remove wire:target="downloadLeadsFile">Download Leads
                                            File</span>
                                        <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                            wire:loading wire:target="downloadLeadsFile"
                                            icon="line-md:loading-twotone-loop"></iconify-icon>

                                    </button>

                                </div>
                            @endcan
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addLead" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Interest Management Modal --}}
    @if ($interestManagementModalOpen && $selectedCorporate)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none" style="max-width: 800px;">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Manage Interests - {{ $selectedCorporate->name }}
                            </h3>
                            <button wire:click="closeInterestManagement" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4" style="max-height: 500px; overflow-y: auto;">
                            <div class="space-y-4">
                                @if($selectedCorporate->interests && count($selectedCorporate->interests) > 0)
                                    @foreach($selectedCorporate->interests as $interest)
                                        <div class="bg-slate-50 dark:bg-slate-600 p-4 rounded-lg">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-slate-900 dark:text-white">
                                                        {{ ucwords(str_replace('_', ' ', $interest->business)) }}
                                                    </h4>
                                                    <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                                                        Status: 
                                                        <span class="font-medium {{ $interest->interested ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ $interest->interested ? 'Interested' : 'Not Interested' }}
                                                        </span>
                                                    </p>
                                                    @if($interest->note)
                                                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                                                            <strong>Note:</strong> {{ $interest->note }}
                                                        </p>
                                                    @endif
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                                        Added: {{ $interest->created_at->format('M d, Y H:i') }}
                                                    </p>
                                                </div>
                                                <div class="flex space-x-2 ml-4">
                                                    <button wire:click="editThisInterest('{{ $interest->interested ? 'YES' : 'NO' }}', '{{ $interest->business }}')"
                                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                        Edit
                                                    </button>
                                                    <button wire:click="removeInterest({{ $interest->id }})"
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium"
                                                        onclick="return confirm('Are you sure you want to remove this interest?')">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8">
                                        <p class="text-slate-500 dark:text-slate-400">No interests recorded for this corporate.</p>
                                    </div>
                                @endif

                                <!-- Add New Interest Button -->
                                <div class="text-center pt-4">
                                    <button wire:click="editThisInterest('YES', '{{ reset($LINES_OF_BUSINESS) }}')"
                                        class="btn btn-primary">
                                        Add New Interest
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Interest Modal --}}
    @if ($editInteresetSec && $selectedCorporate)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Edit Interest
                            </h3>
                            <button wire:click="closeEditInterest" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="input-area">
                                <label for="editedLob" class="form-label">Line of Business</label>
                                <select name="editedLob" id="editedLob"
                                    class="form-control w-full mt-2 @error('editedLob') !border-danger-500 @enderror"
                                    wire:model="editedLob">
                                    <option value="">Select Line of Business</option>
                                    @foreach ($LINES_OF_BUSINESS as $lob)
                                        <option value="{{ $lob }}">{{ ucwords(str_replace('_', ' ', $lob)) }}</option>
                                    @endforeach
                                </select>
                                @error('editedLob')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-area">
                                <label for="interested" class="form-label">Interest Status</label>
                                <select name="interested" id="interested"
                                    class="form-control w-full mt-2 @error('interested') !border-danger-500 @enderror"
                                    wire:model="interested">
                                    <option value="">Select Status</option>
                                    <option value="YES">Interested</option>
                                    <option value="NO">Not Interested</option>
                                </select>
                                @error('interested')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-area">
                                <label for="interestNote" class="form-label">Note</label>
                                <textarea name="interestNote" id="interestNote" rows="3"
                                    class="form-control @error('interestNote') !border-danger-500 @enderror"
                                    wire:model="interestNote"
                                    placeholder="Add any notes about this interest..."></textarea>
                                @error('interestNote')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="isCreateFollowup" wire:model="isCreateFollowup"
                                    class="form-checkbox">
                                <label for="isCreateFollowup" class="form-label mb-0">Create followup after saving</label>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeEditInterest"
                                class="btn inline-flex justify-center text-slate-600 bg-slate-100">
                                Cancel
                            </button>
                            <button wire:click="editInterest"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Save Interest
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Add Followup Modal --}}
    @if ($addFollowupSection && $selectedCorporate)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Add Followup - {{ $selectedCorporate->name }}
                            </h3>
                            <button wire:click="closeFollowupSection" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="input-area">
                                <label for="followupTitle" class="form-label">Title</label>
                                <input type="text" id="followupTitle"
                                    class="form-control @error('followupTitle') !border-danger-500 @enderror"
                                    wire:model="followupTitle"
                                    placeholder="Enter followup title">
                                @error('followupTitle')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="input-area">
                                    <label for="followupCallDate" class="form-label">Call Date</label>
                                    <input type="date" id="followupCallDate"
                                        class="form-control @error('followupCallDate') !border-danger-500 @enderror"
                                        wire:model="followupCallDate">
                                    @error('followupCallDate')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="input-area">
                                    <label for="followupCallTime" class="form-label">Call Time</label>
                                    <input type="time" id="followupCallTime"
                                        class="form-control @error('followupCallTime') !border-danger-500 @enderror"
                                        wire:model="followupCallTime">
                                    @error('followupCallTime')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="input-area">
                                <label for="FollowupLineOfBussiness" class="form-label">Line of Business</label>
                                <select name="FollowupLineOfBussiness" id="FollowupLineOfBussiness"
                                    class="form-control w-full mt-2 @error('FollowupLineOfBussiness') !border-danger-500 @enderror"
                                    wire:model="FollowupLineOfBussiness">
                                    @foreach ($LINES_OF_BUSINESS as $lob)
                                        <option value="{{ $lob }}">{{ ucwords(str_replace('_', ' ', $lob)) }}</option>
                                    @endforeach
                                </select>
                                @error('FollowupLineOfBussiness')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-area">
                                <label for="followupDesc" class="form-label">Description</label>
                                <textarea name="followupDesc" id="followupDesc" rows="3"
                                    class="form-control @error('followupDesc') !border-danger-500 @enderror"
                                    wire:model="followupDesc"
                                    placeholder="Enter followup description..."></textarea>
                                @error('followupDesc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="is_meeting" wire:model="is_meeting"
                                    class="form-checkbox">
                                <label for="is_meeting" class="form-label mb-0">This is a meeting</label>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="closeFollowupSection"
                                class="btn inline-flex justify-center text-slate-600 bg-slate-100">
                                Cancel
                            </button>
                            <button wire:click="addFollowup"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Add Followup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
