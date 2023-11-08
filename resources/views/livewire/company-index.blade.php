<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Companies
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @can('create', \App\Models\Company::class)
                <button wire:click="openAddCompany"
                    class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                    Add Company
                </button>
            @endcan

            @if ($AddCompanySec)
                <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                    style="display: block">
                    <div class="modal-dialog relative w-auto pointer-events-none">
                        <div
                            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                            <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-success-500">
                                    <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                        Add New Company
                                    </h3>
                                    <button type="button" wire:click="closeAddCompany"
                                        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                                        data-bs-dismiss="modal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6 space-y-4">

                                    <div class="input-area">
                                        <label for="name" class="form-label">Company Name*</label>
                                        <input id="name" type="text"
                                            class="form-control @error('newName') !border-danger-500 @enderror"
                                            placeholder="Company Name" wire:model.defer="newName">
                                        @error('newName')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-area">
                                        <label for="name" class="form-label">Note</label>
                                        <textarea id="name" type="text" class="form-control @error('newNote') !border-danger-500 @enderror"
                                            placeholder="Leave a note..." wire:model.defer="newNote"></textarea>
                                        @error('newNote')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <!-- Modal footer -->
                                <div
                                    class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                    <button wire:click="add"
                                        class="btn inline-flex justify-center text-white bg-success-500">Submit</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </div>
    <div class="card">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search" wire:model="search">
        </header>

        <div class="card-body px-6 pb-6">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead
                                class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Note
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Email
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Actions
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($companies as $company)
                                    <tr>

                                        <td class="table-td hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">
                                            {{ $company->name }}</td>
                                        <td class="table-td hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">
                                            {{ $company->note }}</td>

                                        <td class="table-td ">
                                            {{ $company->primary_email }}
                                        </td>

                                        <td class="table-td ">
                                            <div class="flex justify-center">
                                                <button class="toolTip onTop action-btn m-1 " data-tippy-content="Edit"
                                                    wire:click="editRow({{ $company->id }})" type="button">
                                                    <iconify-icon icon="iconamoon:edit-bold"></iconify-icon>
                                                </button>

                                                @can('delete', $company)
                                                    <button class="toolTip onTop action-btn m-1" data-tippy-content="Delete"
                                                        type="button"
                                                        wire:click="deleteThisComp({{ $company->id }}, '{{ $company->name }}')">
                                                        <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @if ($companies->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Companies with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/companies') }}"
                                            class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all companies</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif

                    </div>



                    {{ $companies->links('vendor.livewire.bootstrap') }}

                </div>
            </div>
        </div>
    </div>

    @if ($deleteInfo)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            id="dangerModal" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                        rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                Delete Company
                            </h3>
                            <button wire:click="closeDelete" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                    dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                            11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <h6 class="text-base text-slate-900 dark:text-white leading-6">
                                Are you sure you want to delete <b>{{ $deleteInfo[1] }}</b> ?
                            </h6>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="delete" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($editThisComp)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
            style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none" style="max-width: 800px">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                        rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                {{ $companyInfo->name }}
                            </h3>
                            <button wire:click="closeEdit" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                    dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                            11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="input-area">
                                <label for="name" class="form-label">Company Name*</label>
                                <input id="name" type="text"
                                    class="form-control @error('companyInfoName') !border-danger-500 @enderror"
                                    placeholder="Company Name" wire:model.defer="companyInfoName">
                                @error('companyInfoName')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-area">
                                <label for="name" class="form-label">Note</label>
                                <textarea id="name" type="text" class="form-control @error('companyInfoNote') !border-danger-500 @enderror"
                                    placeholder="Leave a note..." wire:model.defer="companyInfoNote"></textarea>
                                @error('companyInfoNote')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            @can('edit', $companyInfo)
                                <button wire:click="saveChanges" data-bs-dismiss="modal"
                                    class="btn inline-flex justify-center text-white bg-black-500 btn-sm">Save
                                    Changes</button>
                            @endcan
                            @if ($companyInfo->emails->isEmpty())
                                <tr>
                                    <td class="table-td text-center" colspan="4">
                                        <div
                                            class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-info-500 bg-opacity-[14%] text-info-500">
                                            <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                                <div class="flex-1">
                                                    No emails found for this Company!
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <div class="card">
                                    <div class="card-body px-6 pb-6">
                                        <div class="overflow-x-auto -mx-6">
                                            <div class="inline-block min-w-full align-middle">
                                                <div class="overflow-hidden ">
                                                    <table
                                                        class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                                        <thead class="bg-slate-200 dark:bg-slate-700">
                                                            <tr>

                                                                <th scope="col" class=" table-th ">
                                                                    Type
                                                                </th>

                                                                <th scope="col" class=" table-th ">
                                                                    Email
                                                                </th>

                                                                <th scope="col" class=" table-th ">
                                                                    Name
                                                                </th>

                                                                <th scope="col" class=" table-th ">
                                                                    Action
                                                                </th>

                                                            </tr>
                                                        </thead>
                                                        <tbody
                                                            class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">



                                                            @foreach ($companyInfo->emails as $email)
                                                                @if ($email->id === $editableEmailId)
                                                                    <tr>
                                                                        <td class="table-td"
                                                                            style="padding-left: 1px;padding-right: 1px;">
                                                                            <select name="basicSelect"
                                                                                class="form-control w-full @error('editableEmailType') !border-danger-500 @enderror"
                                                                                wire:model.defer="editableEmailType">
                                                                                <option selected="Selected"
                                                                                    disabled="disabled" value="none"
                                                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                                    Select
                                                                                    Type</option>
                                                                                @foreach ($types as $type)
                                                                                    <option
                                                                                        value="{{ $type }}"
                                                                                        class="py-1 inline-block font-Inter font-normal text-sm">
                                                                                        {{ $type }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>

                                                                        <td class="table-td"
                                                                            style="padding-left: 1px;padding-right: 1px;">
                                                                            <input type="text"
                                                                                class="form-control @error('editableEmail') !border-danger-500 @enderror"
                                                                                placeholder="Email"
                                                                                wire:model.defer="editableEmail">
                                                                        </td>

                                                                        <td class="table-td flex justify-center"
                                                                            style="padding-left: 1px;padding-right: 1px;">
                                                                            <input type="text"
                                                                                class="form-control @error('editableEmailFname') !border-danger-500 @enderror"
                                                                                placeholder="First Name"
                                                                                wire:model.defer="editableEmailFname">
                                                                            <input type="text"
                                                                                class="form-control @error('editableEmailLname') !border-danger-500 @enderror"
                                                                                placeholder="Last Name"
                                                                                wire:model.defer="editableEmailLname">
                                                                        </td>

                                                                        <td class="table-td"
                                                                            style="padding-right: 1px;">
                                                                            <div class=" flex justify-center">
                                                                                <div
                                                                                    class="checkbox-area primary-checkbox mr-2 sm:mr-4 mt-2">
                                                                                    <label
                                                                                        class="inline-flex items-center cursor-pointer">
                                                                                        <input type="checkbox"
                                                                                            class="hidden"
                                                                                            name="checkbox"
                                                                                            wire:model.defer="editableEmailIsPrim">
                                                                                        <span
                                                                                            class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                                                            <img src="assets/images/icon/ck-white.svg"
                                                                                                alt=""
                                                                                                class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                                                                        <span
                                                                                            class="text-primary-500 dark:text-slate-400 text-sm leading-6 capitalize">primary</span>
                                                                                    </label>
                                                                                </div>
                                                                                @can('edit', $companyInfo)
                                                                                    <button
                                                                                        class="toolTip onTop action-btn m-1 "
                                                                                        data-tippy-content="Edit"
                                                                                        wire:click="saveEmailRow"
                                                                                        type="button">
                                                                                        <iconify-icon
                                                                                            icon="material-symbols:save"></iconify-icon>
                                                                                    </button>
                                                                                    <button
                                                                                        class="toolTip onTop action-btn m-1 "
                                                                                        data-tippy-content="Edit"
                                                                                        wire:click="closeEditEmailRow"
                                                                                        type="button">
                                                                                        <iconify-icon
                                                                                            icon="material-symbols:close"></iconify-icon>
                                                                                    </button>
                                                                                @endcan
                                                                            </div>




                                                                        </td>
                                                                    </tr>
                                                                @elseif($email->id === $deleteThisEmail)
                                                                    <tr>
                                                                        <td colspan="4" class="table-td">
                                                                            <div
                                                                                class="flex justify-between flex-wrap">
                                                                                <p
                                                                                    class="text-sm align-middle table-td">
                                                                                    Are you sure,
                                                                                    to delete this Email ?</p>
                                                                                <div>
                                                                                    <button
                                                                                        wire:click="declineDeleteEmail"
                                                                                        class="btn inline-flex items-center justify-center btn-light active rounded-full btn-sm">
                                                                                        <span
                                                                                            class="flex items-center">
                                                                                            No
                                                                                        </span>
                                                                                    </button>
                                                                                    <button data-bs-dismiss="modal"
                                                                                        wire:click="deleteEmail"
                                                                                        class="btn inline-flex justify-center  btn-danger bg-black-500 btn-sm">
                                                                                        yes, delete
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @else
                                                                    <tr>
                                                                        <td class="table-td">
                                                                            @if ($email->is_primary)
                                                                                <span class="success-400">
                                                                                    <iconify-icon
                                                                                        icon="material-symbols:star"></iconify-icon>
                                                                                </span>
                                                                            @endif

                                                                            {{ $email->type }}
                                                                        </td>

                                                                        <td class="table-td">{{ $email->email }}</td>

                                                                        <td class="table-td ">
                                                                            {{ $email->contact_first_name }}

                                                                            {{ $email->contact_last_name }}</td>

                                                                        <td class="table-td flex">
                                                                            @can('edit', $companyInfo)
                                                                                <button
                                                                                    class="toolTip onTop action-btn m-1 "
                                                                                    data-tippy-content="Edit"
                                                                                    wire:click="editEmailRow({{ $email->id }})"
                                                                                    type="button">
                                                                                    <iconify-icon
                                                                                        icon="iconamoon:edit-bold"></iconify-icon>
                                                                                </button>

                                                                                <button
                                                                                    class="toolTip onTop action-btn m-1 "
                                                                                    data-tippy-content="Edit"
                                                                                    wire:click="confirmDelEmail({{ $email->id }})"
                                                                                    type="button">
                                                                                    <iconify-icon
                                                                                        icon="heroicons-outline:trash"></iconify-icon>
                                                                                </button>
                                                                            @endcan
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @can('edit', $companyInfo)
                                <div class="input-area">
                                    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-6">

                                        <div>
                                            <label for="name" class="form-label">Type</label>
                                            <select name="basicSelect" id="basicSelect"
                                                class="form-control w-full mt-2 @error('newEmailType') !border-danger-500 @enderror"
                                                wire:model.defer="newEmailType">
                                                <option selected="Selected" disabled="disabled" value="none"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    Select
                                                    Type</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type }}"
                                                        class="py-1 inline-block font-Inter font-normal text-sm">
                                                        {{ $type }}</option>
                                                @endforeach
                                            </select>
                                            @error('newEmailType')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-1 inline-block mb-3">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="name" class="form-label">Email</label>
                                            <input type="text"
                                                class="form-control @error('newEmail') !border-danger-500 @enderror"
                                                placeholder="Email" wire:model.defer="newEmail">
                                            @error('newEmail')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-1 inline-block mb-3">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="name" class="form-label">First Name</label>
                                            <input type="text"
                                                class="form-control @error('newEmailFname') !border-danger-500 @enderror"
                                                placeholder="First Name" wire:model.defer="newEmailFname">
                                            @error('newEmailFname')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-1 inline-block mb-3">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="name" class="form-label">Last Name</label>
                                            <input type="text"
                                                class="form-control @error('newEmailLname') !border-danger-500 @enderror"
                                                placeholder="Last Name" wire:model.defer="newEmailLname">
                                            @error('newEmailLname')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-1 inline-block mb-3">{{ $message }}</span>
                                            @enderror
                                        </div>



                                    </div>
                                    <button class="btn inline-flex justify-center btn-light btn-sm mt-2"
                                        wire:click="addEmail">Submit</button>

                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
