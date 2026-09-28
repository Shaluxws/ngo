@extends('errors.layout')

@section('title', 'Too Many Requests')
@section('code', '429 Rate Limit')
@section('badge_class', 'bg-amber-100 text-amber-800')

@section('icon')
    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
    </svg>
@endsection

@section('message', 'Too Many Requests')

@section('description', 'You have sent too many requests in a short period. Please wait a minute before trying again.')
