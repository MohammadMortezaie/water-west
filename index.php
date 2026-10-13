<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$path = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');

if (PHP_SAPI === 'cli-server' && $path !== '' && is_file(__DIR__ . '/' . $path)) {
    return false;
}

if ($path === '' || $path === 'index.php') {
    render_home();
} elseif ($path === 'services') {
    render_services();
} elseif (isset($services[$path])) {
    render_service($path, $services[$path]);
} elseif ($path === 'areas-we-serve') {
    render_areas();
} elseif ($path === 'about') {
    render_about();
} elseif ($path === 'contact') {
    render_contact();
} elseif ($path === 'privacy') {
    render_privacy();
} else {
    http_response_code(404);
    render_not_found();
}

function render_home(): void
{
    global $services, $homeFaqs, $neighbourhoods;
    $meta = [
        'title' => 'Water Damage Restoration West Vancouver | 24/7',
        'description' => '24/7 water damage restoration in West Vancouver. Fast water extraction, structural drying, free inspection and insurance claim support.',
    ];
    render_header($meta, '', [faq_schema($homeFaqs)]);
    ?>
    <section class="hero hero--home">
        <div class="hero__media">
            <img src="/assets/images/emergency-water-damage-living-room.webp" width="1920" height="1080" alt="Homeowner responding to water leaking into a West Vancouver living room" fetchpriority="high">
        </div>
        <div class="hero__shade"></div>
        <div class="container hero__content">
            <div class="hero__grid">
                <div class="hero__copy">
                <?php render_canadian_badge('Local West Vancouver response'); ?>
                <span class="eyebrow eyebrow--light">Local help when every minute matters</span>
                <h1>Water Damage Restoration West Vancouver</h1>
                <p class="hero__lead">Fast, professional help for flooding, burst pipes and hidden moisture. Available 24/7 across West Vancouver.</p>
                <div class="hero__actions">
                    <a class="button button--primary" href="tel:<?= e(PHONE_LINK) ?>"><?= icon('phone') ?> Call Now</a>
                    <a class="button button--glass" href="#free-inspection">Request Free Inspection</a>
                </div>
                <ul class="hero__checks" aria-label="Service benefits">
                    <li><?= icon('check') ?> 24/7 emergency service</li>
                    <li><?= icon('check') ?> Fast local response</li>
                    <li><?= icon('check') ?> Free inspection</li>
                    <li><?= icon('check') ?> Insurance claim assistance</li>
                </ul>
                </div>
                <?php render_hero_form('home'); ?>
            </div>
        </div>
        <div class="hero__proof">
            <div class="container proof-grid">
                <div class="proof-cert"><img src="/assets/images/iicrc-certified.webp" width="300" height="100" alt="IICRC certification logo"><span><strong>IICRC</strong> Certified</span></div>
                <div><?= icon('clock') ?><span><strong>24/7</strong> Emergency calls</span></div>
                <div><?= icon('meter') ?><span><strong>Measured</strong> Moisture checks</span></div>
                <div><?= icon('shield') ?><span><strong>Residential</strong> & commercial</span></div>
                <div><?= icon('document') ?><span><strong>Clear</strong> Claim documentation</span></div>
            </div>
        </div>
    </section>

    <section class="section section--intro">
        <div class="container intro-grid">
            <div>
                <span class="eyebrow">West Vancouver water damage help</span>
                <h2>Stop the water. Find the moisture. Dry the structure.</h2>
            </div>
            <div class="prose">
                <p>Water damage can spread through flooring, wall cavities and rooms below before the full extent is visible. Our first priority is to make the area safer, control further movement and remove accessible water. We then use moisture instruments to trace affected materials and build the drying plan around your property.</p>
                <p>We help homeowners, strata contacts and local businesses with sudden plumbing leaks, floods, roof leaks, storm water and appliance failures. You receive straightforward updates, practical options and documentation that can support your insurance conversation.</p>
                <a class="text-link" href="/water-damage-restoration-west-vancouver">Explore our complete restoration service <?= icon('arrow') ?></a>
            </div>
        </div>
    </section>

    <section class="section section--mist work-gallery">
        <div class="container split-heading">
            <div>
                <span class="eyebrow">Our equipment and project work</span>
                <h2>Real conditions require the right response</h2>
            </div>
            <p>These photos show the equipment, drying work and damage conditions our restoration team handles.</p>
        </div>
        <div class="container work-gallery__grid">
            <figure class="work-gallery__wide"><img src="/assets/images/restoration-drying-equipment.webp" width="1373" height="1146" loading="lazy" alt="Water damage drying equipment ready for emergency jobs"><figcaption>Drying equipment ready for dispatch</figcaption></figure>
            <figure><img src="/assets/images/living-room-structural-drying.webp" width="1600" height="1200" loading="lazy" alt="Air movers and dehumidifiers drying a living room"><figcaption>Living room structural drying</figcaption></figure>
            <figure><img src="/assets/images/water-damage-wall-restoration.webp" width="1200" height="1600" loading="lazy" alt="Wall opened for water damage drying and repair"><figcaption>Wall drying and repair preparation</figcaption></figure>
            <figure><img src="/assets/images/bathroom-structural-drying.webp" width="1200" height="1510" loading="lazy" alt="Professional drying equipment operating in a bathroom"><figcaption>Bathroom drying equipment</figcaption></figure>
            <figure><img src="/assets/images/basement-flood-cleanup.webp" width="1920" height="1080" loading="lazy" alt="Flood water covering a basement floor and stairs"><figcaption>Basement flood conditions</figcaption></figure>
            <figure class="work-gallery__wide"><img src="/assets/images/ceiling-water-damage.webp" width="1920" height="1080" loading="lazy" alt="Moisture damage beside a ceiling sprinkler fitting"><figcaption>Ceiling moisture inspection</figcaption></figure>
        </div>
    </section>

    <section class="section section--navy services-overview">
        <div class="container split-heading split-heading--light">
            <div>
                <span class="eyebrow eyebrow--aqua">Problems we solve</span>
                <h2>From the first leak to the final dry reading</h2>
            </div>
            <p>One local response for urgent water removal, careful drying and the cleanup that follows.</p>
        </div>
        <div class="container problem-grid">
            <?php
            $problems = [
                ['Flooding', 'drop'], ['Burst pipes', 'drop'], ['Basement water damage', 'drop'],
                ['Appliance leaks', 'drop'], ['Roof leaks', 'drop'], ['Storm damage', 'drop'],
                ['Water extraction', 'meter'], ['Structural drying', 'meter'], ['Moisture detection', 'meter'],
                ['Emergency cleanup', 'shield'],
            ];
            foreach ($problems as $problem): ?>
                <div><?= icon($problem[1]) ?><span><?= e($problem[0]) ?></span></div>
            <?php endforeach; ?>
        </div>
        <div class="container services-link-row">
            <a class="button button--outline-light" href="/services">View all water damage services <?= icon('arrow') ?></a>
        </div>
    </section>

    <section class="section process-section">
        <div class="container process-layout">
            <div class="process-sticky">
                <span class="eyebrow">Our restoration process</span>
                <h2>A clear sequence for a stressful situation</h2>
                <p>Every loss is different, but the work should follow a disciplined path from the emergency call through documented drying and repairs.</p>
                <a class="button button--dark" href="#free-inspection">Get a free inspection</a>
            </div>
            <ol class="process-list">
                <?php
                $process = [
                    ['Emergency Response', 'We gather the key details, explain immediate safety steps and confirm an arrival window.'],
                    ['Inspection and Moisture Detection', 'Visible and hidden moisture is mapped across affected and adjoining areas.'],
                    ['Water Extraction', 'Standing water is removed using equipment selected for the surfaces involved.'],
                    ['Drying and Dehumidification', 'Airflow and humidity control are planned for the specific building materials.'],
                    ['Cleaning and Sanitizing', 'Affected areas are cleaned with methods appropriate for the water source.'],
                    ['Repairs and Restoration', 'Damaged materials are repaired or replaced so the space can return to use.'],
                    ['Insurance Documentation', 'Photos, readings and work records help create a clear claim file.'],
                ];
                foreach ($process as $index => $step): ?>
                    <li><span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span><div><h3><?= e($step[0]) ?></h3><p><?= e($step[1]) ?></p></div></li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>

    <section class="section section--mist">
        <div class="container split-heading">
            <div>
                <span class="eyebrow">Focused restoration services</span>
                <h2>The right response for the water source</h2>
            </div>
            <p>Clean water leaks, outdoor flooding and sewage backups require different handling. Start with the service that best matches your situation.</p>
        </div>
        <div class="container">
            <?php render_service_cards(array_keys($services)); ?>
        </div>
    </section>

    <section class="section local-section">
        <div class="container local-grid">
            <div class="local-map" aria-hidden="true">
                <div class="local-map__coast"></div>
                <?php foreach (array_slice($neighbourhoods, 0, 6) as $index => $area): ?>
                    <span style="--x: <?= 18 + (($index * 17) % 65) ?>%; --y: <?= 18 + (($index * 29) % 62) ?>%;"><?= icon('pin') ?><?= e($area) ?></span>
                <?php endforeach; ?>
            </div>
            <div>
                <span class="eyebrow">Local coverage</span>
                <h2>Serving West Vancouver from Ambleside to Horseshoe Bay</h2>
                <p>Access, slopes, coastal rain and a mix of older and newer construction can all shape a water damage response in West Vancouver. We serve homes and businesses throughout the municipality, including Dundarave, British Properties, Caulfeild, Eagle Harbour and surrounding neighbourhoods.</p>
                <a class="text-link" href="/areas-we-serve">See every area we serve <?= icon('arrow') ?></a>
            </div>
        </div>
    </section>

    <?php render_faqs($homeFaqs, 'Water damage questions from West Vancouver property owners'); ?>
    <?php render_footer();
}

