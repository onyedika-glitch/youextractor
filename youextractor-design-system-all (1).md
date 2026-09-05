# Design System: Youextractor Design System

YouExtractor's design system is built for a developer tool that feels alive — dark, immersive, and energetic without being chaotic. It should feel like a premium code editor merged with a creative studio.

- **Color personality**: Deep blue-gray foundation with a tri-color accent system — purple (brand anchor), pink (warmth and action), cyan (electric/code energy). These three flow into each other through gradients, never feeling static.
- **Surface philosophy**: Layered depth through glassmorphism — frosted blur panels float above the dark base, creating a sense of dimension. Borders glow with accent colors to signal interactivity and importance.
- **Typography**: Inter for clean UI readability at all sizes, JetBrains Mono for code — the pairing signals "developer tool" without feeling sterile. Tight tracking on headlines for impact, relaxed body text for long-form tutorials.
- **Interaction feel**: Buttons lift and glow on hover, inputs radiate purple halos on focus, cards float upward when interactive. Everything has subtle motion that rewards engagement.
- **Light emission**: Glow effects are a signature — purple, pink, and cyan halos that emanate from borders and shadows. Used sparingly for CTAs, featured content, and active states.
- **Badge system**: Pill-shaped indicators for tech stacks, status, and categories — making extraction results scannable at a glance.
- **Overall tone**: Premium, energetic, developer-forward. Not playful/cute, not corporate/sterile — it sits in the sweet spot of "powerful tool that looks exciting to use."

## Component Vocabulary

1. **Button**
The primary interactive element for all user actions. Available in five visual styles: solid primary for standard CTAs, secondary for alternatives, ghost for toolbar-style actions, gradient for high-energy hero moments, and glow for attention-grabbing actions that emit colored light. Supports loading state with a spinner for async operations like video extraction. Three sizes accommodate everything from compact toolbar buttons to full-width form submissions. Pair gradient or glow with secondary/ghost alternatives — never stack two high-energy buttons together.

2. **Card**
Versatile container for grouping related content — extraction results, feature panels, video info cards, and code previews. Offers seven visual treatments: default solid, glass with frosted blur, glass-accent with purple border, glow with purple light emission, glow-electric with cyan emission, gradient for subtle depth, and raised for higher prominence. The interactive flag adds hover lift and glow animation, perfect for clickable result cards. Supports header, body, and footer zones for structured content layout.

3. **Input**
Text input field for collecting user data — primarily the YouTube URL paste field, but also used for search, email, and password fields. Features a purple glow halo on focus that radiates outward, making the active field unmistakable on dark surfaces. Supports a leading icon that shifts to brand color on focus, three sizes to match context importance, and inline error/hint messaging. The large size with icon is the signature look for the main extraction input.

## Theme Reference

