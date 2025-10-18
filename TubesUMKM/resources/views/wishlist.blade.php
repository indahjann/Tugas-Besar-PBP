@extends('layouts.app')

@push('head')
    {{-- wishlist styles are compiled via Vite (app.css) --}}
@endpush

@section('content')
    @include('Wishlist.main')
@endsection

@push('scripts')
    {{-- Wishlist logic is bundled from resources/js/components/book-card.js via Vite --}}
@endpush