function render_services(): void
{
    global $services;
    $serviceFaqs = [
        ['Which water damage service should I choose?', 'Choose emergency water damage help when water is still entering or spreading. Choose water extraction for standing water, flood cleanup for a broad flooded area, and complete restoration when drying and repairs are both needed. If you are unsure, call and describe the source and affected rooms.'],
        ['Are water damage services available 24 hours a day?', 'Emergency calls are answered 24/7 for properties in West Vancouver. Current arrival time depends on active calls, traffic and weather, and we provide an honest availability window by phone.'],
        ['Do you work in houses, strata properties and commercial spaces?', 'Yes. We help homeowners, strata contacts, property managers and local businesses. Access, occupants and shared building systems are considered when planning the response.'],
        ['Can you provide information for an insurance claim?', 'Yes. Photographs, moisture readings and mitigation records can support communication with an adjuster. The insurer determines whether a loss is covered under the policy.'],
    ];
    $meta = [
        'title' => 'Water Damage Services in West Vancouver | 24/7',
        'description' => 'Explore water damage services in West Vancouver, including flood cleanup, water extraction, burst pipe cleanup, drying and emergency response.',
        'image' => 'assets/images/restoration-drying-equipment.webp',
        'schema_type' => 'CollectionPage',
    ];
    $breadcrumbs = [['Home', ''], ['Services', 'services']];
    render_header($meta, 'services', [breadcrumb_schema($breadcrumbs), service_list_schema($services), faq_schema($serviceFaqs)]);
    render_breadcrumbs($breadcrumbs);
    ?>
    <section class="page-hero page-hero--services">
        <div class="container page-hero__grid">
            <div><?php render_canadian_badge(); ?><span class="eyebrow eyebrow--light">One team, focused scopes</span><h1>Water Damage Services in West Vancouver</h1><p class="page-hero__lead">Choose the service that matches the source and scale of your loss. If you are unsure, call us and describe what happened. We will point you to the right next step.</p></div>
            <?php render_hero_form('services'); ?>
        </div>
    </section>
    <section class="section section--compact">
        <div class="container media-feature">
            <?php render_page_photo('restoration-drying-equipment.webp', 'Professional air movers and drying equipment prepared for a West Vancouver water damage project', 'Professional extraction, airflow and dehumidification equipment ready for deployment.'); ?>
            <div class="prose"><span class="eyebrow">Complete mitigation support</span><h2>Match the response to the water source and affected materials</h2><p>A burst supply line, storm entry, sewage backup and slow appliance leak can all leave a building wet, but they do not call for identical cleanup. The source, contamination risk, exposure time and materials involved determine what can be dried, what needs removal and which safety controls are appropriate.</p><p>Our service pages explain each scope in detail so West Vancouver homeowners, strata representatives and business operators can understand the likely first steps before the inspection begins.</p></div>
        </div>
    </section>
    <section class="section section--mist">
        <div class="container">
            <?php render_service_cards(array_keys($services)); ?>
        </div>
    </section>
    <section class="section">
        <div class="container feature-band">
            <div><span class="eyebrow">Not sure where to start?</span><h2>Start with the source, safety and standing water.</h2></div>
            <p>For an active leak or water spreading through the property, use our <a href="/emergency-water-damage-west-vancouver">emergency water damage service</a>. For an older loss or a broad repair scope, begin with <a href="/water-damage-restoration-west-vancouver">water damage restoration</a>.</p>
        </div>
    </section>
    <?php render_faqs($serviceFaqs, 'Water damage service questions'); ?>
    <?php render_footer();
}

