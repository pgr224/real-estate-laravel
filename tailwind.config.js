// Helper: convert hex to RGB channels for Tailwind opacity support
function hexToRgb(hex) {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    return `${r} ${g} ${b}`;
}

function withOpacity(hex) {
    return ({ opacityValue }) => {
        if (opacityValue !== undefined) {
            return `rgb(${hexToRgb(hex)} / ${opacityValue})`;
        }
        return hex;
    };
}

export default {
    darkMode: 'class',
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        "./node_modules/flowbite/**/*.js"
    ],
    theme: {
        extend: {
            colors: {
                // Material Design 3 Color System (from Stitch designs)
                'primary': withOpacity('#a78bfa'),
                'on-primary': withOpacity('#0a0012'),
                'primary-container': withOpacity('#7c3aed'),
                'on-primary-container': withOpacity('#ede9fe'),
                'primary-fixed': withOpacity('#ede9fe'),
                'primary-fixed-dim': withOpacity('#c4b5fd'),
                'on-primary-fixed': withOpacity('#2e1065'),
                'on-primary-fixed-variant': withOpacity('#5b21b6'),
                'inverse-primary': withOpacity('#5b21b6'),

                'secondary': withOpacity('#71717a'),
                'on-secondary': withOpacity('#09090b'),
                'secondary-container': withOpacity('#27272a'),
                'on-secondary-container': withOpacity('#a1a1aa'),
                'secondary-fixed': withOpacity('#a1a1aa'),
                'secondary-fixed-dim': withOpacity('#71717a'),
                'on-secondary-fixed': withOpacity('#18181b'),
                'on-secondary-fixed-variant': withOpacity('#3f3f46'),

                'tertiary': withOpacity('#34d399'),
                'on-tertiary': withOpacity('#001a12'),
                'tertiary-container': withOpacity('#065f46'),
                'on-tertiary-container': withOpacity('#bbf7d0'),
                'tertiary-fixed': withOpacity('#bbf7d0'),
                'tertiary-fixed-dim': withOpacity('#6ee7b7'),
                'on-tertiary-fixed': withOpacity('#003318'),
                'on-tertiary-fixed-variant': withOpacity('#047857'),

                'error': withOpacity('#ef4444'),
                'on-error': withOpacity('#1a0000'),
                'error-container': withOpacity('#3b1111'),
                'on-error-container': withOpacity('#fca5a5'),

                'surface': withOpacity('#0c0c0f'),
                'on-surface': withOpacity('#fafafa'),
                'on-surface-variant': withOpacity('#a1a1aa'),
                'surface-variant': withOpacity('#18181b'),
                'surface-dim': withOpacity('#0c0c0f'),
                'surface-bright': withOpacity('#18181b'),
                'surface-tint': withOpacity('#a78bfa'),
                'surface-container-lowest': withOpacity('#09090b'),
                'surface-container-low': withOpacity('#0f0f12'),
                'surface-container': withOpacity('#121215'),
                'surface-container-high': withOpacity('#18181b'),
                'surface-container-highest': withOpacity('#1e1e22'),

                'background': withOpacity('#09090b'),
                'on-background': withOpacity('#fafafa'),
                'outline': withOpacity('#52525b'),
                'outline-variant': withOpacity('#27272a'),
                'inverse-surface': withOpacity('#fafafa'),
                'inverse-on-surface': withOpacity('#09090b'),
            },
            fontFamily: {
                'headline': ['Geist', 'sans-serif'],
                'display': ['Geist', 'sans-serif'],
                'body': ['Geist', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                'label': ['Geist', 'sans-serif'],
                'sans': ['Geist', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
}
