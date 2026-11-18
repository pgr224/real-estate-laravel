@extends('layouts.app')
@section('content')
    <div class="property-list-page">
        <div class="property-list-hero">
            <div class="site-container py-8 lg:py-16">
                <div class="max-w-3xl">
                    <h1
                        class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">
                        Properties for Sale & Rent</h1>
                    <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
                        Welcome to our comprehensive real estate platform, where you can explore an extensive range of
                        properties available for sale and rent. Our goal is to provide a seamless and efficient experience
                        for
                        buyers, sellers, landlords, and tenants alike. Whether you're looking for your dream home, an
                        investment
                        property, or a rental residence, we have a diverse selection to cater to your needs</p>
                    <a href="/contact"
                        class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                        Contact us
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>


    <div class="container mx-auto px-4 py-8">
        <div class="property-list-controls py-8 antialiased md:py-12">
            <div class="mx-auto max-w-screen-xl px-0 2xl:px-0">
                <div class="d-flex mb-5">
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center" wire:submit.prevent="applySearch">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full mr-2">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="search" id="simple-search" wire:model.live.debounce.300ms="search"
                                    class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Looking for a house, apartment, or commercial space? ...">
                            </div>

                            <button type="submit"
                                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                <svg class="h-3.5 w-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                        d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                </svg>
                                search
                            </button>
                        </form>
                    </div>
                </div>
                <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="postal-code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Postal code</label>
                        <input id="postal-code" type="text" maxlength="32" wire:model.live.debounce.300ms="postalCode"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="property-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Property type</label>
                        <select id="property-type" wire:model.live="propertyType"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All types</option>
                            <option value="House">House</option>
                            <option value="Apartment">Apartment</option>
                            <option value="Condo">Condo</option>
                            <option value="Townhouse">Townhouse</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Listing status</label>
                        <select id="status" wire:model.live="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All statuses</option>
                            <option value="For Sale">For sale</option>
                            <option value="For Rent">For rent</option>
                            <option value="Sold">Sold</option>
                            <option value="Rented">Rented</option>
                        </select>
                    </div>
                    <div>
                        <label for="year-built" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Built after</label>
                        <input id="year-built" type="number" min="1800" max="{{ now()->year }}" wire:model.live="yearBuilt"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="min-price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum price</label>
                        <input id="min-price" type="number" min="0" wire:model.live="minPrice"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="max-price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maximum price</label>
                        <input id="max-price" type="number" min="0" wire:model.live="maxPrice"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="min-bedrooms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum bedrooms</label>
                        <input id="min-bedrooms" type="number" min="0" wire:model.live="minBedrooms"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="max-bedrooms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maximum bedrooms</label>
                        <input id="max-bedrooms" type="number" min="0" wire:model.live="maxBedrooms"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="min-bathrooms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum bathrooms</label>
                        <input id="min-bathrooms" type="number" min="0" wire:model.live="minBathrooms"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="max-bathrooms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maximum bathrooms</label>
                        <input id="max-bathrooms" type="number" min="0" wire:model.live="maxBathrooms"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="min-area" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum area (sq ft)</label>
                        <input id="min-area" type="number" min="0" wire:model.live="minArea"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="max-area" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maximum area (sq ft)</label>
                        <input id="max-area" type="number" min="0" wire:model.live="maxArea"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="sort-by" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sort by</label>
                        <select id="sort-by" wire:model.live="sortBy"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="created_at">Newest</option>
                            <option value="price_asc">Price: low to high</option>
                            <option value="price_desc">Price: high to low</option>
                            <option value="bedrooms">Bedrooms</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" wire:click="resetFilters"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Clear filters
                        </button>
                    </div>
                </div>
                @if ($amenities->isNotEmpty())
                    <fieldset class="mb-6">
                        <legend class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Amenities</legend>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($amenities as $amenity)
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" wire:model.live="selectedAmenities" value="{{ $amenity }}"
                                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                                    <span>{{ $amenity }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                @endif
                @if ($errors->any())
                    <div role="alert" class="mb-6 rounded-md border border-red-300 bg-red-50 p-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse($properties as $property)
                        <div
                            class="property-list-card rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <div class="h-56 w-full">
                                <a href="{{ route('property.detail', $property->id) }}">
                                    <img src="{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}"
                                        alt="{{ $property->title }}" class="mx-auto h-full dark:hidden">
                                </a>
                            </div>
                            <div class="pt-6">
                                <div class="mb-4 flex items-center justify-between gap-4">
                                    <span
                                        class="me-2 rounded bg-primary-100 px-2.5 py-0.5 text-md font-medium text-primary-800 dark:bg-primary-900 dark:text-primary-300">
                                        {{ site_setting('currency') . ' ' . number_format($property->price, 2) }}
                                    </span>
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" data-tooltip-target="tooltip-quick-look"
                                            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                            <span class="sr-only"> Quick look </span>
                                            <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-width="2"
                                                    d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                                <path stroke="currentColor" stroke-width="2"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>
                                        <div id="tooltip-quick-look" role="tooltip"
                                            class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                                            data-popper-placement="top">
                                            Quick look
                                            <div class="tooltip-arrow" data-popper-arrow=""></div>
                                        </div>

                                        <button type="button" data-tooltip-target="tooltip-add-to-favorites"
                                            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                            <span class="sr-only"> Add to Favorites </span>
                                            <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 6C6.5 1 1 8 5.8 13l6.2 7 6.2-7C23 8 17.5 1 12 6Z" />
                                            </svg>
                                        </button>
                                        <div id="tooltip-add-to-favorites" role="tooltip"
                                            class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                                            data-popper-placement="top">
                                            Add to favorites
                                            <div class="tooltip-arrow" data-popper-arrow=""></div>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('property.detail', $property->id) }}"
                                    class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">
                                    {{ Str::limit($property->title, 25) }}</a>

                                <p class="text-xs  text-gray-500 dark:text-gray-400">
                                    {{ Str::limit($property->description, 70   ) }}
                                </p>

                                <ul class="mt-2">
                                    <li class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z" />
                                        </svg>

                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bedrooms :
                                            {{ $property->bedrooms }}</p>
                                    </li>

                                    <li class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 8v8m0-8h8M8 8H6a2 2 0 1 1 2-2v2Zm0 8h8m-8 0H6a2 2 0 1 0 2 2v-2Zm8 0V8m0 8h2a2 2 0 1 1-2 2v-2Zm0-8h2a2 2 0 1 0-2-2v2Z" />
                                        </svg>

                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bathrooms :
                                            {{ $property->bathrooms }}</p>
                                    </li>

                                    <li class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 7H7m2 3H7m2 3H7m4 2v2m3-2v2m3-2v2M4 5v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-9a1 1 0 0 1-1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1Z" />
                                        </svg>

                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Square Foot :
                                            {{ $property->area_sqft }}</p>
                                    </li>
                                </ul>


                                <div class="mt-4 flex items-center justify-between gap-4">
                                    <p class="text-sm font-extrabold leading-tight text-gray-900 dark:text-white">

                                    </p>

                                    <a href="{{ route('property.detail', $property->id) }}"
                                        class="inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4  focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                        <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2"
                                                d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                            <path stroke="currentColor" stroke-width="2"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>

                                        VIEW DETAILS
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full py-8 text-center text-gray-500 dark:text-gray-400">No properties match your search.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-gray-50 py-8 antialiased dark:bg-gray-900 md:py-12">
            <div class="flex items-center justify-between border-gray-100 bg-white px-4 py-3 sm:px-6">
                <div class="flex flex-1 justify-between sm:hidden">
                    <button type="button" wire:click="previousPage" @disabled($properties->onFirstPage())
                        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</button>
                    <button type="button" wire:click="nextPage" @disabled(!$properties->hasMorePages())
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</button>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>

                    </div>
                    <div>
                        <div class="">
                            {{ $properties->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