```css
/* theme/breakpoints.css */
:root {
  /* Breakpoints (reference only — must be hardcoded in media queries) */
  --theme-breakpoint-sm: 640px;
  --theme-breakpoint-md: 768px;
  --theme-breakpoint-lg: 1024px;
  --theme-breakpoint-xl: 1280px;
  --theme-breakpoint-2xl: 1536px;

  /* Container max-widths */
  --theme-container-sm: 640px;
  --theme-container-md: 768px;
  --theme-container-lg: 1024px;
  --theme-container-xl: 1200px;
}

/* theme/colors.css */
:root {
  /* Neutral / Surface ramp — deep blue-gray foundation */
  --theme-neutral-0: #ffffff;
  --theme-neutral-50: #f8fafc;
  --theme-neutral-100: #f1f5f9;
  --theme-neutral-200: #e2e8f0;
  --theme-neutral-300: #cbd5e1;
  --theme-neutral-400: #94a3b8;
  --theme-neutral-500: #64748b;
  --theme-neutral-600: #475569;
  --theme-neutral-700: #334155;
  --theme-neutral-800: #1e293b;
  --theme-neutral-850: #172033;
  --theme-neutral-900: #0f172a;
  --theme-neutral-950: #0a1022;
  --theme-neutral-1000: #060b18;

  /* Purple ramp — primary brand energy */
  --theme-purple-50: #faf5ff;
  --theme-purple-100: #f3e8ff;
  --theme-purple-200: #e9d5ff;
  --theme-purple-300: #d8b4fe;
  --theme-purple-400: #c084fc;
  --theme-purple-500: #a855f7;
  --theme-purple-600: #9333ea;
  --theme-purple-700: #7e22ce;
  --theme-purple-800: #6b21a8;
  --theme-purple-900: #581c87;
  --theme-purple-1000: #3b0764;

  /* Pink ramp — dynamic secondary accent */
  --theme-pink-50: #fdf2f8;
  --theme-pink-100: #fce7f3;
  --theme-pink-200: #fbcfe8;
  --theme-pink-300: #f9a8d4;
  --theme-pink-400: #f472b6;
  --theme-pink-500: #ec4899;
  --theme-pink-600: #db2777;
  --theme-pink-700: #be185d;
  --theme-pink-800: #9d174d;
  --theme-pink-900: #831843;
  --theme-pink-1000: #500724;

  /* Cyan ramp — electric tertiary accent */
  --theme-cyan-50: #ecfeff;
  --theme-cyan-100: #cffafe;
  --theme-cyan-200: #a5f3fc;
  --theme-cyan-300: #67e8f9;
  --theme-cyan-400: #22d3ee;
  --theme-cyan-500: #06b6d4;
  --theme-cyan-600: #0891b2;
  --theme-cyan-700: #0e7490;
  --theme-cyan-800: #155e75;
  --theme-cyan-900: #164e63;
  --theme-cyan-1000: #083344;

  /* Green ramp — success states */
  --theme-green-50: #f0fdf4;
  --theme-green-100: #dcfce7;
  --theme-green-200: #bbf7d0;
  --theme-green-300: #86efac;
  --theme-green-400: #4ade80;
  --theme-green-500: #22c55e;
  --theme-green-600: #16a34a;
  --theme-green-700: #15803d;
  --theme-green-800: #166534;
  --theme-green-900: #14532d;
  --theme-green-1000: #052e16;

  /* Red ramp — error/danger states */
  --theme-red-50: #fef2f2;
  --theme-red-100: #fee2e2;
  --theme-red-200: #fecaca;
  --theme-red-300: #fca5a5;
  --theme-red-400: #f87171;
  --theme-red-500: #ef4444;
  --theme-red-600: #dc2626;
  --theme-red-700: #b91c1c;
  --theme-red-800: #991b1b;
  --theme-red-900: #7f1d1d;
  --theme-red-1000: #450a0a;

  /* Yellow ramp — warning states */
  --theme-yellow-50: #fefce8;
  --theme-yellow-100: #fef9c3;
  --theme-yellow-200: #fef08a;
  --theme-yellow-300: #fde047;
  --theme-yellow-400: #facc15;
  --theme-yellow-500: #eab308;
  --theme-yellow-600: #ca8a04;
  --theme-yellow-700: #a16207;
  --theme-yellow-800: #854d0e;
  --theme-yellow-900: #713f12;
  --theme-yellow-1000: #422006;
}

/* theme/motion.css */
:root {
  /* Durations */
  --theme-motion-fast: 150ms;
  --theme-motion-normal: 250ms;
  --theme-motion-slow: 400ms;
  --theme-motion-slower: 600ms;

  /* Easing curves */
  --theme-ease-default: cubic-bezier(0.4, 0, 0.2, 1);
  --theme-ease-in: cubic-bezier(0.4, 0, 1, 1);
  --theme-ease-out: cubic-bezier(0, 0, 0.2, 1);
  --theme-ease-bounce: cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* theme/radii.css */
:root {
  /* Border radii — generous rounding for modern feel */
  --theme-radius-none: 0;
  --theme-radius-sm: 0.25rem;
  --theme-radius-md: 0.5rem;
  --theme-radius-lg: 0.75rem;
  --theme-radius-xl: 1rem;
  --theme-radius-2xl: 1.25rem;
  --theme-radius-3xl: 1.5rem;
  --theme-radius-full: 9999px;
}

/* theme/shadows.css */
:root {
  /* Base shadows — subtle depth on dark surfaces */
  --theme-shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.3);
  --theme-shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2);
  --theme-shadow-md: 0 4px 12px rgba(0, 0, 0, 0.4), 0 2px 4px rgba(0, 0, 0, 0.2);
  --theme-shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.5), 0 4px 8px rgba(0, 0, 0, 0.3);
  --theme-shadow-xl: 0 16px 48px rgba(0, 0, 0, 0.6), 0 8px 16px rgba(0, 0, 0, 0.3);

  /* Glow shadows — colored light emission for dynamic feel */
  --theme-glow-purple: 0 0 20px rgba(168, 85, 247, 0.3), 0 0 60px rgba(168, 85, 247, 0.1);
  --theme-glow-pink: 0 0 20px rgba(236, 72, 153, 0.3), 0 0 60px rgba(236, 72, 153, 0.1);
  --theme-glow-cyan: 0 0 20px rgba(6, 182, 212, 0.3), 0 0 60px rgba(6, 182, 212, 0.1);
  --theme-glow-brand: 0 0 24px rgba(168, 85, 247, 0.25), 0 0 48px rgba(236, 72, 153, 0.1);

  /* Overlay values */
  --theme-overlay-dark: rgba(10, 16, 34, 0.75);
  --theme-overlay-medium: rgba(10, 16, 34, 0.5);
  --theme-overlay-light: rgba(10, 16, 34, 0.3);

  /* Glass surface backgrounds */
  --theme-glass-surface: rgba(30, 41, 59, 0.5);
  --theme-glass-surface-hover: rgba(30, 41, 59, 0.7);
  --theme-glass-border: rgba(148, 163, 184, 0.1);
  --theme-glass-border-accent: rgba(168, 85, 247, 0.2);
}

/* theme/spacing.css */
:root {
  /* Spacing scale — 4px base unit */
  --theme-spacing-px: 1px;
  --theme-spacing-0: 0;
  --theme-spacing-1: 0.25rem;
  --theme-spacing-2: 0.5rem;
  --theme-spacing-3: 0.75rem;
  --theme-spacing-4: 1rem;
  --theme-spacing-5: 1.25rem;
  --theme-spacing-6: 1.5rem;
  --theme-spacing-8: 2rem;
  --theme-spacing-10: 2.5rem;
  --theme-spacing-12: 3rem;
  --theme-spacing-16: 4rem;
  --theme-spacing-20: 5rem;
  --theme-spacing-24: 6rem;
  --theme-spacing-32: 8rem;

  /* Semantic spacing aliases */
  --theme-spacing-xs: var(--theme-spacing-1);
  --theme-spacing-sm: var(--theme-spacing-2);
  --theme-spacing-md: var(--theme-spacing-4);
  --theme-spacing-lg: var(--theme-spacing-6);
  --theme-spacing-xl: var(--theme-spacing-8);
  --theme-spacing-2xl: var(--theme-spacing-12);
  --theme-spacing-3xl: var(--theme-spacing-16);
  --theme-spacing-4xl: var(--theme-spacing-24);
}

/* theme/typography.css */
:root {
  /* Font families */
  --theme-font-sans: 'Inter', sans-serif;
  --theme-font-mono: 'JetBrains Mono', monospace;

  /* Font sizes — modular scale (1.25 ratio) */
  --theme-font-size-xs: 0.75rem;
  --theme-font-size-sm: 0.875rem;
  --theme-font-size-md: 1rem;
  --theme-font-size-lg: 1.125rem;
  --theme-font-size-xl: 1.25rem;
  --theme-font-size-2xl: 1.5rem;
  --theme-font-size-3xl: 1.875rem;
  --theme-font-size-4xl: 2.25rem;
  --theme-font-size-5xl: 3rem;
  --theme-font-size-6xl: 3.75rem;

  /* Font weights */
  --theme-font-weight-regular: 400;
  --theme-font-weight-medium: 500;
  --theme-font-weight-semibold: 600;
  --theme-font-weight-bold: 700;
  --theme-font-weight-extrabold: 800;

  /* Line heights */
  --theme-line-height-tight: 1.1;
  --theme-line-height-snug: 1.25;
  --theme-line-height-normal: 1.5;
  --theme-line-height-relaxed: 1.625;

  /* Letter spacing */
  --theme-letter-spacing-tight: -0.025em;
  --theme-letter-spacing-normal: 0em;
  --theme-letter-spacing-wide: 0.025em;
  --theme-letter-spacing-wider: 0.05em;
}
```

## Tokens and Recipes

