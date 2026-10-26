// The search of the start page filters the cards of the table of contents on the page while the visitor types
const form = document.querySelector('[data-sg-start-search]');
const cards = [...document.querySelectorAll('.typo3-styleguide-toc__card, .typo3-styleguide-toc__area')];

// Without cards on the page there is nothing to search, so the field stays hidden
if (form && cards.length > 0) {
  form.hidden = false;

  const input = form.querySelector('[data-sg-start-search-input]');
  const status = form.querySelector('[data-sg-start-search-status]');
  const groups = [...document.querySelectorAll('.typo3-styleguide-toc__group')];
  const textOf = element => element.textContent.replace(/\s+/g, ' ').trim().toLowerCase();

  const apply = () => {
    const query = input.value.trim().toLowerCase();
    let shown = 0;
    cards.forEach(card => {
      const match = query === '' || textOf(card).includes(query);
      card.hidden = !match;
      if (match) shown++;
    });
    // A group without a visible card hides with its title
    groups.forEach(group => { group.hidden = query !== '' && group.querySelector('.typo3-styleguide-toc__card:not([hidden])') === null; });
    status.textContent = query === ''
      ? ''
      : (shown === 0 ? form.dataset.none : form.dataset.count.replace('{shown}', shown).replace('{total}', cards.length));
  };

  input.addEventListener('input', apply);
  input.addEventListener('keydown', event => {
    if (event.key === 'Escape' && input.value !== '') {
      input.value = '';
      apply();
    }
  });
  // Enter does not reload the page, it moves to the first card that is left
  form.addEventListener('submit', event => {
    event.preventDefault();
    document.querySelector('.typo3-styleguide-toc__card:not([hidden]) , .typo3-styleguide-toc__area:not([hidden]) a')?.focus();
  });
}
