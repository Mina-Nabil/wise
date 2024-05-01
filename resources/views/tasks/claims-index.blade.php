@extends('layouts.app')

@section('title')
• Claims
@endsection

@section('claims')
    active
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection


@section('content')
    <livewire:claim-index :filters="$filters ?? []" />
@endsection

@section('body')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script></script>
@endsection
