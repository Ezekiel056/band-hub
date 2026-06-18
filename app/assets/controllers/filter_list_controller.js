import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['input', 'item'];

  filter() {
    const search = this.inputTarget.value.toLowerCase();
    this.itemTargets.forEach(item => {
      item.classList.toggle('hidden', !item.dataset.label.toLowerCase().includes(search));
    });
  }
}