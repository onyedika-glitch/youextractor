// Button Custom Element
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

  get label() {
    return this.getAttribute('label') || 'Button';
  }
  set label(val) {
    this.setAttribute('label', val);
  }

  get variant() {
    return this.getAttribute('variant') || 'primary';
  }
  set variant(val) {
    this.setAttribute('variant', val);
  }

  get size() {
    return this.getAttribute('size') || 'md';
  }
  set size(val) {
    this.setAttribute('size', val);
  }

  get disabled() {
    return this.hasAttribute('disabled');
  }
  set disabled(val) {
    if (val) {
      this.setAttribute('disabled', '');
    } else {
      this.removeAttribute('disabled');
    }
  }

  get loading() {
    return this.hasAttribute('loading');
  }
  set loading(val) {
    if (val) {
      this.setAttribute('loading', '');
    } else {
      this.removeAttribute('loading');
    }
  }

  get fullWidth() {
    return this.hasAttribute('full-width');
  }
  set fullWidth(val) {
    if (val) {
      this.setAttribute('full-width', '');
    } else {
      this.removeAttribute('full-width');
    }
  }

  render() {
    const label = this.label;
    const variant = this.variant;
    const size = this.size;
    const icon = this.getAttribute('icon') || '';
    const iconPosition = this.getAttribute('icon-position') || 'left';
    const disabled = this.disabled;
    const loading = this.loading;
    const fullWidth = this.fullWidth;

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

// Card Custom Element
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

// Input Custom Element
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
    name: { type: 'string', default: '' },
  };

  _skipRender = false;
  _rendered = false;

  connectedCallback() {
    this.render();
  }

  static get observedAttributes() {
    return ['label', 'placeholder', 'value', 'type', 'size', 'icon', 'hint', 'error', 'disabled', 'required', 'name'];
  }

  attributeChangedCallback(attrName, oldVal, newVal) {
    if (this._skipRender) return;

    if (attrName === 'value' && this._rendered) {
      const input = this.querySelector('input');
      if (input && input.value !== newVal) {
        input.value = newVal || '';
      }
      return;
    }

    if (this._rendered) {
      this.render();
    }
  }

  get value() {
    return this.querySelector('input')?.value || this.getAttribute('value') || '';
  }
  set value(val) {
    this.setAttribute('value', val);
    const input = this.querySelector('input');
    if (input) {
      input.value = val;
    }
  }

  get disabled() {
    return this.hasAttribute('disabled');
  }
  set disabled(val) {
    if (val) {
      this.setAttribute('disabled', '');
    } else {
      this.removeAttribute('disabled');
    }
  }

  get error() {
    return this.getAttribute('error') || '';
  }
  set error(val) {
    if (val) {
      this.setAttribute('error', val);
    } else {
      this.removeAttribute('error');
    }
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
    const name = this.getAttribute('name') || '';

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

    // Save previous value if input already exists in DOM
    const oldInput = this.querySelector('input');
    const currentValue = oldInput ? oldInput.value : value;

    this.innerHTML = `
      <div class="${wrapperClasses}">
        ${labelHtml}
        <div class="ds-input__field-wrapper">
          ${iconHtml}
          <input
            class="ds-input__field"
            type="${type}"
            placeholder="${placeholder}"
            ${name ? `name="${name}"` : ''}
            ${disabled ? 'disabled' : ''}
            ${required ? 'required' : ''}
          />
        </div>
        ${messageHtml}
      </div>
    `;

    const newInput = this.querySelector('input');
    if (newInput) {
      newInput.value = currentValue;

      newInput.addEventListener('input', () => {
        this._skipRender = true;
        this.setAttribute('value', newInput.value);
        this._skipRender = false;
      });
      newInput.addEventListener('change', () => {
        this._skipRender = true;
        this.setAttribute('value', newInput.value);
        this._skipRender = false;
      });
    }

    this._rendered = true;
  }
}

customElements.define('ds-input', DsInput);
