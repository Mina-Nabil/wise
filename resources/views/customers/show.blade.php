@extends('layouts.app')


@section('title')
• Customer
@endsection

@section('customers')
    active
@endsection

@section('content')
    <livewire:customer-show :customerId="$customerId" />
@endsection
