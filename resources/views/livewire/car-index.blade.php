<div>
    <div>
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
            id="disabled_backdrop" tabindex="-1" aria-labelledby="disabled_backdrop" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">

                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            @if ($carPriceListId)
                                <h3 class="text-base text-white dark:text-white capitalize">
                                    <ul class="m-0 p-0 list-none">
                                        <li class="inline-block relative top-[3px] text-base font-Inter ">
                                            {{ $prices->car_model->brand->name }}
                                            <iconify-icon icon="heroicons-outline:chevron-right"
                                                class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                        </li>
                                        <li class="inline-block relative top-[3px] text-base font-Inter ">
                                            {{ $prices->car_model->name }}
                                            <iconify-icon icon="heroicons-outline:chevron-right"
                                                class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                        </li>
                                        <li
                                            class="inline-block relative text-sm top-[3px] text-slate-500 font-Inter dark:text-white">
                                            {{ $prices->category }}</li>
                                    </ul>
                                </h3>
                            @else
                                No Prices
                            @endif

                            <button type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
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
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>

                                        <th scope="col" class=" table-th ">
                                            Year
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Price
                                        </th>

                                        <th scope="col" class=" table-th ">
                                            Action
                                        </th>

                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                    @if ($carPriceListId)
                                        @foreach ($prices->car_prices as $price)
                                            <tr>
                                                <td class="table-td">{{ $price->model_year }}</td>
                                                <td class="table-td text-success-500">{{ $price->price }}</td>
                                                <td class="table-td ">
                                                    <span
                                                        class="flex-none space-x-2 text-base text-secondary-500 flex rtl:space-x-reverse">
                                                        <button type="button" class="text-slate-400">
                                                            <iconify-icon
                                                                icon="heroicons-outline:pencil-alt"></iconify-icon>
                                                        </button>
                                                        <button type="button"
                                                            class="transition duration-150 hover:text-danger-500 text-slate-400  delete-button">
                                                            <iconify-icon icon="heroicons-outline:trash"></iconify-icon>
                                                        </button>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">Accept</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Cars
            </h4>
            <!---->
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">

            <button data-bs-toggle="modal" data-bs-target="#successModal"
                class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Car
            </button>


            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog relative w-auto pointer-events-none">
                    <div
                        class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                rounded-md outline-none text-current">
                        <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-success-500">
                                <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                    Add new Car
                                </h3>
                                <button type="button"
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
                            <livewire:add-new-car />
                            <!-- Modal footer -->

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <header class="card-header cust-card-header noborder">
            <iconify-icon wire:loading class="loading-icon text-lg" icon="line-md:loading-twotone-loop"></iconify-icon>
            <input type="text" class="form-control !pl-9 mr-1 basis-1/4" placeholder="Search" wire:model="search">
            {{-- <div data-select2-id="select2-data-17-14zv py-1 text-sm" class="d-none">
                <select name="select2basic" id="select2basic"
                    class="select2 form-control w-full mt-2 py-2 select2-hidden-accessible"
                    data-select2-id="select2-data-select2basic" tabindex="-1" aria-hidden="true">
                    <option value="All" class=" inline-block font-Inter font-normal text-sm text-slate-600"
                        data-select2-id="select2-data-2-yt7a">All
                    </option>
                    <option value="option2" class=" inline-block font-Inter font-normal text-sm text-slate-600"
                        data-select2-id="select2-data-19-qp84">Option 2</option>
                    <option value="option3" class=" inline-block font-Inter font-normal text-sm text-slate-600"
                        data-select2-id="select2-data-20-ylak">Option 3</option>

                </select>
            </div> --}}
        </header>

        {{-- <header class="card-header cust-card-header filter-padges">
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
            <!-- Rest of your content goes here if needed -->
        </header> --}}


        <div class="card-body px-6 pb-6">
            <div class=" -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead
                                class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                <tr>

                                    <th scope="col" class=" table-th ">
                                        <div class="checkbox-area">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden" id="select-all">
                                                <span
                                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                    <img src="assets/images/icon/ck-white.svg" alt=""
                                                        class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                            </label>
                                        </div>
                                    </th>

                                    <th scope="col" class="table-th" wire:click="sortByColumn('brand')">
                                        <span data-tippy-content="Sort by brand"
                                            class="scale cursor-pointer">Brand</span>
                                        @if ($sortBy === 'brand')
                                            @if ($sortDirection === 'asc')
                                                <iconify-icon icon="mdi:arrow-up-bold"></iconify-icon>
                                            @else
                                                <iconify-icon icon="mdi:arrow-down-bold"></iconify-icon>
                                            @endif
                                        @endif
                                    </th>

                                    <th scope="col" class=" table-th " wire:click="sortByColumn('model')">
                                        <span data-tippy-content="Sort by Model"
                                            class="scale cursor-pointer">Model</span>
                                        @if ($sortBy === 'model')
                                            @if ($sortDirection === 'asc')
                                                <iconify-icon icon="mdi:arrow-up-bold"></iconify-icon>
                                            @else
                                                <iconify-icon icon="mdi:arrow-down-bold"></iconify-icon>
                                            @endif
                                        @endif
                                    </th>

                                    <th scope="col" class=" table-th " wire:click="sortByColumn('category')">
                                        <span data-tippy-content="Sort by Category"
                                            class="scale cursor-pointer">Category</span>
                                        @if ($sortBy === 'category')
                                            @if ($sortDirection === 'asc')
                                                <iconify-icon icon="mdi:arrow-up-bold"></iconify-icon>
                                            @else
                                                <iconify-icon icon="mdi:arrow-down-bold"></iconify-icon>
                                            @endif
                                        @endif
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Description
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Prices
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($cars as $car)
                                    <tr>
                                        <td class="table-td">
                                            <div class="checkbox-area">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="hidden row-checkbox"
                                                        value="">
                                                    <span
                                                        class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                        <img src="assets/images/icon/ck-white.svg" alt=""
                                                            class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="table-td">{{ $car->car_model->brand->name }}</td>
                                        <td class="table-td">{{ $car->car_model->name }}</td>
                                        <td class="table-td ">{{ $car->category }}</td>
                                        <td class="table-td ">{{ $car->desc }}</td>
                                        <td class="table-td ">

                                            <button data-bs-toggle="modal" data-bs-target="#disabled_backdrop"
                                                class="btn btn-sm inline-flex justify-center btn-outline-dark"
                                                wire:click="showPrices({{ $car->id }})">Show Prices
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @if ($cars->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Cars with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/cars') }}"
                                            class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all cars</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif

                    </div>



                    {{ $cars->links('vendor.livewire.bootstrap') }}

                </div>
            </div>
        </div>
    </div>


</div>
