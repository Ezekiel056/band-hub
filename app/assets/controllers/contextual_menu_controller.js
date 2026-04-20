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
    this.menuClickHandler = (e) => {
      if (e.target !== this.menuTarget) {
        this.close();
      }
    };

    document.addEventListener('click', this.outsideClickHandler);
    this.menuTarget.addEventListener('click', this.menuClickHandler);
  }

  disconnect() {
    document.removeEventListener('click', this.outsideClickHandler);
    this.menuTarget.removeEventListener('click', this.menuClickHandler);
  }
}
