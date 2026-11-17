# Marketplace Product Roadmap

This roadmap prioritizes the Indian residential-marketplace opportunity described in [India property market research](./market-research-india.md). It distinguishes features present in source code from features that still need implementation or runtime proof.

## Now: make discovery dependable

- Keep `/properties` search, filters, sorting, pagination, and empty states coherent.
- Run the focused Livewire tests and the supported-database migration before release.
- Measure listing-query latency and add indexes based on query plans, not guesswork.
- Preserve filter state in shareable, canonical URLs while avoiding an index explosion from arbitrary facet combinations.

## Next: India readiness and listing trust

- Add India-specific currency, address hierarchy, PIN code, BHK, and area-unit handling without discarding source data.
- Make listing source and verification state visible; add evidence, timestamp, duplicate reporting, and moderation trails.
- Provide state-specific RERA references only when backed by authoritative public sources; explicitly state that this is not title or legal verification.
- Pilot one city end to end before adding nationwide defaults.

## Then: measurable acquisition

- Build useful city/locality landing pages with canonical metadata, sitemap coverage, and an SEO baseline.
- Run a small Instagram Reels/YouTube Shorts property-tour pilot with consented lead capture and campaign attribution.
- Track qualified leads, visit bookings, conversion, and cost per qualified inquiry.
- Explore licensed portal feeds and creator/agency partnerships; do not depend on unauthorized scraping.

## Later: experience enhancements

- Evaluate 360-degree tours or other rich media against mobile performance, bandwidth, and measured buyer demand.
- Expand beyond the core marketplace only after discovery, trust, and attribution outcomes are measured.

## Release gates

1. Search/filter feature tests pass and database behavior is verified.
2. Migration, localization, and property details work for the first target Indian city.
3. Listing provenance and moderation are usable before scaling inventory acquisition.
4. SEO and creator pilots have attribution and success metrics before additional investment.