function service_enrichment(string $slug): array
{
    $content = [
        'water-damage-restoration-west-vancouver' => [
            'image' => 'water-damage-wall-restoration.webp',
            'alt' => 'Wall cavity opened for professional water damage drying and restoration',
            'caption' => 'Selective access helps expose trapped moisture while limiting unnecessary removal.',
            'title' => 'Restoration decisions should follow the water, not just the stain',
            'copy' => [
                'A visible mark is often only the edge of the affected area. Water can wick into drywall, move under flooring and collect inside insulated cavities. We check adjoining rooms and lower levels so the restoration scope reflects the actual moisture pattern.',
                'In West Vancouver homes, multi-level layouts, finished basements and complex wall assemblies can make that investigation especially important. Drying targets, equipment placement and repair recommendations are documented as the project progresses.',
            ],
            'heading' => 'What shapes a complete restoration plan',
            'points' => [
                ['Water category and exposure time', 'The source and the time materials stayed wet influence cleaning, removal and safety decisions.'],
                ['Material response', 'Drywall, insulation, hardwood, carpet and cabinetry absorb and release moisture differently.'],
                ['Drying verification', 'Recorded moisture readings show whether the assembly is responding before equipment is removed.'],
            ],
        ],
        'emergency-water-damage-west-vancouver' => [
            'image' => 'emergency-water-damage-living-room.webp',
            'alt' => 'Active ceiling leak creating an emergency water damage situation in a living room',
            'caption' => 'Active leaks require quick source control, hazard awareness and moisture mapping.',
            'title' => 'The first hour is about safety and stopping further spread',
            'copy' => [
                'Active water can reach electrical fixtures, weaken ceiling finishes and travel into rooms below. Our emergency questions focus on the source, where the water has moved, whether occupants are safe and whether a plumber or other trade is also required.',
                'Once the immediate flow is controlled, extraction and moisture mapping establish the affected footprint. Photographs and initial readings create a useful record of conditions before drying changes the scene.',
            ],
            'heading' => 'Information that helps us prepare the response',
            'points' => [
                ['Where the water started', 'Tell us whether the source is plumbing, an appliance, the roof, outside drainage or unknown.'],
                ['Rooms and levels affected', 'Water through a ceiling or into a lower storey may require access and equipment in more than one area.'],
                ['Known hazards', 'Mention sewage, electrical concerns, a sagging ceiling or vulnerable occupants before arrival.'],
            ],
        ],
        'flood-cleanup-west-vancouver' => [
            'image' => 'basement-flood-cleanup.webp',
            'alt' => 'Flood water covering a basement floor before professional cleanup',
            'caption' => 'Standing water is only the visible part of a flood; porous finishes can remain wet afterward.',
            'title' => 'Flood cleanup continues after the standing water is gone',
            'copy' => [
                'Pumps and extractors remove accessible water, but moisture may remain in wall bases, flooring systems, insulation and stored contents. We inspect the perimeter of the loss and nearby areas before setting the drying plan.',
                'Outdoor floodwater and long-standing water can carry contaminants. The source and condition of the water guide personal protection, containment, cleaning and material removal decisions.',
            ],
            'heading' => 'A thorough flood cleanup considers more than volume',
            'points' => [
                ['Contamination and source', 'Rain entry, groundwater, plumbing water and sewage require different handling.'],
                ['Porous contents and finishes', 'Carpet, underpad, boxes, drywall and insulation may hold water after hard surfaces look clear.'],
                ['Humidity control', 'Dehumidification helps prevent evaporated water from loading unaffected rooms and materials.'],
            ],
        ],
        'water-extraction-west-vancouver' => [
            'image' => 'restoration-drying-equipment.webp',
            'alt' => 'Commercial water extraction and structural drying equipment',
            'caption' => 'Extraction equipment removes bulk water so controlled structural drying can begin.',
            'title' => 'Efficient extraction shortens the work left for drying equipment',
            'copy' => [
                'Removing liquid water is generally more efficient than relying on evaporation alone. The extraction method depends on water depth, access, floor covering, contamination and whether water is trapped beneath a finished surface.',
                'After extraction, moisture measurements identify what is still wet. Air movement and dehumidification are then balanced for the materials and room conditions rather than simply placing equipment by the visible puddle.',
                'We provide water extraction for West Vancouver houses, finished basements, strata units, common areas and commercial spaces. The work is documented from the visible water conditions through the transition to structural drying.',
            ],
            'heading' => 'What happens around the extraction stage',
            'points' => [
                ['Bulk water removal', 'Pumps or professional extractors are selected for the depth, surface and water condition.'],
                ['Subsurface checks', 'Flooring edges, underlay, wall bases and nearby rooms are checked for concealed migration.'],
                ['Drying transition', 'Equipment placement follows the post-extraction moisture map and is adjusted through monitoring.'],
                ['Carpet and underlay', 'The water source, exposure time and material condition determine whether textile flooring can be retained.'],
                ['Hard flooring', 'Seams, perimeters and transitions are checked for water beneath laminate, engineered wood or other finishes.'],
                ['Multi-level losses', 'Ceilings, wall cavities and lower rooms are inspected when water has travelled vertically through the building.'],
            ],
        ],
        'basement-flood-cleanup-west-vancouver' => [
            'image' => 'basement-flood-cleanup.webp',
            'alt' => 'Flooded basement stairs and floor requiring emergency water removal',
            'caption' => 'Basements need careful source identification before water removal and drying proceed.',
            'title' => 'Basements collect water from several possible pathways',
            'copy' => [
                'A failed water heater, foundation seepage, drain backup and plumbing break can create similar-looking basement flooding but very different cleanup requirements. We first identify the likely source and any contamination or electrical concerns.',
                'Finished basements can conceal water behind baseboards, beneath floating floors and inside insulated exterior walls. Storage contents and limited airflow also affect how the space is cleared and dried.',
            ],
            'heading' => 'Basement-specific issues we check',
            'points' => [
                ['Floor and wall assemblies', 'Water can spread beneath laminate, along sill areas and into insulated perimeter walls.'],
                ['Drainage and sewage indicators', 'Odour, debris and the point of entry help determine whether contaminated water is involved.'],
                ['Contents and access', 'Stored belongings, stairs and equipment access influence the safest cleanup sequence.'],
            ],
        ],
        'burst-pipe-cleanup-west-vancouver' => [
            'image' => 'ceiling-water-damage.webp',
            'alt' => 'Ceiling water damage near a plumbing fitting after a pipe leak',
            'caption' => 'Pipe leaks often spread through cavities before appearing at a ceiling or wall surface.',
            'title' => 'A burst pipe can affect more rooms than the break location suggests',
            'copy' => [
                'Pressurized supply water can release a large volume quickly and follow framing, wiring and penetrations into lower areas. We trace the moisture path after the water is shut off and coordinate the mitigation scope around plumbing access.',
                'Cold weather, exterior-wall plumbing and vacant properties can increase the extent of a pipe loss. Early photographs, extraction and controlled drying help establish conditions and limit avoidable secondary damage.',
            ],
            'heading' => 'Key parts of burst pipe mitigation',
            'points' => [
                ['Source isolation', 'The main water supply or affected branch must be controlled before cleanup can progress.'],
                ['Vertical moisture tracing', 'Ceilings, walls, floors and rooms below are checked along the likely travel path.'],
                ['Plumbing and repair access', 'Openings are planned with the plumber’s access needs and later restoration in mind.'],
            ],
        ],
        'sewage-backup-cleanup-west-vancouver' => [
            'image' => 'bathroom-structural-drying.webp',
            'alt' => 'Professional drying equipment operating in a bathroom after contaminated water cleanup',
            'caption' => 'Containment, removal and cleaning come before controlled drying after a sewage backup.',
            'title' => 'Sewage cleanup requires contamination controls from the start',
            'copy' => [
                'A sewer or drain backup can contain microorganisms and other contaminants that should not be spread through foot traffic or household cleaning tools. Keep people and pets away, avoid operating fans in the area and arrange professional assessment.',
                'Cleanup typically includes containment, removal of unsalvageable porous materials, detailed cleaning and controlled drying. The affected route and cause should also be addressed so restoration does not hide an unresolved drainage problem.',
            ],
            'heading' => 'Priorities for contaminated water damage',
            'points' => [
                ['Isolation', 'The affected area is separated to reduce tracking and cross-contamination into clean spaces.'],
                ['Material evaluation', 'Porous materials with sewage contact often require removal rather than surface cleaning.'],
                ['Cleaning before drying', 'Soil and contamination are addressed before airflow is used to complete structural drying.'],
            ],
        ],
        'storm-water-damage-west-vancouver' => [
            'image' => 'ceiling-water-damage.webp',
            'alt' => 'Ceiling moisture damage caused by water entering a building during severe weather',
            'caption' => 'Storm water may travel far from the exterior opening before becoming visible indoors.',
            'title' => 'Storm damage needs both interior mitigation and source investigation',
            'copy' => [
                'Wind-driven rain can enter around roofing, flashing, windows or wall transitions and then follow hidden building paths. We document the interior moisture pattern while the appropriate exterior specialist identifies and repairs the entry point.',
                'West Vancouver’s coastal exposure and hillside properties can also produce drainage or groundwater issues during prolonged rain. Drying should begin only with a clear plan to prevent continuing entry from undoing the work.',
            ],
            'heading' => 'Factors in a storm water response',
            'points' => [
                ['Weather and entry history', 'Wind direction, rainfall duration and when the leak appears can help narrow the exterior source.'],
                ['Water travel path', 'Attics, wall cavities and ceiling voids are checked between the entry area and visible damage.'],
                ['Temporary protection', 'Safe temporary source control may be needed before permanent exterior repairs can be completed.'],
            ],
        ],
        'commercial-water-damage-west-vancouver' => [
            'image' => 'living-room-structural-drying.webp',
            'alt' => 'Air movers and dehumidifiers arranged for professional structural drying',
            'caption' => 'Equipment layouts can be adapted around access, occupants and operating requirements.',
            'title' => 'Commercial mitigation must account for people, access and operations',
            'copy' => [
                'In an office, retail unit, strata building or professional space, the technical drying scope is only part of the plan. Occupant safety, opening hours, shared systems, noise, equipment security and communication responsibilities all need attention.',
                'We establish a site contact and document affected zones, equipment and moisture progress. Where possible, work is sequenced to protect unaffected areas and reduce disruption without compromising the mitigation requirements.',
            ],
            'heading' => 'Planning a commercial water damage project',
            'points' => [
                ['Defined decision-makers', 'Owner, tenant, manager and insurer contacts should know who approves access and scope changes.'],
                ['Operational zoning', 'Affected and unaffected spaces are separated so safe business activity can continue where practical.'],
                ['Consistent reporting', 'Photos, readings and updates give multiple stakeholders the same factual project record.'],
            ],
        ],
    ];

    return $content[$slug];
}

