<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Customers
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <button wire:click="toggleAddLead" class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Lead
            </button>
            <button wire:click="toggleAddCustomer" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Customer
            </button>
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
                            <thead class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        Customer Name
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
                                        Profession
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Arabic Name
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($customers as $customer)
                                    <tr wire:click="redirectToShowPage({{ $customer }})" class="hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">

                                        <td class="table-td ">
                                            <b>{{ $customer->name }}</b> 
                                        </td>

                                        <td class="table-td ">
                                            {{ $customer->type }}
                                        </td>

                                        <td class="table-td ">
                                            @foreach ($customer->phones->take(1) as $phones)
                                            {{ $phones->number }}
                                            @endforeach
                                            
                                        </td>

                                        <td class="table-td ">
                                            {{ $customer->email ?? 'N/A' }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $customer->profession->title ?? 'N/A' }}
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ $customer->arabic_name }}</b>
                                        </td>


                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @if ($customers->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Customers with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/customers') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all Customers</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif

                    </div>



                    {{ $customers->links('vendor.livewire.bootstrap') }}

                </div>
            </div>
        </div>
    </div>




    @if ($addCustomerSection)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                            Add customer
                        </h3>
                        
                        <button wire:click="toggleAddCustomer" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                            <p class="text-lg"><b>Customer Info</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Customer Name</label>
                                    <input id="firstName" type="text" class="form-control @error('name') !border-danger-500 @enderror" wire:model="name">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Arabic Name</label>
                                    <input id="lastName" type="text" class="form-control @error('arabic_name') !border-danger-500 @enderror" wire:model="arabic_name">
                                </div>
                            </div>
                            @error('name')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('arabic_name')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="input-area mt-2">
                                <label for="name" class="form-label">Email</label>
                                <input id="name" type="text" class="form-control @error('email') !border-danger-500 @enderror" wire:model="email">
                            </div>
                            @error('email')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-2">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Birth Date</label>
                                    <input type="date" class="form-control @error('bdate') !border-danger-500 @enderror" wire:model="bdate">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Gender</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2 @error('gender') !border-danger-500 @enderror" wire:model="gender">
                                        @foreach ($GENDERS as $gender)
                                            <option value="{{ $gender }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Marital Status</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2 @error('maritalStatus') !border-danger-500 @enderror" wire:model="maritalStatus">
                                        @foreach ($MARITALSTATUSES as $status)
                                            <option value="{{ $status }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('bdate')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('gender')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('maritalStatus')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <hr class="mt-5">
                            <p class="mt-3 text-lg"><b>National Info</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">ID Type</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2 @error('idType') !border-danger-500 @enderror" wire:model="idType">
                                        <option>None</option>
                                        @foreach ($IDTYPES as $idtype)
                                            <option value="{{ $idtype }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ str_replace('_', ' ', $idtype) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">ID Number</label>
                                    <input id="lastName" type="text" class="form-control @error('idNumber') !border-danger-500 @enderror" wire:model="idNumber">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Nationality</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2 @error('nationalId') !border-danger-500 @enderror" wire:model="nationalId">
                                        <option>None</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('idType')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('idNumber')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('nationalId')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <hr class="mt-5">
                            <p class="mt-3 text-lg"><b>Profession</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Profession title</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2 @error('profession_id') !border-danger-500 @enderror" wire:model="profession_id">
                                        <option>None</option>
                                        @foreach ($professions as $profession)
                                            <option value="{{ $profession->id }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $profession->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Salary range</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2 @error('salaryRange') !border-danger-500 @enderror" wire:model="salaryRange">
                                        <option>None</option>
                                        @foreach ($SALARY_RANGES as $range)
                                            <option value="{{ $range }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ str_replace('_to_', 'K to ', $range) }}K</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Income source</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2 @error('incomeSource') !border-danger-500 @enderror" wire:model="incomeSource">
                                        <option>None</option>
                                        @foreach ($INCOME_SOURCES as $source)
                                            <option value="{{ $source }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $source }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('profession_id')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('salaryRange')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('incomeSource')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="addCustomer" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                            Accept
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
                            Add Lead
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
                            <p class="text-lg"><b>Lead Info</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Customer Name</label>
                                    <input id="firstName" type="text" class="form-control @error('LeadName') !border-danger-500 @enderror" wire:model="LeadName">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Arabic Name</label>
                                    <input id="lastName" type="text" class="form-control @error('LeadPhone') !border-danger-500 @enderror" wire:model="LeadPhone">
                                </div>
                            </div>
                            @error('LeadName')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('LeadPhone')
                                <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="addLead" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                            Accept
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif



</div>