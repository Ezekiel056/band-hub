const trigger = document.querySelector('.trigger-arrow');
const selector = trigger?.closest('.band-selector');

document.addEventListener('click', () => {
  selector?.removeAttribute('open');
});

trigger?.addEventListener('click', (event) => {
  event.stopPropagation();
  selector?.toggleAttribute('open');
});


