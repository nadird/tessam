import { Controller } from '@hotwired/stimulus';

export default class ModalController extends Controller {
    connect() {
        this.element.addEventListener('shown.bs.modal', () => {
            const autofocusElements = this.element.querySelectorAll('[autofocus]');
            autofocusElements.forEach(el => {
                el.focus();
            });
        });
    }
}