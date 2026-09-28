@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500 Error')
@section('badge_class', 'bg-rose-100 text-rose-800')

@section('icon')
    <svg class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
    </svg>
@endsection

@section('message', 'Internal Server Error')

@section('description', 'An unexpected error occurred while processing your request. Our technical team has been notified.')
