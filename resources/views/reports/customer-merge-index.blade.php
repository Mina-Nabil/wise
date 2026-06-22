@extends('layouts.app')

@section('title')
    • Reports • Customer Merge
@endsection

@section('reports.customer-duplicates')
    active
@endsection

@section('content')
    <livewire:customer-merge :ids="$ids" />
@endsection
