// assets/controllers/toast_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  connect() {
    setTimeout(() => {
      this.element.classList.add('toast-hidden');
    }, 3000);
  }
}
