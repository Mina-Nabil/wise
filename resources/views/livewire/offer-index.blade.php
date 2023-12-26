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
            <button wire:click="toggleAddCustomer" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
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
                                            <b>{{ $offer->assignee_id->first_name }}</b> 
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


</div>
