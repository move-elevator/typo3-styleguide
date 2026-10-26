class Copy {
  constructor() {
    this.init();
  }

  init() {
    this.createLiveRegion();

    document.querySelectorAll('.typo3-styleguide__element[data-copy]').forEach(el => {
      el.setAttribute('tabindex', '0');
      el.setAttribute('role', 'button');
      el.setAttribute('aria-label', `Copy "${el.getAttribute('data-copy')}" to clipboard`);

      el.addEventListener('click', () => this.copyToClipboard(el));
      el.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.copyToClipboard(el);
        }
      });
    });
  }

  // Created up front: screen readers can miss a status node that is inserted right before its first message
  createLiveRegion() {
    this.liveRegion = document.createElement('div');
    this.liveRegion.setAttribute('role', 'status');
    // Inline because this script also loads on pages without the headline stylesheet that defines .visually-hidden
    this.liveRegion.style.cssText = 'position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap';
    document.body.appendChild(this.liveRegion);
  }

  announce(message) {
    // Clearing first makes screen readers repeat an identical message
    this.liveRegion.textContent = '';
    setTimeout(() => {
      this.liveRegion.textContent = message;
    }, 50);
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
  }

  showCopiedHint(el, message = 'Copied!', type = 'success') {
    const existingHint = el.querySelector('.copy-hint');
    if (existingHint) {
      existingHint.remove();
    }

    this.announce(message);

    const hint = document.createElement('span');
    hint.setAttribute('aria-hidden', 'true');
    hint.textContent = message;
    hint.classList.add('copy-hint');
    if (type === 'error') {
      hint.classList.add('copy-hint--error');
    }

    el.style.position = 'relative';
    el.appendChild(hint);

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
