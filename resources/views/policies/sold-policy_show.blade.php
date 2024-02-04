@extends('layouts.app')

@section('sold-policies')
    active
@endsection

@section('content')
    <livewire:sold-policy-show :id="$id" />
@endsection
