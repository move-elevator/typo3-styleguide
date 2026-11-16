// Filters the icons by their name while the visitor types. The status line tells screen readers how many icons are left
document.querySelectorAll('[data-sg-icon-search]').forEach(root => {
  const input = root.querySelector('[data-sg-icon-search-input]');
  const status = root.querySelector('[data-sg-icon-search-status]');
  const items = root.parentElement.querySelectorAll('.typo3-styleguide__element--icon[data-copy]');

  const apply = () => {
    const query = input.value.trim().toLowerCase();
    let shown = 0;
    items.forEach(item => {
      const match = query === '' || item.dataset.copy.toLowerCase().includes(query);
      item.hidden = !match;
      if (match) shown++;
    });
    // Without a query nothing changed, so there is nothing to announce
    status.textContent = query === ''
      ? ''
      : shown === 0 ? root.dataset.none : root.dataset.count.replace('{shown}', shown).replace('{total}', items.length);
  };

  input.addEventListener('input', apply);
  input.addEventListener('keydown', event => {
    if (event.key === 'Escape' && input.value !== '') {
      input.value = '';
      apply();
    }
  });
  apply();
});