function render_service(string $slug, array $service): void
{
    global $services;
    $extra = service_enrichment($slug);
    $breadcrumbs = [['Home', ''], ['Services', 'services'], [$service['nav'], $slug]];
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        '@id' => path_url($slug) . '#service',
        'name' => $service['nav'] . ' in West Vancouver',
        'serviceType' => $service['nav'],
        'description' => $service['description'],
        'image' => path_url('assets/images/' . $extra['image']),
        'provider' => ['@id' => SITE_URL . '/#organization'],
        'areaServed' => ['@type' => 'City', 'name' => 'West Vancouver'],
        'availableChannel' => [
            '@type' => 'ServiceChannel',
            'serviceUrl' => path_url($slug),
            'servicePhone' => [
                '@type' => 'ContactPoint',
                'telephone' => PHONE_LINK,
                'contactType' => 'emergency service',
            ],
        ],
        'audience' => [
            '@type' => 'Audience',
            'audienceType' => 'Homeowners, strata representatives, property managers and businesses',
        ],
        'url' => path_url($slug),
    ];
    render_header(['title' => $service['title'], 'description' => $service['description'], 'image' => 'assets/images/' . $extra['image']], $slug, [breadcrumb_schema($breadcrumbs), $schema, faq_schema($service['faqs'])]);
    render_breadcrumbs($breadcrumbs);
    ?>
    <section class="page-hero page-hero--service">
        <div class="container page-hero__grid">
            <div>
                <?php render_canadian_badge(); ?>
                <span class="eyebrow eyebrow--aqua"><?= e($service['eyebrow']) ?></span>
                <h1><?= e($service['h1']) ?></h1>
                <p class="page-hero__lead"><?= e($service['lead']) ?></p>
                <div class="hero__actions">
                    <a class="button button--primary" href="tel:<?= e(PHONE_LINK) ?>"><?= icon('phone') ?> Request Emergency Service</a>
                    <a class="button button--outline-light" href="#free-inspection">Free Inspection</a>
                </div>
            </div>
            <?php render_hero_form('service-' . $slug); ?>
        </div>
    </section>

    <section class="section section--compact">
        <div class="container media-feature">
            <?php render_page_photo($extra['image'], $extra['alt'], $extra['caption']); ?>
            <div class="prose"><span class="eyebrow">Local service insight</span><h2><?= e($extra['title']) ?></h2><?php foreach ($extra['copy'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?></div>
        </div>
    </section>

    <section class="section">
        <div class="container content-grid">
            <div class="prose prose--large">
                <span class="eyebrow">A response built for the loss</span>
                <h2><?= e($service['intro_title']) ?></h2>
                <p><?= e($service['intro']) ?></p>
                <h2><?= e($service['why_title']) ?></h2>
                <p><?= e($service['why']) ?></p>
            </div>
            <aside class="check-card">
                <h2>Common situations we address</h2>
                <ul>
                    <?php foreach ($service['situations'] as $situation): ?>
                        <li><?= icon('check') ?> <?= e($situation) ?></li>
                    <?php endforeach; ?>
                </ul>
                <a class="text-link" href="#free-inspection">Get a free inspection <?= icon('arrow') ?></a>
            </aside>
        </div>
    </section>

    <section class="section service-guidance">
        <div class="container split-heading">
            <div><span class="eyebrow">A better-informed response</span><h2><?= e($extra['heading']) ?></h2></div>
            <p>Our inspection connects these considerations to the actual source, materials and conditions at your West Vancouver property.</p>
        </div>
        <div class="container guidance-grid">
            <?php foreach ($extra['points'] as $point): ?>
                <article><span><?= icon('check') ?></span><h3><?= e($point[0]) ?></h3><p><?= e($point[1]) ?></p></article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section section--navy service-steps">
        <div class="container split-heading split-heading--light">
            <div><span class="eyebrow eyebrow--aqua">What to expect</span><h2>How we handle this type of water damage</h2></div>
            <p>We adapt equipment and material decisions to the source, construction and site conditions.</p>
        </div>
        <ol class="container four-steps">
            <?php foreach ($service['steps'] as $index => $step): ?>
                <li><span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span><h3><?= e($step[0]) ?></h3><p><?= e($step[1]) ?></p></li>
            <?php endforeach; ?>
        </ol>
    </section>

    <section class="section section--mist related-services">
        <div class="container split-heading">
            <div><span class="eyebrow">Connected services</span><h2>Support for the next stage</h2></div>
            <p>Water losses often need more than one focused service. These related pages explain the work that commonly follows.</p>
        </div>
        <div class="container"><?php render_service_cards($service['related']); ?></div>
    </section>
    <?php render_faqs($service['faqs'], $service['nav'] . ' questions'); ?>
    <?php render_footer();
}

function render_areas(): void
{
    global $neighbourhoods;
    $areaFaqs = [
        ['Which West Vancouver neighbourhoods do you serve?', 'We serve the full municipality of West Vancouver, including Ambleside, Dundarave, British Properties, Caulfeild, Horseshoe Bay, West Bay, Sentinel Hill, Chartwell, Eagle Harbour and Gleneagles.'],
        ['Do you respond to waterfront and hillside properties?', 'Yes. We work with waterfront, hillside and village properties. Parking, stairs, gated access and multi-level layouts are discussed before arrival so the response can be planned safely.'],
        ['Do you provide water damage help for strata properties?', 'Yes. We assist strata units and common areas and can document moisture movement for owners, strata representatives, property managers and insurers.'],
        ['How quickly can you reach my West Vancouver address?', 'Response time varies with traffic, weather and active emergencies. Call with your address and loss details for the most accurate current arrival window.'],
    ];
    $meta = [
        'title' => 'Water Damage Service Areas | West Vancouver',
        'description' => '24/7 water damage response throughout West Vancouver, including Ambleside, Dundarave, British Properties, Caulfeild and Horseshoe Bay.',
        'image' => 'assets/images/emergency-water-damage-living-room.webp',
        'schema_type' => 'CollectionPage',
    ];
    $breadcrumbs = [['Home', ''], ['Areas We Serve', 'areas-we-serve']];
    render_header($meta, 'areas-we-serve', [breadcrumb_schema($breadcrumbs), faq_schema($areaFaqs)]);
    render_breadcrumbs($breadcrumbs);
    ?>
    <section class="page-hero page-hero--areas">
        <div class="container page-hero__grid">
            <div><?php render_canadian_badge('Serving every West Vancouver neighbourhood'); ?><span class="eyebrow eyebrow--aqua">Local coverage</span><h1>Water Damage Response Across West Vancouver</h1><p class="page-hero__lead">From waterfront apartments to hillside homes and village businesses, we provide water extraction, drying and cleanup throughout West Vancouver.</p></div>
            <?php render_hero_form('areas'); ?>
        </div>
    </section>
    <section class="section section--compact">
        <div class="container media-feature">
            <?php render_page_photo('emergency-water-damage-living-room.webp', 'Water entering a West Vancouver living room during an urgent property loss', 'Local response for water damage in homes, strata properties and businesses throughout West Vancouver.'); ?>
            <div class="prose"><span class="eyebrow">Local response planning</span><h2>Property type and access matter as much as the postal code</h2><p>Water damage in a waterfront condo, a Dundarave character home and a multi-level property in the British Properties can demand different access, equipment and communication plans. We ask about parking, stairs, elevators, strata contacts and occupied areas before arrival.</p><p>Our service area covers the municipality of West Vancouver. Current response time depends on active emergencies, weather and traffic, so we provide an honest arrival window when you call.</p></div>
        </div>
    </section>
    <section class="section">
        <div class="container intro-grid">
            <div><span class="eyebrow">One municipality, varied properties</span><h2>Local conditions influence how water moves</h2></div>
            <div class="prose"><p>West Vancouver properties range from older homes with finished basements to newer homes with several levels, strata buildings and commercial units. Steep sites, coastal storms and long travel paths through several storeys can complicate a water loss.</p><p>We assess what is happening at your specific address rather than making assumptions based on the neighbourhood. If specialist roof, drainage, plumbing or electrical work is needed, we identify that dependency clearly.</p></div>
        </div>
    </section>
    <section class="section section--mist">
        <div class="container area-list">
            <?php foreach ($neighbourhoods as $area): ?>
                <article><span><?= icon('pin') ?></span><div><h2><?= e($area) ?></h2><p>Emergency water removal, moisture inspection and structural drying for homes and businesses in <?= e($area) ?>.</p></div></article>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="section">
        <div class="container feature-band">
            <div><span class="eyebrow">Serving all of West Vancouver</span><h2>Do not see your neighbourhood listed?</h2></div>
            <p>Our service area includes the full municipality, not only the communities named above. Call <a href="tel:<?= e(PHONE_LINK) ?>"><?= e(PHONE_DISPLAY) ?></a> with your address and we will confirm current availability.</p>
        </div>
    </section>
    <?php render_faqs($areaFaqs, 'West Vancouver service area questions'); ?>
    <?php render_footer();
}

function render_about(): void
{
    $aboutFaqs = [
        ['Is this a Canadian water damage service?', 'Yes. We are proudly Canadian and locally focused on water damage response for West Vancouver homes, strata properties and businesses.'],
        ['How do you decide what materials need removal?', 'The water source, contamination, exposure time, material condition and moisture readings guide the decision. We explain what can potentially be dried and why removal may be needed.'],
        ['How do you confirm that an area is dry?', 'We compare moisture readings in affected materials with appropriate dry reference areas and monitor progress before drying equipment is removed.'],
        ['Do you decide whether insurance will cover the work?', 'No. Coverage decisions belong to the insurer. We can provide photographs, moisture readings and mitigation records that support a clear claim conversation.'],
    ];
    $meta = [
        'title' => 'About Our West Vancouver Water Damage Service',
        'description' => 'Learn about our practical approach guided by measurements for water damage cleanup, drying, communication and insurance documentation in West Vancouver.',
        'image' => 'assets/images/living-room-structural-drying.webp',
        'schema_type' => 'AboutPage',
    ];
    $breadcrumbs = [['Home', ''], ['About', 'about']];
    render_header($meta, 'about', [breadcrumb_schema($breadcrumbs), faq_schema($aboutFaqs)]);
    render_breadcrumbs($breadcrumbs);
    ?>
    <section class="page-hero page-hero--about">
        <div class="container page-hero__grid">
            <div><?php render_canadian_badge('Canadian owned and locally focused'); ?><span class="eyebrow eyebrow--aqua">Local, clear and accountable</span><h1>A Practical Approach to Water Damage in West Vancouver</h1><p class="page-hero__lead">We help property owners move from an uncertain water loss to a documented plan for extraction, drying, cleanup and repairs.</p></div>
            <?php render_hero_form('about'); ?>
        </div>
    </section>
    <section class="section section--compact">
        <div class="container media-feature">
            <?php render_page_photo('living-room-structural-drying.webp', 'Professional structural drying equipment in an occupied residential room', 'A measured drying setup designed around the affected materials and usable space.'); ?>
            <div class="prose"><span class="eyebrow">Proudly Canadian</span><h2>Local service backed by a disciplined restoration process</h2><p>Our focus is West Vancouver: its homeowners, strata communities, commercial spaces and distinctive mix of coastal and hillside properties. Being proudly Canadian means showing up with practical communication, respect for the property and clear accountability for the work performed.</p><p>We combine that local focus with moisture measurement, documented drying goals and a careful handoff to repairs. The result is a process property owners can understand even when the loss itself is stressful.</p></div>
        </div>
    </section>
    <section class="section">
        <div class="container intro-grid">
            <div><span class="eyebrow">Our standard</span><h2>Explain the problem before recommending the work</h2></div>
            <div class="prose"><p>Water damage is disruptive, and it becomes more difficult when the scope is unclear. Our work begins with the source, the affected materials and measurable moisture conditions. We explain what we found, what needs immediate attention and which decisions can wait.</p><p>That measured approach helps protect materials that can be saved and supports removal when safety, contamination or material condition makes it necessary. Throughout the project, we keep the response connected to the actual property and its needs.</p></div>
        </div>
    </section>
    <section class="section section--navy values-section">
        <div class="container values-grid">
            <article><?= icon('clock') ?><h2>Responsive</h2><p>Emergency calls are answered 24/7, with honest arrival windows and useful first steps.</p></article>
            <article><?= icon('meter') ?><h2>Measured</h2><p>Moisture readings guide drying decisions and help verify progress.</p></article>
            <article><?= icon('document') ?><h2>Documented</h2><p>Clear photos, notes and readings support owners, managers and insurers.</p></article>
            <article><?= icon('shield') ?><h2>Respectful</h2><p>We protect unaffected areas and treat occupied homes and workplaces with care.</p></article>
        </div>
    </section>
    <section class="section">
        <div class="container content-grid">
            <div class="prose prose--large"><span class="eyebrow">Insurance support</span><h2>Useful documentation without making promises about coverage</h2><p>Insurance policies and loss circumstances vary. We do not decide whether a claim is covered. We do provide the factual information an adjuster may request, including affected areas, moisture conditions and mitigation work performed.</p><p>Property owners can choose whether to contact us directly or involve their insurer first. For urgent losses, stopping further damage and documenting the initial conditions are often the most important early actions.</p></div>
            <aside class="check-card"><h2>What you can expect</h2><ul><li><?= icon('check') ?> Clear site communication</li><li><?= icon('check') ?> Scope tied to visible and measured conditions</li><li><?= icon('check') ?> Residential and commercial service</li><li><?= icon('check') ?> Free inspection</li><li><?= icon('check') ?> Practical next steps for other trades</li></ul><a class="text-link" href="#free-inspection">Get a free inspection <?= icon('arrow') ?></a></aside>
        </div>
    </section>
    <?php render_faqs($aboutFaqs, 'Questions about our restoration approach'); ?>
    <?php render_footer();
}

function render_contact(): void
{
    $contactFaqs = [
        ['What number should I call for emergency water damage?', 'Call (604)800-3900. The emergency line is answered 24 hours a day for water damage calls in West Vancouver.'],
        ['What information should I provide when I call?', 'Share the property address, likely water source, when the loss started, affected rooms or levels, and whether sewage, electrical hazards or a sagging ceiling may be involved.'],
        ['Can I request a free inspection online?', 'Yes. Submit the form with your name, phone number, email and a short description of the problem. If water is actively spreading, call instead of waiting for a form response.'],
        ['What should I do before help arrives?', 'Keep away from electrical hazards, contaminated water and sagging ceilings. If it is safe, stop the water source and move only light, easy-to-handle belongings away from the wet area.'],
    ];
    $meta = [
        'title' => 'Contact Water Damage Help | West Vancouver',
        'description' => 'Request 24/7 water damage help or a free inspection in West Vancouver. Call (604)800-3900 or send your contact details.',
        'image' => 'assets/images/ceiling-water-damage.webp',
        'schema_type' => 'ContactPage',
    ];
    $breadcrumbs = [['Home', ''], ['Contact', 'contact']];
    render_header($meta, 'contact', [breadcrumb_schema($breadcrumbs), faq_schema($contactFaqs)]);
    render_breadcrumbs($breadcrumbs);
    ?>
    <section class="page-hero page-hero--contact">
        <div class="container page-hero__grid">
            <div><?php render_canadian_badge(); ?><span class="eyebrow eyebrow--aqua">Talk to a restoration team</span><h1>Request Water Damage Help in West Vancouver</h1><p class="page-hero__lead">For active flooding or an emergency outside regular hours, call now. Otherwise, send your contact details and we will arrange your free inspection.</p></div>
            <div>
                <?php if (isset($_GET['sent'])): ?>
                    <div class="form-status form-status--success" role="status"><strong>Thank you.</strong> Your request has been sent. If water is actively spreading, please call us now.</div>
                <?php elseif (isset($_GET['error'])): ?>
                    <div class="form-status form-status--error" role="alert"><strong>We could not send the form.</strong> Please check the fields or call <?= e(PHONE_DISPLAY) ?>.</div>
                <?php endif; ?>
                <?php render_hero_form('contact'); ?>
            </div>
        </div>
    </section>
    <section class="section section--compact">
        <div class="container media-feature media-feature--reverse">
            <?php render_page_photo('ceiling-water-damage.webp', 'Ceiling water damage requiring moisture inspection and urgent restoration advice', 'Tell us where the water started, which rooms are affected and whether it is still spreading.'); ?>
            <div class="prose"><span class="eyebrow">Prepare for the call</span><h2>A few details help us understand the urgency</h2><p>If you can do so safely, note when the loss began, the likely source, affected rooms and whether water is reaching electrical fixtures or ceilings. Let us know if sewage, outdoor floodwater, tenants or a strata manager are involved.</p><p>You do not need to diagnose hidden damage yourself. We will ask focused questions, explain immediate precautions and arrange the appropriate next step for your West Vancouver property.</p></div>
        </div>
    </section>
    <section class="section contact-section">
        <div class="container contact-details contact-details--wide">
            <span class="eyebrow">Emergency line</span>
            <a class="contact-phone" href="tel:<?= e(PHONE_LINK) ?>"><?= e(PHONE_DISPLAY) ?></a>
            <p>Answered 24 hours a day for water damage emergencies in West Vancouver.</p>
            <div class="contact-note"><h2>Before you call</h2><ul><li><?= icon('check') ?> Keep away from electrical hazards</li><li><?= icon('check') ?> Stop the water source only if safe</li><li><?= icon('check') ?> Note which rooms and levels are affected</li><li><?= icon('check') ?> Tell us if sewage or outdoor water is involved</li></ul></div>
        </div>
    </section>
    <?php render_faqs($contactFaqs, 'Contact and emergency response questions'); ?>
    <?php render_footer();
}

function render_privacy(): void
{
    $privacyFaqs = [
        ['What information does the service request form collect?', 'The form collects the name, phone number, email address and message that you choose to submit.'],
        ['Why is submitted information used?', 'Submitted information is used to respond to the water damage request, arrange an inspection and maintain related business records.'],
        ['Is submitted personal information sold?', 'No. The privacy policy states that submitted personal information is not sold.'],
    ];
    $meta = ['title' => 'Privacy Policy | West Vancouver Water Damage', 'description' => 'Privacy information for visitors and service request forms on waterdamagewestvancouver.ca.', 'image' => 'assets/images/restoration-drying-equipment.webp'];
    $breadcrumbs = [['Home', ''], ['Privacy', 'privacy']];
    render_header($meta, 'privacy', [breadcrumb_schema($breadcrumbs), faq_schema($privacyFaqs)]);
    render_breadcrumbs($breadcrumbs);
    ?>
    <section class="simple-hero"><div class="container page-hero__grid"><div><?php render_canadian_badge('Canadian privacy commitment'); ?><span class="eyebrow eyebrow--aqua">Your information</span><h1>Privacy Policy</h1><p class="page-hero__lead">How we handle the contact details submitted through our free inspection forms.</p></div><?php render_hero_form('privacy'); ?></div></section>
    <section class="section"><div class="container legal-layout"><div class="prose legal-copy"><p>When you submit our contact form, we use the full name, phone number, email address and message you provide to respond to your water damage service request.</p><h2>How information is used</h2><p>Information is used to respond to your request, arrange your free inspection and maintain related business records. We do not sell personal information.</p><h2>Website data</h2><p>Our web server may record standard technical information such as an IP address, browser type, requested pages and access time for security and performance purposes.</p><h2>Your choices</h2><p>You may request access to or correction of the personal information you submitted by calling <a href="tel:<?= e(PHONE_LINK) ?>"><?= e(PHONE_DISPLAY) ?></a>.</p><p><small>Last updated: October 1, 2026.</small></p></div><?php render_page_photo('restoration-drying-equipment.webp', 'Professional water damage restoration equipment representing our service website', 'Contact details are used to respond to your service request.'); ?></div></section>
    <?php render_faqs($privacyFaqs, 'Privacy questions'); ?>
    <?php render_footer();
}

function render_not_found(): void
{
    $meta = ['title' => 'Page Not Found | West Vancouver Water Damage', 'description' => 'The page you requested could not be found.', 'robots' => 'noindex,follow'];
    render_header($meta, '404');
    ?>
    <section class="not-found"><div class="container"><?php render_canadian_badge('West Vancouver water damage help'); ?><span class="eyebrow">404 error</span><h1>This page has moved or does not exist.</h1><p>Return home or call if you need urgent water damage help in West Vancouver.</p><?php render_page_photo('emergency-water-damage-living-room.webp', 'Emergency water damage in a living room', 'Need help now? Our emergency line is available 24 hours.'); ?><div class="hero__actions"><a class="button button--dark" href="/">Return home</a><a class="button button--outline-dark" href="tel:<?= e(PHONE_LINK) ?>">Call <?= e(PHONE_DISPLAY) ?></a></div></div></section>
    <?php render_footer();
}
