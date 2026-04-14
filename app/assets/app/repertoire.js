document.addEventListener('turbo:load', () => {
  const active = document.querySelector('.tab-item.active');
  if (active) {
    active.scrollIntoView({ behavior: 'instant', block: 'nearest', inline: 'start' });
  }
});

document.addEventListener('turbo:load', () => {
  const input = document.querySelector('[data-filter-input]');
  if (!input) return;

  input.addEventListener('input', () => {
    const search = input.value.toLowerCase();
    document.querySelectorAll('[data-filter-song]').forEach(song => {
      song.classList.toggle('hidden', !(song.dataset.title.toLowerCase().includes(search) || song.dataset.artist.toLowerCase().includes(search)));
    });
  });
});
