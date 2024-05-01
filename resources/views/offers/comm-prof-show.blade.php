@extends('layouts.app')

@section('title')
• Commission Profile
@endsection

@section('commissions')
    active
@endsection

@section('content')
    <livewire:comm-profile-show :id="$id" />
@endsection