```css
/* styles/tokens.css */
:root {
  /* ─── Surface tokens ─── */
  --ds-surface-base: var(--theme-neutral-950);
  --ds-surface-raised: var(--theme-neutral-900);
  --ds-surface-card: var(--theme-neutral-850);
  --ds-surface-overlay: var(--theme-neutral-800);
  --ds-surface-input: var(--theme-neutral-900);
  --ds-surface-glass: var(--theme-glass-surface);
  --ds-surface-glass-hover: var(--theme-glass-surface-hover);

  /* ─── Text tokens ─── */
  --ds-text-primary: var(--theme-neutral-50);
  --ds-text-secondary: var(--theme-neutral-400);
  --ds-text-muted: var(--theme-neutral-500);
  --ds-text-disabled: var(--theme-neutral-600);
  --ds-text-inverse: var(--theme-neutral-950);
  --ds-text-brand: var(--theme-purple-400);
  --ds-text-accent: var(--theme-pink-400);
  --ds-text-electric: var(--theme-cyan-400);

  /* ─── Border tokens ─── */
  --ds-border-subtle: var(--theme-glass-border);
  --ds-border-default: var(--theme-neutral-700);
  --ds-border-strong: var(--theme-neutral-600);
  --ds-border-accent: var(--theme-glass-border-accent);
  --ds-border-input: var(--theme-neutral-700);
  --ds-border-input-focus: var(--theme-purple-500);
  --ds-border-error: var(--theme-red-500);
  --ds-border-success: var(--theme-green-500);

  /* ─── Brand / accent color tokens ─── */
  --ds-color-brand: var(--theme-purple-500);
  --ds-color-brand-hover: var(--theme-purple-600);
  --ds-color-brand-subtle: var(--theme-purple-400);
  --ds-color-brand-muted: var(--theme-purple-900);
  --ds-color-accent: var(--theme-pink-500);
  --ds-color-accent-hover: var(--theme-pink-600);
  --ds-color-accent-subtle: var(--theme-pink-400);
  --ds-color-electric: var(--theme-cyan-500);
  --ds-color-electric-hover: var(--theme-cyan-600);
  --ds-color-electric-subtle: var(--theme-cyan-400);

  /* ─── Status tokens ─── */
  --ds-color-success: var(--theme-green-500);
  --ds-color-success-subtle: var(--theme-green-400);
  --ds-color-success-muted: var(--theme-green-900);
  --ds-color-error: var(--theme-red-500);
  --ds-color-error-subtle: var(--theme-red-400);
  --ds-color-error-muted: var(--theme-red-900);
  --ds-color-warning: var(--theme-yellow-500);
  --ds-color-warning-subtle: var(--theme-yellow-400);
  --ds-color-warning-muted: var(--theme-yellow-900);

  /* ─── Focus tokens ─── */
  --ds-focus-ring: 0 0 0 2px var(--theme-neutral-950), 0 0 0 4px var(--theme-purple-500);
  --ds-focus-ring-error: 0 0 0 2px var(--theme-neutral-950), 0 0 0 4px var(--theme-red-500);

  /* ─── Gradient tokens ─── */
  --ds-gradient-brand: linear-gradient(135deg, var(--theme-purple-500), var(--theme-pink-500));
  --ds-gradient-brand-hover: linear-gradient(135deg, var(--theme-purple-600), var(--theme-pink-600));
  --ds-gradient-electric: linear-gradient(135deg, var(--theme-purple-500), var(--theme-cyan-500));
  --ds-gradient-warm: linear-gradient(135deg, var(--theme-pink-500), var(--theme-yellow-400));
  --ds-gradient-surface: linear-gradient(180deg, var(--theme-neutral-850), var(--theme-neutral-900));

  /* ─── Layer / elevation tokens ─── */
  --ds-layer-glow: var(--theme-glow-brand);
  --ds-layer-glow-purple: var(--theme-glow-purple);
  --ds-layer-glow-pink: var(--theme-glow-pink);
  --ds-layer-glow-cyan: var(--theme-glow-cyan);
  --ds-layer-overlay: var(--theme-overlay-dark);
  --ds-layer-scrim: var(--theme-overlay-medium);
}

/* styles/recipes/badge.css */
/* Badge — small status/category indicators */
.ds-badge-brand {
  display: inline-flex;
  align-items: center;
  padding: var(--theme-spacing-1) var(--theme-spacing-3);
  background: var(--ds-color-brand-muted);
  color: var(--ds-color-brand-subtle);
  border-radius: var(--theme-radius-full);
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-xs);
  font-weight: var(--theme-font-weight-semibold);
  line-height: var(--theme-line-height-normal);
}

.ds-badge-accent {
  display: inline-flex;
  align-items: center;
  padding: var(--theme-spacing-1) var(--theme-spacing-3);
  background: rgba(236, 72, 153, 0.1);
  color: var(--ds-color-accent-subtle);
  border-radius: var(--theme-radius-full);
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-xs);
  font-weight: var(--theme-font-weight-semibold);
  line-height: var(--theme-line-height-normal);
}

.ds-badge-electric {
  display: inline-flex;
  align-items: center;
  padding: var(--theme-spacing-1) var(--theme-spacing-3);
  background: rgba(6, 182, 212, 0.1);
  color: var(--ds-color-electric-subtle);
  border-radius: var(--theme-radius-full);
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-xs);
  font-weight: var(--theme-font-weight-semibold);
  line-height: var(--theme-line-height-normal);
}

.ds-badge-success {
  display: inline-flex;
  align-items: center;
  padding: var(--theme-spacing-1) var(--theme-spacing-3);
  background: var(--ds-color-success-muted);
  color: var(--ds-color-success-subtle);
  border-radius: var(--theme-radius-full);
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-xs);
  font-weight: var(--theme-font-weight-semibold);
  line-height: var(--theme-line-height-normal);
}

.ds-badge-error {
  display: inline-flex;
  align-items: center;
  padding: var(--theme-spacing-1) var(--theme-spacing-3);
  background: var(--ds-color-error-muted);
  color: var(--ds-color-error-subtle);
  border-radius: var(--theme-radius-full);
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-xs);
  font-weight: var(--theme-font-weight-semibold);
  line-height: var(--theme-line-height-normal);
}

.ds-badge-warning {
  display: inline-flex;
  align-items: center;
  padding: var(--theme-spacing-1) var(--theme-spacing-3);
  background: var(--ds-color-warning-muted);
  color: var(--ds-color-warning-subtle);
  border-radius: var(--theme-radius-full);
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-xs);
  font-weight: var(--theme-font-weight-semibold);
  line-height: var(--theme-line-height-normal);
}

/* styles/recipes/surface.css */
/* Card — standard elevated container */
.ds-surface-card {
  background: var(--ds-surface-card);
  border: 1px solid var(--ds-border-subtle);
  border-radius: var(--theme-radius-2xl);
  box-shadow: var(--theme-shadow-sm);
}

/* Card raised — higher prominence */
.ds-surface-card-raised {
  background: var(--ds-surface-card);
  border: 1px solid var(--ds-border-default);
  border-radius: var(--theme-radius-2xl);
  box-shadow: var(--theme-shadow-md);
}

/* Glass — frosted glass effect */
.ds-surface-glass {
  background: var(--ds-surface-glass);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid var(--ds-border-subtle);
  border-radius: var(--theme-radius-2xl);
}

/* Glass accent — glass with purple border glow */
.ds-surface-glass-accent {
  background: var(--ds-surface-glass);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid var(--ds-border-accent);
  border-radius: var(--theme-radius-2xl);
  box-shadow: var(--theme-shadow-sm);
}

/* Inset — recessed input/well area */
.ds-surface-inset {
  background: var(--ds-surface-input);
  border: 1px solid var(--ds-border-input);
  border-radius: var(--theme-radius-xl);
}

/* Overlay — modal/dropdown backdrop */
.ds-surface-overlay {
  background: var(--ds-surface-overlay);
  border: 1px solid var(--ds-border-default);
  border-radius: var(--theme-radius-2xl);
  box-shadow: var(--theme-shadow-xl);
}

/* Gradient — brand gradient background */
.ds-surface-gradient {
  background: var(--ds-gradient-surface);
  border: 1px solid var(--ds-border-subtle);
  border-radius: var(--theme-radius-2xl);
}

/* Glow — card with colored light emission */
.ds-surface-glow {
  background: var(--ds-surface-card);
  border: 1px solid var(--ds-border-accent);
  border-radius: var(--theme-radius-2xl);
  box-shadow: var(--ds-layer-glow);
}

/* Glow electric — cyan-tinted glow */
.ds-surface-glow-electric {
  background: var(--ds-surface-card);
  border: 1px solid rgba(6, 182, 212, 0.2);
  border-radius: var(--theme-radius-2xl);
  box-shadow: var(--ds-layer-glow-cyan);
}

/* styles/recipes/type.css */
/* Display — hero headlines with impact */
.ds-type-display-lg {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-6xl);
  font-weight: var(--theme-font-weight-extrabold);
  line-height: var(--theme-line-height-tight);
  letter-spacing: var(--theme-letter-spacing-tight);
}

.ds-type-display-md {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-5xl);
  font-weight: var(--theme-font-weight-bold);
  line-height: var(--theme-line-height-tight);
  letter-spacing: var(--theme-letter-spacing-tight);
}

.ds-type-display-sm {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-4xl);
  font-weight: var(--theme-font-weight-bold);
  line-height: var(--theme-line-height-tight);
  letter-spacing: var(--theme-letter-spacing-tight);
}

/* Headings — section titles */
.ds-type-heading-lg {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-3xl);
  font-weight: var(--theme-font-weight-bold);
  line-height: var(--theme-line-height-snug);
  letter-spacing: var(--theme-letter-spacing-tight);
}

.ds-type-heading-md {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-2xl);
  font-weight: var(--theme-font-weight-bold);
  line-height: var(--theme-line-height-snug);
}

.ds-type-heading-sm {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-xl);
  font-weight: var(--theme-font-weight-semibold);
  line-height: var(--theme-line-height-snug);
}

/* Body — readable content */
.ds-type-body-lg {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-lg);
  font-weight: var(--theme-font-weight-regular);
  line-height: var(--theme-line-height-relaxed);
}

.ds-type-body-md {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-md);
  font-weight: var(--theme-font-weight-regular);
  line-height: var(--theme-line-height-normal);
}

.ds-type-body-sm {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-sm);
  font-weight: var(--theme-font-weight-regular);
  line-height: var(--theme-line-height-normal);
}

/* Labels — UI chrome, caps for scannability */
.ds-type-label-lg {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-sm);
  font-weight: var(--theme-font-weight-semibold);
  line-height: var(--theme-line-height-normal);
  letter-spacing: var(--theme-letter-spacing-wide);
  text-transform: uppercase;
}

.ds-type-label-md {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-xs);
  font-weight: var(--theme-font-weight-semibold);
  line-height: var(--theme-line-height-normal);
  letter-spacing: var(--theme-letter-spacing-wider);
  text-transform: uppercase;
}

/* Code — monospace for snippets */
.ds-type-code-lg {
  font-family: var(--theme-font-mono);
  font-size: var(--theme-font-size-md);
  font-weight: var(--theme-font-weight-regular);
  line-height: var(--theme-line-height-relaxed);
}

.ds-type-code-md {
  font-family: var(--theme-font-mono);
  font-size: var(--theme-font-size-sm);
  font-weight: var(--theme-font-weight-regular);
  line-height: var(--theme-line-height-relaxed);
}

.ds-type-code-sm {
  font-family: var(--theme-font-mono);
  font-size: var(--theme-font-size-xs);
  font-weight: var(--theme-font-weight-regular);
  line-height: var(--theme-line-height-normal);
}
```

