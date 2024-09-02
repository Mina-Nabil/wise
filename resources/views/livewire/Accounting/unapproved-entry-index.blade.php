<div>
    <div class="flex justify-between flex-wrap items-center mb-5">
        <div class="md:mb-6 mb-4 flex space-x-3 rtl:space-x-reverse">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4">
                Unapproved Journal Entry
            </h4>
        </div>
    </div>

    <div class="card">
        {{-- <header class=" card-header noborder">
            <h4 class="card-title">Table Head
            </h4>
        </header> --}}

        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700 no-wrap">
                                <tr>

                                    <th scope="col" class=" table-th !p-1">
                                        #
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Serial
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Title
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Amount
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Credit Account
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Debit Account
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Status
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Currency
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Currency Amount
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Currency Rate
                                    </th>

                                    <th scope="col" class=" table-th ">
                                        Actions
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 no-wrap">

                                @forelse ($entries as $entry)
                                    <tr>

                                        <td class="table-td"><b>#{{ $entry->id }}</b></td>

                                        <td class="table-td"><b>{{ $entry->day_serial }}</b></td>

                                        <td class="table-td ">{{ $entry->entry_title->name }}</td>

                                        <td class="table-td "><b>{{ number_format($entry->amount, 2) }}</b></td>

                                        <td class="table-td ">{{ $entry->credit_account->name }}</td>

                                        <td class="table-td ">{{ $entry->debit_account->name }}</td>

                                        <td class="table-td ">
                                            @if ($entry->is_reviewed)
                                                <span class="badge bg-success-500 text-white capitalize inline-flex items-center mt-2">
                                                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="gg:check-o"></iconify-icon>
                                                    Reviewed</span>
                                            @endif
                                            @if ($entry->approver_id)
                                                <span class="badge btn-outline-success text-white capitalize inline-flex items-center mt-2">
                                                    <iconify-icon class="ltr:mr-1 rtl:ml-1" icon="gg:check-o"></iconify-icon>
                                                    Approved</span>
                                            @endif
                                        </td>

                                        <td class="table-td ">
                                            <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize rounded-3xl">{{ $entry->currency }}</span>
                                        </td>

                                        <td class="table-td ">{{ number_format($entry->currency_amount, 2) }}</td>

                                        <td class="table-td ">{{ number_format($entry->currency_rate, 2) }}</td>

                                        <td class="table-td flex justify-between">
                                            <div>
                                                <button class="action-btn" type="button" wire:click="showEntry({{ $entry->id }})">
                                                    <iconify-icon icon="bi:info" class="text-lg"></iconify-icon>
                                                </button>

                                            </div>
                                            <div class="dropstart relative text-right">
                                                <button class="inline-flex justify-center items-center" type="button" id="tableDropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                </button>
                                                <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                    <a href="{{ route('entries.unapproved', ['id' => $entry->id]) }}">
                                                        <li>
                                                            <span
                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                <iconify-icon icon="bx:edit"></iconify-icon>
                                                                <span>Edit info</span></span>
                                                        </li>
                                                    </a>
                                                    @can('approve', \App\Models\Accounting\JournalEntry::class)
                                                        <li wire:click="$emit('showConfirmation', 'Are you sure you want to Approve this entry?','black','approveEntry' , {{ $entry->id }})">
                                                            <span
                                                                class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                                <iconify-icon icon="mdi:check-all"></iconify-icon>
                                                                <span>Approve</span></span>
                                                        </li>
                                                    @endcan
                                                    <li wire:click="$emit('showConfirmation', 'Are you sure you want to delete this entry?','danger','deleteEntry' , {{ $entry->id }})">
                                                        <span
                                                            class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                                            <iconify-icon icon="material-symbols:delete-outline"></iconify-icon>
                                                            <span>Delete</span></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11">
                                            {{-- START: empty filter result --}}
                                            <div class="card m-5 p-5">
                                                <div class="card-body rounded-md bg-white dark:bg-slate-800 m-5">
                                                    <div class="items-center text-center p-5 m-5">
                                                        <h2><iconify-icon icon="icon-park-outline:search"></iconify-icon></h2>
                                                        <h2 class="card-title text-slate-900 dark:text-white mb-3">No Entries Found!</h2>
                                                        <p class="card-text">Try changing the filters or search terms for this view.</p>
                                                        <a href="{{ url('/entries/new') }}" class="btn inline-flex justify-center mx-2 mt-3 btn-primary active btn-sm">New Entry</a>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- END: empty filter result --}}
                                        </td>
                                    </tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                    {{ $entries->links('vendor.livewire.bootstrap') }}
                </div>
            </div>
        </div>
    </div>

</div>
