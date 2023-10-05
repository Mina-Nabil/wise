@extends('layouts.app')

@section('cars')
    active
@endsection


@section('content')
    <livewire:policy-show :policyId="$policyId" />
@endsection
