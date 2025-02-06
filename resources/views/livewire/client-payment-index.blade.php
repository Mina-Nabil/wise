<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Client Payments
            </h4>
        </div>


        {{-- <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center md:mb-6 mb-4 rtl:space-x-reverse">
            <button wire:click="" data-bs-toggle="modal" data-bs-target="#successModal"
                class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                Add Payment
            </button>
        </div> --}}
    </div>

    <div class="flex mb-2">
        <div class="dropdown relative">
            <button class="btn inline-flex justify-center btn-dark items-center" type="button" id="darkDropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false">
                @if ($filteredStatus)
                    Status: {{ str_replace('_', ' ', $filteredStatus[0]) }}
                @else
                    Select Status
                @endif

                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
            </button>
            <ul
                class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
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

        <input class="form-control py-2 w-auto ml-5" style="width:300px" value="" type="text"
            wire:model="searchText" placeholder="Search by policy number">

        <input class="form-control py-2 flatpickr flatpickr-input active w-auto ml-5" style="width:300px"
            id="range-picker" data-mode="range" value="" type="text" readonly="readonly"
            wire:model="dateRange">

        <div class="flex items-center mr-2 sm:mr-4 mt-2 space-x-2 justify-end ml-5 pb-2">
            <label
                class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                <input type="checkbox" checked class="sr-only peer" wire:model="myPayments">
                <div
                    class="w-14 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:z-10 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500">
                </div>
                <span
                    class="absolute left-1 z-20 text-xs text-white font-Inter font-normal opacity-0 peer-checked:opacity-100">Me</span>
                <span
                    class="absolute right-2 z-20 text-xs text-white font-Inter font-normal opacity-100 peer-checked:opacity-0">All</span>
            </label>
            {{-- <span class="text-sm text-primary-600 font-Inter font-normal capitalize ml-5 pb-2">My Tasks</span> --}}
        </div>
    </div>

    <div class="card-body px-6 pb-6">
        <div class=" -mx-6">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden ">
                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                        <thead class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                            <tr>

                                <th scope="col" class=" table-th ">
                                    Policy#
                                </th>

                                <th scope="col" class="table-th">
                                    Creator
                                </th>

                                <th scope="col" class=" table-th ">
                                    Client
                                </th>

                                <th scope="col" class=" table-th ">
                                    Due
                                </th>


                                <th scope="col" class="table-th">
                                    Assignee
                                </th>

                                <th scope="col" class="table-th">
                                    Amount
                                </th>

                                <th scope="col" class="table-th">
                                    Type
                                </th>

                                <th scope="col" class=" table-th ">
                                    Status
                                </th>

                                <th scope="col" class=" table-th ">
                                    Date
                                </th>
                                <th scope="col" class=" table-th ">
                                    Note
                                </th>

                                <th scope="col" class=" table-th ">
                                    Closed by
                                </th>
                                <th scope="col" class=" table-th ">
                                    Sales
                                </th>

                            </tr>
                        </thead>
                        <tbody
                            class="bg-white divide-y cursor-pointer divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                            @foreach ($payments as $payment)
                                <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">

                                    <td class="table-td">
                                        <a href="{{ route('sold.policy.show', $payment->sold_policy->id) }}"
                                            target="_blank"
                                            class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                            <span
                                                class="block date-text">{{ $payment->sold_policy->policy_number }}</span>
                                        </a>
                                    </td>
                                    <td class="table-td">
                                        {{ $payment->sold_policy->creator->username }}
                                    </td>
                                    <td class="table-td">
                                        {{ $payment->sold_policy->client->full_name }}
                                    </td>

                                    <td class="table-td">
                                        {{ $payment->due ? \Carbon\Carbon::parse($payment->due)->format('D d/m/Y') : 'Not set.' }}
                                    </td>

                                    <td class="table-td">
                                        {{ $payment->assigned?->username }}
                                    </td>

                                    <td class="table-td">
                                        <p><b>{{ number_format($payment->amount, 2, '.', ',') }} EGP
                                    </td>

                                    <td class="table-td">
                                        {{ ucwords(str_replace('_', ' ', $payment->type)) }}
                                    </td>

                                    <td class="table-td">
                                        @if ($payment->status === 'new')
                                            <div
                                                class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-primary-500 bg-primary-500 text-xs">
                                                New
                                            </div>
                                        @elseif($payment->status === 'paid')
                                            <div
                                                class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500 text-xs">
                                                Paid
                                            </div>
                                        @elseif($payment->status === 'prem_collected')
                                            <div
                                                class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-info-500 bg-info-500 text-xs">
                                                Prem Collected
                                            </div>
                                        @elseif($payment->status === 'cancelled')
                                            <div
                                                class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-danger-500 bg-danger-500 text-xs">
                                                Cancelled
                                            </div>
                                        @endif
                                    </td>

                                    <td class="table-td">
                                        {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('D d/m/Y') : 'Not set.' }}
                                    </td>

                                

                                    <td class="table-td">
                                        @if ($payment->closed_by)
                                            <b>
                                                {{ $payment->closed_by->first_name }}
                                                {{ $payment->closed_by->last_name }}
                                            </b>
                                        @else
                                            <b> - </b>
                                        @endif
                                    </td>

                                    <td class="table-td">
                                        <ul>

                                            @foreach ($payment->sold_policy->active_sales_comms as $sales_comm)
                                            <li>
                                                {{$sales_comm->comm_profile->title}}: {{$sales_comm->amount}} {{$sales_comm->status}}
                                            </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    @if ($payments->isEmpty())
                        {{-- START: empty filter result --}}
                        <div class="card p-5">
                            <div class="card-body rounded-md bg-white dark:bg-slate-800">
                                <div class="items-center text-center p-5">
                                    <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                    <h2 class="card-title text-slate-900 dark:text-white mb-3">No Payments with the
                                        applied
                                        filters</h2>
                                    <p class="card-text">Try changing the filters or search terms for this view.
                                    </p>
                                    <a href="{{ url('/payments') }}"
                                        class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">View
                                        all Payments</a>
                                </div>
                            </div>
                        </div>
                        {{-- END: empty filter result --}}
                    @endif

                </div>


                {{ $payments->links('vendor.livewire.bootstrap') }}

            </div>
        </div>
    </div>


    <script>
        document.addEventListener('livewire:load', function() {
            $('#demo').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "startDate": "10/20/2023",
                "endDate": "10/26/2023",
                "drops": "up"
            }, function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format(
                    'YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            });
        });
    </script>
</div>
