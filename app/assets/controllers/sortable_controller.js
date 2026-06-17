import { Controller } from '@hotwired/stimulus';
import Sortable from 'sortablejs';

export default class extends Controller {
  static values = { url: String };

  connect() {
    this.sortable = Sortable.create(this.element, {
      handle: '[data-sortable-handle]', // element qui déclenche l'action
      animation: 150,
      onEnd: () => this.#save(),
    });
  }

  disconnect() {
    this.sortable.destroy();
  }

  #save() {
    const positions = [...this.element.querySelectorAll('[data-id]')].map(
      (el, index) => ({ id: parseInt(el.dataset.id), position: index })
    );

    fetch(this.urlValue, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ positions }),
    });
  }
}
