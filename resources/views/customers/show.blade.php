@extends('layouts.app')


@section('customers')
    active
@endsection

@section('content')
    <livewire:customer-show :customerId="$customerId" />
@endsection
