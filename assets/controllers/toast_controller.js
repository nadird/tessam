import { Controller } from '@hotwired/stimulus';


export default class extends Controller {

    static targets = ["toast"];

    connect() {
        if(this.hasToastTarget) {
            // Get the toast element and set a timer to close it after 5 seconds
            const toast = this.toastTarget;
            const toastClose = toast.querySelector(".toast-close");
           // console.log(toastClose, toast);
            setTimeout(() => {
                this.closeToast(toast);
            }, 5000); // Adjust the timer duration (in milliseconds) as needed
        }
    }

    closeToast(toast) {
        // Close the toast by removing the 'show' class
        toast.classList.remove("show");
    }
}
