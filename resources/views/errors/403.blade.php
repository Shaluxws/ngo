@extends('errors.layout')

@section('title', 'Access Denied')
@section('code', '403 Forbidden')
@section('badge_class', 'bg-rose-100 text-rose-800')

@section('icon')
    <svg class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-6V7a4 4 0 00-8 0v4h8z" />
    </svg>
@endsection

@section('message', 'Access Forbidden')

@section('description', 'You do not have administrative permission to view or interact with this protected resource.')
