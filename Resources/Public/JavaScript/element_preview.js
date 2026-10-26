// The preview frames are same-origin pages. The height follows the content once the frame has loaded, whenever the width changes
// and whenever the content grows later, for example by images or fonts that load late
const MAX_REFITS = 20;

// The frame has the width of the chosen device, so the page inside reacts like on that device, and is scaled down when the column is narrower
const place = (frame) => {
  const stage = frame.parentElement;
  const width = Number(frame.dataset.sgWidth);
  if (!width) return;

  const scale = Math.min(1, stage.clientWidth / width);
  const height = `${Math.round(frame.offsetHeight * scale)}px`;
  stage.classList.add('is-scaled');
  frame.style.width = `${width}px`;
  frame.style.transform = `translateX(-50%) scale(${scale})`;
  if (stage.style.height !== height) stage.style.height = height;

  const note = frame.closest('figure')?.querySelector('[data-sg-preview-scale]');
  if (note) {
    note.hidden = scale >= 1;
    note.textContent = scale < 1 ? ` \u00b7 ${note.dataset.label} ${Math.round(scale * 100)} %` : '';
  }
};

const fit = (frame) => {
  // A frame with a fixed height, such as the preview of a whole page, scrolls inside instead
  if (frame.dataset.sgFixedHeight) {
    place(frame);

    return false;
  }

  try {
    const doc = frame.contentDocument;
    if (!doc?.documentElement) return false;
    // The frame's own border is not part of the document height
    const border = frame.offsetHeight - frame.clientHeight;
    const previous = frame.style.height;
    // With the frame at no height the page shows how high its content is. Measured in the frame, a page with `height: 100%` on html
    // and body would only report the height of the frame itself. The height is set again before anything is painted
    frame.style.height = '0px';
    const height = `${Math.ceil(doc.documentElement.scrollHeight) + border}px`;
    const changed = previous !== height;
    frame.style.height = height;
    // The frame is as high as its content, a scrollbar would only show a rounding difference
    doc.documentElement.style.overflow = 'hidden';
    place(frame);

    return changed;
  } catch {
    // Not readable (for example another origin), the default height stays
    return false;
  }
};

const watch = (frame) => {
  fit(frame);
  if (frame.dataset.sgFixedHeight) return;

  const doc = frame.contentDocument;
  doc?.fonts?.ready.then(() => fit(frame));
  if (!doc?.body) return;

  // The content can grow after the load: images and fonts that arrive late, and above all what the visitor opens in the frame,
  // such as an accordion. A change of the page and a resize of the body both trigger a new fit.
  // Content that is as high as the frame (vh units) could grow with every fit, so the fits are limited until the visitor does something
  let refits = 0;
  let waiting = false;
  const refit = () => {
    if (waiting) return;
    waiting = true;
    requestAnimationFrame(() => {
      waiting = false;
      if (refits < MAX_REFITS && fit(frame)) refits++;
    });
  };
  const interaction = () => {
    refits = 0;
    // An opening element animates, so the height is read again when the animation is over
    [50, 350].forEach(delay => setTimeout(refit, delay));
  };

  if (window.ResizeObserver) new ResizeObserver(refit).observe(doc.body);
  if (window.MutationObserver) new MutationObserver(refit).observe(doc.documentElement, { subtree: true, childList: true, attributes: true });
  ['click', 'keyup', 'toggle', 'transitionend', 'animationend'].forEach(type => doc.addEventListener(type, type === 'click' || type === 'keyup' ? interaction : refit, true));
};

document.querySelectorAll('[data-sg-preview]').forEach(root => {
  const frames = root.querySelectorAll('[data-sg-preview-frame]');

  frames.forEach(frame => {
    frame.addEventListener('load', () => watch(frame));
    if (window.ResizeObserver) new ResizeObserver(() => place(frame)).observe(frame.parentElement);
  });

  // The width buttons are hidden in the markup, so without JavaScript the previews simply use the full width
  const group = root.querySelector('[data-sg-preview-widths]');
  if (!group) return;

  const buttons = group.querySelectorAll('button[data-width]');

  const apply = (selected) => {
    buttons.forEach(button => button.setAttribute('aria-pressed', String(button === selected)));
    frames.forEach(frame => {
      frame.dataset.sgWidth = selected.dataset.width;
      place(frame);
      requestAnimationFrame(() => fit(frame));
    });
  };

  buttons.forEach(button => button.addEventListener('click', () => apply(button)));
  group.hidden = false;
  apply(group.querySelector('button[aria-pressed="true"]') ?? buttons[0]);
});
