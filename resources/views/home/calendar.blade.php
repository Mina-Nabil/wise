@extends('layouts.app')

@section('title')
• Calendar
@endsection

@section('calendar')
    {{ $mode === 'all' ? 'active' : false }}
@endsection

@section('followups-calendar')
    {{ $mode === 'followups' ? 'active' : false }}
@endsection

@section('content')
    <livewire:calendar mode="{{ $mode }}" />  
@endsection
