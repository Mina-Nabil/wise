<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Offers
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            {{-- <button wire:click="toggleAddLead" class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Lead
            </button> --}}
            <button wire:click="openAddOfferSection" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Create Offer
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
                                        Client Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Client Type
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Offer Type
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Item Title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Assigned To
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Due
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Actions
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($offers as $offer)
                                    <tr wire:click="redirectToShowPage({{ $offer }})" class="hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">

                                        <td class="table-td ">
                                            <b>{{ $offer->client->name }}</b>
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ $offer->client_type }}</b>
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ $offer->type }}</b>
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ $offer->status }}</b>
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ $offer->item_title }}</b>
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ $offer->assignee_id->first_name ?? 'N/A' }}</b>
                                        </td>

                                        <td class="table-td ">
                                            <b>{{ $offer->due }}</b>
                                        </td>

                                        <td class="table-td ">
                                            ...
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
                                        <a href="{{ url('/offers') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
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
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Create Offer
                            </h3>

                            <button wire:click="closeAddOfferSection" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
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
                                <p class="text-lg"><b>Select offer client</b></p>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">Client Type</label>
                                        @if ($item)
                                            {{ $clientType }}
                                        @else
                                            <select class="form-control w-full mt-2 @error('clientType') !border-danger-500 @enderror" wire:model="clientType">
                                                <option value="Customer" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Customer</option>
                                                <option value="Corporate" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Corporate</option>
                                            </select>
                                        @endif

                                    </div>
                                    <div class="input-area">
                                        <label for="lastName" class="form-label">
                                            @if ($item)
                                                Selected client
                                            @else
                                                Search client
                                            @endif

                                        </label>
                                        @if ($item)
                                            {{ $selectedClientName }}
                                        @else
                                            <input placeholder="Serach..." type="text" class="form-control" wire:model="searchClient">
                                        @endif

                                    </div>
                                </div>
                                @error('clientType')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="text-sm">
                                @if ($clientNames)
                                    @foreach ($clientNames as $client)
                                        <p><iconify-icon icon="material-symbols:person"></iconify-icon> {{ $client->name }} | {{ $client->email ?? 'N/A' }} | <Span wire:click="selectClient({{ $client->id }})" class="cursor-pointer text-primary-500">Select Client</Span></p>
                                    @endforeach

                                @endif
                            </div>
                            @if ($item)
                                @if ($clientCars)
                                    <div class="from-group">
                                        <label for="lastName" class="form-label">Select Client Car</label>
                                        <select name="basicSelect" class="form-control w-full mt-2 @error('item') !border-danger-500 @enderror" wire:model="item">
                                            @foreach ($clientCars as $car)
                                                <option value="{{ $car->id }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">{{ $car->car->category }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500">
                                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                            <div class="flex-1">
                                                No cars for selected client!
                                            </div>
                                        </div>
                                    </div>

                                @endif
                            @endif
                            <div class="from-group">
                                <label for="lastName" class="form-label">Offer Type</label>
                                <select name="basicSelect" id="basicSelect" class="form-control w-full mt-2 @error('type') !border-danger-500 @enderror" wire:model="type">

                                    @foreach ($LINES_OF_BUSINESS as $line)
                                        <option value="{{ $line }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $line)) }}
                                        </option>
                                    @endforeach

                                </select>
                                @error('type')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Item title</label>
                                <input type="text" class="form-control mt-2 w-full" wire:model.defer="item_title">
                                @error('item_title')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Item value</label>
                                <input type="number" class="form-control mt-2 w-full" wire:model.defer="item_value">
                                @error('item_value')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Item Description</label>
                                <textarea class="form-control mt-2 w-full" wire:model.defer="item_desc"></textarea>
                                @error('item_desc')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="lastName" class="form-label">Note</label>
                                <textarea class="form-control mt-2 w-full" wire:model.defer="note"></textarea>
                                @error('note')
                                    <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <div class="input-area mb-3">
                                    <label for="time-date-picker" class="form-label">Due Date</label>
                                    <input class="form-control py-2 flatpickr cursor-pointer flatpickr-input active @error('dueDate') !border-danger-500 @enderror" id="default-picker" value="" type="date" wire:model.defer="dueDate" autocomplete="off">
                                    @error('dueDate')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-area mb-3">
                                    <label for="time-date-picker" class="form-label">Time </label>
                                    <input type="time" class="form-control  @error('dueTime') !border-danger-500 @enderror" id="appt" name="appt" min="09:00" max="18:00" wire:model.defer="dueTime" autocomplete="off" />
                                    {{-- <input class="form-control cursor-pointer py-2 flatpickr time flatpickr-input active @error('dueTime') !border-danger-500 @enderror" id="time-picker" data-enable-time="true" value="" type="text" wire:model.defer="dueTime" autocomplete="off"> --}}
                                    @error('dueTime')
                                        <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="newOffer" data-bs-dismiss="modal" class="btn inline-flex justify-center text-white bg-black-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
