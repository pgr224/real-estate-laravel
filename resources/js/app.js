import 'flowbite/dist/flowbite.js';

const closeNavigation = () => {
    const toggle = document.getElementById('menuToggleButton');
    document.getElementById('site-navigation')?.classList.remove('is-open');
    toggle?.setAttribute('aria-expanded', 'false');
    toggle?.setAttribute('aria-label', 'Open main menu');
};

document.addEventListener('click', (event) => {
    const target = event.target;

    if (!(target instanceof Element)) {
        return;
    }

    const toggle = target.closest('#menuToggleButton');
    const navigation = document.getElementById('site-navigation');

    if (!toggle || !navigation) {
        if (navigation && (!navigation.contains(target) || target.closest('a'))) {
            closeNavigation();
        }

        return;
    }

    const isOpen = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(isOpen));
    toggle.setAttribute('aria-label', isOpen ? 'Close main menu' : 'Open main menu');
    navigation.classList.toggle('is-open', isOpen);
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeNavigation();
    }
});

const initializePropertyMap = async (element) => {
    if (element.dataset.initialized === 'true') {
        return;
    }

    element.dataset.initialized = 'true';

    try {
        const [leafletModule] = await Promise.all([
            import('leaflet'),
            import('leaflet/dist/leaflet.css'),
        ]);
        const L = leafletModule.default;
        const properties = JSON.parse(element.dataset.properties || '[]');
        const map = L.map(element, { scrollWheelZoom: false });
        const markers = [];

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);

        properties.forEach((property) => {
            const latitude = Number(property.latitude);
            const longitude = Number(property.longitude);

            if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
                return;
            }

            const marker = L.marker([latitude, longitude]).addTo(map);
            const popup = document.createElement('div');
            const title = document.createElement('strong');
            const price = document.createElement('p');

            title.textContent = property.title || 'Property';
            price.textContent = `${element.dataset.currency || ''} ${Number(property.price || 0).toLocaleString()}`;
            popup.append(title, price);
            marker.bindPopup(popup);
            markers.push(marker);
        });

        if (markers.length) {
            const group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.12), { maxZoom: 14 });
        } else {
            map.setView([20.5937, 78.9629], 4);
        }

        element.querySelector('.property-map__loading')?.remove();
        element.setAttribute('aria-busy', 'false');
    } catch (error) {
        element.dataset.initialized = 'false';
        element.setAttribute('aria-busy', 'false');
        element.setAttribute('aria-label', 'Property map could not be loaded');
        const loadingMessage = element.querySelector('.property-map__loading');

        if (loadingMessage) {
            loadingMessage.textContent = 'Map unavailable. Please try again later.';
        }

        console.error('Property map failed to load.', error);
    }
};

const propertyMaps = document.querySelectorAll('[data-property-map]');

if ('IntersectionObserver' in window) {
    const mapObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                observer.unobserve(entry.target);
                initializePropertyMap(entry.target);
            }
        });
    }, { rootMargin: '200px' });

    propertyMaps.forEach((map) => mapObserver.observe(map));
} else {
    propertyMaps.forEach(initializePropertyMap);
}
