@extends('layouts.app')

@section('title')
    • Reports • Corporate Merge
@endsection

@section('reports.corporate-duplicates')
    active
@endsection

@section('content')
    <livewire:corporate-merge :ids="$ids" />
@endsection
