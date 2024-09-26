<div>
    <div class="card">
        <input type="text" class="form-control w-auto d-inline-block cursor-pointer" style="width:auto" name="datetimes" id="reportrange" />
        <header class=" card-header noborder">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Start Balance</p>
                <h4 class="card-title">{{ number_format('2500', 2) }}
                </h4>
            </div>

        </header>
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800 no-wrap">
                                <tr>

                                    <th scope="col" class="table-th">Debit Amount</th>
                                    <th scope="col" class="table-th">Credit Amount</th>
                                    <th scope="col" class="table-th">Account Balance</th>
                                    <th scope="col" class="table-th">Debit Foreign Amount</th>
                                    <th scope="col" class="table-th">Credit Foreign Amount</th>
                                    <th scope="col" class="table-th">Account Foreign Balance</th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700 no-wrap">

                                @foreach ($entries as $entry)
                                    <tr>
                                        {{-- <td class="table-td">{{ $entry->creator->full_name }}</td> --}}
                                        {{-- <td class="table-td">{{ $entry->day_serial }}</td> --}}
                                        {{-- <td class="table-td">{{ $entry->entry_title->name }}</td> --}}
                                        <td class="table-td">{{ number_format($entry->debit_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->credit_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->account_balance, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->debit_foreign_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->credit_foreign_amount, 2) }}</td>
                                        <td class="table-td">{{ number_format($entry->account_foreign_balance, 2) }}</td>
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
                <h4 class="card-title">{{ number_format('1800', 2) }}
                </h4>
            </div>
        </header>
    </div>




</div>


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(function() {
        var start = moment().subtract(4, 'months');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            // console.log('Start date: ' + start.format('YYYY-MM-DD'));
            // console.log('End date: ' + end.format('YYYY-MM-DD'));

            Livewire.emit('dateRangeSelected', start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            "alwaysShowCalendars": true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                // 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                //     'month').endOf('month')],
                'Last 3 Months': [moment().subtract(3, 'months'), moment()],
            }
        }, cb);

        cb(start, end);


    });
</script>
