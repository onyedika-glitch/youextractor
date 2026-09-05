/* components/card/card.js */
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
    box-shadow: 0 0 30px rgba(20, 184, 166, 0.4), 0 0 80px rgba(20, 184, 166, 0.15);
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
