// The headings of the page become the entries below the active entry of the side menu: the technical headlines and the parts of an
// element documentation. The one in view is marked while the page scrolls
const MIN_ENTRIES = 2;
const OFFSET = 120;

const active = document.querySelector('.typo3-styleguide-frame__sidenav-link--active');
const titleOf = heading => (heading.querySelector('.typo3-styleguide-technical-headline__title') ?? heading).textContent.trim();
// A first headline that is named like the page is the page itself, so it gets no entry below its own entry
const headings = [...document.querySelectorAll('.typo3-styleguide-technical-headline[id], [data-sg-anchor][id]')]
  .filter(heading => !active || titleOf(heading).toLowerCase() !== active.textContent.trim().toLowerCase());

if (active && headings.length >= MIN_ENTRIES) {
  const list = document.createElement('ul');
  list.className = 'typo3-styleguide-frame__sidenav-anchors';

  // Two element documentations on one page have the same parts, so their entries carry what tells them apart. That is the part in
  // brackets of the element name ("Hero Header (Startseite)") as long as it is unique, else the whole name
  const titles = headings.map(titleOf);
  const contexts = headings.map(heading => heading.getAttribute('data-sg-anchor') ?? '');
  const short = contexts.map(context => context.match(/\(([^)]+)\)\s*$/)?.[1] ?? context);
  const labelOf = i => {
    const sameTitle = titles.map((title, j) => (title === titles[i] ? j : -1)).filter(j => j >= 0);
    if (sameTitle.length < 2 || contexts[i] === '') return titles[i];
    const unique = new Set(sameTitle.map(j => short[j])).size === sameTitle.length;

    return `${titles[i]} ${unique ? short[i] : contexts[i]}`;
  };

  const links = headings.map((heading, i) => {
    const item = document.createElement('li');

    const link = document.createElement('a');
    link.className = 'typo3-styleguide-frame__sidenav-anchor';
    link.href = `#${heading.id}`;
    link.textContent = labelOf(i);

    item.append(link);
    list.append(item);

    return link;
  });

  active.after(list);

  const aside = active.closest('.typo3-styleguide-frame__aside');
  let current = null;

  const update = () => {
    // A page that fits the screen does not scroll, so its first heading is the current one and not the last
    const scrollable = document.documentElement.scrollHeight > window.innerHeight + 2;
    const atEnd = scrollable && window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 2;
    let next = atEnd ? headings.length - 1 : -1;
    if (!atEnd) headings.forEach((heading, i) => { if (heading.getBoundingClientRect().top <= OFFSET) next = i; });

    if (next === current) return;
    current = next;

    links.forEach((link, i) => {
      if (i === next) link.setAttribute('aria-current', 'location');
      else link.removeAttribute('aria-current');
    });

    // A long menu scrolls itself, so the marked entry stays visible
    const marked = links[next];
    if (aside && marked) {
      const box = aside.getBoundingClientRect();
      const rect = marked.getBoundingClientRect();
      if (rect.top < box.top) aside.scrollTop -= box.top - rect.top;
      else if (rect.bottom > box.bottom) aside.scrollTop += rect.bottom - box.bottom;
    }
  };

  let waiting = false;
  const onScroll = () => {
    if (waiting) return;
    waiting = true;
    requestAnimationFrame(() => {
      waiting = false;
      update();
    });
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll);
  update();
}

// The filter of the side menu. Groups with a match open while the visitor types and return to their state when the field is empty
const filter = document.querySelector('[data-sg-sidenav-filter]');
if (filter) {
  filter.hidden = false;

  const input = filter.querySelector('[data-sg-sidenav-filter-input]');
  const status = filter.querySelector('[data-sg-sidenav-filter-status]');
  const nav = filter.closest('nav');
  const groups = [...nav.querySelectorAll('[data-sg-sidenav-group]')];
  const links = [...nav.querySelectorAll('.typo3-styleguide-frame__sidenav-list a.typo3-styleguide-frame__sidenav-link')];
  const wasOpen = new Map(groups.map(group => [group, group.open]));
  const textOf = element => element.textContent.trim().toLowerCase();

  const apply = () => {
    const query = input.value.trim().toLowerCase();

    if (query === '') {
      nav.querySelectorAll('[data-sg-sidenav-entry]').forEach(entry => { entry.hidden = false; });
      groups.forEach(group => { group.open = wasOpen.get(group); });
      status.textContent = '';
      return;
    }

    let found = 0;
    groups.forEach(group => {
      const groupMatches = textOf(group.querySelector('summary span')).includes(query);
      let visible = 0;
      group.querySelectorAll('[data-sg-sidenav-entry]').forEach(entry => {
        const overview = entry.hasAttribute('data-sg-sidenav-overview');
        const match = groupMatches || (!overview && textOf(entry).includes(query));
        entry.hidden = !match;
        if (match && !overview) visible++;
      });
      group.closest('[data-sg-sidenav-entry]').hidden = visible === 0;
      group.open = visible > 0;
      found += visible;
    });

    links.filter(link => !link.closest('[data-sg-sidenav-group]')).forEach(link => {
      const match = textOf(link).includes(query);
      link.closest('[data-sg-sidenav-entry]').hidden = !match;
      if (match) found++;
    });

    status.textContent = found === 0 ? filter.dataset.none : '';
  };

  input.addEventListener('input', apply);
  input.addEventListener('keydown', event => {
    if (event.key === 'Escape' && input.value !== '') {
      input.value = '';
      apply();
    }
  });
}

// On a narrow screen the menu starts closed behind its summary, on a wide one it is always open
const toggle = document.querySelector('[data-sg-sidenav-toggle]');
if (toggle && window.matchMedia) {
  const narrow = window.matchMedia('(max-width: 768px)');
  const sync = () => { toggle.open = !narrow.matches; };
  sync();
  narrow.addEventListener('change', sync);
}
