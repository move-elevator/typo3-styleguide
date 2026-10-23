class Copy {
  constructor() {
    this.init();
  }

  init() {
    this.status = document.createElement('div');
    this.status.setAttribute('role', 'status');
    this.status.className = 'typo3-styleguide__sr-only';
    document.body.appendChild(this.status);

    document.querySelectorAll('.typo3-styleguide__element[data-copy], .typo3-styleguide__element [data-copy]').forEach(el => {
      if (!el.hasAttribute('aria-label')) {
        el.setAttribute('aria-label', `Copy "${el.getAttribute('data-copy')}" to clipboard`);
      }

      el.addEventListener('click', () => this.copyToClipboard(el));
      if (el.tagName !== 'BUTTON') {
        el.setAttribute('tabindex', '0');
        el.setAttribute('role', 'button');
        el.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.copyToClipboard(el);
          }
        });
      }
    });
  }

  copyToClipboard(el) {
    const value = el.getAttribute('data-copy');
    if (!value) return;

    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(value).then(() => {
        this.showCopiedHint(el, 'Copied!', 'success');
      }).catch(() => {
        this.fallbackCopyTextToClipboard(value, el);
      });
    } else {
      this.fallbackCopyTextToClipboard(value, el);
    }
  }

  fallbackCopyTextToClipboard(text, el) {
    const previouslyFocused = document.activeElement;
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
      const successful = document.execCommand('copy');
      if (successful) {
        this.showCopiedHint(el, 'Copied!', 'success');
      } else {
        this.showCopiedHint(el, 'Copy failed', 'error');
      }
    } catch (err) {
      this.showCopiedHint(el, 'Copy not supported', 'error');
    }

    document.body.removeChild(textArea);
    previouslyFocused?.focus();
  }

  announce(text) {
    clearTimeout(this.statusTimer);
    this.status.textContent = '';
    requestAnimationFrame(() => {
      this.status.textContent = text;
    });
    this.statusTimer = setTimeout(() => {
      this.status.textContent = '';
    }, 1500);
  }

  showCopiedHint(el, message = 'Copied!', type = 'success') {
    const host = el.closest('.typo3-styleguide__element');
    const existingHint = host.querySelector('.copy-hint');
    if (existingHint) {
      existingHint.remove();
    }

    this.announce(type === 'error' ? message : `Copied ${el.getAttribute('data-copy')}`);

    const hint = document.createElement('div');
    hint.textContent = message;
    hint.setAttribute('aria-hidden', 'true');
    hint.classList.add('copy-hint');
    if (type === 'error') {
      hint.classList.add('copy-hint--error');
    }

    host.style.position = 'relative';
    host.appendChild(hint);

    requestAnimationFrame(() => {
      hint.style.opacity = '1';
      hint.style.transform = 'translate(-50%, -50%) scale(1)';
    });

    setTimeout(() => {
      hint.style.opacity = '0';
      hint.style.transform = 'translate(-50%, -50%) scale(0.9)';
      setTimeout(() => {
        if (hint.parentNode) {
          hint.remove();
        }
      }, 200);
    }, 1500);
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => new Copy());
} else {
  new Copy();
}
