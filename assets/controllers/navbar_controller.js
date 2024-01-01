import { Controller } from '@hotwired/stimulus';

export default class NavbarController extends Controller {
    static targets = ["navbarVerticalCollapse", "navbarVerticalToggle", "activeNavLink"];

    connect() {
        this.setDocumentMinHeight();
        window.addEventListener('resize', this.resizeHandler);
        //this.initializeCollapseState();
    }

    disconnect() {
        window.removeEventListener('resize', this.resizeHandler);
    }

    toggleCollapse(event) {
        event.preventDefault();
        this.navbarVerticalToggleTarget.blur();
        document.documentElement.classList.toggle('navbar-vertical-collapsed');

        const isCollapsed = this.isNavbarVerticalCollapsed();
        this.setItemToStore('phoenixIsNavbarVerticalCollapsed', !isCollapsed);
        this.dispatch('navbar-vertical-toggle');
        this.setDocumentMinHeight();

    }

    navbarVerticalToggleHandler() {
        this.setDocumentMinHeight();
    }

    resizeHandler = () => {
        this.setDocumentMinHeight();
    }

    setDocumentMinHeight() {
        const bodyHeight = document.body.offsetHeight;
        const navbarVerticalHeight = this.navbarVerticalCollapseTarget.offsetHeight;

        if (document.documentElement.classList.contains('navbar-vertical-collapsed') && bodyHeight < navbarVerticalHeight) {
            document.documentElement.style.minHeight = `${navbarVerticalHeight}px`;
        } else {
            document.documentElement.removeAttribute('style');
        }
    }

    /*
    initializeCollapseState() {

            if (this.activeNavLinkTarget && !this.isNavbarVerticalCollapsed()) {
                this.activeNavLinkTarget.scrollIntoView({behavior: 'smooth'});
            }
    }
     */

    isNavbarVerticalCollapsed() {
        return this.getItemFromStore('phoenixIsNavbarVerticalCollapsed', false);
    }

    getItemFromStore(key, defaultValue) {
        const item = localStorage.getItem(key);
        if (item) {
            return JSON.parse(item);
        }
        return defaultValue;
    }

    setItemToStore(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
    }
}
