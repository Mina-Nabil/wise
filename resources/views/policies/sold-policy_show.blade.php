@extends('layouts.app')

@section('title')
• Sold Policy
@endsection


@section('sold-policies')
    active
@endsection

@section('content')
    <livewire:sold-policy-show :id="$id" />
@endsection
