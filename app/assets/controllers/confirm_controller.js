// confirm_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['dialog', 'title', 'message'];
    static values = { url: String, method: String };

    open({ params: { url, title, message = '' , method = 'POST' } }) {
        this.urlValue = url;
        this.methodValue = method;
        this.titleTarget.textContent = title;
        this.messageTarget.textContent = message;
        this.dialogTarget.classList.add('active');
    }

    confirm() {
        const form = document.createElement('form');
        form.method = 'post';
        form.action = this.urlValue;
        document.body.appendChild(form);
        form.submit();
    }

    cancel() {
        this.dialogTarget.classList.remove('active');
    }
}
