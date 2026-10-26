// The width buttons are hidden in the markup, so without JavaScript the previews simply use the full width
document.querySelectorAll('[data-sg-preview]').forEach(root => {
  const group = root.querySelector('[data-sg-preview-widths]');
  if (!group) return;

  const canvases = root.querySelectorAll('[data-sg-preview-canvas]');
  const buttons = group.querySelectorAll('button[data-width]');

  const apply = (selected) => {
    buttons.forEach(button => button.setAttribute('aria-pressed', String(button === selected)));
    canvases.forEach(canvas => {
      canvas.style.width = `${selected.dataset.width}px`;
    });
  };

  buttons.forEach(button => button.addEventListener('click', () => apply(button)));
  group.hidden = false;
  apply(group.querySelector('button[aria-pressed="true"]') ?? buttons[0]);
});
