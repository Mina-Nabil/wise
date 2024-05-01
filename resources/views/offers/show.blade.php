@extends('layouts.app')

@section('title')
• Offer
@endsection

@section('offers')
    active
@endsection

@section('content')
    <livewire:offer-show :offerId="$offerId" />
@endsection
