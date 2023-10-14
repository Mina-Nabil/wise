@extends('layouts.app')

@section('content')
    <livewire:task-show :taskId="$taskId" />
@endsection
