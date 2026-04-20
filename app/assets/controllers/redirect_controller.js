// redirect_controller.js
import { Controller } from '@hotwired/stimulus';
import { visit } from '@hotwired/turbo';

export default class extends Controller {
  static values = { url: String }

  connect() {
    if (document.documentElement.hasAttribute('data-turbo-preview')) return;
    visit(this.urlValue);
  }
}
