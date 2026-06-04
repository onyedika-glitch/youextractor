/* components/input/input.js */
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

  _rendered = false;

  connectedCallback() {
    this.render();
  }

  static get observedAttributes() {
    return ['label', 'placeholder', 'value', 'type', 'size', 'icon', 'hint', 'error', 'disabled', 'required', 'name'];
  }

  attributeChangedCallback(attrName, oldVal, newVal) {
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
    }

    this._rendered = true;
  }
}

customElements.define('ds-input', DsInput);
