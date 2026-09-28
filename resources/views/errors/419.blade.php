@extends('errors.layout')

@section('title', 'Page Expired')
@section('code', '419 Expired')
@section('badge_class', 'bg-blue-100 text-blue-800')

@section('icon')
    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
@endsection

@section('message', 'Page Has Expired')

@section('description', 'Your security session token has expired due to inactivity. Please refresh the page or log in again.')
