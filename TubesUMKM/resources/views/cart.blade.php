@extends('layouts.app')

@push('head')
    {{-- cart styles are compiled via Vite (app.css) --}}
@endpush

@section('content')
    @includeWhen(true, 'Cart.main')
@endsection

@push('scripts')
    {{-- Cart logic is bundled from resources/js/cart.js via Vite --}}
@endpush