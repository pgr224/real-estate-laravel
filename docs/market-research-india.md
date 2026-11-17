# India Property Marketplace Research

**Prepared:** 2026-10-15  
**Scope:** Indian residential property portals, one query-specific Google India search snapshot, and candidate property/home-tour creators. This is product discovery, not a comprehensive SEO audit or influencer due diligence.

## Evidence limits

Search positions below are one Google India organic-results snapshot for the exact query **"best real estate websites and apps in India"**. The observed results included Square Yards at position 2, 99acres at position 3, and Magicbricks at position 6. A result in this query does not establish a domain's overall SEO rank, market share, conversion rate, or position for city-specific transactional searches. Rankings vary by location, personalization, device, and date.

The same search results surfaced a Semrush real-estate category page listing estimated visits for 99acres, Magicbricks, Housing.com, and NoBroker. Those are third-party traffic estimates, not search rankings, and are not treated as independently verified analytics here.

The competitor comparison summarizes public product positioning from the linked official sites. Dynamic listing counts, claims such as "verified," and marketing superlatives are not independently audited.

## Competitor scan

| Competitor | Public positioning / observed search evidence | Product implication |
|---|---|---|
| 99acres | Buy, rent, and sale search across residential and commercial property; search result also surfaced price/trend information. It appeared at position 3 in the captured broad discovery query. | Compete on useful local search, transparent listing provenance, and clear price context rather than inventory-count claims. |
| Magicbricks | Broad Indian buy/sell/rent marketplace, with city-specific property search pages. It appeared at position 6 in the captured query. | Make city and locality discovery fast, filterable, and shareable; support useful property detail pages. |
| Housing.com | Consumer-facing property discovery and housing-related services. A third-party category result listed it among high-traffic real-estate domains; that estimate is not a ranking. | Benchmark the complete discovery journey and related services, but validate demand before expanding scope. |
| NoBroker | Consumer property marketplace with a no-broker positioning. A third-party category result listed it among high-traffic real-estate domains; that estimate is not a ranking. | If adopting an owner-direct proposition, make who supplied and verified a listing explicit. Do not imply a listing is trustworthy merely because it is direct. |
| Square Yards | Buy/sell/rent property discovery and developer-oriented inventory. It appeared at position 2 in the captured query. | Treat search visibility and high-intent landing pages as product capabilities; keep any lead or advisory services clearly separated from listings. |

### Search snapshot source

