<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Customers
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <button wire:click="openAddCompany" class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Customer
            </button>
        </div>
    </div>

    <div class="card-text h-full mb-5">
        <form wire:submit.prevent="addLead">
            <div class="from-group">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="input-area">
                        <label for="firstName" class="form-label">New Lead</label>
                        <input wire:model="newLeadName" id="firstName" type="text" class="form-control  @error('newLeadName') !border-danger-500 @enderror" placeholder="Customer Name">
                        @error('newLeadName')
                            <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-between items-end space-x-6">
                        <div class="input-area w-full">
                            <label for="phone" class="form-label"></label>
                            <input wire:model="newLeadPhone" id="phone" type="tel" class="form-control @error('newLeadPhone') !border-danger-500 @enderror" placeholder="Phone Number">
                            @error('newLeadPhone')
                            <span class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $message }}</span>
                        @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center h-10 w-10 bg-success-500 text-lg border rounded border-success-500 text-white rb-zeplin-focused" style="height: 35px;">
                            <iconify-icon icon="material-symbols:add"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        </form>
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
                                        Arabic Name
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @foreach ($customers as $customer)
                                    <tr>

                                        <td class="table-td ">
                                            <b>{{ $customer->name }}</b> 
                                        </td>

                                        <td class="table-td ">
                                            {{ $customer->type }}
                                        </td>

                                        <td class="table-td ">
                                            {{ $customer->phone }}
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



</div>