## Components

### Button
Tag: `<ds-button>`
Props:
- label (string, default: Button)
- variant (select: primary | secondary | ghost | gradient | glow, default: primary)
- size (select: sm | md | lg, default: md)
- icon (icon, default: )
- icon-position (select: left | right, default: left)
- disabled (boolean, default: false)
- loading (boolean, default: false)
- full-width (boolean, default: false)
Slots: none

Example data:
```json
{
  "props": {
    "label": "Extract & Learn",
    "variant": "gradient",
    "icon": "rocket-launch"
  }
}
```

Implementation (`components/button/button.js`):
```js
class DsButton extends HTMLElement {
  static props = {
    label: { type: 'string', default: 'Button' },
    variant: { type: 'select', options: ['primary', 'secondary', 'ghost', 'gradient', 'glow'], default: 'primary' },
    size: { type: 'select', options: ['sm', 'md', 'lg'], default: 'md' },
    icon: { type: 'icon', default: '' },
    'icon-position': { type: 'select', options: ['left', 'right'], default: 'left' },
    disabled: { type: 'boolean', default: false },
    loading: { type: 'boolean', default: false },
    'full-width': { type: 'boolean', default: false },
  };

  connectedCallback() {
    this.render();
  }

  static get observedAttributes() {
    return ['label', 'variant', 'size', 'icon', 'icon-position', 'disabled', 'loading', 'full-width'];
  }

  attributeChangedCallback() {
    this.render();
  }

  render() {
    const label = this.getAttribute('label') || 'Button';
    const variant = this.getAttribute('variant') || 'primary';
    const size = this.getAttribute('size') || 'md';
    const icon = this.getAttribute('icon') || '';
    const iconPosition = this.getAttribute('icon-position') || 'left';
    const disabled = this.hasAttribute('disabled');
    const loading = this.hasAttribute('loading');
    const fullWidth = this.hasAttribute('full-width');

    const classes = [
      'ds-button',
      `ds-button--${variant}`,
      `ds-button--${size}`,
      fullWidth ? 'ds-button--full' : '',
      disabled ? 'ds-button--disabled' : '',
      loading ? 'ds-button--loading' : '',
    ].filter(Boolean).join(' ');

    const iconHtml = icon && !loading
      ? `<i class="ph ph-${icon} ds-button__icon"></i>`
      : '';

    const spinnerHtml = loading
      ? `<span class="ds-button__spinner"></span>`
      : '';

    const contentHtml = iconPosition === 'left'
      ? `${spinnerHtml}${iconHtml}<span class="ds-button__label">${label}</span>`
      : `<span class="ds-button__label">${label}</span>${iconHtml}${spinnerHtml}`;

    this.innerHTML = `<button class="${classes}" ${disabled || loading ? 'disabled' : ''}>${contentHtml}</button>`;
  }
}

customElements.define('ds-button', DsButton);

```

