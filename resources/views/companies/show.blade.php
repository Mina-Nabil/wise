@extends('layouts.app')

@section('title')
• Companies • {{ $name }}
@endsection

@section('companies')
    active
@endsection

@section('content')
    <livewire:company-show :company_id="$company_id" />
@endsection
