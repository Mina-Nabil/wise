<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Client Interests
            </h4>
        </div>

        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @if (Auth::user()->is_admin)
                <button wire:click="exportReport" class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
                    <span wire:loading.remove wire:target="exportReport">Export</span>
                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                        wire:target="exportReport" icon="line-md:loading-twotone-loop"></iconify-icon>
                </button>
            @endif
            <div class="dropdown relative ">
                <button class="btn inline-flex justify-center btn-dark items-center cursor-default relative !pr-14"
                    type="button" id="darksplitDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Add filter
                    <span
                        class="cursor-pointer absolute ltr:border-l rtl:border-r border-slate-100 h-full ltr:right-0 rtl:left-0 px-2 flex
                                items-center justify-center leading-none">
                        <iconify-icon class="leading-none text-xl" icon="ic:round-keyboard-arrow-down"></iconify-icon>
                    </span>
                </button>
                <ul
                    class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                            z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                    <li wire:click="toggleCreationDate">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Creation date ( From-To )</span>
                    </li>

                    <li wire:click="togglelobs">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Line of businesses</span>
                    </li>

                    <li wire:click="toggleCreator">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            Creator</span>
                    </li>

                    <li wire:click="toggleIsWelcomed">
                        <span
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                    dark:hover:text-white cursor-pointer">
                            is Welcomed</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card-body px-6 pb-6">
        <div class=" -mx-6">
            <div class="inline-block min-w-full align-middle">
                <div class="card">
                    <header class="card-header cust-card-header noborder">
                        <iconify-icon wire:loading class="loading-icon text-lg"
                            icon="line-md:loading-twotone-loop"></iconify-icon>
                        <input class="form-control py-2 w-auto" type="text" wire:model="searchText" style="padding-left: 35px"
                            placeholder="Search..." />
                    </header>

                    <header class="card-header cust-card-header noborder" style="display: block;">
                        <div class="flex gap-2">
                            @if ($creation_from || $creation_to)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleCreationDate">
                                        {{ $creation_from ? 'Creation From: ' . \Carbon\Carbon::parse($creation_from)->format('l d/m/Y') : '' }}
                                        {{ $creation_from && $creation_to ? '-' : '' }}
                                        {{ $creation_to ? 'Creation To: ' . \Carbon\Carbon::parse($creation_to)->format('l d/m/Y') : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearCreationDates">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($lobs)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="togglelobs">
                                        Status:&nbsp;(
                                        @foreach ($lobs as $lob)
                                            {{ ucwords(str_replace('_', ' ', $lob)) }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                        )
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearlobs">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if ($creator_id)
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleCreator">
                                        {{ $creator_id ? 'Creator: ' . $creatorName : '' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearCreator">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                            @if (!is_null($isWelcomed))
                                <button class="btn inline-flex justify-center btn-dark btn-sm">
                                    <span wire:click="toggleIsWelcomed">
                                        {{ $isWelcomed ? 'Welcomed: Yes' : 'Welcomed: No' }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span wire:click="clearWelcomed">
                                        <iconify-icon icon="material-symbols:close" width="1.2em"
                                            height="1.2em"></iconify-icon>
                                    </span>
                                </button>
                            @endif

                        </div>
                    </header>

                            <div class=" ">
                                {{-- overflow-hidden --}}
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead
                                        class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                                        <tr>
        
                                            <th scope="col" class=" table-th ">
                                                #
                                            </th>
        
                                            <th scope="col" class=" table-th ">
                                                Owner
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Customer
                                            </th>
        
                                            <th scope="col" class=" table-th ">
                                                Type
                                            </th>
        
                                            {{-- <th scope="col" class=" table-th ">
                                                Phone
                                            </th> --}}
        
                                            <th scope="col" class=" table-th ">
                                                Business
                                            </th>
        
                                            <th scope="col" class=" table-th ">
                                                Interested?
                                            </th>
        
                                            <th scope="col" class=" table-th ">
                                                Note
                                            </th>
        
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
        
                                        @foreach ($customers as $customer)
                                            <tr>
                                                <td class="table-td ">
                                                    {{ $customer->id }}
                                                </td>
        
                                                <td class="table-td ">
                                                    {{ $customer->owner->username }}
                                                </td>
        
                                                <td wire:click="redirectToShowPage({{ $customer }})"
                                                    class="table-td hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">
                                                    <b>{{ $customer->full_name }}</b>
                                                </td>
        
                                                <td class="table-td hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer"
                                                    wire:click="openStatusSection({{ $customer->id }})">
                                                    {{ $customer->type }} - {{ $customer->status?->status }}
                                                </td>
        
        
                                                <td class="table-td ">
                                                    {{ $customer->business ? ucfirst(str_replace('_', " ", $customer->business))  : 'N/A' }}
                                                </td>
        
                                                <td class="table-td ">
                                                    {{ $customer->interested ? 'Yes' : "No"}}
                                                </td>
        
                                                <td class="table-td ">
                                                    <b>{{ $customer->note ?? 'N/A' }}</b>
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
                                                <a href="{{ url('/customers') }}"
                                                    class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
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



    @if ($creationSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show"
            tabindex="-1" aria-labelledby="vertically_center" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Creation date
                            </h3>
                            <button wire:click="toggleCreationDate" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
        11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd">
                                    </path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Ecreation_from" class="form-label">Creation from</label>
                                <input name="Ecreation_from" type="date"
                                    class="form-control mt-2 w-full @error('Ecreation_from') !border-danger-500 @enderror"
                                    wire:model.defer="Ecreation_from">
                                @error('Ecreation_from')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="from-group">
                                <label for="Ecreation_to" class="form-label">Creation to</label>
                                <input name="Ecreation_to" type="date"
                                    class="form-control mt-2 w-full @error('Ecreation_to') !border-danger-500 @enderror"
                                    wire:model.defer="Ecreation_to">
                                @error('Ecreation_to')
                                    <span
                                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setCreationDates" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setCreationDates">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setCreationDates"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- lobsSection --}}
    @if ($lobsSection)
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
                                Statuses
                            </h3>
                            <button wire:click="togglelobs" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd">
                                    </path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Eline_of_business" class="form-label">Select line of business</label>
                                @foreach ($all_lobs as $one_lob)
                                    <div class="checkbox-area">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="hidden" value="{{ $one_lob }}"
                                                name="checkbox" wire:model="Elobs">
                                            <span
                                                class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                    alt=""
                                                    class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                            <span
                                                class="text-slate-500 dark:text-slate-400 text-sm leading-6">{{ ucwords(str_replace('_', ' ', $one_lob)) }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setLobs" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setLobs">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setLobs"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($creatorSection)
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
                                Creator
                            </h3>
                            <button wire:click="toggleCreator" type="button"
                                class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd">
                                    </path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="from-group">
                                <label for="Ecreator_id" class="form-label">Creator</label>
                                <select name="Ecreator_id" id="Ecreator_id" class="form-control w-full mt-2"
                                    wire:model.defer="Ecreator_id">
                                    <option class="py-1 inline-block font-Inter font-normal text-sm text-slate-600"
                                        value="">
                                        Select user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                        </option>
                                    @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setCreator" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setCreator">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setCreator"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