Styles (`components/button/button.css`):
```css
/* Base button */
.ds-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: var(--theme-spacing-2);
  border: none;
  border-radius: var(--theme-radius-xl);
  font-family: var(--theme-font-sans);
  font-weight: var(--theme-font-weight-semibold);
  cursor: pointer;
  transition:
    background var(--theme-motion-fast) var(--theme-ease-default),
    box-shadow var(--theme-motion-fast) var(--theme-ease-default),
    transform var(--theme-motion-fast) var(--theme-ease-bounce),
    opacity var(--theme-motion-fast) var(--theme-ease-default);
  white-space: nowrap;
  text-decoration: none;
  position: relative;
  overflow: hidden;
}

.ds-button:active:not(:disabled) {
  transform: scale(0.97);
}

.ds-button:focus-visible {
  outline: none;
  box-shadow: var(--ds-focus-ring);
}

/* Sizes */
.ds-button--sm {
  padding: var(--theme-spacing-2) var(--theme-spacing-4);
  font-size: var(--theme-font-size-sm);
  border-radius: var(--theme-radius-lg);
}

.ds-button--md {
  padding: var(--theme-spacing-3) var(--theme-spacing-6);
  font-size: var(--theme-font-size-md);
}

.ds-button--lg {
  padding: var(--theme-spacing-4) var(--theme-spacing-8);
  font-size: var(--theme-font-size-lg);
}

/* Primary variant */
.ds-button--primary {
  background: var(--ds-color-brand);
  color: var(--theme-neutral-0);
}

.ds-button--primary:hover:not(:disabled) {
  background: var(--ds-color-brand-hover);
  box-shadow: var(--ds-layer-glow-purple);
}

/* Secondary variant */
.ds-button--secondary {
  background: var(--ds-surface-glass);
  color: var(--ds-text-primary);
  border: 1px solid var(--ds-border-default);
}

.ds-button--secondary:hover:not(:disabled) {
  background: var(--ds-surface-glass-hover);
  border-color: var(--ds-border-strong);
}

/* Ghost variant */
.ds-button--ghost {
  background: transparent;
  color: var(--ds-text-secondary);
}

.ds-button--ghost:hover:not(:disabled) {
  background: var(--ds-surface-glass);
  color: var(--ds-text-primary);
}

/* Gradient variant */
.ds-button--gradient {
  background: var(--ds-gradient-brand);
  color: var(--theme-neutral-0);
}

.ds-button--gradient:hover:not(:disabled) {
  background: var(--ds-gradient-brand-hover);
  box-shadow: var(--ds-layer-glow);
  transform: translateY(-1px);
}

.ds-button--gradient:active:not(:disabled) {
  transform: scale(0.97) translateY(0);
}

/* Glow variant */
.ds-button--glow {
  background: var(--ds-color-brand);
  color: var(--theme-neutral-0);
  box-shadow: var(--ds-layer-glow-purple);
}

.ds-button--glow:hover:not(:disabled) {
  background: var(--ds-color-brand-hover);
  box-shadow: 0 0 30px rgba(168, 85, 247, 0.5), 0 0 80px rgba(168, 85, 247, 0.2);
  transform: translateY(-1px);
}

.ds-button--glow:active:not(:disabled) {
  transform: scale(0.97) translateY(0);
}

/* Full width */
.ds-button--full {
  width: 100%;
}

/* Disabled state */
.ds-button--disabled,
.ds-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  pointer-events: none;
}

/* Loading state */
.ds-button--loading {
  cursor: wait;
  pointer-events: none;
}

.ds-button__spinner {
  width: 1em;
  height: 1em;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: currentColor;
  border-radius: var(--theme-radius-full);
  animation: ds-button-spin 0.6s linear infinite;
}

@keyframes ds-button-spin {
  to { transform: rotate(360deg); }
}

/* Icon sizing */
.ds-button__icon {
  font-size: 1.2em;
  line-height: 1;
}

```

### Card
Tag: `<ds-card>`
Props:
- variant (select: default | glass | glass-accent | glow | glow-electric | gradient | raised, default: default)
- padding (select: none | sm | md | lg, default: md)
- interactive (boolean, default: false)
Slots: header, (default), footer

Example data:
```json
{
  "props": {
    "variant": "glass-accent",
    "interactive": true
  },
  "children": "<h3 slot=\"header\" class=\"ds-type-heading-sm\" style=\"color: var(--ds-text-primary);\">Code Extraction Complete</h3>\n<p class=\"ds-type-body-md\" style=\"color: var(--ds-text-secondary);\">12 files extracted from React tutorial. Ready to download as ZIP.</p>\n<div slot=\"footer\" style=\"display: flex; gap: var(--theme-spacing-3);\">\n  <span class=\"ds-badge-brand\">React</span>\n  <span class=\"ds-badge-electric\">TypeScript</span>\n  <span class=\"ds-badge-success\">Complete</span>\n</div>"
}
```

Implementation (`components/card/card.js`):
```js
const CARD_SHADOW_CSS = `
  :host {
    display: block;
    width: 100%;
  }

  .card {
    background: var(--ds-surface-card);
    border: 1px solid var(--ds-border-subtle);
    border-radius: var(--theme-radius-2xl);
    box-shadow: var(--theme-shadow-sm);
    overflow: hidden;
    transition:
      border-color var(--theme-motion-normal) var(--theme-ease-default),
      box-shadow var(--theme-motion-normal) var(--theme-ease-default),
      transform var(--theme-motion-normal) var(--theme-ease-default);
  }

  /* Variants */
  :host([variant="glass"]) .card {
    background: var(--ds-surface-glass);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
  }

  :host([variant="glass-accent"]) .card {
    background: var(--ds-surface-glass);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-color: var(--ds-border-accent);
  }

  :host([variant="glow"]) .card {
    border-color: var(--ds-border-accent);
    box-shadow: var(--ds-layer-glow);
  }

  :host([variant="glow-electric"]) .card {
    border-color: rgba(6, 182, 212, 0.2);
    box-shadow: var(--ds-layer-glow-cyan);
  }

  :host([variant="gradient"]) .card {
    background: var(--ds-gradient-surface);
  }

  :host([variant="raised"]) .card {
    border-color: var(--ds-border-default);
    box-shadow: var(--theme-shadow-md);
  }

  /* Interactive hover */
  :host([interactive]) .card {
    cursor: pointer;
  }

  :host([interactive]) .card:hover {
    border-color: var(--ds-border-accent);
    box-shadow: var(--ds-layer-glow);
    transform: translateY(-2px);
  }

  :host([interactive][variant="glow"]) .card:hover {
    box-shadow: 0 0 30px rgba(168, 85, 247, 0.4), 0 0 80px rgba(168, 85, 247, 0.15);
  }

  :host([interactive][variant="glow-electric"]) .card:hover {
    box-shadow: 0 0 30px rgba(6, 182, 212, 0.4), 0 0 80px rgba(6, 182, 212, 0.15);
  }

  /* Padding sizes */
  :host([padding="none"]) .body { padding: 0; }
  :host([padding="sm"]) .body { padding: var(--theme-spacing-4); }
  :host([padding="lg"]) .body { padding: var(--theme-spacing-8); }

  /* Slots layout */
  .header {
    padding: var(--theme-spacing-5) var(--theme-spacing-6) 0;
  }

  .body {
    padding: var(--theme-spacing-6);
  }

  .footer {
    padding: 0 var(--theme-spacing-6) var(--theme-spacing-5);
  }

  /* Hide empty slot wrappers via slotted */
  ::slotted([slot="header"]) {
    display: block;
  }

  ::slotted([slot="footer"]) {
    display: block;
    padding-top: var(--theme-spacing-4);
    border-top: 1px solid var(--ds-border-subtle);
  }
`;

class DsCard extends HTMLElement {
  static props = {
    variant: { type: 'select', options: ['default', 'glass', 'glass-accent', 'glow', 'glow-electric', 'gradient', 'raised'], default: 'default' },
    padding: { type: 'select', options: ['none', 'sm', 'md', 'lg'], default: 'md' },
    interactive: { type: 'boolean', default: false },
  };

  connectedCallback() {
    const shadow = this.attachShadow({ mode: 'open' });
    if (window.__DS_STYLES) shadow.adoptedStyleSheets = [window.__DS_STYLES];
    shadow.innerHTML = `<style>${CARD_SHADOW_CSS}</style>
      <div class="card">
        <div class="header"><slot name="header"></slot></div>
        <div class="body"><slot></slot></div>
        <div class="footer"><slot name="footer"></slot></div>
      </div>`;
  }
}

customElements.define('ds-card', DsCard);

```

