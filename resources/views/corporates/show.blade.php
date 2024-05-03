@extends('layouts.app')

@section('title')
• Corporate
@endsection

@section('corporates')
    active
@endsection

@section('content')
    <livewire:corporate-show :corporateId="$corporateId" />
@endsection