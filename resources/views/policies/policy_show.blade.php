@extends('layouts.app')

@section('policies')
    active
@endsection

@section('content')
    <livewire:policy-show :policyId="$policyId" />
@endsection