- [Google India search: "best real estate websites and apps in India"](https://www.google.com/search?q=best+real+estate+websites+and+apps+in+India&gl=IN&hl=en) — query used for the dated observation above; current results may differ.
- [Semrush: Top Real Estate Websites in India](https://www.semrush.com/trending-websites/in/real-estate) — third-party estimated visits by category, not an organic-position report.

### Official competitor sources

- [99acres](https://www.99acres.com/)
- [Magicbricks](https://www.magicbricks.com/)
- [Housing.com](https://housing.com/)
- [NoBroker](https://www.nobroker.in/)
- [Square Yards](https://www.squareyards.com/)

## Creator candidates

These are starting points for a small, manually vetted campaign shortlist, not endorsements. Public content and audience metrics change; confirm recent posts, audience geography, engagement quality, brand safety, and availability directly before outreach. No follower counts are used.

| Creator | Public channel | Why it may fit | Suggested validation |
|---|---|---|---|
| Snehil Real Estate | [YouTube channel](https://www.youtube.com/@SnehilRealEstate) | Search results show property tours and home walkthroughs, a direct fit for visual listing discovery. | Check city mix, whether viewers are India-based, recent upload consistency, and qualified inquiry conversion. |
| Priyam Saraswat | [Instagram profile](https://www.instagram.com/priyamsaraswat/) | Public profile/search description centers on houses and home stories; potentially suitable for home tours and owner-submitted stories. | Confirm target-city relevance, audience authenticity, and whether property-tour placements fit the creator's current format. |
| The Realty Presenter (Malitha Fernandes) | [Instagram profile](https://www.instagram.com/therealtypresenter/) | Public profile describes real estate, architecture, and interiors across India and UAE; possible fit for design-led tours. | Confirm India audience share and local coverage before any paid placement. |

The search pass did not establish a comparable India-focused TikTok shortlist. This report therefore recommends testing Instagram Reels and YouTube Shorts first, while verifying platform availability and audience reach at campaign time.

## What the repository already contains

This is a source-level inventory, not a runtime audit:

- The `/properties` Livewire listing now has working keyword, postal-code, status, year-built, property-type, amenity, price, bedroom, bathroom, and area filters, allowlisted sorting, empty results, and mobile pagination controls.
- The repository contains code for auctions/bidding, bookings, and price alerts (for example `AuctionController`, `AuctionInterface`, `BookingCalendar`, and `PriceAlertManager`). Their production behavior was not exercised as part of this research.
- The source includes UK portal sync components such as Zoopla, Jupix, and OnTheMarket. No Indian portal feed or authorized India listing partnership was verified in this review.
- A nullable indexed `postal_code` column was added because the listing model already referenced postal-code search but the schema lacked that column.

Laravel tests were added for listing behavior but were not run: PHP, Composer, and the installed vendor dependencies were unavailable in the environment at preparation time.

## Recommended roadmap

| Priority | Work | Why now | Acceptance signal |
|---|---|---|---|
| P0 | Stabilize property discovery: run the new Livewire tests, verify all filter combinations on the production database engine, add query indexes only after measuring, and keep URL/query-state behavior consistent. | Search is the first high-intent path; code changes need runtime verification before expansion. | Automated tests pass; filtered results and pagination remain correct under the supported database. |
| P1 | Localize the listing model for India: INR display/formatting, BHK terminology, PIN code, state/city/locality hierarchy, area-unit handling, and normalized location data. | Portals compete on local inventory discovery; UK defaults do not automatically fit Indian users. | Listings can be searched and compared consistently across at least one target city without losing source values. |
| P1 | Add listing provenance and moderation: source/owner/agent disclosure, verification evidence and timestamps, user reporting, duplicate detection, and moderator review. | Trust is a differentiator and mitigates stale, duplicated, or misleading inventory. | Every public listing has a clear source state; reports enter a traceable moderation workflow. |
| P1 | Design a state-aware RERA information workflow with official authority links and evidence timestamps. | Helps users inspect project-registration information while avoiding blanket legal assurances. | A user can see registration identifiers, source links, last-checked date, and a clear "not legal advice / not title verification" boundary. |
| P2 | Improve organic discoverability: indexable city/locality landing pages, canonical URLs, sitemap coverage, useful unique descriptions, and structured data only where Google supports the chosen type. Keep faceted URLs from creating unbounded duplicate pages. | The captured results are query-specific; the app needs its own measurable city-level SEO baseline. | Search Console tracks impressions/clicks by city and landing page; crawl/index coverage is reviewed. |
| P2 | Run a four-week creator pilot with one or two creators in one or two cities. Route each creator to a consented landing form using unique UTM/referral IDs. | Tests the acquisition hypothesis before investing in broad influencer campaigns. | Compare qualified inquiries, booked visits, cost per qualified lead, and lead-to-visit conversion—not follower count alone. |
| P3 | Evaluate 360-degree/virtual tours and licensed partner feeds after search, trust, and lead attribution are measurable. | These features can be costly and do not fix poor inventory quality or discovery. | Ship only after a partner/legal review, mobile-performance budget, and a measured user-demand test. |

## Sources

- Official property portals: [99acres](https://www.99acres.com/), [Magicbricks](https://www.magicbricks.com/), [Housing.com](https://housing.com/), [NoBroker](https://www.nobroker.in/), [Square Yards](https://www.squareyards.com/).
- Search context: [Google India query](https://www.google.com/search?q=best+real+estate+websites+and+apps+in+India&gl=IN&hl=en); [Semrush real-estate category estimates](https://www.semrush.com/trending-websites/in/real-estate).
- Creator sources: [Snehil Real Estate on YouTube](https://www.youtube.com/@SnehilRealEstate), [Priyam Saraswat on Instagram](https://www.instagram.com/priyamsaraswat/), [The Realty Presenter on Instagram](https://www.instagram.com/therealtypresenter/).

