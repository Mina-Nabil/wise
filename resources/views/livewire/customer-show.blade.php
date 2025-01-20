<div>
    {{--  justify-center --}}
    <div class="sm:flex">
        <div class="max-w-screen-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <b>#{{ $customer->id }} - {{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}
                        {{ $customer->arabic_first_name ? '-' : '' }} {{ $customer->arabic_first_name }}
                        {{ $customer->arabic_middle_name }} {{ $customer->arabic_last_name }} </b><iconify-icon
                        class="ml-3" style="position: absolute" wire:loading wire:target="changeSection"
                        icon="svg-spinners:180-ring"></iconify-icon>
                </div>
                <div class="card-body flex flex-col col-span-2" wire:ignore>
                    <div class="card-text h-full">
                        <div>
                            <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0"
                                id="tabs-tab" role="tablist">
                                <li class="nav-item" role="presentation" wire:click="changeSection('profile')">
                                    <a href="#tabs-profile-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'profile') active @endif dark:text-slate-300"
                                        id="tabs-profile-withIcon-tab" data-bs-toggle="pill"
                                        data-bs-target="#tabs-profile-withIcon" role="tab"
                                        aria-controls="tabs-profile-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1" icon="heroicons-outline:user"></iconify-icon>
                                        Profile</a>
                                </li>
                                <li class="nav-item" role="presentation" wire:click="changeSection('cars')">
                                    <a href="#tabs-messages-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'cars') active @endif dark:text-slate-300"
                                        id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                                        data-bs-target="#tabs-messages-withIcon" role="tab"
                                        aria-controls="tabs-messages-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1" icon="mingcute:car-line"></iconify-icon>
                                        cars</a>
                                </li>
                                <li class="nav-item" role="presentation" wire:click="changeSection('followups')">
                                    <a href="#tabs-messages-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'followups') active @endif dark:text-slate-300"
                                        id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                                        data-bs-target="#tabs-messages-withIcon" role="tab"
                                        aria-controls="tabs-messages-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1"
                                            icon="icon-park-outline:cycle-arrow"></iconify-icon>
                                        Follow Ups</a>
                                </li>
                                <li class="nav-item" role="presentation" wire:click="changeSection('offers')">
                                    <a href="#tabs-messages-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'offers') active @endif dark:text-slate-300"
                                        id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                                        data-bs-target="#tabs-messages-withIcon" role="tab"
                                        aria-controls="tabs-messages-withIcon" aria-selected="false">
                                        <iconify-icon class="mr-1" icon="ic:outline-local-offer"></iconify-icon>
                                        Offers</a>
                                </li>
                                <li class="nav-item" role="presentation" wire:click="changeSection('tasks')">
                                    <a href="#tabs-messages-withIcon"
                                        class="nav-link w-full flex items-center font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent  @if ($section === 'tasks') active @endif dark:text-slate-300"
                                        id="tabs-messages-withIcon-tab" data-bs-toggle="pill"
                                        data-bs-target="#tabs-messages-withIcon" role="tab"
                                        aria-controls="tabs-messages-withIcon" aria-selected="false">
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

                @if ($section === 'cars')
                    <div class="flex-1 rounded-md col-span-2">
                        <div
                            class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                            <div class="card-text flex flex-col justify-between h-full menu-open">
                                <p class="mb-2">
                                    <b>Owned Cars ({{ $customer->cars->count() }})</b>

                                </p>

                                @if ($customer->cars->isEmpty())
                                    <p class="text-center m-5 text-primary">No cars added to this customer.</p>
                                @else
                                    @foreach ($customer->cars as $car)
                                        <div class="card-body flex flex-col justify-between border rounded-lg h-full menu-open p-0 mb-5"
                                            style="border-color:rgb(224, 224, 224)">
                                            @if ($car->wise_insured)
                                                <span
                                                    class="badge bg-success-500 text-white capitalize inline-flex items-center">
                                                    <iconify-icon class="ltr:mr-1 rtl:ml-1"
                                                        icon="wpf:security-checked"></iconify-icon>
                                                    Wise Insured</span>
                                            @endif
                                            <div class="break-words flex items-center my-1 m-4">

                                                <h3 class="text-base capitalize py-3">
                                                    <ul class="m-0 p-0 list-none">
                                                        <li
                                                            class="inline-block relative top-[3px] text-base font-Inter ">
                                                            {{ $car->car->car_model->brand->name }}
                                                            <iconify-icon icon="heroicons-outline:chevron-right"
                                                                class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                        </li>
                                                        <li
                                                            class="inline-block relative top-[3px] text-base font-Inter ">
                                                            {{ $car->car->car_model->name }}
                                                            <iconify-icon icon="heroicons-outline:chevron-right"
                                                                class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                        </li>
                                                        <li
                                                            class="inline-block relative text-sm top-[3px] text-slate-500 font-Inter dark:text-white mr-5">
                                                            {{ $car->car->category }}
                                                        </li>
                                                    </ul>
                                                </h3>
                                                @if ($car->payment_frequency)
                                                    <span
                                                        class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize rounded-3xl float-right">{{ $car->payment_frequency }}
                                                        Payment</span>
                                                @endif



                                                <div class="ml-auto">
                                                    <div class="relative">
                                                        <div class="dropdown relative">
                                                            <button class="text-xl text-center block w-full "
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <iconify-icon
                                                                    icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                            </button>
                                                            <ul
                                                                class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                                <li>
                                                                    <button
                                                                        wire:click="editThisCar({{ $car->id }})"
                                                                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                        Edit</button>
                                                                </li>
                                                                <li>
                                                                    <button
                                                                        wire:click="deleteThisCar({{ $car->id }})"
                                                                        class="text-slate-600 dark:text-white block font-Inter text-left font-normal w-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                        Delete</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <p class="ml-4">Model year: <b>{{ $car->model_year }}</b> </p>
                                            <hr><br>
                                            <div class="grid grid-cols-3 mb-4">
                                                <div class="border-r ml-5">
                                                    <p><b>Sum insured</b></p>
                                                    <p>{{ number_format($car->sum_insured, 0, '.', ',') }}</p>
                                                </div>
                                                <div class="border-r ml-5">
                                                    <p class="mr-2"><b>Insurance Company </b></p>
                                                    <p>{{ $car->insurance_company->name ?? 'N/A' }}</p>
                                                </div>
                                                <div class="ml-5">
                                                    <p><b>Insurance payment</b></p>
                                                    <p>{{ number_format($car->insurance_payment, 2, '.', ',') }}</p>
                                                </div>
                                            </div>
                                            @if ($car->renewal_date)
                                                <p class="m-4 mt-0">Renewal Date:
                                                    <b>{{ optional(\Carbon\Carbon::parse($car->renewal_date))->format('l d M Y') ?? '' }}
                                                    </b>
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif

                                <button wire:click="toggleAddCar"
                                    class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add
                                    car</button>

                            </div>
                        </div>
                    </div>
                @endif


                @if ($section === 'profile')
                    <div class="md:flex-1 rounded-md overlay max-w-[520px] min-w-\[var\(500px\)\] sm:col-span-2"
                        style="min-width: 400px;">


                        {{-- <div class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                            <div class="card-text flex flex-col justify-between h-full menu-open">
                                <p class="mb-2">
                                    <b>Owned Cars ({{ $customer->cars->count() }})</b>

                                </p>

                                @if ($customer->cars->isEmpty())
                                    <p class="text-center m-5 text-primary">No cars added to this customer.</p>
                                @else
                                    @foreach ($customer->cars as $car)
                                        <div class="card-body flex flex-col justify-between border rounded-lg h-full menu-open p-0 mb-5" style="border-color:rgb(224, 224, 224)">
                                            <div class="break-words flex items-center my-1 m-4">
                                                <h3 class="text-base capitalize py-3">
                                                    <ul class="m-0 p-0 list-none">
                                                        <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                            {{ $car->car->car_model->brand->name }}
                                                            <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                        </li>
                                                        <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                            {{ $car->car->car_model->name }}
                                                            <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                        </li>
                                                        <li class="inline-block relative text-sm top-[3px] text-slate-500 font-Inter dark:text-white mr-5">
                                                            {{ $car->car->category }}
                                                        </li>
                                                    </ul>
                                                </h3>
                                                @if ($car->payment_frequency)
                                                    <span class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize rounded-3xl float-right">{{ $car->payment_frequency }} Payment</span>
                                                @endif

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
                                                                    <button wire:click="editThisCar({{ $car->id }})"
                                                                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                    dark:hover:text-white">
                                                                        Edit</button>
                                                                </li>
                                                                <li>
                                                                    <button wire:click="deleteThisCar({{ $car->id }})"
                                                                        class="text-slate-600 dark:text-white block font-Inter text-left font-normal w-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                    dark:hover:text-white">
                                                                        Delete</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>

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
                                @endif

                                <button wire:click="toggleAddCar" class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add car</button>

                            </div>
                        </div> --}}

                        <div class="card-body flex flex-col justify-center bg-cover card p-4 ">
                            <div class="card-text flex flex-col justify-between  menu-open">
                                <p>
                                    <b>Phones</b>
                                </p>
                                <br>

                                @if ($customer->phones->isEmpty())
                                    <p class="text-center m-5 text-primary">No cars Phones to this customer.</p>
                                @else
                                    @foreach ($customer->phones as $phone)
                                        <div class="flex items-center ">
                                            @if ($phone->is_default)
                                                <iconify-icon class="text-primary"
                                                    icon="material-symbols:star"></iconify-icon>
                                            @endif
                                            <b class="mr-auto">{{ ucfirst($phone->type) }}</b>


                                            <div class="ml-auto">
                                                <div class="relative">
                                                    <div class="dropdown relative">
                                                        <button class="text-xl text-center block w-full "
                                                            type="button" id="tableDropdownMenuButton1"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <iconify-icon
                                                                icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                        </button>
                                                        <ul
                                                            class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">

                                                            <li>
                                                                <button
                                                                    wire:click="setPhoneAsDefault({{ $phone->id }})"
                                                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Set as primary</button>
                                                            </li>
                                                            <li>
                                                                <button
                                                                    wire:click="editThisPhone({{ $phone->id }})"
                                                                    class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                    Edit</button>
                                                            </li>
                                                            <li>
                                                                <button
                                                                    wire:click="deleteThisPhone({{ $phone->id }})"
                                                                    class="text-slate-600 dark:text-white block font-Inter text-left font-normal w-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600  dark:hover:text-white">
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

                            <button wire:click="toggleAddPhone"
                                class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add
                                Phone</button>
                        </div>

                        <div class="card-body flex flex-col justify-center bg-cover card p-4 mt-5">
                            <div class="card-text flex flex-col justify-between  menu-open">
                                <p>
                                    <b>Addresses</b>
                                </p>
                                <br>
                                @if ($customer->addresses->isEmpty())
                                    <p class="text-center m-5 text-primary">No addresses added to this customer.</p>
                                @else
                                    @foreach ($customer->addresses as $address)
                                        <p><b>Address {{ $loop->index + 1 }}</b>
                                            <button wire:click="deleteThisAddress({{ $address->id }})"
                                                class="action-btn float-right" type="button">
                                                <iconify-icon icon="heroicons:trash"></iconify-icon>
                                            </button>
                                            <button wire:click="editThisAddress({{ $address->id }})"
                                                class="action-btn float-right mr-1" type="button">
                                                <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                            </button>
                                        </p>
                                        <p>{{ $address->line_1 }}</p>
                                        <p>{{ $address->line_2 }}</p>
                                        <p>Flat: {{ $address->flat }}, Building: {{ $address->building }}</p>
                                        <p>{{ $address->area }}, {{ $address->city }}, {{ $address->country }}</p>
                                        <br>
                                    @endforeach
                                @endif
                                <button wire:click="toggleAddAddress"
                                    class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add
                                    address</button>
                            </div>
                        </div>

                        <div
                            class="card-body flex flex-col justify-center mt-5  bg-no-repeat bg-center bg-cover card p-4 active">
                            <div class="card-text flex flex-col justify-between h-full menu-open">
                                <p class="mb-2">
                                    <b>Relatives</b>
                                </p>

                                @if ($customer->relatives->isEmpty())
                                    <p class="text-center m-5 text-primary">No relatives added to this customer.</p>
                                @else
                                    @foreach ($customer->relatives as $relative)
                                        <p><b> {{ $relative->name }}</b>
                                            <button wire:click="deleteThisRelative({{ $relative->id }})"
                                                class="action-btn float-right" type="button">
                                                <iconify-icon icon="heroicons:trash"></iconify-icon>
                                            </button>
                                            <button wire:click="editThisRelative({{ $relative->id }})"
                                                class="action-btn float-right mr-1" type="button">
                                                <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                            </button>
                                        </p>

                                        <p> {{ $relative->phone ?? 'N/A' }} | {{ $relative->relation ?? 'N/A' }},
                                            {{ $relative->gender ?? 'N/A' }},
                                            {{ $relative->birth_date != null ? $relative->birth_date->format('d/m/Y') : 'N/A' }}
                                        </p>
                                        <br>
                                    @endforeach
                                @endif

                                <button wire:click="toggleAddRelative"
                                    class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add
                                    relative</button>

                            </div>
                        </div>

                        <div
                            class="card-body flex flex-col justify-center mt-5  bg-no-repeat bg-center bg-cover card p-4 active">
                            <div class="card-text flex flex-col justify-between h-full menu-open">
                                <p class="mb-2">
                                    <b>Relative Customers</b>
                                </p>
                                @if ($customer->customer_relatives->isEmpty())
                                    <p class="text-center m-5 text-primary">No customer relatives added to this
                                        customer.</p>
                                @else
                                    @foreach ($customer->customer_relatives as $cust_relative)
                                        <p><b> {{ $cust_relative->name }}</b>
                                            <button wire:click="deleteThisRelativeCustomer({{ $cust_relative->id }})"
                                                class="action-btn float-right" type="button">
                                                <iconify-icon icon="heroicons:trash"></iconify-icon>
                                            </button>
                                        </p>


                                        <p>
                                            {{ ucwords($cust_relative->pivot->relation) }}
                                        </p>
                                        <br>
                                    @endforeach
                                @endif

                                <button wire:click="toggleAddCustomerRelative"
                                    class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add
                                    relative</button>

                            </div>
                        </div>

                        <button wire:click="deleteCustomer({{ $customer->id }})" class="btn btn-danger mt-2">Delete Customer</button>
                    </div>

                    <div class="md:flex-1 rounded-md overlay  max-w-[400px] min-w-[310px] sm:col-span-2">

                        {{-- note section --}}
                        <div
                            class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active">
                            <div class="card-text flex flex-col justify-between h-full menu-open">
                                <p>
                                    <b>Note</b>
                                    <span class="float-right cursor-pointer text-slate-500" wire:click="openEditNote">
                                        <iconify-icon icon="material-symbols:edit-outline"></iconify-icon>
                                    </span>
                                </p>
                                <p class="text-wrap">{{ $customer->note }}</p>
                            </div>
                        </div>



                        <div class="card-body  flex flex-col justify-center mt-5 bg-cover card p-4 active">
                            <div class="card-text flex flex-col justify-between h-full menu-open">
                                <p>
                                    <b>Customer</b>
                                    <span class="float-right cursor-pointer text-slate-500"
                                        wire:click="toggleEditCustomer">
                                        <iconify-icon icon="material-symbols:edit-outline"></iconify-icon>
                                    </span>
                                </p>
                                <br>

                                <p><b>Contact information</b></p>
                                <a href="">{{ $customer->email ?? 'N/A' }}</a>
                                <br>

                                <p class="mb-1"><b>National ID</b></p>
                                <p>{{ $customer->id_type ?? 'N/A' }} @if ($customer->id_doc)
                                        | <span wire:click="downloadDoc('{{ $customer->id_doc }}')"
                                            class="text-primary-500 cursor-pointer">download</span>
                                    @endif
                                </p>
                                <br>

                                <p class="mb-1"><b>National ID 2</b></p>
                                <p>{{ $customer->id_type ?? 'N/A' }} @if ($customer->id_doc_2)
                                        | <span wire:click="downloadDoc('{{ $customer->id_doc_2 }}')"
                                            class="text-primary-500 cursor-pointer">download</span>
                                    @endif
                                </p>
                                <br>


                                <p class="mb-1"><b>Marital status</b></p>
                                <p>{{ $customer->marital_status ?? 'N/A' }} </p>
                                <br>

                                <p class="mb-1"><b>Nationality</b></p>
                                <p>{{ $customer->country->name ?? 'N/A' }}</p>
                                <br>

                                <p class="mb-1"><b>Birth Date</b></p>
                                <p>
                                    {{ $customer->birth_date ? $customer->birth_date->format('d/m/Y') : 'N/A' }}
                                    <span class="text-xs">
                                        {{ $customer->birth_date ? '(' . $customer->birth_date->diffInYears(now()) . 'years)' : null }}
                                    </span>
                                </p>
                                <br>

                                <p class="mb-1"><b>Driver License</b></p>
                                <p>
                                    @if ($customer->driver_license_doc)
                                        <span wire:click="downloadDoc('{{ $customer->driver_license_doc }}')"
                                            class="text-primary-500 cursor-pointer">download license</span>
                                    @else
                                        No driver license provided.
                                    @endif
                                </p>

                                <p class="mb-1"><b>Driver License 2</b></p>
                                <p>
                                    @if ($customer->driver_license_doc_2)
                                        <span wire:click="downloadDoc('{{ $customer->driver_license_doc_2 }}')"
                                            class="text-primary-500 cursor-pointer">download license</span>
                                    @else
                                        No driver license document 2 provided.
                                    @endif
                                </p>


                            </div>
                        </div>




                        {{-- bank accounts section --}}
                        <div
                            class="card-body flex flex-col justify-center mt-5  bg-no-repeat bg-center bg-cover card p-4 active">
                            <div class="card-text flex flex-col justify-between h-full menu-open">
                                <p class="mb-2">
                                    <b>Bank Accounts</b>
                                </p>

                                @if ($customer->bank_accounts->isEmpty())
                                    <p class="text-center m-5 text-primary">No bank accounts added to this customer.
                                    </p>
                                @else
                                    @foreach ($customer->bank_accounts as $account)
                                        <p><b> {{ $account->bank_name }}</b>
                                            <button wire:click="deleteThisBankAccount({{ $account->id }})"
                                                class="action-btn float-right" type="button">
                                                <iconify-icon icon="heroicons:trash"></iconify-icon>
                                            </button>
                                            <button wire:click="editThisBankAccount({{ $account->id }})"
                                                class="action-btn float-right mr-1" type="button">
                                                <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                            </button>
                                        </p>

                                        <p>{{ $account->type }}</p>
                                        <p>{{ $account->account_number }}</p>
                                        <p>{{ $account->owner_name }}</p>
                                        <p>{{ $account->bank_branch }}</p>
                                        <p>{{ $account->iban }}</p>
                                        @if ($account->evidence_doc)
                                            <span wire:click="downloadEvidenceDoc('{{ $account->evidence_doc }}')"
                                                class="text-primary-500 cursor-pointer">download</span>
                                        @endif
                                        <br>
                                    @endforeach
                                @endif

                                <button wire:click="toggleAddBankAccount"
                                    class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add
                                    Bank Account</button>

                            </div>
                        </div>



                        {{-- Interests section --}}
                        <div
                            class="card-body flex flex-col justify-center mt-5  bg-no-repeat bg-center bg-cover card p-4 active">
                            <div class="card-text flex flex-col justify-between h-full menu-open">
                                <p class="mb-2">
                                    <b>Policy Interests</b>
                                </p>





                                @foreach ($LINES_OF_BUSINESS as $lob)
                                    @php
                                        $customerHasInterest = false;
                                        $interestStatus = 'N/A';
                                        $interestNote = '';
                                        $timestamp = null;
                                    @endphp
                                    @foreach ($customer->interests as $interest)
                                        @if ($interest->business === $lob)
                                            @php
                                                $customerHasInterest = true;
                                                $interestStatus = $interest->interested ? 'YES' : 'NO';
                                                $interestNote = $interest->note;
                                                $timestamp = $interest->created_at ?? null;
                                            @endphp
                                        @break
                                    @endif
                                @endforeach

                                <div class="flex">
                                    <span
                                        class="badge bg-slate-900 text-white capitalize inline-flex items-center mr-3">
                                        @if ($interestStatus === 'YES')
                                            <iconify-icon class="ltr:mr-1 rtl:ml-1 text-info-500"
                                                icon="mdi:stars"></iconify-icon>
                                        @elseif($interestStatus === 'NO')
                                            <iconify-icon class="ltr:mr-1 rtl:ml-1 text-danger-500"
                                                icon="mdi:stars"></iconify-icon>
                                        @endif
                                        {{ ucwords(str_replace('_', ' ', $lob)) }}
                                    </span>

                                    @if ($interestStatus === 'YES')
                                        <span
                                            class="badge bg-success-500 text-white capitalize inline-flex items-center">YES</span>
                                    @elseif($interestStatus === 'NO')
                                        <span
                                            class="badge bg-danger-500 text-white capitalize inline-flex items-center">NO</span>
                                    @else
                                        <span
                                            class="badge bg-slate-300 text-white capitalize inline-flex items-center">N/A</span>
                                    @endif


                                    <div class="ml-auto">
                                        <div class="relative">
                                            <div class="dropdown relative">
                                                <button class="text-xl text-center block w-full " type="button"
                                                    id="tableDropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <iconify-icon
                                                        icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                </button>
                                                <ul
                                                    class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                    @if ($interestStatus !== 'N/A')
                                                        <li>
                                                            <button
                                                                wire:click="removeInterest({{ $interest->id }})"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                Remove</button>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <button
                                                            wire:click="editThisInterest('YES','{{ $lob }}')"
                                                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                            Yes</button>
                                                    </li>
                                                    <li>
                                                        <button
                                                            wire:click="editThisInterest('NO','{{ $lob }}')"
                                                            class="text-slate-600 dark:text-white block font-Inter text-left font-normal w-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600  dark:hover:text-white">
                                                            No</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($interestNote)
                                    <p>Note: {{ $interestNote }}</p>
                                @endif
                                <p class="ml-auto">{{ $timestamp }}</p>
                                <br>
                            @endforeach




                        </div>
                    </div>




                    <div class="card-body flex flex-col justify-center bg-cover card p-4 mt-5">
                        <div class="card-text flex flex-col justify-between  menu-open">
                            <p>
                                <b>Profession</b>
                            </p>
                            <p>{{ $customer->profession->title ?? 'N/A' }}</p>
                            <br>

                            <p><b>Salary Range</b></p>
                            <p>{{ $customer->salary_range ? str_replace('_to_', 'K to ', $customer->salary_range) . 'K' : 'N/A' }}

                            </p>
                            <br>

                            <p><b>Income source</b></p>
                            <p>{{ $customer->income_source ?? 'N/A' }}</p>
                            <br>
                        </div>
                    </div>



                    <div
                        class="card-body flex flex-col justify-center  bg-no-repeat bg-center bg-cover card p-4 active  mt-5">
                        <div class="card-text flex flex-col justify-between h-full menu-open">
                            <p>
                                Owned by
                            </p>
                            <p class="text-wrap"><b>{{ $customer->owner?->first_name }}
                                    {{ $customer->owner?->last_name }}</b></p>
                        </div>
                    </div>


                </div>
            @endif

            @if ($section === 'followups')
                <div class="card-body flex flex-col justify-center bg-cover card p-4 mt-5  col-span-2">
                    <div class="card-text flex flex-col justify-between  menu-open">
                        <p>
                            <b>Followups</b>
                        </p>
                        <br>

                        @if ($customer->followups->isEmpty())
                            <p class="text-center m-5 text-primary">No Followups for this customer.</p>
                        @else
                            @foreach ($customer->followups as $followup)
                                <div class="flex items-center ">

                                    <div>
                                        <b class="mr-auto">{{ ucfirst($followup->title) }}</b>
                                        @if ($followup->line_of_business)
                                            <p>{{ ucwords(str_replace('_',' ',$followup->line_of_business)) }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="ml-auto">
                                        <div class="relative flex">
                                            @if ($followup->is_meeting)
                                                <span
                                                    class="badge bg-primary-500 text-white capitalize rounded-3xl mr-2">Meeting</span>
                                            @endif

                                            <span
                                                class="badge bg-slate-900 text-slate-900 dark:text-slate-200 bg-opacity-30 capitalize ml-auto h-auto">{{ $followup->status }}</span>

                                            <div class="dropdown relative">
                                                <button class="text-xl text-center block w-full " type="button"
                                                    id="tableDropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <iconify-icon
                                                        icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                </button>
                                                <ul
                                                    class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">

                                                    @if ($followup->status === 'new')
                                                        <li>
                                                            <button
                                                                wire:click="editThisFollowup({{ $followup->id }})"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                Edit</button>
                                                        </li>
                                                        <li>
                                                            <button
                                                                wire:click="toggleCallerNote('called',{{ $followup->id }})"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                Set as called</button>
                                                        </li>
                                                        <li>
                                                            <button
                                                                wire:click="toggleCallerNote('cancelled',{{ $followup->id }})"
                                                                class="text-slate-600 dark:text-white block font-Inter font-normal px-4  w-full text-left py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                                Set as cancelled</button>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <button
                                                            wire:click="deleteThisFollowup({{ $followup->id }})"
                                                            class="text-slate-600 dark:text-white block font-Inter text-left font-normal w-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                            Delete</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p><b>Desc:</b> {{ $followup->desc }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 text-right">
                                    {{ $followup->call_time }}</p>
                                <br>
                            @endforeach
                        @endif
                    </div>


                    <button wire:click="OpenAddFollowupSection"
                        class="btn inline-flex justify-center btn-light rounded-[25px] btn-sm float-right">Add
                        Followup</button>
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
                                            <a
                                                class=" text-slate-600 dark:text-slate-300 block w-full px-4 py-3 text-sm mb-2 last:mb-0">
                                                <div class="flex ltr:text-left rtl:text-right">
                                                    <div class="flex-none ltr:mr-3 rtl:ml-3">
                                                        <div
                                                            class="h-8 w-8 bg-white dark:bg-slate-700 rounded-full relative">
                                                            <span
                                                                class="block w-full h-full object-cover text-center text-lg leading-8">
                                                                {{ strtoupper(substr($task->open_by->first_name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div
                                                            class="text-slate-800 dark:text-slate-300 text-sm font-medium mb-1`">
                                                            {{ $task->open_by->first_name . ' ' . $task->open_by->last_name }}
                                                        </div>
                                                        <div
                                                            class="text-xs hover:text-[#68768A] font-normal text-slate-600 dark:text-slate-300">
                                                            <b>
                                                                {{ $task->title }}
                                                            </b>
                                                            @if ($task->status === 'new')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-primary-500 text-xs">
                                                                    New
                                                                </div>
                                                            @elseif($task->status === 'assigned')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-info-500 text-xs">
                                                                    Assigned
                                                                </div>
                                                            @elseif($task->status === 'in_progress')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-secondary-500 text-xs">
                                                                    in Progress
                                                                </div>
                                                            @elseif($task->status === 'pending')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-warning-500 text-xs">
                                                                    Pending
                                                                </div>
                                                            @elseif($task->status === 'completed')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-success-500 text-xs">
                                                                    Completed
                                                                </div>
                                                            @elseif($task->status === 'closed')
                                                                <div
                                                                    class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 bg-black-500 text-xs">
                                                                    Closed
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div
                                                            class="text-xs hover:text-[#68768A] font-normal text-slate-600 dark:text-slate-300 text-break">
                                                            {{ $task->desc }}
                                                        </div>
                                                        <div
                                                            class="text-slate-400 dark:text-slate-400 text-xs mt-1">
                                                            {{ $task->created_at }}
                                                            <span class="float-right">
                                                                {{ $task->comments->count() }} <iconify-icon
                                                                    icon="fa6-regular:comment"></iconify-icon></span>
                                                            <span class="float-right mr-3">
                                                                {{ $task->files->count() }} <iconify-icon
                                                                    icon="ph:files-bold"></iconify-icon></iconify-icon></span>
                                                        </div>
                                                    </div>



                                                    <div class="flex-0">
                                                        <button wire:click="redirectToTask({{ $task->id }})"
                                                            class="btn btn-sm inline-flex justify-center btn-light light">view</button>
                                                    </div>

                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                    @if ($tasks->isEmpty())
                                        <li class="p-2">
                                            <div
                                                class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500">
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
                                        <iconify-icon
                                            class="text-3xl inline-block ltr:mr-2 rtl:ml-2 text-success-500"
                                            icon="ic:outline-local-offer"></iconify-icon>
                                        <h3 class="card-title mb-0 text-success-500">
                                            <ul class="m-0 p-0 list-none">
                                                <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                    {{ $offer->item->car->car_model->brand->name }}
                                                    <iconify-icon icon="heroicons-outline:chevron-right"
                                                        class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                </li>
                                                <li class="inline-block relative top-[3px] text-base font-Inter ">
                                                    {{ $offer->item->car->car_model->name }}
                                                    <iconify-icon icon="heroicons-outline:chevron-right"
                                                        class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                                </li>
                                                <li
                                                    class="inline-block relative text-sm top-[3px] text-slate-500 font-Inter dark:text-white mr-5">
                                                    {{ $offer->item->car->category }}
                                                </li>
                                            </ul>
                                        </h3>
                                    </div>

                                    <div>
                                        <button wire:click="redirectToOffer({{ $offer->id }})"
                                            class="btn btn-sm inline-flex justify-center btn-light light">view</button>
                                    </div>
                                </header>
                                <div class="py-3 px-5">
                                    {{-- <h5 class="card-subtitle">Card Subtitle</h5> --}}
                                    <div class="grid grid-cols-4 mb-4">
                                        <div class="border-r ml-5 col-span-2">
                                            <p><b>Item Details</b></p>
                                            <p class="text-wrap pr-3">
                                                {{ $offer->item_title }}312312331231331231231213</p>
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
                                                <p class="card-text mt-3 text-wrap"><b>Due: </b>
                                                    {{ $offer->due }}</p>
                                            @endif
                                        </div>

                                        <div class="float-right">
                                            @if ($offer->status === 'new')
                                                <span class="badge bg-info-500 h-auto">
                                                    <iconify-icon
                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                </span>
                                            @elseif(str_contains($offer->status, 'pending'))
                                                <span class="badge bg-warning-500 h-auto">
                                                    <iconify-icon
                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                </span>
                                            @elseif(str_contains($offer->status, 'declined') || str_contains($offer->status, 'cancelled'))
                                                <span class="badge bg-danger-500 h-auto">
                                                    <iconify-icon
                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
                                                </span>
                                            @elseif($offer->status === 'approved')
                                                <span class="badge bg-success-500 h-auto">
                                                    <iconify-icon
                                                        icon="pajamas:status"></iconify-icon>&nbsp;{{ ucwords(str_replace('_', ' ', $offer->status)) }}
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
                    <div
                        class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] w-full text-warning-500">
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

@if ($customerNoteSec)
    {{-- add address section --}}
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
                            Edit Note
                        </h3>
                        <button wire:click="closeEditNote" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                <label for="customerNote" class="form-label">Note</label>
                                <input id="customerNote" type="text"
                                    class="form-control @error('customerNote') !border-danger-500 @enderror"
                                    wire:model.defer="customerNote">
                            </div>
                            @error('customerNote')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="setNote" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($deleteCarId)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
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
                            Delete Car
                        </h3>
                        <button wire:click="dismissDeleteCar" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-4">
                        <h6 class="text-base text-slate-900 dark:text-white leading-6">
                            Are you sure ! you Want to delete this Car ?
                        </h6>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="deleteCar" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($deleteRelativeCustId)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
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
                            Delete Relative Customer
                        </h3>
                        <button wire:click="dismissDeleteRelativeCustomer" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-4">
                        <h6 class="text-base text-slate-900 dark:text-white leading-6">
                            Are you sure ! you Want to delete this relative customer ?
                        </h6>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="deleteRelativeCustomer" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($deletePhoneId)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
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
                            Delete Phone
                        </h3>
                        <button wire:click="dismissDeletePhone" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-4">
                        <h6 class="text-base text-slate-900 dark:text-white leading-6">
                            Are you sure ! you Want to delete this Phone ?
                        </h6>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="deletePhone" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($deleteFollowupId)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
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
                            Delete Followup
                        </h3>
                        <button wire:click="dismissDeleteFollowup" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                    <div
                        class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="deleteFollowup" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($deleteAddressId)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
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
                            Delete Address
                        </h3>
                        <button wire:click="dismissDeleteAddress" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-4">
                        <h6 class="text-base text-slate-900 dark:text-white leading-6">
                            Are you sure ! you want to delete this Address ?
                        </h6>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="deleteAddress" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($deleteRelativeId)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
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
                            Delete Relative
                        </h3>
                        <button wire:click="dismissDeleteRelative" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-4">
                        <h6 class="text-base text-slate-900 dark:text-white leading-6">
                            Are you sure ! you want to delete this Relative ?
                        </h6>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="deleteRelative" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($editCustomerSection)
    {{-- edit customer section --}}
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        id="basic_modal" tabindex="-1" aria-labelledby="basic_modal" aria-hidden="true"
        style="display: block">
        <!-- BEGIN: Modal -->
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                            Edit customer
                        </h3>
                        <button wire:click="toggleEditCustomer" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input id="firstName" type="text"
                                        class="form-control @error('firstName') !border-danger-500 @enderror"
                                        wire:model.defer="firstName">
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Middle Name</label>
                                    <input id="firstName" type="text"
                                        class="form-control @error('middleName') !border-danger-500 @enderror"
                                        wire:model.defer="middleName">
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Last Name</label>
                                    <input id="firstName" type="text"
                                        class="form-control @error('lastName') !border-danger-500 @enderror"
                                        wire:model.defer="lastName">
                                </div>
                            </div>
                            @error('firstName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                            @error('middleName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                            @error('lastName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Arabic First Name</label>
                                    <input id="firstName" type="text"
                                        class="form-control @error('ArabicFirstName') !border-danger-500 @enderror"
                                        wire:model.defer="ArabicFirstName">
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Arabic Middle Name</label>
                                    <input id="firstName" type="text"
                                        class="form-control @error('ArabicMiddleName') !border-danger-500 @enderror"
                                        wire:model.defer="ArabicMiddleName">
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Arabic Last Name</label>
                                    <input id="firstName" type="text"
                                        class="form-control @error('ArabicLastName') !border-danger-500 @enderror"
                                        wire:model.defer="ArabicLastName">
                                </div>
                            </div>
                            @error('ArabicFirstName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                            @error('ArabicMiddleName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                            @error('ArabicLastName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="input-area mt-2">
                                <label for="name" class="form-label">Email</label>
                                <input id="name" type="text"
                                    class="form-control @error('email') !border-danger-500 @enderror"
                                    wire:model="email">
                            </div>
                            @error('email')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-2">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Birth Date</label>
                                    <input type="date"
                                        class="form-control @error('bdate') !border-danger-500 @enderror"
                                        wire:model="bdate">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Gender</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2 @error('gender') !border-danger-500 @enderror"
                                        wire:model="gender">
                                        @foreach ($GENDERS as $gender)
                                            <option value="{{ $gender }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Marital Status</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2 @error('maritalStatus') !border-danger-500 @enderror"
                                        wire:model="maritalStatus">
                                        @foreach ($MARITALSTATUSES as $status)
                                            <option value="{{ $status }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('bdate')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('gender')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('maritalStatus')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <hr class="mt-5">
                            <p class="mt-3 text-lg"><b>National Info</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">ID Type</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2 @error('idType') !border-danger-500 @enderror"
                                        wire:model="idType">
                                        <option>None</option>
                                        @foreach ($IDTYPES as $idtype)
                                            <option value="{{ $idtype }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ str_replace('_', ' ', $idtype) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">ID Number</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('idNumber') !border-danger-500 @enderror"
                                        wire:model="idNumber">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Nationality</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2 @error('nationalId') !border-danger-500 @enderror"
                                        wire:model="nationalId">
                                        <option>None</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area col-span-3">
                                    @if (!$idDoc)
                                        <label for="lastName" class="form-label">ID document</label>
                                        <input wire:model="idDoc" type="file" class="form-control w-full "
                                            name="basic" />
                                    @else
                                        <span class="block min-w-[140px] text-left">
                                            <span class="inline-block text-center text-sm mx-auto py-1">
                                                <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                    <span
                                                        class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                    <span>
                                                        Document added
                                                        <span wire:click="clearIdDoc"
                                                            class="text-xs text-slate-500 dark:text-slate-400 mt-1 cursor-pointer">|
                                                            remove</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    @endif
                                </div>
                                <div class="input-area col-span-3">
                                    @if (!$idDoc2)
                                        <label for="lastName" class="form-label">ID document 2</label>
                                        <input wire:model="idDoc2" type="file" class="form-control w-full "
                                            name="basic" />
                                    @else
                                        <span class="block min-w-[140px] text-left">
                                            <span class="inline-block text-center text-sm mx-auto py-1">
                                                <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                    <span
                                                        class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                    <span>
                                                        Document 2 added
                                                        <span wire:click="clearIdDoc2"
                                                            class="text-xs text-slate-500 dark:text-slate-400 mt-1 cursor-pointer">|
                                                            remove</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @error('idType')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('idNumber')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('nationalId')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <hr class="mt-5">
                            <p class="mt-3 text-lg"><b>Profession</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Profession title</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2 @error('profession_id') !border-danger-500 @enderror"
                                        wire:model="profession_id">
                                        <option>None</option>
                                        @foreach ($professions as $profession)
                                            <option value="{{ $profession->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $profession->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Salary range</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2 @error('salaryRange') !border-danger-500 @enderror"
                                        wire:model="salaryRange">
                                        <option>None</option>
                                        @foreach ($SALARY_RANGES as $range)
                                            <option value="{{ $range }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ str_replace('_to_', 'K to ', $range) }}K</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Income source</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2 @error('incomeSource') !border-danger-500 @enderror"
                                        wire:model="incomeSource">
                                        <option>None</option>
                                        @foreach ($INCOME_SOURCES as $source)
                                            <option value="{{ $source }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $source }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('profession_id')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('salaryRange')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('incomeSource')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <hr class="mt-5">
                            <p class="mt-3 text-lg"><b>Driver License Document</b></p>
                            <div class="input-area ">
                                @if (!$driverLicenseDoc)
                                    <input wire:model="driverLicenseDoc" type="file"
                                        class="form-control w-full " name="basic" />
                                @else
                                    <span class="block min-w-[140px] text-left">
                                        <span class="inline-block text-center text-sm mx-auto py-1">
                                            <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                <span
                                                    class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                <span>
                                                    Document added
                                                    <span wire:click="cleardriverLicenseDoc"
                                                        class="text-xs text-slate-500 dark:text-slate-400 mt-1 cursor-pointer">|
                                                        remove</span>
                                                </span>
                                            </span>
                                        </span>
                                    </span>
                                @endif
                            </div>
                            <div class="input-area ">
                                @if (!$driverLicenseDoc2)
                                    <input wire:model="driverLicenseDoc2" type="file"
                                        class="form-control w-full " name="basic" />
                                @else
                                    <span class="block min-w-[140px] text-left">
                                        <span class="inline-block text-center text-sm mx-auto py-1">
                                            <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                <span
                                                    class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                <span>
                                                    Document 2 added
                                                    <span wire:click="cleardriverLicenseDoc2"
                                                        class="text-xs text-slate-500 dark:text-slate-400 mt-1 cursor-pointer">|
                                                        remove</span>
                                                </span>
                                            </span>
                                        </span>
                                    </span>
                                @endif
                            </div>
                            @error('driverLicenseDoc')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('driverLicenseDoc2')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="editInfo" data-bs-dismiss="modal" wire:loading.remove
                            wire:target="addCustomer"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Accept
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif

@if ($addCarSection)
    {{-- add car section --}}
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
                            Add car to Customer
                        </h3>
                        <button wire:click="toggleAddCar" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-4">
                        <div class="from-group">
                            <p class="text-lg"><b>Select Car Model</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Car Brand</label>
                                    <select name="basicSelect" class="form-control w-full mt-2"
                                        wire:model="carBrand">
                                        <option value=''>Select an Option</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($carBrand && $carBrand !== '')
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Car Model</label>

                                        <select name="basicSelect" class="form-control w-full mt-2"
                                            wire:model="carModel">
                                            <option value=''>Select an Option</option>
                                            @foreach ($models as $model)
                                                <option value="{{ $model->id }}"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    {{ $model->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if ($carModel && $carModel !== '' && $carBrand && $carBrand !== '')
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Car Category</label>
                                        <select name="basicSelect"
                                            class="form-control w-full mt-2 @error('CarCategory') !border-danger-500 @enderror"
                                            wire:model="CarCategory">
                                            <option selected>Select an Option</option>
                                            @foreach ($cars as $car)
                                                <option value="{{ $car->id }}"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    {{ $car->category }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if ($CarCategory && $CarCategory !== '')
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Car Model Year</label>
                                        <select name="basicSelect"
                                            class="form-control w-full mt-2 @error('modelYear') !border-danger-500 @enderror"
                                            wire:model="modelYear">
                                            <option selected>Select an Option</option>
                                            @foreach ($modelYears as $year)
                                                <option value="{{ $year->model_year }}">
                                                    {{ $year->model_year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            @error('modelYear')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <hr class="mt-5">
                            <p class="mt-3 text-lg"><b>Insurance Info</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Sum insurance</label>
                                    <input id="lastName" type="number"
                                        class="form-control @error('sumInsurance') !border-danger-500 @enderror"
                                        wire:model="sumInsurance">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Insurance payment</label>
                                    <input id="lastName" type="number"
                                        class="form-control @error('insurancePayment') !border-danger-500 @enderror"
                                        wire:model="insurancePayment">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Payment frequency</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2  @error('paymentFreqs') !border-danger-500 @enderror"
                                        wire:model="paymentFreqs">
                                        @foreach ($PAYMENT_FREQS as $freqs)
                                            <option value="{{ $freqs }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $freqs }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('sumInsurance')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('insurancePayment')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('paymentFreqs')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Insurance Company</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2 @error('insuranceCompanyId') !border-danger-500 @enderror"
                                        wire:model="insuranceCompanyId">
                                        <option
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select an option...</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords($company->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Renewal Date</label>
                                    <input id="lastName" type="date"
                                        class="form-control @error('renewalDate') !border-danger-500 @enderror"
                                        wire:model="renewalDate">
                                </div>
                                <div class="checkbox-area">
                                    <label for="lastName" class="form-label">Wise Insured</label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden" name="checkbox"
                                            wire:model="wiseInsured">
                                        <span
                                            class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                alt=""
                                                class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6"></span>
                                    </label>
                                </div>
                            </div>
                            @error('insuranceCompanyId')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('renewalDate')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('wiseInsured')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror


                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="addCar" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($editedCarId)
    {{-- add car section --}}
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
                            Edit car
                        </h3>
                        <button wire:click="closeEditCar" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-4">
                        <div class="from-group">
                            <p class="text-lg"><b>Select Car Model</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Car Brand</label>
                                    <select name="basicSelect"
                                        class="form-control w-full mt-2 @error('carBrand') !border-danger-500 @enderror"
                                        wire:model="carBrand">
                                        <option value=''>Select an Option</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($carBrand && $carBrand !== '')
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Car Model</label>

                                        <select name="basicSelect"
                                            class="form-control w-full mt-2 @error('carModel') !border-danger-500 @enderror"
                                            wire:model="carModel">
                                            <option value=''>Select an Option</option>
                                            @foreach ($models as $model)
                                                <option value="{{ $model->id }}"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    {{ $model->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if ($carModel && $carModel !== '' && $carBrand && $carBrand !== '')
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Car Category</label>
                                        <select name="basicSelect"
                                            class="form-control w-full mt-2  @error('CarCategory') !border-danger-500 @enderror"
                                            wire:model="CarCategory">
                                            <option selected>Select an Option</option>
                                            @foreach ($cars as $car)
                                                <option value="{{ $car->id }}"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    {{ $car->category }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if ($CarCategory && $CarCategory !== '')
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Car Model Year</label>
                                        <select name="basicSelect"
                                            class="form-control w-full mt-2 @error('modelYear') !border-danger-500 @enderror"
                                            wire:model="modelYear">
                                            <option selected>Select an Option</option>
                                            @foreach ($modelYears as $year)
                                                <option value="{{ $year->model_year }}">
                                                    {{ $year->model_year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                            </div>
                            @error('modelYear')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <hr class="mt-5">
                            <p class="mt-3 text-lg"><b>Insurance Info</b></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Sum insurance</label>
                                    <input id="lastName" type="number"
                                        class="form-control @error('sumInsurance') !border-danger-500 @enderror"
                                        wire:model="sumInsurance">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Insurance payment</label>
                                    <input id="lastName" type="number"
                                        class="form-control @error('insurancePayment') !border-danger-500 @enderror"
                                        wire:model="insurancePayment">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Payment frequency</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2 @error('paymentFreqs') !border-danger-500 @enderror"
                                        wire:model="paymentFreqs">
                                        <option
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select an option...</option>
                                        @foreach ($PAYMENT_FREQS as $freqs)
                                            <option value="{{ $freqs }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $freqs }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('sumInsurance')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('insurancePayment')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('paymentFreqs')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Insurance Company</label>
                                    <select name="basicSelect" id="basicSelect"
                                        class="form-control w-full mt-2 @error('insuranceCompanyId') !border-danger-500 @enderror"
                                        wire:model="insuranceCompanyId">
                                        <option
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            Select an option...</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords($company->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Renewal Date</label>
                                    <input id="lastName" type="date"
                                        class="form-control @error('renewalDate') !border-danger-500 @enderror"
                                        wire:model="renewalDate">
                                </div>
                                <div class="checkbox-area">
                                    <label for="lastName" class="form-label">Wise Insured</label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden" name="checkbox"
                                            wire:model="wiseInsured">
                                        <span
                                            class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                alt=""
                                                class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6"></span>
                                    </label>
                                </div>
                            </div>
                            @error('insuranceCompanyId')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('renewalDate')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('wiseInsured')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="updateCar" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($editedAddressId)
    {{-- add address section --}}
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
                            Edit Address
                        </h3>
                        <button wire:click="closeEditAddress" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                <select name="basicSelect"
                                    class="form-control w-full mt-2 @error('EditedAddressType') !border-danger-500 @enderror"
                                    wire:model="EditedAddressType">
                                    @foreach ($addressTypes as $type)
                                        <option value="{{ $type }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('EditedAddressType')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Line 1</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('EditedLine1') !border-danger-500 @enderror"
                                    wire:model="EditedLine1">
                            </div>
                            @error('EditedLine1')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Line 2</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('EditedLine2') !border-danger-500 @enderror"
                                    wire:model="EditedLine2">
                            </div>
                            @error('EditedLine2')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Flat</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('EditedFlat') !border-danger-500 @enderror"
                                        wire:model="EditedFlat">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Building</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('EditedBuilding') !border-danger-500 @enderror"
                                        wire:model="EditedBuilding">
                                </div>

                                <div class="input-area">
                                    <label for="lastName" class="form-label">Area</label>
                                    <input list="areas" type="text"
                                        class="form-control @error('EditedArea') !border-danger-500 @enderror"
                                        wire:model="EditedArea">
                                    <datalist id="areas">
                                        @foreach ($areas as $areas)
                                            <option value="{{ $areas->name }}"> {{ $areas->name }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">City</label>
                                    <input list="cities" type="text"
                                        class="form-control @error('EditedCity') !border-danger-500 @enderror"
                                        wire:model="EditedCity">
                                    <datalist id="cities">
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->name }}"> {{ $city->name }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Country</label>
                                    <input list="countries" type="text"
                                        class="form-control @error('EditedCountry') !border-danger-500 @enderror"
                                        wire:model="EditedCountry">
                                    <datalist id="countries">
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->name }}"> {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            @error('EditedFlat')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('EditedBuilding')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('EditedCity')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('EditedCountry')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="editAddress" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
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
                            Add Address to Customer
                        </h3>
                        <button wire:click="toggleAddAddress" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                <select name="basicSelect"
                                    class="form-control w-full mt-2 @error('addressType') !border-danger-500 @enderror"
                                    wire:model="addressType">
                                    @foreach ($addressTypes as $type)
                                        <option value="{{ $type }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('addressType')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Line 1</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('line1') !border-danger-500 @enderror"
                                    wire:model="line1">
                            </div>
                            @error('line1')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Line 2</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('line2') !border-danger-500 @enderror"
                                    wire:model="line2">
                            </div>
                            @error('line2')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Flat</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('flat') !border-danger-500 @enderror"
                                        wire:model="flat">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Building</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('building') !border-danger-500 @enderror"
                                        wire:model="building">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Area</label>
                                    <input list="areas" type="text"
                                        class="form-control @error('area') !border-danger-500 @enderror"
                                        wire:model="area">
                                    <datalist id="areas">
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->name }}"> {{ $area->name }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">City</label>
                                    <input list="cities" type="text"
                                        class="form-control @error('city') !border-danger-500 @enderror"
                                        wire:model="city">
                                    <datalist id="cities">
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->name }}"> {{ $city->name }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Country</label>
                                    <input list="countries" type="text"
                                        class="form-control @error('country') !border-danger-500 @enderror"
                                        wire:model="country">
                                    <datalist id="countries">
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->name }}"> {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            @error('flat')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('building')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('city')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('country')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="addAddress" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($addRelativeSection)
    {{-- add address section --}}
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
                            Add Relative
                        </h3>
                        <button wire:click="toggleAddRelative" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                <label for="firstName" class="form-label">Full Name</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('relativeName') !border-danger-500 @enderror"
                                    wire:model="relativeName">
                            </div>
                            @error('relativeName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Relation</label>
                                    <select name="basicSelect"
                                        class="form-control w-full mt-2 @error('relation') !border-danger-500 @enderror"
                                        wire:model="relation">
                                        @foreach ($RELATIONS as $relation)
                                            <option value="{{ $relation }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $relation }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Gender</label>
                                    <select name="basicSelect"
                                        class="form-control w-full mt-2 @error('relativeGender') !border-danger-500 @enderror"
                                        wire:model="relativeGender">
                                        <option selected>Select an Option</option>
                                        @foreach ($GENDERS as $gender)
                                            <option value="{{ $gender }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Relative Phone</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('RelativePhone') !border-danger-500 @enderror"
                                        wire:model="RelativePhone">
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Relative birth date</label>
                                    <input id="lastName" type="date"
                                        class="form-control @error('relativeBdate') !border-danger-500 @enderror"
                                        wire:model="relativeBdate">
                                </div>
                            </div>
                            @error('relation')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('relativeGender')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('RelativePhone')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('relativeBdate')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="addRelative" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($editedRelativeId)
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
                            Edit Relative
                        </h3>
                        <button wire:click="closeEditRelative" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                <input id="lastName" type="text"
                                    class="form-control @error('editedRelativeName') !border-danger-500 @enderror"
                                    wire:model="editedRelativeName">
                            </div>
                            @error('editedRelativeName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Relation</label>
                                    <select name="basicSelect"
                                        class="form-control w-full mt-2 @error('editedRelation') !border-danger-500 @enderror"
                                        wire:model="editedRelation">
                                        @foreach ($RELATIONS as $relation)
                                            <option value="{{ $relation }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $relation }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Gender</label>
                                    <select name="basicSelect"
                                        class="form-control w-full mt-2 @error('editedRelativeGender') !border-danger-500 @enderror"
                                        wire:model="editedRelativeGender">
                                        <option selected>Select an Option</option>
                                        @foreach ($GENDERS as $gender)
                                            <option value="{{ $gender }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Relative Phone</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('editedRelativePhone') !border-danger-500 @enderror"
                                        wire:model="editedRelativePhone">
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Relative birth date</label>
                                    <input id="lastName" type="date"
                                        class="form-control @error('editedRelativeBdate') !border-danger-500 @enderror"
                                        wire:model="editedRelativeBdate">
                                </div>
                            </div>
                            @error('editedRelation')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('editedRelativeGender')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('editedRelativePhone')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('editedRelativeBdate')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="editRelative" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
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
                            Add Phone
                        </h3>
                        <button wire:click="toggleAddPhone" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                    <select name="basicSelect"
                                        class="form-control w-full mt-2 @error('phoneType') !border-danger-500 @enderror"
                                        wire:model.defer="phoneType">
                                        @foreach ($phoneTypes as $type)
                                            <option value="{{ $type }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Number</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('number') !border-danger-500 @enderror"
                                        wire:model.defer="number">
                                </div>
                                <div class="checkbox-area">
                                    <label for="firstName" class="form-label">Set as default</label>
                                    <input type="checkbox" name="checkbox" wire:model.defer="setPhoneDefault">
                                </div>
                            </div>
                            @error('phoneType')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('number')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="addPhone" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($addCustomerRelativeSection)
    {{-- add address section --}}
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
                            Add Customer Relative
                        </h3>
                        <button wire:click="toggleAddCustomerRelative" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                @if ($selectedRelative)
                                    <div class="input-area col-span-2">
                                        <label for="lastName" class="form-label"
                                            style="margin: 0">Customer</label>
                                        <p>{{ $selectedRelative->name }}</p>
                                        <p class="text-sm">{{ $selectedRelative->email }}</p>
                                        <p wire:click="clearCustomerRelative"
                                            class="text-primary-500 cursor-pointer text-sm"><iconify-icon
                                                icon="mdi:clear"></iconify-icon></p><br>
                                    </div>
                                    <div class="input-area col-span-1">
                                        <label for="firstName" class="form-label">Relation</label>
                                        <select name="basicSelect"
                                            class="form-control w-full mt-2 @error('custRelation') !border-danger-500 @enderror"
                                            wire:model.defer="custRelation">
                                            <option value="{{ $relation }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                select an option..</option>
                                            @foreach ($RELATIONS as $relation)
                                                <option value="{{ $relation }}"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    {{ $relation }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="input-area col-span-3">
                                        <label for="firstName" class="form-label">Search Customers <iconify-icon
                                                wire:loading wire:target="searchCustomer"
                                                icon="svg-spinners:180-ring"></iconify-icon></label>
                                        <input id="lastName" type="text" class="form-control"
                                            wire:model="searchCustomer">
                                        <div class="text-sm mt-0">
                                            @if ($customerResult)
                                                @foreach ($customerResult as $searchedCustomer)
                                                    <p><iconify-icon icon="raphael:customer"></iconify-icon>
                                                        {{ $searchedCustomer->name }} |
                                                        {{ $searchedCustomer->email }} | <Span
                                                            wire:click="selectCustomerRelative({{ $searchedCustomer->id }})"
                                                            class="cursor-pointer text-primary-500">Select
                                                            Relative</Span></p>
                                                @endforeach

                                            @endif
                                        </div>

                                    </div>
                                @endif

                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="addRelariveCustomer" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endif

@if ($editInteresetSec)
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
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-4">
                        <div class="from-group">
                            <div class="input-area col-span-3">
                                <label for="firstName" class="form-label">Leave a Note</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('interestNote') !border-danger-500 @enderror"
                                    wire:model.defer="interestNote">
                            </div>
                            @error('interestNote')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="checkbox-area black-checkbox mr-2 sm:mr-4 mt-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="hidden" name="checkbox"
                                    wire:model="isCreateFollowup">
                                <span
                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                    <img src="{{ asset('assets/images/icon/ck-white.svg') }}" alt=""
                                        class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                <span
                                    class="text-black-500 dark:text-slate-400 text-sm leading-6 capitalize">Create
                                    followup</span>
                            </label>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="editInterest" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($interestSection)
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
                            Add Interest
                        </h3>
                        <button wire:click="toggleAddInterest" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                <div class="input-area col-span-2">
                                    <label for="firstName" class="form-label">Relation</label>
                                    <select name="basicSelect"
                                        class="form-control w-full mt-2 @error('lob') !border-danger-500 @enderror"
                                        wire:model.defer="lob">
                                        @foreach ($LINES_OF_BUSINESS as $lob)
                                            <option value="{{ $lob }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords(str_replace('_', ' ', $lob)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="checkbox-area">
                                    <label for="firstName" class="form-label">Mark as Interested</label>
                                    <input type="checkbox" name="checkbox" wire:model.defer="interested">
                                </div>
                                <div class="input-area col-span-3">
                                    <label for="firstName" class="form-label">Leave a Note</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('interestNote') !border-danger-500 @enderror"
                                        wire:model.defer="interestNote">
                                </div>
                            </div>
                            @error('lob')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('interestNote')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="addInterest" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($editedPhoneId)
    {{-- add address section --}}
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
                            Add Phone
                        </h3>
                        <button wire:click="closeEditPhone" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                    <select name="basicSelect"
                                        class="form-control w-full mt-2 @error('editedPhoneType') !border-danger-500 @enderror"
                                        wire:model.defer="editedPhoneType">
                                        @foreach ($phoneTypes as $type)
                                            <option value="{{ $type }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Number</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('editedNumber') !border-danger-500 @enderror"
                                        wire:model.defer="editedNumber">
                                </div>
                            </div>
                            @error('editedPhoneType')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('editedNumber')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="editPhone" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
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
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog"
        style="display: block;">
        <div class="modal-dialog  relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                            @if ($is_meeting)
                                Add Meeting
                                @else 
                                Add Follow up
                            @endif
                            
                        </h3>
                        <button wire:click="closeFollowupSection" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                <input id="lastName" type="text"
                                    class="form-control @error('followupTitle') !border-danger-500 @enderror"
                                    wire:model.defer="followupTitle">
                            </div>
                            @error('followupTitle')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                            @if (!$is_meeting)
                            <div class="checkbox-area black-checkbox mr-2 sm:mr-4 mt-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="hidden" name="checkbox"
                                        wire:model="is_meeting">
                                    <span
                                        class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                        <img src="{{ asset('assets/images/icon/ck-white.svg') }}" alt=""
                                            class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                    <span
                                        class="text-black-500 dark:text-slate-400 text-sm leading-6 capitalize">is Meeting ?</span>
                                </label>
                            </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Call Date</label>
                                    <input id="lastName" type="date"
                                        class="form-control @error('followupCallDate') !border-danger-500 @enderror"
                                        wire:model.defer="followupCallDate">
                                </div>
                                @error('followupCallDate')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area">
                                    <label for="firstName" class="form-label"> Time</label>
                                    <input id="lastName" type="time"
                                        class="form-control @error('followupCallTime') !border-danger-500 @enderror"
                                        wire:model.defer="followupCallTime">
                                </div>
                                @error('followupCallTime')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Line of business</label>
                                <select name="basicSelect"
                                    class="form-control w-full mt-2 @error('FollowupLineOfBussiness') !border-danger-500 @enderror"
                                    wire:model="FollowupLineOfBussiness">
                                    @foreach ($LINES_OF_BUSINESS as $LOB)
                                        <option value="{{ $LOB }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $LOB)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Description</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('followupDesc') !border-danger-500 @enderror"
                                    wire:model.defer="followupDesc">
                            </div>
                            @error('followupDesc')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="addFollowup" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
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
                            @if ($is_meeting)
                                Edit Meeting
                                @else 
                                Edit Follow up
                            @endif
                            
                        </h3>
                        <button wire:click="closeEditFollowup" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                <input id="lastName" type="text"
                                    class="form-control @error('followupTitle') !border-danger-500 @enderror"
                                    wire:model.defer="followupTitle">
                            </div>
                            @error('followupTitle')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Call Date</label>
                                    <input id="lastName" type="date"
                                        class="form-control @error('followupCallDate') !border-danger-500 @enderror"
                                        wire:model.defer="followupCallDate">
                                </div>
                                @error('followupCallDate')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                                <div class="input-area">
                                    <label for="firstName" class="form-label"> Time</label>
                                    <input id="lastName" type="time"
                                        class="form-control @error('followupCallTime') !border-danger-500 @enderror"
                                        wire:model.defer="followupCallTime">
                                </div>
                                @error('followupCallTime')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Line of business</label>
                                <select name="basicSelect"
                                    class="form-control w-full mt-2 @error('FollowupLineOfBussiness') !border-danger-500 @enderror"
                                    wire:model="FollowupLineOfBussiness">
                                    @foreach ($LINES_OF_BUSINESS as $LOB)
                                        <option value="{{ $LOB }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $LOB)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Description</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('followupDesc') !border-danger-500 @enderror"
                                    wire:model.defer="followupDesc">
                            </div>
                            @error('followupDesc')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="editFollowup" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($callerNoteSec)
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
                            Caller Note
                        </h3>
                        <button wire:click="toggleCallerNote" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                <input id="lastName" type="text"
                                    class="form-control @error('followupTitle') !border-danger-500 @enderror"
                                    wire:model.defer="note">
                            </div>
                            @error('note')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="submitCallerNote" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($deleteBankAccountId)
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
        tabindex="-1" aria-labelledby="dangerModalLabel" aria-modal="true" role="dialog"
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
                            Delete Bank Account
                        </h3>
                        <button wire:click="closeDeleteBankAccount" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                            dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                    <div
                        class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="deleteBankAccount" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-danger-500">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($bankAccountId)
    {{-- add address section --}}
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
                            Edit Bank Account
                        </h3>
                        <button wire:click="closeEditBankAccount" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                <select name="basicSelect"
                                    class="form-control w-full mt-2 @error('accountType') !border-danger-500 @enderror"
                                    wire:model="accountType">
                                    @foreach ($bankAccTypes as $type)
                                        <option value="{{ $type }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('accountType')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Bank Name</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('bankName') !border-danger-500 @enderror"
                                    wire:model="bankName">
                            </div>
                            @error('bankName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Account Number</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('accountNumber') !border-danger-500 @enderror"
                                    wire:model="accountNumber">
                            </div>
                            @error('accountNumber')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Owner Name</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('ownerName') !border-danger-500 @enderror"
                                        wire:model="ownerName">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Evidence Doc</label>
                                    @if (!$evidenceDoc)
                                        <input wire:model="evidenceDoc" type="file"
                                            class="form-control w-full  @error('evidenceDoc') !border-danger-500 @enderror"
                                            name="basic" />
                                    @else
                                        <span class="block min-w-[140px] text-left">
                                            <span class="inline-block text-center text-sm mx-auto py-1">
                                                <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                    <span
                                                        class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                    <span>
                                                        Document added
                                                        <span wire:click="clearEvidenceDoc"
                                                            class="text-xs text-slate-500 dark:text-slate-400 mt-1 cursor-pointer">|
                                                            remove</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    @endif
                                </div>

                                <div class="input-area">
                                    <label for="lastName" class="form-label">Iban</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('iban') !border-danger-500 @enderror"
                                        wire:model="iban">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Bank Branch</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('bankBranch') !border-danger-500 @enderror"
                                        wire:model="bankBranch">
                                </div>
                            </div>
                            @error('ownerName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('evidenceDoc')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('iban')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('bankBranch')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="editBankAccount" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
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
                            Add Bank Account
                        </h3>
                        <button wire:click="toggleAddBankAccount" type="button"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
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
                                <select name="basicSelect"
                                    class="form-control w-full mt-2 @error('accountType') !border-danger-500 @enderror"
                                    wire:model="accountType">
                                    @foreach ($bankAccTypes as $type)
                                        <option value="{{ $type }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('accountType')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Bank Name</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('bankName') !border-danger-500 @enderror"
                                    wire:model="bankName">
                            </div>
                            @error('bankName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="input-area mt-3">
                                <label for="firstName" class="form-label">Account Number</label>
                                <input id="lastName" type="text"
                                    class="form-control @error('accountNumber') !border-danger-500 @enderror"
                                    wire:model="accountNumber">
                            </div>
                            @error('accountNumber')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                <div class="input-area">
                                    <label for="firstName" class="form-label">Owner Name</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('ownerName') !border-danger-500 @enderror"
                                        wire:model="ownerName">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Evidence Doc</label>
                                    <input wire:model="evidenceDoc" type="file"
                                        class="form-control w-full  @error('evidenceDoc') !border-danger-500 @enderror"
                                        name="basic" />
                                </div>

                                <div class="input-area">
                                    <label for="lastName" class="form-label">Iban</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('iban') !border-danger-500 @enderror"
                                        wire:model="iban">
                                </div>
                                <div class="input-area">
                                    <label for="lastName" class="form-label">Bank Branch</label>
                                    <input id="lastName" type="text"
                                        class="form-control @error('bankBranch') !border-danger-500 @enderror"
                                        wire:model="bankBranch">
                                </div>
                            </div>
                            @error('ownerName')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('evidenceDoc')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('iban')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                            @error('bankBranch')
                                <span
                                    class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button wire:click="addBankAccount" data-bs-dismiss="modal"
                            class="btn inline-flex justify-center text-white bg-black-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

</div>
</div>
