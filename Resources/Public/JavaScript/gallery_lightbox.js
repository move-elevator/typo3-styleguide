// The large view of a gallery image. Without script, or without <dialog>, the link around the image opens the original file instead
(() => {
  if (typeof HTMLDialogElement === 'undefined') return;

  const ROWS = ['copyright', 'usageRights', 'licenseUntil', 'source'];

  const init = (dialog) => {
    const items = [...(dialog.closest('.typo3-styleguide__frame')?.querySelectorAll('[data-sg-lightbox-item]') ?? [])];
    if (!items.length) return;

    const image = dialog.querySelector('[data-sg-lightbox-image]');
    const title = dialog.querySelector('[data-sg-lightbox-title]');
    const description = dialog.querySelector('[data-sg-lightbox-description]');
    const download = dialog.querySelector('[data-sg-lightbox-download]');
    const previous = dialog.querySelector('[data-sg-lightbox-prev]');
    const next = dialog.querySelector('[data-sg-lightbox-next]');
    let current = 0;
    let opener = null;

    const show = (index) => {
      current = (index + items.length) % items.length;
      const data = items[current].dataset;

      image.src = data.large;
      image.alt = data.alt ?? '';
      title.textContent = data.title ?? '';
      title.hidden = !data.title;
      description.textContent = data.description ?? '';
      description.hidden = !data.description;
      ROWS.forEach((row) => {
        const element = dialog.querySelector(`[data-sg-lightbox-row="${row}"]`);
        element.querySelector('dd').textContent = data[row] ?? '';
        element.hidden = !data[row];
      });
      if (download) download.href = items[current].href;
      // Hidden by visibility, so the image keeps its column
      previous.style.visibility = next.style.visibility = items.length < 2 ? 'hidden' : '';
    };

    items.forEach((item, index) => {
      item.addEventListener('click', (event) => {
        event.preventDefault();
        opener = item;
        show(index);
        dialog.showModal();
        dialog.querySelector('[data-sg-lightbox-close]').focus();
      });
    });

    previous.addEventListener('click', () => show(current - 1));
    next.addEventListener('click', () => show(current + 1));
    dialog.querySelector('[data-sg-lightbox-close]').addEventListener('click', () => dialog.close());
    // A click on the backdrop reaches the dialog itself, a click inside reaches a child
    dialog.addEventListener('click', (event) => {
      if (event.target === dialog) dialog.close();
    });
    dialog.addEventListener('keydown', (event) => {
      if (items.length < 2) return;
      if (event.key === 'ArrowLeft') show(current - 1);
      if (event.key === 'ArrowRight') show(current + 1);
    });
    dialog.addEventListener('close', () => {
      image.removeAttribute('src');
      opener?.focus();
    });
  };

  document.querySelectorAll('[data-sg-lightbox]').forEach(init);
})();
