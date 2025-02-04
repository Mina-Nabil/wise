<div>
    <div class="flex justify-between flex-wrap items-center">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Client Payments
            </h4>
        </div>

    </div>

    <div class="flex mb-2">
        <div class="dropdown relative">
            <button class="btn inline-flex justify-center btn-dark items-center" type="button" id="darkDropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false">
                @if (count($filteredStatus) == 1)
                    Status: {{ str_replace('_', ' ', $filteredStatus[0]) }}
                @elseif(count($filteredStatus) > 1)
                    Status: Not Paid
                @else
                    Select Status
                @endif

                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
            </button>
            <ul
                class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                <li wire:click="filterByStatus('not_paid')">
                    <a href="#"
                        class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                        Not Paid
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

        <div class="dropdown relative ml-2">
            <button class="btn inline-flex justify-center btn-dark items-center" type="button"
                id="darkDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                @if ($selectedCompany)
                    Company: {{ $selectedCompany->name }}
                @else
                    Select Company
                @endif

                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
            </button>
            <ul
                class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">

                @foreach ($companies as $company)
                    <li wire:click="filterByCompany('{{ $company->id }}')">
                        <a href="#"
                            class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                            {{ $company->name }}
                        </a>
                    </li>
                @endforeach
            </ul>



        </div>

        <input class="form-control py-2 w-auto ml-5" style="width:250px" type="text" wire:model="searchText"
            placeholder="Search by policy number">
        <input class="form-control py-2 w-auto ml-5" style="width:100px" type="number" wire:model="dueDays"
            placeholder="Days">

        <div class="flex items-center mr-2 sm:mr-4 mt-2 space-x-2 justify-end ml-5 pb-2">
            <label
                class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                <input type="checkbox" checked class="sr-only peer" wire:model="isDuePassed">
                <div
                    class="w-14 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:z-10 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500">
                </div>
                <span
                    class="absolute left-1 z-20 text-xs text-white font-Inter font-normal opacity-0 peer-checked:opacity-100"></span>
                <span
                    class="absolute right-2 z-20 text-xs text-white font-Inter font-normal opacity-100 peer-checked:opacity-0"></span>
            </label>
            {{-- <span class="text-sm text-primary-600 font-Inter font-normal capitalize ml-5 pb-2">My Tasks</span> --}}
        </div>

        <div class=" mx-3 my-3">
            <p class="font-small text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                @if ($isDuePassed)
                    Days passed after due
                @else
                    Days before due
                @endif
            </p>
        </div>


    </div>

    <div class="card-body px-6 pb-6">
        <div class=" -mx-6">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden ">
                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 no-wrap">
                        <thead class=" border-t border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700">
                            <tr>

                                <th scope="col" class=" table-th ">
                                    Policy#
                                </th>

                                <th scope="col" class=" table-th ">
                                    Client
                                </th>

                                <th scope="col" class=" table-th cursor-pointer">
                                    <span wire:click="sortByColomn('start')" class="clickable-header">Start
                                        @if ($sortColomn === 'start')
                                            @if ($sortDirection === 'asc')
                                                <iconify-icon icon="fluent:arrow-up-12-filled"></iconify-icon>
                                            @else
                                                <iconify-icon icon="fluent:arrow-down-12-filled"></iconify-icon>
                                            @endif
                                        @endif
                                    </span>

                                </th>

                                <th scope="col" class=" table-th cursor-pointer">
                                    <span wire:click="sortByColomn('due')" class="clickable-header">Due
                                        @if ($sortColomn === 'due')
                                            @if ($sortDirection === 'asc')
                                                <iconify-icon icon="fluent:arrow-up-12-filled"></iconify-icon>
                                            @else
                                                <iconify-icon icon="fluent:arrow-down-12-filled"></iconify-icon>
                                            @endif
                                        @endif
                                    </span>
                                </th>


                                <th scope="col" class="table-th">
                                    Assignee
                                </th>

                                <th scope="col" class="table-th">
                                    Amount
                                </th>

                                <th scope="col" class=" table-th ">
                                    Status
                                </th>

                                <th scope="col" class=" table-th ">
                                    Penalty
                                </th>
                                <th scope="col" class=" table-th ">
                                    Left
                                </th>

                                <th scope="col" class=" table-th ">
                                    Penalty
                                </th>
                                <th scope="col" class=" table-th ">
                                    Note
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
                                        {{ $payment->sold_policy->client->name }}
                                    </td>

                                    <td class="table-td">
                                        {{ \Carbon\Carbon::parse($payment->sold_policy->start)->format('d/m/Y') }}
                                    </td>

                                    <td class="table-td">
                                        {{ $payment->due ? \Carbon\Carbon::parse($payment->due)->format('d/m/Y') : 'Not set.' }}
                                    </td>

                                    <td class="table-td">
                                        {{ $payment->assigned?->username }}
                                    </td>

                                    <td class="table-td">
                                        <p><b>{{ number_format($payment->amount, 0, '.', ',') }}
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
                                        {{ \Carbon\Carbon::parse($payment->policy_payment_due)->addDays($payment->due_penalty ?? 0)->format('d/m/Y') }}
                                    </td>
                                    <td class="table-td">
                                        {{ \Carbon\Carbon::now()->diffInDays(
                                            \Carbon\Carbon::parse($payment->policy_payment_due)->addDays($payment->due_penalty ?? 0),
                                            false,
                                        ) }}
                                        Days
                                    </td>

                                    <td class="table-td">
                                        {{ number_format(($payment->penalty_percent / 100) * ($payment->calculation_type == '%' ? ($payment->value / 100) * $payment->net_premium : $payment->value), 2) }}
                                    </td>

                                    <td class="table-td">
                                        @if ($payment->finance_note)
                                            <iconify-icon wire:click='openNoteSection({{ $payment->id }})'
                                                icon="basil:comment-outline" width="24"
                                                height="24"></iconify-icon>
                                        @else
                                            <span wire:click='openNoteSection({{ $payment->id }})' class="text-xs">Add
                                                Note</span>
                                        @endif
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



    @if ($noteSection)
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
                                Update Note
                            </h3>
                            <button wire:click="closeNoteSection" type="button"
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

                            <div class="from-group">
                                <div class="input-area">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea id="note" class="form-control @error('note') !border-danger-500 @enderror" wire:model.lazy="note"
                                        autocomplete="off"></textarea>
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
                            <button wire:click="setNote" data-bs-dismiss="modal"
                                class="btn inline-flex justify-center text-white bg-black-500">
                                <span wire:loading.remove wire:target="setNote">Submit</span>
                                <iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]"
                                    wire:loading wire:target="setNote"
                                    icon="line-md:loading-twotone-loop"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
