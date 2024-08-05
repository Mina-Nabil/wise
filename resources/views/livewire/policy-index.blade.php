<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Policies
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @can('create', \App\Models\Insurance\Policy::class)
                <button wire:click="downloadPoliciesConfExport"
                    class="btn inline-flex justify-center btn-white white:bg-slate-700 white:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:arrow-down"></iconify-icon>
                    Export Configurations
                </button>
                <button wire:click="toggleImportConfSection"
                    class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                    Import Configurations
                </button>
                <button wire:click="downloadPoliciesExport"
                    class="btn inline-flex justify-center btn-white white:bg-slate-700 white:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:arrow-down"></iconify-icon>
                    Export Policies
                </button>
                <button wire:click="toggleImportSection"
                    class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                    Import Policies
                </button>
                <button wire:click="openPolicySec"
                    class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                    Add Policy
                </button>
            @endcan


            @if ($newPolicySec)
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
                                        Add New Policy
                                    </h3>
                                    <button type="button" wire:click="closePolicySec"
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
                                <div class="card-text h-full">
                                    <div class="px-4 pt-4 pb-3">
                                        <div class="input-area mb-3">
                                            <label for="name" class="form-label">Policy Name</label>
                                            <input type="text"
                                                class="form-control @error('policyName') !border-danger-500 @enderror"
                                                wire:model.defer="policyName">
                                            @error('policyName')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="name" class="form-label">Business</label>
                                            <select name="business"
                                                class="form-control w-full mt-2  @error('policyBusiness') !border-danger-500 @enderror"
                                                wire:model.defer="policyBusiness">
                                                <option
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                                    selected>
                                                    Please select business type
                                                </option>
                                                @foreach ($linesOfBusiness as $line)
                                                    <option value="{{ $line }}"
                                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                        {{ $line }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('policyBusiness')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-area mb-3">
                                            <label for="name" class="form-label">Company</label>
                                            <select name="business"
                                                class="form-control w-full mt-2  @error('company') !border-danger-500 @enderror"
                                                wire:model.defer="company">
                                                <option
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                                    selected>
                                                    Please select company
                                                </option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}"
                                                        class="py-1 inline-block font-Inter font-normal text-sm">
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('company')
                                                <span
                                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="input-area mb-3">
                                            <label for="name" class="form-label">Note</label>
                                            <textarea class="form-control @error('note') !border-danger-500 @enderror" wire:model.defer="note"
                                                placeholder="Leave a note" name="note"></textarea>
                                            @error('note')
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

            @if ($importFileSection)
                <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                    tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
                    style="display: block;">
                    <div class="modal-dialog relative w-auto pointer-events-none">
                        <div
                            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                            <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                    <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                        Upload Policies File
                                    </h3>
                                    <button type="button"
                                        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                        wire:click=toggleImportSection>
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
                                    <div class="from-group">
                                        <label for="lastName" class="form-label">Policies File</label>
                                        <input wire:model="policiesFile" type="file" class="form-control w-full "
                                            name="basic" />
                                        @error('policiesFile')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Modal footer -->
                                <div wire:click="uploadPolicies"
                                    class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b white:border-slate-600">
                                    <button 
                                        class="btn inline-flex justify-center text-white bg-black-500">
                                        Upload
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($importConfFileSection)
                <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
                    tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
                    style="display: block;">
                    <div class="modal-dialog relative w-auto pointer-events-none">
                        <div
                            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                            <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                                    <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                        Upload Policies Configurations File
                                    </h3>
                                    <button type="button"
                                        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                        wire:click=toggleImportConfSection>
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
                                    <div class="from-group">
                                        <label for="lastName" class="form-label">Configurations File</label>
                                        <input wire:model="policiesConfFile" type="file" class="form-control w-full "
                                            name="basic" />
                                        @error('policiesConfFile')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Modal footer -->
                                <div wire:click="uploadPoliciesConfigurations"
                                    class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b white:border-slate-600">
                                    <button 
                                        class="btn inline-flex justify-center text-white bg-black-500">
                                        Upload
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div>
        <div class="input-area mb-3">
            <div class="relative">
                <div>
                    <iconify-icon wire:loading class="loading-icon text-lg pt-2"
                        icon="line-md:loading-twotone-loop"></iconify-icon>
                    <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search"
                        wire:model="search">
                </div>
            </div>
        </div>
        {{-- <div class="mb-5">
            <button class="btn inline-flex justify-center btn-primary btn-sm">
                <span class="flex items-center">
                    <span>Aston Martin</span>
                    <iconify-icon class="ltr:mr-1 rtl:ml-1 ml-2" icon="zondicons:close-solid"></iconify-icon>
                </span>
            </button>
            <button class="btn inline-flex justify-center btn-primary btn-sm">
                <span class="flex items-center">
                    <span>Vantage Roadster</span>
                    <iconify-icon class="ltr:mr-1 rtl:ml-1 ml-2" icon="zondicons:close-solid"></iconify-icon>
                </span>
            </button>
        </div> --}}
        <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">

            {{-- BEGIN: Policy list --}}
            @foreach ($policies as $policy)
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="card-text h-full">
                            <header class="border-b px-4 pt-4 pb-3 flex justify-between border-primary-500 ">
                                <div class="flex-wrap items-center">
                                    <h5 class="mb-0 text-primary-500" style="display: flex; align-items: center;">
                                        <iconify-icon class="text-xl inline-block ltr:mr-2 rtl:ml-2 text-primary-500"
                                            icon="iconoir:privacy-policy"></iconify-icon>
                                        {{ $policy->name }} &nbsp; <span
                                            class="badge bg-primary-500 text-white capitalize">{{ $policy->company->name }}</span>
                                    </h5>

                                </div>

                                <div class="flex space-x-3 rtl:space-x-reverse float-right">
                                    <a href={{ route('policies.show', $policy->id) }}>
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                        </button>
                                    </a>
                                </div>
                            </header>
                            <div class="py-3 px-5">
                                <h5 class="card-subtitle">{{ $policy->business }} - {{ $policy->company->name }}</h5>
                                {{-- @foreach ($policy->conditions as $condition)
                                    <h5 class="card-subtitle">
                                        <span class="badge bg-slate-900 text-white capitalize">{{ $condition->scope }}</span>
                                        <span class="badge bg-info-500 text-white capitalize">{{ $condition->operator }}</span>
                                        <span class="badge bg-slate-900 text-white capitalize">{{ $condition->value }}</span>
                                    </h5>
                                @endforeach --}}

                                @foreach ($policy->conditions as $condition)
                                    <h5 class="card-subtitle">
                                        <span
                                            class="badge bg-slate-900 text-white capitalize">{{ $condition->scope }}</span>
                                        <span class="badge bg-info-500 text-white capitalize">
                                            @if ($condition->operator === 'gte')
                                                >=
                                            @elseif($condition->operator === 'gt')
                                                >
                                            @elseif($condition->operator === 'lte')
                                            <= @elseif($condition->operator === 'lt') <
                                                @elseif($condition->operator === 'e')=@endif
                                        </span>
                                        <span
                                            class="badge bg-slate-900 text-white capitalize">{{ $condition->value_name }}</span>
                                        <span
                                            class="badge bg-slate-900 text-white capitalize">{{ $condition->rate }}</span>
                                    </h5>
                                @endforeach

                                <p class="card-text mt-3">{{ $policy->note }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{-- END: Policy list --}}

        </div>
        {{ $policies->links('vendor.livewire.bootstrap') }}

    </div>
</div>

@if ($deleteThisPolicy)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        id="dangerModal" tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
        style="display: block;">
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-danger-500">
                        <h3 class="text-base font-medium text-white dark:text-white capitalize">
                            Delete Policy
                        </h3>
                        <button type="button"
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
                        <h6 class="text-base text-slate-900 dark:text-white leading-6">
                            Are you sure, to delete this Policy ?
                        </h6>
                        <p class="text-base text-slate-600 dark:text-slate-400 leading-6">
                            Oat cake ice cream candy chocolate cake
                            apple pie. Brownie carrot cake candy
                            canes. Cake sweet roll cake cheesecake
                            cookie chocolate cake liquorice.
                        </p>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-danger-500">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
