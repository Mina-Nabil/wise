@extends('layouts.app')

@section('commissions')
    active
@endsection

@section('content')
    <livewire:comm-profile-show :id="$id" />
@endsection
