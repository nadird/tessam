import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['tab', 'pane','nextButton', 'prevButton'];

    connect() {
        this.showCurrentTab();
    }
    handleNameChange(event) {
        console.log(event.target.value);
        this.form.submit();

    }
    handleTabClick(event) {
        event.preventDefault();
        let stepIndex = this.tabTargets.indexOf(event.currentTarget);
        this.changeStep(this.getCurrentStep(), stepIndex);
    }

    showCurrentTab() {
        let currentStep = this.getCurrentStep();
        this.showTab(currentStep);
    }
    nextStep(event) {
        event.preventDefault();
        let currentStep = this.getCurrentStep();
        this.changeStep(currentStep, currentStep + 1);

    }

    prevStep(event) {
        event.preventDefault();
        let currentStep = this.getCurrentStep();
        this.changeStep(currentStep, currentStep - 1);
    }

    changeStep(currentStep, newStep) {

        // Mark all tabs before the new step as 'done'
        for (let i = 0; i < newStep; i++) {
            this.tabTargets[i].classList.add('done');
        }

        // If moving forward, mark the current step as 'completed'
        if (newStep > currentStep) {
            this.tabTargets[currentStep].classList.add('completed');
        }

        // Remove 'completed' from the new active step in case we are moving backwards
        this.tabTargets[newStep].classList.remove('completed', 'done');

        // Hide the current tab and show the new tab
        this.hideTab(currentStep);
        this.showTab(newStep);
    }

    getCurrentStep() {
        return this.tabTargets.findIndex(tab => tab.classList.contains('active'));
    }

    hideTab(step) {
        this.tabTargets[step].classList.remove('active');
        this.paneTargets[step].classList.remove('show', 'active');
    }

    showTab(step) {
        this.tabTargets[step].classList.add('active');
        this.paneTargets[step].classList.add('show', 'active');

        if (step > 0) {
            this.prevButtonTarget.classList.remove('d-none');
        } else {
            this.prevButtonTarget.classList.add('d-none');
        }

        // Show 'Next' button except on the last step
        if (step < this.tabTargets.length - 1) {
            this.nextButtonTarget.classList.remove('d-none');
        } else {
            this.nextButtonTarget.classList.add('d-none');
        }
    }
}
