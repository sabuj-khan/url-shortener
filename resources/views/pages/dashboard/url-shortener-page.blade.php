@extends('layouts.sidenav-layout')
@section('content')
    @include('components.url.url-list')
    @include('components.url.create-url-form')
    @include('components.url.delete-url')
@endsection