<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Invoices Report -- Total: {{ number_format($totalInvoices, 2) }}
            </h4>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            @if (Auth::user()->is_admin)
                <button wire:click="exportReport"
                    class="btn inline-flex justify-center btn-outline-dark rounded-[25px]">
                    <span wire:loading.remove wire:target="exportReport">Export</span>
                    <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" wire:loading
                        wire:target="exportReport" icon="line-md:loading-twotone-loop"></iconify-icon>
                </button>
            @endif
        </div>
    </div>

    <div class="card p-6">
        <div class="card-body px-6 pb-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <!-- Creation Date Filter -->
                <div class="filter-group">
                    <button wire:click="toggleCreatedDate" type="button" class="btn btn-dark flex items-center justify-between w-full">
                        <span class="truncate">
                            @if($created_from && $created_to)
                                {{ Carbon\Carbon::parse($created_from)->format('d/m/Y') }} - 
                                {{ Carbon\Carbon::parse($created_to)->format('d/m/Y') }}
                            @else
                                Creation Date
                            @endif
                        </span>
                        <iconify-icon class="text-xl ml-2" icon="heroicons-outline:calendar"></iconify-icon>
                    </button>
                </div>

                <!-- Company Filter -->
                <div class="filter-group">
                    <button wire:click="toggleCompany" type="button" class="btn btn-dark flex items-center justify-between w-full">
                        <span class="truncate">
                            @if(count($company_ids))
                                {{ count($company_ids) }} Companies Selected
                            @else
                                Companies
                            @endif
                        </span>
                        <iconify-icon class="text-xl ml-2" icon="heroicons-outline:office-building"></iconify-icon>
                    </button>
                </div>

                <!-- Search -->
                <div class="filter-group col-span-2">
                    <input type="text" class="form-control" placeholder="Search by serial or creator..." wire:model="searchText">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto -mx-6 mt-4">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th wire:click="sortByColumn('id')" class="table-th cursor-pointer">
                                        System ID
                                        @if($sortColumn === 'id')
                                            <iconify-icon icon="{{ $sortDirection === 'asc' ? 'heroicons:chevron-up' : 'heroicons:chevron-down' }}"></iconify-icon>
                                        @endif
                                    </th>
                                    <th wire:click="sortByColumn('serial')" class="table-th cursor-pointer">
                                        Serial
                                        @if($sortColumn === 'serial')
                                            <iconify-icon icon="{{ $sortDirection === 'asc' ? 'heroicons:chevron-up' : 'heroicons:chevron-down' }}"></iconify-icon>
                                        @endif
                                    </th>
                                    <th wire:click="sortByColumn('created_at')" class="table-th cursor-pointer">
                                        Creation Date
                                        @if($sortColumn === 'created_at')
                                            <iconify-icon icon="{{ $sortDirection === 'asc' ? 'heroicons:chevron-up' : 'heroicons:chevron-down' }}"></iconify-icon>
                                        @endif
                                    </th>
                                    <th class="table-th">Creator</th>
                                    <th class="table-th">Company</th>
                                    <th class="table-th">Gross Total</th>
                                    <th class="table-th">Tax Total</th>
                                    <th class="table-th">Net Total</th>
                                    <th class="table-th">Payment Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach($invoices as $invoice)
                                    <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">
                                        <td class="table-td">{{ $invoice->id }}</td>
                                        <td class="table-td">{{ $invoice->serial }}</td>
                                        <td class="table-td">{{ $invoice->created_at->format('d/m/Y') }}</td>
                                        <td class="table-td">{{ $invoice->creator->username }}</td>
                                        <td class="table-td">
                                            <a href="{{ route('companies.show', $invoice->company->id) }}"
                                                target="_blank"
                                                class="text-primary-500 hover:text-primary-600">
                                                {{ $invoice->company->name }}
                                            </a>
                                        </td>
                                        <td class="table-td">{{ number_format($invoice->gross_total, 2) }}</td>
                                        <td class="table-td">{{ number_format($invoice->tax_total, 2) }}</td>
                                        <td class="table-td">{{ number_format($invoice->net_total, 2) }}</td>
                                        <td class="table-td">
                                            {{ $invoice->payment_date ?? 'Not Paid' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>

    <!-- Creation Date Modal -->
    @if($createdSection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="createdDateModal" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white">
                                Select Creation Date Range
                            </h3>
                            <button wire:click="toggleCreatedDate" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">From Date</label>
                                    <input type="date" class="form-control" wire:model="Ecreated_from">
                                </div>
                                <div>
                                    <label class="form-label">To Date</label>
                                    <input type="date" class="form-control" wire:model="Ecreated_to">
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setCreatedDates" type="button" class="btn btn-dark">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Company Modal -->
    @if($companySection)
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" tabindex="-1" aria-labelledby="companyModal" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white">
                                Select Companies
                            </h3>
                            <button wire:click="toggleCompany" type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <input type="text" class="form-control" placeholder="Search companies..." wire:model="searchCompany">
                            <div class="space-y-2">
                                @foreach($companies as $company)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               class="form-checkbox" 
                                               value="{{ $company->id }}"
                                               wire:model="Ecompany_ids"
                                        >
                                        <label class="ml-2">{{ $company->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button wire:click="setCompany" type="button" class="btn btn-dark">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div> 