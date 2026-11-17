@props(['properties' => []])

<div class="property-map-shell">
    <div class="property-map"
        data-property-map
        data-properties='@json($properties)'
        data-currency="{{ SiteConfig::get('currency') }}"
        role="region"
        aria-label="Map showing property locations"
        aria-busy="true">
        <span class="property-map__loading">Map loads when you scroll here</span>
    </div>
</div>
