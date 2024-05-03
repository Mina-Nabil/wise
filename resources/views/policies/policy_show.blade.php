@extends('layouts.app')

@section('title')
• Policy
@endsection


@section('policies')
    active
@endsection

@section('content')
    <livewire:policy-show :policyId="$policyId" />
@endsection
