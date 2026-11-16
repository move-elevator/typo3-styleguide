// The switch in the header sets the theme of the frame and remembers the choice. Without a choice the theme follows the system
(() => {
  const KEY = 'typo3-styleguide-theme';
  const root = document.documentElement;
  const query = window.matchMedia('(prefers-color-scheme: dark)');

  const read = () => {
    try {
      return localStorage.getItem(KEY);
    } catch {
      return null;
    }
  };

  const write = (theme) => {
    try {
      localStorage.setItem(KEY, theme);
    } catch {
      // The choice only lasts until the page is closed
    }
  };

  // Runs in the head, so the page is not shown in the wrong theme first
  const stored = read();
  if (stored === 'light' || stored === 'dark') root.dataset.sgTheme = stored;

  const isDark = () => (root.dataset.sgTheme ? root.dataset.sgTheme === 'dark' : query.matches);

  const init = () => {
    const button = document.querySelector('[data-sg-theme-toggle]');
    if (!button) return;

    const sync = () => button.setAttribute('aria-pressed', String(isDark()));
    button.hidden = false;
    sync();

    button.addEventListener('click', () => {
      const next = isDark() ? 'light' : 'dark';
      root.dataset.sgTheme = next;
      write(next);
      sync();
    });
    query.addEventListener('change', sync);
  };

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
