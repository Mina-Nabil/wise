@extends('layouts.app')

@section('tasks')
    active
@endsection

@section('content')
    <livewire:task-index :filters="$filters ?? []" />

@endsection
