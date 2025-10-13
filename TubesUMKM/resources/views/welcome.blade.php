@extends('layouts.app')

@push('head')
    {{-- welcome styles are compiled via Vite (app.css) --}}
@endpush

@section('content')
    @includeWhen(true, 'welcome.promo')
    @includeWhen(true, 'welcome.featured')
    @includeWhen(true, 'welcome.categories')
@endsection

@push('scripts')
    {{-- Carousel logic is bundled from resources/js/carousel.js via Vite. If you need Bootstrap JS/CSS via CDN temporarily, add it to the layout or install via npm and import into app.css/app.js. --}}
@endpush