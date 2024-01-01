import { Controller } from '@hotwired/stimulus';

export default class PhoenixOffcanvasController extends Controller {
    static targets = ["offcanvas", "backdrop", "scroll", "faq", "faqShow", "toggle", "close"];

    connect() {
        this.toggleTargets.forEach(toggle => {
            toggle.addEventListener('click', () => {
                this.showOffcanvas(toggle);
            });
        });

        this.closeTargets.forEach(close => {
            close.addEventListener('click', () => {
                this.hideOffcanvas();
            });
        });

        if (this.backdropTarget) {
            this.backdropTarget.addEventListener('click', () => {
                this.hideOffcanvas();
            });
        }
    }

    showOffcanvas(toggleEl) {
        const offcanvasTarget = toggleEl.getAttribute('data-phoenix-target');
        const offcanvasEl = this.element.querySelector(offcanvasTarget);

        offcanvasEl.classList.add('show');
        if (!this.hasScrollTarget) {
            document.body.style.overflow = 'hidden';
        }

        // Handle FAQ specific logic
        if (this.hasFaqTarget && this.faqShowTarget.classList.contains('show')) {
            this.faqTarget.classList.add('newFaq');
        }
    }

    hideOffcanvas() {
        this.offcanvasTarget.classList.remove('show');
        document.body.style.removeProperty('overflow');
    }
}
