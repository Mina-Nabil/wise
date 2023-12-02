@extends('layouts.app')


@section('corporates')
    active
@endsection

@section('content')
    <livewire:corporate-show :corporateId="$corporateId" />
@endsection