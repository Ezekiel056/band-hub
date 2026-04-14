import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  close() {
    document.querySelector('turbo-frame#modal').innerHTML = '';
  }
}
