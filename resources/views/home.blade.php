@extends('layouts.app')

@section('content')
    <div class="home-page">
        <section class="home-hero">
            <div class="site-container home-hero__inner">
                <div class="home-hero__copy">
                    <p class="home-eyebrow"><span></span> A better way to find your place</p>
                    <h1>Find a place that feels like <em>yours.</em></h1>
                    <p class="home-hero__description">
                        Explore homes, compare the details that matter, and take your next step with confidence.
                    </p>

                    <form action="{{ route('property.list') }}" method="GET" class="home-search" role="search">
                        <label class="home-search__field">
                            <span>Location or keyword</span>
                            <input type="search" name="search" value="{{ request('search') }}"
                                placeholder="Try a city, neighborhood, or home type" autocomplete="off">
                        </label>
                        <label class="home-search__field home-search__type">
                            <span>Property type</span>
                            <select name="propertyType">
                                <option value="">All property types</option>
                                <option value="House">House</option>
                                <option value="Apartment">Apartment</option>
                                <option value="Condo">Condo</option>
                                <option value="Townhouse">Townhouse</option>
                            </select>
                        </label>
                        <button class="home-search__submit" type="submit" aria-label="Search properties">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="10.8" cy="10.8" r="6.8" stroke="currentColor" stroke-width="1.8" />
                                <path d="m16 16 4.5 4.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            <span>Explore homes</span>
                        </button>
                    </form>

                    <a href="{{ route('property.list') }}" class="home-hero__browse">
                        Browse all properties <span aria-hidden="true">→</span>
                    </a>
                </div>

                <div class="home-hero__art" aria-hidden="true">
                    <div class="home-hero__sun"></div>
                    <div class="home-hero__house">
                        <div class="home-hero__roof"></div>
                        <div class="home-hero__building">
                            <div class="home-hero__window home-hero__window--left"></div>
                            <div class="home-hero__door"></div>
                            <div class="home-hero__window home-hero__window--right"></div>
                        </div>
                    </div>
                    <div class="home-hero__plant home-hero__plant--one"></div>
                    <div class="home-hero__plant home-hero__plant--two"></div>
                    <div class="home-hero__note">
                        <span class="home-hero__note-icon" aria-hidden="true">⌂</span>
                        <span><strong>Your next chapter</strong><small>starts right here</small></span>
                    </div>
                </div>
            </div>
        </section>

        <section class="home-featured site-container" aria-labelledby="featured-heading">
            <div class="home-section-heading">
                <div>
                    <p class="home-eyebrow"><span></span> Handpicked for you</p>
                    <h2 id="featured-heading">Featured properties</h2>
                    <p>Take a closer look at homes selected to inspire your search.</p>
                </div>
                <a class="home-text-link" href="{{ route('property.list') }}">View all homes <span aria-hidden="true">→</span></a>
            </div>

            <div class="home-property-grid">
                @forelse($featuredProperties as $property)
                    <article class="home-property-card">
                        <a class="home-property-card__image" href="{{ route('property.detail', $property->id) }}"
                            aria-label="View {{ $property->title }}">
                            <img src="{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}"
                                alt="{{ $property->title }}" width="640" height="420"
                                loading="lazy" decoding="async">
                            <span class="home-property-card__badge">Featured</span>
                        </a>
                        <div class="home-property-card__body">
                            <p class="home-property-card__location">{{ $property->location }}</p>
                            <h3><a href="{{ route('property.detail', $property->id) }}">{{ $property->title }}</a></h3>
                            <p class="home-property-card__description">{{ Str::limit($property->description, 110) }}</p>
                            <div class="home-property-card__facts">
                                <span>{{ $property->bedrooms }} beds</span>
                                <span>{{ $property->bathrooms }} baths</span>
                                <span>{{ number_format($property->area_sqft) }} sq ft</span>
                            </div>
                            <div class="home-property-card__footer">
                                <strong>{{ site_setting('currency') }} {{ number_format($property->price) }}</strong>
                                <a href="{{ route('property.detail', $property->id) }}">View details <span aria-hidden="true">→</span></a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="home-empty-state">
                        <span aria-hidden="true">⌂</span>
                        <h3>New homes are on their way</h3>
                        <p>Browse the full collection to see what is currently available.</p>
                        <a href="{{ route('property.list') }}" class="home-button">Browse properties</a>
                    </div>
                @endforelse
            </div>
        </section>

        @if ($mapProperties->isNotEmpty())
            <section class="home-map-section" aria-labelledby="map-heading">
                <div class="site-container">
                    <div class="home-section-heading home-section-heading--map">
                        <div>
                            <p class="home-eyebrow"><span></span> Find your neighborhood</p>
                            <h2 id="map-heading">Explore homes on the map</h2>
                            <p>See where available properties are located and discover the areas around them.</p>
                        </div>
                    </div>
                    <x-property-map :properties="$mapProperties" />
                </div>
            </section>
        @endif
    </div>
@endsection
