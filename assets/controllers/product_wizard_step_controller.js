import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['tab', 'pane','nextButton', 'prevButton'];

    connect() {
     console.log('connected');
    }

}
