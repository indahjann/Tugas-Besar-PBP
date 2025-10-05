<section class="container my-5">
    <h2 class="text-center">Testimonial Pelanggan</h2>
    <div class="row">
        @foreach($testimonials ?? [] as $t)
            <div class="col-md-3">
                <div class="testimonial-card">
                    <p>"{{ $t['message'] ?? '' }}"</p>
                    <p><strong>{{ $t['name'] ?? '' }}</strong> - {{ $t['city'] ?? '' }}</p>
                    <p class="stars">{{ $t['stars'] ?? '⭐⭐⭐⭐' }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
<!-- duplicate removed -->