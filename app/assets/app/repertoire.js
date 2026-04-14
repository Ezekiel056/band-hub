document.addEventListener('turbo:load', () => {
  const active = document.querySelector('.tab-item.active');
  if (active) {
    active.scrollIntoView({ behavior: 'instant', block: 'nearest', inline: 'start' });
  }
});
