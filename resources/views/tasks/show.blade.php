@extends('layouts.app')

@section('title')
• Task
@endsection


@section('tasks')
    active
@endsection

@section('content')
    <livewire:task-show :taskId="$taskId" />
@endsection