Styles (`components/card/card.css`):
```css
ds-card {
  display: block;
  width: 100%;
}
```

### Input
Tag: `<ds-input>`
Props:
- label (string, default: )
- placeholder (string, default: )
- value (string, default: )
- type (select: text | email | password | url | search | number, default: text)
- size (select: sm | md | lg, default: md)
- icon (icon, default: )
- hint (string, default: )
- error (string, default: )
- disabled (boolean, default: false)
- required (boolean, default: false)
Slots: none

Example data:
```json
{
  "props": {
    "label": "YouTube Video URL",
    "placeholder": "https://www.youtube.com/watch?v=...",
    "icon": "link",
    "size": "lg"
  }
}
```

Implementation (`components/input/input.js`):
```js
class DsInput extends HTMLElement {
  static props = {
    label: { type: 'string', default: '' },
    placeholder: { type: 'string', default: '' },
    value: { type: 'string', default: '' },
    type: { type: 'select', options: ['text', 'email', 'password', 'url', 'search', 'number'], default: 'text' },
    size: { type: 'select', options: ['sm', 'md', 'lg'], default: 'md' },
    icon: { type: 'icon', default: '' },
    hint: { type: 'string', default: '' },
    error: { type: 'string', default: '' },
    disabled: { type: 'boolean', default: false },
    required: { type: 'boolean', default: false },
  };

  connectedCallback() {
    this.render();
  }

  static get observedAttributes() {
    return ['label', 'placeholder', 'value', 'type', 'size', 'icon', 'hint', 'error', 'disabled', 'required'];
  }

  attributeChangedCallback() {
    this.render();
  }

  render() {
    const label = this.getAttribute('label') || '';
    const placeholder = this.getAttribute('placeholder') || '';
    const value = this.getAttribute('value') || '';
    const type = this.getAttribute('type') || 'text';
    const size = this.getAttribute('size') || 'md';
    const icon = this.getAttribute('icon') || '';
    const hint = this.getAttribute('hint') || '';
    const error = this.getAttribute('error') || '';
    const disabled = this.hasAttribute('disabled');
    const required = this.hasAttribute('required');

    const hasError = !!error;
    const wrapperClasses = [
      'ds-input',
      `ds-input--${size}`,
      hasError ? 'ds-input--error' : '',
      disabled ? 'ds-input--disabled' : '',
      icon ? 'ds-input--has-icon' : '',
    ].filter(Boolean).join(' ');

    const labelHtml = label
      ? `<label class="ds-input__label">${label}${required ? '<span class="ds-input__required">*</span>' : ''}</label>`
      : '';

    const iconHtml = icon
      ? `<i class="ph ph-${icon} ds-input__icon"></i>`
      : '';

    const messageHtml = error
      ? `<span class="ds-input__message ds-input__message--error">${error}</span>`
      : hint
        ? `<span class="ds-input__message">${hint}</span>`
        : '';

    this.innerHTML = `
      <div class="${wrapperClasses}">
        ${labelHtml}
        <div class="ds-input__field-wrapper">
          ${iconHtml}
          <input
            class="ds-input__field"
            type="${type}"
            placeholder="${placeholder}"
            value="${value}"
            ${disabled ? 'disabled' : ''}
            ${required ? 'required' : ''}
          />
        </div>
        ${messageHtml}
      </div>
    `;
  }
}

customElements.define('ds-input', DsInput);

```

Styles (`components/input/input.css`):
```css
/* Wrapper */
.ds-input {
  display: flex;
  flex-direction: column;
  gap: var(--theme-spacing-2);
  width: 100%;
}

/* Label */
.ds-input__label {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-sm);
  font-weight: var(--theme-font-weight-medium);
  color: var(--ds-text-secondary);
}

.ds-input__required {
  color: var(--ds-color-error);
  margin-left: var(--theme-spacing-1);
}

/* Field wrapper */
.ds-input__field-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

/* Icon */
.ds-input__icon {
  position: absolute;
  left: var(--theme-spacing-4);
  color: var(--ds-text-muted);
  font-size: 1.25em;
  line-height: 1;
  pointer-events: none;
  transition: color var(--theme-motion-fast) var(--theme-ease-default);
}

/* Field */
.ds-input__field {
  width: 100%;
  background: var(--ds-surface-input);
  border: 1px solid var(--ds-border-input);
  border-radius: var(--theme-radius-xl);
  color: var(--ds-text-primary);
  font-family: var(--theme-font-sans);
  transition:
    border-color var(--theme-motion-fast) var(--theme-ease-default),
    box-shadow var(--theme-motion-fast) var(--theme-ease-default),
    background var(--theme-motion-fast) var(--theme-ease-default);
}

.ds-input__field::placeholder {
  color: var(--ds-text-muted);
}

.ds-input__field:hover:not(:disabled) {
  border-color: var(--ds-border-strong);
  background: var(--ds-surface-glass);
}

.ds-input__field:focus {
  outline: none;
  border-color: var(--ds-border-input-focus);
  box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.15), 0 0 20px rgba(168, 85, 247, 0.1);
  background: var(--ds-surface-input);
}

/* Icon color on focus */
.ds-input__field:focus ~ .ds-input__icon,
.ds-input__field-wrapper:focus-within .ds-input__icon {
  color: var(--ds-color-brand-subtle);
}

/* Sizes */
.ds-input--sm .ds-input__field {
  padding: var(--theme-spacing-2) var(--theme-spacing-3);
  font-size: var(--theme-font-size-sm);
  border-radius: var(--theme-radius-lg);
}

.ds-input--md .ds-input__field {
  padding: var(--theme-spacing-3) var(--theme-spacing-5);
  font-size: var(--theme-font-size-md);
}

.ds-input--lg .ds-input__field {
  padding: var(--theme-spacing-4) var(--theme-spacing-6);
  font-size: var(--theme-font-size-lg);
}

/* Icon padding offset */
.ds-input--has-icon.ds-input--sm .ds-input__field {
  padding-left: var(--theme-spacing-10);
}

.ds-input--has-icon.ds-input--md .ds-input__field {
  padding-left: var(--theme-spacing-12);
}

.ds-input--has-icon.ds-input--lg .ds-input__field {
  padding-left: calc(var(--theme-spacing-12) + var(--theme-spacing-2));
}

/* Error state */
.ds-input--error .ds-input__field {
  border-color: var(--ds-border-error);
}

.ds-input--error .ds-input__field:focus {
  border-color: var(--ds-border-error);
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15), 0 0 20px rgba(239, 68, 68, 0.1);
}

/* Disabled state */
.ds-input--disabled .ds-input__field {
  opacity: 0.5;
  cursor: not-allowed;
}

.ds-input--disabled .ds-input__label {
  opacity: 0.5;
}

/* Message */
.ds-input__message {
  font-family: var(--theme-font-sans);
  font-size: var(--theme-font-size-xs);
  color: var(--ds-text-muted);
}

.ds-input__message--error {
  color: var(--ds-color-error-subtle);
}

```

