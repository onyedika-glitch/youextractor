/* components/button/button.js */
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
