<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Offers
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <button wire:click="openAddOfferSection"
                class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Create Offer
            </button>
        </div>
    </div>

    <div class="flex items-center space-x-7 flex-wrap h-[30px]">
        <div class="dropdown relative">
            <button class="btn inline-flex justify-center btn-dark items-center" type="button"
                id="darkDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                @if ($filteredStatus)
                    Status: {{ str_replace('_', ' ', $filteredStatus[0]) }}
                @else
                    Select Status
                @endif

                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
            </button>
            <ul
                class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                <li wire:click="filterByStatus('active')">
                    <a href="#"
                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                        Active
                    </a>
                </li>
                <li wire:click="filterByStatus('all')">
                    <a href="#"
                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                        All
                    </a>
                </li>
                @foreach ($statuses as $status)
                    <li wire:click="filterByStatus('{{ $status }}')">
                        <a href="#"
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </a>
                    </li>
                @endforeach
            </ul>



        </div>
        <div class="secondary-radio">
            <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden" value="all" wire:model="isRenewalCB">
                <span
                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                          duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-secondary-500 text-sm leading-6 capitalize">All</span>
            </label>
        </div>

        <div class="secondary-radio">
            <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden" value="isRenewal" wire:model="isRenewalCB">
                <span
                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                          duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-secondary-500 text-sm leading-6 capitalize">Renewal</span>
            </label>
        </div>

        <div class="secondary-radio">
            <label class="flex items-center cursor-pointer">
                <input type="radio" class="hidden" value="notRenewal" wire:model="isRenewalCB">
                <span
                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                          duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                <span class="text-secondary-500 text-sm leading-6 capitalize">New Business</span>
            </label>
        </div>
    </div>


    <div class="card mt-5 pt-5">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4"
                placeholder="Search using client name, email or phone number" wire:model="search">
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
                                        Client Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Type
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Offer Type
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Renewal
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Value
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Assignee
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Due
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                {{ $offers->links() ?? "" }}
                                @foreach ($offers as $offer)
                                    <tr wire:click="redirectToShowPage({{ $offer->id }})"
                                        class="hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">

                                        <td class="table-td ">
                                            @if ($offer->client_type === 'corporate')
                                                <b>{{ $offer->client->name }}</b>
                                            @elseif($offer->client_type === 'customer')
                                                <b>{{ $offer->client->first_name . ' ' . $offer->client->middle_name . ' ' . $offer->client->last_name }}</b>
                                            @endif

                                        </td>

                                        <td class="table-td ">
                                            {{ $offer->client_type }}
                                        </td>

                                        <td class="table-td ">
                                            {{ ucwords(str_replace('_', ' ', $offer->type)) }}
                                        </td>

                                        <td class="table-td ">
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

                                            @if ($offer->is_renewal)
                                                <span
                                                    class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize rounded-3xl"
                                                    style="vertical-align: top;">Renewal</span>
                                            @endif
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ $offer->renewal_policy }}</b>
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ number_format($offer->item_value, 0, '.', ',') }}</b>
                                        </td>

                                        <td class="table-td ">
                                            {{ $offer->assignee ? ucwords($offer->assignee->first_name) . ' ' . ucwords($offer->assignee->last_name) : ($offer->assignee_type ? ucwords($offer->assignee_type) : 'No one/team assigned') }}
                                        </td>

                                        <td class="table-td ">
                                            {{ date_format(date_create($offer->due), 'Y-m-d') }}
                                        </td>


                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @if ($offers->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No offers with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/offers') }}"
                                            class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all offers</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif

                    </div>



                    {{ $offers->links('vendor.livewire.bootstrap') }}

                </div>
            </div>
        </div>
    </div>

    @if ($addOfferSection)
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
                                Create Offer
                            </h3>

                            <button wire:click="closeAddOfferSection" type="button"
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
                            <div class="float-right">
                                <livewire:new-lead />
                            </div>
                            <div class="from-group">
                                <p class="text-lg"><b>Select offer client</b></p>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Client Type</label>
                                        @if ($owner)
                                            {{ $clientType }}
                                        @else
                                            <select
                                                class="form-control w-full mt-2 @error('clientType') !border-danger-500 @enderror"
                                                wire:model="clientType">
                                                <option value="Customer"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    Customer</option>
                                                <option value="Corporate"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    Corporate</option>
                                            </select>
                                        @endif

                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">
                                            @if ($owner)
                                                Selected client
                                            @else
                                                Search client <iconify-icon wire:loading wire:target="searchClient"
                                                    class="loading-icon text-lg"
                                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                                            @endif

                                        </label>
                                        @if ($owner)
                                            {{ $selectedClientName }}
                                        @else
                                            <input placeholder="Search..." type="text" class="form-control"
                                                wire:model="searchClient">
                                        @endif

                                    </div>
                                </div>
                                @error('clientType')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="text-sm">
                                @if ($clientNames)
                                    @foreach ($clientNames as $client)
                                        @if ($clientType !== 'Customer')
                                            <p><iconify-icon icon="material-symbols:person"></iconify-icon>
                                                {{ $client->name }} | {{ $client->email ?? 'N/A' }} | <Span
                                                    wire:click="selectClient({{ $client->id }})"
                                                    class="cursor-pointer text-primary-500">Select Client</Span></p>
                                        @else
                                            <p><iconify-icon icon="material-symbols:person"></iconify-icon>
                                                {{ $client->first_name }} {{ $client->last_name }} |
                                                {{ $client->email ?? 'N/A' }} | <Span
                                                    wire:click="selectClient({{ $client->id }})"
                                                    class="cursor-pointer text-primary-500">Select
                                                    Client</Span></p>
                                        @endif
                                    @endforeach

                                @endif
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Offer Type</label>
                                <select name="basicSelect"
                                    class="form-control w-full mt-2 @error('type') !border-danger-500 @enderror"
                                    wire:model="type">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select an option...</option>
                                    @if ($clientType === 'Customer')
                                        @foreach ($PERSONAL_TYPES as $line)
                                            <option value="{{ $line }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords(str_replace('_', ' ', $line)) }}
                                            </option>
                                        @endforeach
                                    @elseif($clientType === 'Corporate')
                                        @foreach ($CORPORATE_TYPES as $line)
                                            <option value="{{ $line }}"
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ ucwords(str_replace('_', ' ', $line)) }}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                                @error('type')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            @if ($type === 'personal_motor' && $clientType === 'Customer')
                                @if ($owner)
                                    @if ($clientCars)
                                        <div class="from-group">
                                            <label for="lastName" class="form-label">Select Client Car</label>
                                            <select name="basicSelect"
                                                class="form-control w-full mt-2 @error('item') !border-danger-500 @enderror"
                                                wire:model="item">
                                                <option
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                    Select an option</option>
                                                @foreach ($clientCars as $car)
                                                    <option value="{{ $car->id }}"
                                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                        {{ $car->car->car_model->brand->name }}
                                                        {{ $car->car->car_model->name }} {{ $car->car->category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <button wire:click="addNewCar"
                                            class="btn btn-sm mt-2 inline-flex justify-center btn-dark">Add new
                                            car</button>
                                    @else
                                        <p class="text-lg"><b>Select new car</b></p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                                            style="margin: 0">
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
                                        </div>
                                        @error('CarCategory')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror




                                    @endif
                                @endif

                                @if ($CarCategory || $item)
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Model Year</label>
                                        <select name="basicSelect"
                                            class="form-control w-full mt-2 @error('carPrice') !border-danger-500 @enderror text-dark"
                                            wire:model="carPrice">
                                            <option selected>Select an Option</option>
                                            @foreach ($CarPrices as $price)
                                                <option value="{{ $price }}" class="">
                                                    {{ $price->model_year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            @elseif($type === 'personal_medical' && $clientType === 'Customer')
                                @if ($owner)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                                        <div class="input-area">
                                            <label for="firstName" class="form-label">Birth Date</label>
                                            <input type="date"
                                                class="form-control @error('bdate') !border-danger-500 @enderror"
                                                wire:model="bdate">
                                        </div>
                                        <div class="input-area">
                                            <label for="lastName" class="form-label">Gender</label>
                                            <select name="basicSelect"
                                                class="form-control w-full mt-2 @error('gender') !border-danger-500 @enderror"
                                                wire:model="gender">
                                                @foreach ($GENDERS as $gender)
                                                    <option value="{{ $gender }}"
                                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                        {{ $gender }}</option>
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

                                    <div class="from-group">
                                        @if (!empty($relatives))
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-3">
                                                <div class="input-area">
                                                    <label for="time-date-picker" class="form-label"
                                                        style="margin: 0">Relative Info</label>
                                                </div>
                                            </div>
                                        @endif
                                        @foreach ($relatives as $index => $relative)
                                            <div
                                                class="card-body rounded-md bg-[#E5F9FF] dark:bg-slate-700 shadow-base mb-5 p-2">
                                                <div
                                                    class="grid grid-cols-8 md:grid-cols-8 lg:grid-cols-8 gap-2 items-center">
                                                    <div class="input-area col-span-4">
                                                        <input
                                                            class="form-control w-full mt-2  @error('relatives.' . $index . '.name') !border-danger-500 @enderror"
                                                            wire:model="relatives.{{ $index }}.name"
                                                            type="text" placeholder="Relative name">
                                                    </div>
                                                    <div class="input-area col-span-4">
                                                        <input
                                                            class="form-control w-full mt-2  @error('relatives.' . $index . '.phone') !border-danger-500 @enderror"
                                                            wire:model="relatives.{{ $index }}.phone"
                                                            type="number" placeholder="Relative phone">
                                                    </div>
                                                    <div class="input-area col-span-3">
                                                        <select name="basicSelect"
                                                            class="form-control w-full mt-2  @error('relatives.' . $index . '.relation') !border-danger-500 @enderror"
                                                            wire:model="relatives.{{ $index }}.relation">
                                                            <option
                                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                Select Relation...</option>
                                                            @foreach ($RELATIONS as $relation)
                                                                <option value="{{ $relation }}"
                                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                    {{ ucwords($relation) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="input-area col-span-2">
                                                        <select name="basicSelect"
                                                            class="form-control w-full mt-2   @error('relatives.' . $index . '.gender') !border-danger-500 @enderror"
                                                            wire:model="relatives.{{ $index }}.gender">
                                                            <option
                                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                Select Gender...</option>
                                                            @foreach ($GENDERS as $gender)
                                                                <option value="{{ $gender }}"
                                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                                    {{ ucwords($gender) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="input-area col-span-3">
                                                        <input
                                                            class="form-control w-full mt-2   @error('relatives.' . $index . '.birth_date') !border-danger-500 @enderror"
                                                            wire:model="relatives.{{ $index }}.birth_date"
                                                            type="date" placeholder="birth_date">
                                                    </div>
                                                    <div class="col-span-1 flex items-center">
                                                        <button class="action-btn"
                                                            wire:click="removeRelative({{ $index }})"
                                                            type="button">
                                                            <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <button wire:click="addAnotherField"
                                            class="btn btn-sm mt-2 inline-flex justify-center btn-dark">Add
                                            Relative</button>
                                    </div>
                                @endif
                            @endif


                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-2">
                                @if ($type === 'personal_motor' && $clientType === 'Customer')
                                @else
                                    <div class="from-group">
                                        <label for="lastName" class="form-label">Item title</label>
                                        <input type="text" class="form-control mt-2 w-full"
                                            wire:model.defer="item_title">
                                        @error('item_title')
                                            <span
                                                class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                                <div class="from-group">
                                    <label for="lastName" class="form-label">Is Renewal</label>
                                    <div class="flex items-center mr-2 sm:mr-4 mt-2 space-x-2">
                                        <label
                                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" checked class="sr-only peer"
                                                wire:model="isRenewal">
                                            <div
                                                class="w-14 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:z-10 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500">
                                            </div>
                                            <span
                                                class="absolute left-1 z-20 text-xs text-white font-Inter font-normal opacity-0 peer-checked:opacity-100">On</span>
                                            <span
                                                class="absolute right-1 z-20 text-xs text-white font-Inter font-normal opacity-100 peer-checked:opacity-0">Off</span>
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Item value</label>
                                <input type="number" class="form-control mt-2 w-full" wire:model.defer="item_value">
                                @error('item_value')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Item Description</label>
                                <textarea class="form-control mt-2 w-full" wire:model.defer="item_desc"></textarea>
                                @error('item_desc')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Note</label>
                                <textarea class="form-control mt-2 w-full" wire:model.defer="note"></textarea>
                                @error('note')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-area mb-3">
                                <label for="inFavorTo" class="form-label">In Favor To</label>
                                <input name="inFavorTo"
                                    class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('inFavorTo') !border-danger-500 @enderror"
                                    id="default-picker" type="text" wire:model.defer="inFavorTo"
                                    autocomplete="off">
                                @error('inFavorTo')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="input-area mb-3">
                                    <label for="time-date-picker" class="form-label">Due Date</label>
                                    <input
                                        class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('dueDate') !border-danger-500 @enderror"
                                        id="default-picker" value="" type="date" wire:model.defer="dueDate"
                                        autocomplete="off">
                                    @error('dueDate')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-area mb-3">
                                    <label for="time-date-picker" class="form-label">Time </label>
                                    <input type="time"
                                        class="form-control  @error('dueTime') !border-danger-500 @enderror"
                                        id="appt" name="appt" min="09:00" max="18:00"
                                        wire:model.defer="dueTime" autocomplete="off" />
                                    {{-- <input class="form-control cursor-pointer py-2 flatpickr time flatpickr-input active @error('dueTime') !border-danger-500 @enderror" id="time-picker" data-enable-time="true" value="" type="text" wire:model.defer="dueTime" autocomplete="off"> --}}
                                    @error('dueTime')
                                        <span
                                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="newOffer" data-bs-dismiss="modal"
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
