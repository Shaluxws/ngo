@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404 Error')
@section('badge_class', 'bg-amber-100 text-amber-800')

@section('icon')
    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
@endsection

@section('message', 'Page Not Found')

@section('description', 'The page you are looking for might have been moved, removed, or is temporarily unavailable.')