## Guidelines

### guidelines/interaction-patterns.md

## Input Flow

The URL input is the centerpiece of the app. It should always be the largest, most prominent input on screen — use the large size with an icon. Pair it immediately with the primary gradient CTA below or beside it, inside a glass-accent card to create a clear "action zone."

## Loading States

Extraction takes time (10-30 seconds). During loading:

- The submit button enters loading state (spinner replaces icon)
- The input becomes non-interactive but stays visible (user can see what they submitted)
- Use rotating status messages to maintain engagement ("Analyzing video...", "Extracting code...")

Never show a blank loading screen — always keep context visible.

## Result Cards

Extraction results should be interactive cards (hover lift + glow). Each result needs:

- Video title as heading
- Channel + duration as secondary text
- Tech stack badges (scannable at a glance)
- Primary action (Download ZIP) and secondary action (View Tutorial)

Results appear with a fade-in animation, not a hard cut.

## Button Pairing

Action groups follow a clear hierarchy:

- Hero CTA: gradient + full-width
- Primary + secondary: gradient or glow + ghost side by side
- Toolbar actions: all ghost, same size
- Destructive: use error color only for irreversible actions (sign out is not destructive)

## Empty States

When the video library is empty or search returns nothing, show a centered message with a muted icon, brief text, and a single CTA to extract a new video. Keep it simple — don't over-illustrate.

### guidelines/visual-hierarchy.md

## Glow Budget

Only one element per viewport should emit a strong glow at rest. Multiple glowing elements compete and dilute impact. Reserve glow variants for:

- The primary CTA on a page ("Extract & Learn")
- A featured result or promoted content card
- Never for secondary actions or repeated list items

## Gradient vs Solid

Gradient buttons are for the single most important action on a screen. If there are two buttons side by side, one is gradient and the other is secondary or ghost — never two gradients.

Solid purple (primary) is the workhorse for standard CTAs like "Sign In", "View Details", "Copy Code".

## Card Variant Selection

- **Glass-accent**: Extraction results, main content containers that hold user data
- **Glow**: Feature callouts, promotional content, single highlighted items
- **Glow-electric**: Code previews, terminal output, anything representing extracted code
- **Glass** (plain): Feature grid items, secondary panels, repeated cards in a list
- **Default**: Quiet containers that don't need visual emphasis

## Badge Density

Tech stack badges should max out at 4-5 per card. If a video uses more technologies, show the most relevant and add a "+3 more" indicator. Badges are for scanning, not cataloguing.

## Dark Surface Layering

Surfaces progress from dark to lighter as they rise:

1. Page base (deepest)
2. Sections/raised areas
3. Cards and panels
4. Overlays and modals (lightest dark)

Never place a darker surface above a lighter one — it breaks the elevation mental model.

## Gallery

### Button Variants

```html
<div style="padding: var(--theme-spacing-8); display: flex; flex-direction: column; gap: var(--theme-spacing-10); background: var(--ds-surface-base); min-height: 100%;">

  <!-- Primary & Gradient -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">Primary & Gradient</div>
    <div style="display: flex; flex-wrap: wrap; gap: var(--theme-spacing-4); align-items: center;">
      <ds-button label="Extract Video" variant="primary" icon="play"></ds-button>
      <ds-button label="Extract & Learn" variant="gradient" icon="rocket-launch"></ds-button>
      <ds-button label="Download ZIP" variant="glow" icon="download-simple"></ds-button>
    </div>
  </div>

  <!-- Secondary & Ghost -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">Secondary & Ghost</div>
    <div style="display: flex; flex-wrap: wrap; gap: var(--theme-spacing-4); align-items: center;">
      <ds-button label="View on YouTube" variant="secondary" icon="youtube-logo"></ds-button>
      <ds-button label="My Library" variant="ghost" icon="books"></ds-button>
      <ds-button label="Copy Code" variant="ghost" icon="copy"></ds-button>
    </div>
  </div>

  <!-- Sizes -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">Sizes</div>
    <div style="display: flex; flex-wrap: wrap; gap: var(--theme-spacing-4); align-items: center;">
      <ds-button label="Small" variant="primary" size="sm"></ds-button>
      <ds-button label="Medium" variant="primary" size="md"></ds-button>
      <ds-button label="Large" variant="primary" size="lg"></ds-button>
    </div>
  </div>

  <!-- States -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">States</div>
    <div style="display: flex; flex-wrap: wrap; gap: var(--theme-spacing-4); align-items: center;">
      <ds-button label="Extracting..." variant="gradient" loading></ds-button>
      <ds-button label="Disabled" variant="primary" disabled></ds-button>
      <ds-button label="Sign In" variant="primary" full-width size="lg"></ds-button>
    </div>
  </div>

</div>
```

### Card Variants

