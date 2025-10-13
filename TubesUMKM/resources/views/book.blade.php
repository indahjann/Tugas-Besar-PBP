@extends('layouts.app')

@push('head')
    <meta name="description" content="{{ $book->description ?? $book->name . ' by ' . $book->author }}">
    <meta property="og:title" content="{{ $book->name }}">
    <meta property="og:description" content="{{ $book->description ?? $book->name . ' by ' . $book->author }}">
    <meta property="og:image" content="{{ $book->cover_url }}">
@endpush

@section('content')
    @includeWhen(true, 'book.detail-content')
@endsection

@push('scripts')
    {{-- Book detail logic is bundled from resources/js/book-detail.js via Vite --}}
@endpush