@extends('layouts.app')

@section('title')
• Logs
@endsection

@section('logs')
    active
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
    <livewire:app-log-index />
@endsection

@section('body')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(function() {
            var start = moment().subtract(1, 'months');
            var end = moment().add(2, 'days');

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
@endsection
