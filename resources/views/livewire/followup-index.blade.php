<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Follow Ups
            </h4>
        </div>
        {{-- <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <button wire:click="toggleAddLead" class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Lead
            </button>
            <button wire:click="toggleAddCorporate" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Corporate
            </button>
        </div> --}}
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
                                        Title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Type
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Name
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Call Time
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Description
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Created by
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($followups as $followup)
                                    <tr wire:click="redirectToShowPage({{ $followup->id }})" class="hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">

                                        <td class="table-td ">
                                            <b>{{ $followup->title }}</b> 
                                        </td>

                                        <td class="table-td ">
                                            {{ $followup->called_type }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $followup->called->name }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $followup->status }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $followup->call_time }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $followup->desc }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $followup->creator->first_name }}
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @if ($followups->isEmpty())
                            {{-- START: empty filter result --}}
                            <div class="card m-5 p-5">
                                <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                    <div class="items-center text-center p-5">
                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Follow ups with the
                                            applied
                                            filters</h2>
                                        <p class="card-text">Try changing the filters or search terms for this view.
                                        </p>
                                        <a href="{{ url('/followups') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                            all follow ups</a>
                                    </div>
                                </div>
                            </div>
                            {{-- END: empty filter result --}}
                        @endif

                    </div>



                    {{ $followups->links('vendor.livewire.bootstrap') }}

                </div>
            </div>
        </div>
    </div>


</div>
