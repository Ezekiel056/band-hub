// options_menu_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['menu'];

  toggle() {
    this.menuTarget.classList.toggle('hidden');
  }

  close() {
    this.menuTarget.classList.add('hidden');
  }

  connect() {
    this.outsideClickHandler = (e) => {
      if (!this.element.contains(e.target)) {
        this.close();
      }
    };
    document.addEventListener('click', this.outsideClickHandler);
  }

  disconnect() {
    document.removeEventListener('click', this.outsideClickHandler);
  }
}
