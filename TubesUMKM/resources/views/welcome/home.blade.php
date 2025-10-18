{{-- Loop through all sections --}}
@foreach(($sections ?? []) as $sectionKey => $section)
    @if($section['books']->count() > 0)
        <section class="featured-section">
            <div class="container">
                <div class="carousel-container-bg">
                    <button class="nav-btn prev" id="prevBtn{{ $loop->index }}"><i class="fas fa-chevron-left"></i></button>
                    <button class="nav-btn next" id="nextBtn{{ $loop->index }}"><i class="fas fa-chevron-right"></i></button>
                    <h2 class="section-title text-center mb-4">{{ $section['title'] }}</h2>
                    <div class="carousel-viewport">
                        <div class="custom-carousel" id="bookCarousel{{ $loop->index }}">
                            <div class="carousel-track" id="carouselTrack{{ $loop->index }}">
                                {{-- Loop books using book card component --}}
                                @foreach($section['books'] as $book)
                                    <div class="carousel-item-custom">
                                        <x-book-card :book="$book" :user-wishlist="$userWishlist ?? []" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            @if($sectionKey === 'recent')
                                <a href="/categories" class="btn view-more-btn">VIEW MORE</a>
                            @elseif($sectionKey === 'fiction')
                                <a href="/categories?category=1" class="btn view-more-btn">VIEW MORE FICTION</a>
                            @elseif($sectionKey === 'manga')
                                <a href="/categories?category=3" class="btn view-more-btn">VIEW MORE MANGA</a>
                            @elseif($sectionKey === 'selfhelp')
                                <a href="/categories?category=5" class="btn view-more-btn">VIEW MORE SELF-HELP</a>
                            @elseif($sectionKey === 'technology')
                                <a href="/categories?category=6" class="btn view-more-btn">VIEW MORE TECH</a>
                            @else
                                <a href="/categories" class="btn view-more-btn">VIEW MORE</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        {{-- Add spacing between sections like Gramedia --}}
        @if(!$loop->last)
            <div style="height: 60px;"></div>
        @endif
    @endif
@endforeach
