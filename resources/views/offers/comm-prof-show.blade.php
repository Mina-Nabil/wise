@extends('layouts.app')

@section('offers')
    active
@endsection

@section('content')
    <livewire:comm-profile-show :id="$id" />
@endsection
