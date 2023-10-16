@extends('layouts.app')


@section('tasks')
    active
@endsection

@section('content')
    <livewire:task-show :taskId="$taskId" />
@endsection
