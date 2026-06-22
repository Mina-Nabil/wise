@extends('layouts.app')

@section('title')
    • Reports • Customer Duplicates
@endsection

@section('reports.customer-duplicates')
    active
@endsection

@section('content')
    <livewire:customer-duplicates-report />
@endsection
