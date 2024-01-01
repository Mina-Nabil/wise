@extends('layouts.app')

@section('offers')
    active
@endsection

@section('content')
    <livewire:offer-show :offerId="$offerId" />
@endsection
