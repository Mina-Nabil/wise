<div>

    <div class="flex justify-center">
        <div class="max-w-screen-lg">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                <div class="flex-1 rounded-md overlay max-w-[520px] min-w-\[var\(500px\)\]" style="min-width: 400px;">
                    <div class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                        <b>{{ $customer->name }}
                            <span class="float-right cursor-pointer text-slate-500" wire:click="">
                                <iconify-icon icon="material-symbols:edit-outline"></iconify-icon>
                            </span></b>
                    </div>

                    <div class="card-body flex flex-col justify-center mt-5  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p class="mb-2">
                                <b>Owned Cars ({{ $customer->cars->count() }})</b>
                                <span class="float-right cursor-pointer text-slate-500" wire:click="">
                                    <iconify-icon icon="material-symbols:edit-outline"></iconify-icon>
                                </span>
                            </p>

                            @foreach ($customer->cars as $car)
                                <div class="card-body flex flex-col justify-between border rounded-lg h-full menu-open p-0 mb-5" style="border-color:rgb(224, 224, 224)">
                                    <div class="break-words flex items-center my-1 m-4">
                                        <h3 class="text-base capitalize py-3">
                                            <ul class="m-0 p-0 list-none">
                                                <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                    N/A
                                                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                </li>
                                                <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                    N/A
                                                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                </li>
                                                <li class="inline-block relative text-sm top-[3px] text-slate-500 font-Inter dark:text-white mr-5">
                                                    N/A
                                                </li>
                                                <span class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize rounded-3xl float-right">{{ $car->payment_frequency }} Payment</span>

                                            </ul>

                                        </h3>
                                    </div>
                                    <hr><br>
                                    <div class="grid grid-cols-2 mb-4">
                                        <div class="border-r ml-5">
                                            <p><b>Sum insured</b></p>
                                            <p>{{ number_format($car->sum_insured, 0, '.', ',') }}</p>
                                        </div>
                                        <div class="ml-5">
                                            <p><b>Insurance payment</b></p>
                                            <p>{{ number_format($car->insurance_payment, 0, '.', ',') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="card-body flex flex-col justify-center bg-cover card p-4 mt-5">
                        <div class="card-text flex flex-col justify-between  menu-open">
                            <p>
                                <b>Addresses</b>
                                <span class="float-right cursor-pointer text-slate-500" wire:click="">
                                    <iconify-icon icon="material-symbols:edit-outline"></iconify-icon>
                                </span>
                            </p>
                            <br>

                            @foreach ($customer->addresses as $address)
                                <p><b>Address {{ $loop->index + 1 }}</b></p>
                                <p>{{ $address->line_1 }}</p>
                                <p>{{ $address->line_2 }}</p>
                                <p>Flat: {{ $address->flat }}, Building: {{ $address->building }}</p>
                                <p>{{ $address->city }}, {{ $address->country }}</p>
                                <br>
                            @endforeach


                        </div>
                    </div>

                    <div class="card-body flex flex-col justify-center mt-5  bg-no-repeat bg-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p class="mb-2">
                                <b>Relatives</b>
                                <span class="float-right cursor-pointer text-slate-500" wire:click="">
                                    <iconify-icon icon="material-symbols:edit-outline"></iconify-icon>
                                </span>
                            </p>

                            @foreach ($customer->relatives as $relative)
                                <p><b> {{ $relative->name }}</b></p>
                                <p><a href="tel:{{ $relative->phone }}" style="text-decoration:revert;color:blue">{{ $relative->phone }}</a> | {{ $relative->relation }}, {{ $relative->gender }}, {{ $relative->birth_date->format('d/m/Y') }}</p>
                                <br>
                            @endforeach

                        </div>
                    </div>


                </div>

                <div class="flex-1 rounded-md overlay  max-w-[400px] min-w-[310px]">
                    <div class="card-body  flex flex-col justify-center bg-cover card p-4 active">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p>
                                <b>Customer</b>
                                <span class="float-right cursor-pointer text-slate-500" wire:click="toggleEditCustomer">
                                    <iconify-icon icon="material-symbols:edit-outline"></iconify-icon>
                                </span>
                            </p>
                            <br>

                            <p><b>Contact information</b></p>
                            <a href="">{{ $customer->email }}</a>
                            <br>



                            <p class="mb-1"><b>Marital status</b></p>
                            <p>{{ $customer->marital_status }}</p>
                            <br>

                            <p class="mb-1"><b>Nationality ID</b></p>
                            <p>{{ $customer->nationality_id }}</p>
                            <br>

                            <p class="mb-1"><b>Birth Date</b></p>
                            <p>
                                {{ $customer->birth_date->format('d/m/Y') }}
                                <span class="text-xs">
                                    ({{ $customer->birth_date->diffInYears(now()) }} years)
                                </span>
                            </p>

                        </div>


                    </div>

                    <div class="card-body flex flex-col justify-center bg-cover card p-4 mt-5">
                        <div class="card-text flex flex-col justify-between  menu-open">
                            <p>
                                <b>Profession</b>
                                <span class="float-right cursor-pointer text-slate-500" wire:click="">
                                    <iconify-icon icon="material-symbols:edit-outline"></iconify-icon>
                                </span>
                            </p>
                            <p>{{ $customer->profession->title }}</p>
                            <br>

                            <p><b>Salary Range</b></p>
                            <p>{{ str_replace('_to_', 'K to ', $customer->salary_range) }}K

                            </p>
                            <br>

                            <p><b>Income source</b></p>
                            <p>{{ $customer->income_source }}</p>
                            <br>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>


    @if ($editCustomerSection)
        
    
    {{-- edit customer section --}}
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                            Edit customer
                        </h3>
                        <button wire:click="toggleEditCustomer" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                    <input id="firstName" type="text" class="form-control" wire:model="name">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Arabic Name</label>
                                    <input id="lastName" type="text" class="form-control" wire:model="arabic_name">
                                </div>
                            </div>
                            <div class="input-area mt-2">
                                <label for="name" class="form-label">Email</label>
                                <input id="name" type="text" class="form-control" wire:model="email">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Phone</label>
                                    <input id="firstName" type="text" class="form-control" wire:model="phone1">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Phone 2</label>
                                    <input id="lastName" type="text" class="form-control" wire:model="phone2">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-2">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Birth Date</label>
                                    <input type="date" class="form-control" wire:model="bdate">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Gender</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="gender">
                                        @foreach ($GENDERS as $gender)
                                            <option value="{{ $gender }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Marital Status</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="maritalStatus">
                                        @foreach ($MARITALSTATUSES as $status)
                                            <option value="{{ $status }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr class="mt-5">
                            <p class="mt-3 text-lg"><b>National Info</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">ID Type</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="idType">
                                        @foreach ($IDTYPES as $idtype)
                                            <option value="{{ $idtype }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ str_replace('_', ' ', $idtype) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">ID Number</label>
                                    <input id="lastName" type="text" class="form-control" wire:model="idNumber">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Nationality ID</label>
                                    <input id="lastName" type="text" class="form-control" wire:model="nationalId">
                                </div>
                            </div>
                            <hr class="mt-5">
                            <p class="mt-3 text-lg"><b>Profession</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Profession title</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="profession_id">
                                        @foreach ($professions as $profession)
                                            <option value="{{ $profession->id }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $profession->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Salary range</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="salaryRange">
                                        @foreach ($SALARY_RANGES as $range)
                                            <option value="{{ $range }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ str_replace('_to_', 'K to ', $range ) }}K</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Income source</label>
                                    <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2" wire:model="incomeSource">
                                        @foreach ($INCOME_SOURCES as $source)
                                            <option value="{{ $source }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $source }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                            Accept
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif


</div>
