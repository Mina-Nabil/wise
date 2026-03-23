@extends('layouts.app')

@section('title')
• Campaign
@endsection

@section('campaigns')
    active
@endsection

@section('content')
    <livewire:campaign-show :id="$id" />
@endsection
