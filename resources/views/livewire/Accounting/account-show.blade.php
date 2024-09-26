<div>
    <div class="card">
        
        <header class=" card-header noborder">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Start Balance</p>
                <h4 class="card-title">{{ $entries->first() ? number_format($entries->first()->account_balance, 2) : number_format(0, 2) }}
                </h4>
            </div>
        <input type="text" class="form-control w-auto d-inline-block cursor-pointer" style="width:auto" name="datetimes" id="reportrange" />


        </header>
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800 no-wrap">
                                <tr>
                                    <th scope="col" class="table-th">Day Serial</th>
                                    <th scope="col" class="table-th">Title</th>
                                    <th scope="col" class="table-th">Debit Amount</th>
                                    <th scope="col" class="table-th">Credit Amount</th>
                                    <th scope="col" class="table-th">Account Balance</th>
                                    <th scope="col" class="table-th">Debit Foreign Amount</th>
                                    <th scope="col" class="table-th">Credit Foreign Amount</th>
                                    <th scope="col" class="table-th">Account Foreign Balance</th>
                                    <th scope="col" class="table-th">Creator</th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 no-wrap">

                                @foreach ($entries as $entry)
                                    <tr>
                                        <td class="table-td">{{ $entry->day_serial }}</td>
                                        <td class="table-td">{{ $entry->name }}</td>
                                        <td class="table-td">{{ number_format($entry->debit_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->credit_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->account_balance, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->debit_foreign_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->credit_foreign_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->account_foreign_balance, 2) }}</td>
                                        <td class="table-td">{{ $entry->username }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <header class=" card-header noborder">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">End Balance</p>
                <h4 class="card-title">{{ $entries->last() ? number_format($entries->last()->account_balance, 2) : number_format(0, 2) }}
                </h4>
            </div>
        </header>
    </div>




</div>
