@extends('layouts.app')

@push('head')
    {{-- categories styles are compiled via Vite (app.css) --}}
@endpush

@section('content')
    @includeWhen(true, 'Categories.main')
@endsection

@push('scripts')
    {{-- Categories logic is bundled from resources/js/categories.js via Vite --}}
@endpush
