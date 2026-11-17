<?php

namespace Tests\Feature;

use App\Http\Livewire\PropertyList;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PropertyListTest extends TestCase
{
    use RefreshDatabase;

    public function test_listing_applies_keyword_postal_code_status_and_year_filters(): void
    {
        Property::factory()->create([
            'title' => 'South Delhi family home',
            'postal_code' => '110001',
            'status' => 'For Sale',
            'year_built' => 2020,
        ]);

        Property::factory()->create([
            'title' => 'South Delhi rental',
            'postal_code' => '110001',
            'status' => 'For Rent',
            'year_built' => 2022,
        ]);

        Property::factory()->create([
            'title' => 'Mumbai apartment',
            'postal_code' => '400001',
            'status' => 'For Sale',
            'year_built' => 2010,
        ]);

        Livewire::test(PropertyList::class)
            ->set('search', 'South Delhi')
            ->set('postalCode', '110001')
            ->set('status', 'For Sale')
            ->set('yearBuilt', 2018)
            ->assertSee('South Delhi family home')
            ->assertDontSee('South Delhi rental')
            ->assertDontSee('Mumbai apartment');
    }

    public function test_listing_sort_options_use_a_fixed_order_by_allowlist(): void
    {
        Property::factory()->create([
            'title' => 'Higher priced home',
            'price' => 500000,
        ]);

        Property::factory()->create([
            'title' => 'Lower priced home',
            'price' => 200000,
        ]);

        Livewire::test(PropertyList::class)
            ->set('sortBy', 'price_asc')
            ->assertSeeInOrder(['Lower priced home', 'Higher priced home']);
    }

    public function test_empty_search_and_postal_code_values_are_accepted(): void
    {
        Property::factory()->create(['title' => 'Available family home']);

        Livewire::test(PropertyList::class)
            ->set('search', null)
            ->set('postalCode', null)
            ->assertSee('Available family home');
    }
}