```html
<div style="padding: var(--theme-spacing-8); display: flex; flex-direction: column; gap: var(--theme-spacing-8); background: var(--ds-surface-base); min-height: 100%;">

  <!-- Glass Accent Card -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">Glass Accent (Extraction Result)</div>
    <ds-card variant="glass-accent">
      <div slot="header">
        <h3 class="ds-type-heading-sm" style="color: var(--ds-text-primary); margin: 0;">Building a Full-Stack App with Next.js 14</h3>
        <p class="ds-type-body-sm" style="color: var(--ds-text-secondary); margin-top: var(--theme-spacing-2);">Traversy Media • 45:23 • 12 files extracted</p>
      </div>
      <div style="display: flex; flex-wrap: wrap; gap: var(--theme-spacing-2);">
        <span class="ds-badge-brand">Next.js</span>
        <span class="ds-badge-electric">TypeScript</span>
        <span class="ds-badge-accent">Tailwind</span>
        <span class="ds-badge-success">Complete</span>
      </div>
      <div slot="footer" style="display: flex; gap: var(--theme-spacing-3);">
        <ds-button label="Download ZIP" variant="gradient" size="sm" icon="download-simple"></ds-button>
        <ds-button label="Watch on YouTube" variant="ghost" size="sm" icon="youtube-logo"></ds-button>
      </div>
    </ds-card>
  </div>

  <!-- Glow Card -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">Glow (Featured)</div>
    <ds-card variant="glow">
      <div style="text-align: center; padding: var(--theme-spacing-4) 0;">
        <p class="ds-type-label-md" style="color: var(--ds-text-brand); margin: 0 0 var(--theme-spacing-3);">AI-Powered</p>
        <h3 class="ds-type-heading-lg" style="color: var(--ds-text-primary); margin: 0 0 var(--theme-spacing-3);">Instant Code Extraction</h3>
        <p class="ds-type-body-md" style="color: var(--ds-text-secondary); margin: 0;">Don't manually copy code from paused videos. We generate working file structures instantly.</p>
      </div>
    </ds-card>
  </div>

  <!-- Interactive Cards Row -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">Interactive (Feature Grid)</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--theme-spacing-4);">
      <ds-card variant="glass" interactive padding="sm">
        <div style="text-align: center;">
          <div style="font-size: 2rem; margin-bottom: var(--theme-spacing-3);"><i class="ph ph-book-open" style="color: var(--ds-text-brand); font-size: 2rem;"></i></div>
          <h4 class="ds-type-heading-sm" style="color: var(--ds-text-primary); margin: 0 0 var(--theme-spacing-2);">Tutorial Guide</h4>
          <p class="ds-type-body-sm" style="color: var(--ds-text-secondary); margin: 0;">Complete written explanations</p>
        </div>
      </ds-card>
      <ds-card variant="glass" interactive padding="sm">
        <div style="text-align: center;">
          <div style="font-size: 2rem; margin-bottom: var(--theme-spacing-3);"><i class="ph ph-terminal" style="color: var(--ds-text-electric); font-size: 2rem;"></i></div>
          <h4 class="ds-type-heading-sm" style="color: var(--ds-text-primary); margin: 0 0 var(--theme-spacing-2);">Setup & Run</h4>
          <p class="ds-type-body-sm" style="color: var(--ds-text-secondary); margin: 0;">Step-by-step instructions</p>
        </div>
      </ds-card>
      <ds-card variant="glass" interactive padding="sm">
        <div style="text-align: center;">
          <div style="font-size: 2rem; margin-bottom: var(--theme-spacing-3);"><i class="ph ph-file-zip" style="color: var(--ds-text-accent); font-size: 2rem;"></i></div>
          <h4 class="ds-type-heading-sm" style="color: var(--ds-text-primary); margin: 0 0 var(--theme-spacing-2);">Download ZIP</h4>
          <p class="ds-type-body-sm" style="color: var(--ds-text-secondary); margin: 0;">Full project ready to run</p>
        </div>
      </ds-card>
    </div>
  </div>

  <!-- Glow Electric -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">Glow Electric (Code Preview)</div>
    <ds-card variant="glow-electric" padding="sm">
      <p class="ds-type-label-md" style="color: var(--ds-text-electric); margin: 0 0 var(--theme-spacing-3);">App.tsx</p>
      <pre class="ds-type-code-md" style="color: var(--ds-text-primary); margin: 0; white-space: pre-wrap;">import React from 'react';
import { useState } from 'react';

export default function App() {
  const [count, setCount] = useState(0);
  return &lt;div&gt;Hello World&lt;/div&gt;;
}</pre>
    </ds-card>
  </div>

</div>
```

### Extraction Flow

```html
<div style="padding: var(--theme-spacing-8); display: flex; flex-direction: column; gap: var(--theme-spacing-8); background: var(--ds-surface-base); min-height: 100%; max-width: 640px; margin: 0 auto;">

  <!-- Header -->
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: var(--theme-spacing-3);">
      <i class="ph ph-film-strip" style="color: var(--ds-text-brand); font-size: 1.5rem;"></i>
      <span class="ds-type-heading-sm" style="color: var(--ds-text-primary);">YouExtractor</span>
    </div>
    <div style="display: flex; align-items: center; gap: var(--theme-spacing-3);">
      <ds-button label="My Library" variant="ghost" icon="books" size="sm"></ds-button>
      <ds-button label="Get Started" variant="primary" size="sm"></ds-button>
    </div>
  </div>

  <!-- Hero -->
  <div style="text-align: center; padding: var(--theme-spacing-8) 0;">
    <span class="ds-badge-brand" style="margin-bottom: var(--theme-spacing-4); display: inline-block;">AI-Powered Visual Learning</span>
    <h1 class="ds-type-display-sm" style="color: var(--ds-text-primary); margin: var(--theme-spacing-4) 0;">Turn Videos into <span style="color: var(--ds-text-brand);">Real Code</span></h1>
    <p class="ds-type-body-lg" style="color: var(--ds-text-secondary); margin: 0;">Stop pausing and typing. Extract working projects instantly.</p>
  </div>

  <!-- Input Section -->
  <ds-card variant="glass-accent">
    <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-4);">
      <ds-input placeholder="https://www.youtube.com/watch?v=..." icon="link" size="lg"></ds-input>
      <ds-button label="Extract & Learn" variant="gradient" icon="rocket-launch" size="lg" full-width></ds-button>
    </div>
  </ds-card>

  <!-- Result Preview -->
  <ds-card variant="glass" interactive>
    <div slot="header">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <h3 class="ds-type-heading-sm" style="color: var(--ds-text-primary); margin: 0;">React Dashboard Tutorial</h3>
        <span class="ds-badge-success">Complete</span>
      </div>
      <p class="ds-type-body-sm" style="color: var(--ds-text-muted); margin: var(--theme-spacing-1) 0 0;">Web Dev Simplified • 32:15</p>
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: var(--theme-spacing-2); margin-bottom: var(--theme-spacing-4);">
      <span class="ds-badge-brand">React</span>
      <span class="ds-badge-electric">Vite</span>
      <span class="ds-badge-accent">CSS Modules</span>
    </div>
    <div style="display: flex; gap: var(--theme-spacing-3);">
      <ds-button label="Download ZIP" variant="glow" size="sm" icon="download-simple"></ds-button>
      <ds-button label="View Tutorial" variant="secondary" size="sm" icon="book-open"></ds-button>
    </div>
  </ds-card>

</div>
```

### Input States

```html
<div style="padding: var(--theme-spacing-8); display: flex; flex-direction: column; gap: var(--theme-spacing-10); background: var(--ds-surface-base); min-height: 100%; max-width: 560px;">

  <!-- Hero Input -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">Hero Input (Large)</div>
    <ds-input label="YouTube Video URL" placeholder="https://www.youtube.com/watch?v=..." icon="link" size="lg"></ds-input>
  </div>

  <!-- Standard Inputs -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">Standard Fields</div>
    <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-5);">
      <ds-input label="Email Address" placeholder="you@example.com" icon="envelope" type="email"></ds-input>
      <ds-input label="Password" placeholder="••••••••" icon="lock" type="password"></ds-input>
    </div>
  </div>

  <!-- Search -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">Search</div>
    <ds-input placeholder="Search videos by title or content..." icon="magnifying-glass" size="md"></ds-input>
  </div>

  <!-- Error State -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">Error State</div>
    <ds-input label="YouTube URL" placeholder="https://www.youtube.com/watch?v=..." icon="link" error="Please enter a valid YouTube URL" value="not-a-valid-url"></ds-input>
  </div>

  <!-- With Hint -->
  <div>
    <div style="font-size: var(--theme-font-size-xs); color: var(--ds-text-secondary); text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: var(--theme-spacing-sm); font-family: var(--theme-font-sans); font-weight: 600;">With Hint</div>
    <ds-input label="API Key" placeholder="sk-..." icon="key" hint="Your Gemini or OpenAI API key for extraction" type="password"></ds-input>
  </div>

</div>
```

## Icon Style

This design system uses Phosphor icons.

## Font Configuration

- Inter: weights 400, 500, 600, 700, 800, fallback: sans-serif
- JetBrains Mono: weights 400, 500, fallback: monospace