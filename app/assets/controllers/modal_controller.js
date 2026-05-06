import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

  open({ params: { url } }) {
    document.querySelector('turbo-frame#modal').src = url;
  }

  close() {
    document.querySelector('turbo-frame#modal').innerHTML = '';
  }
}
