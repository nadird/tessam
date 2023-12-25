import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        //console.log('analyticsDataToForm_controller');
        this.sendLocalStorageDataToForm();
    }

    sendLocalStorageDataToForm() {
        // Retrieve the data from localStorage
        const storedData = localStorage.getItem('data');
        if (!storedData) return;

        const data = JSON.parse(storedData);

        // Append a hidden input for urlParams
        this.appendHiddenInput('urlParams', JSON.stringify(data.urlParams));

        // Append a hidden input for pageUrl with all its data as a JSON string
        this.appendHiddenInput('pageUrl', JSON.stringify(data.pageUrl));
    }

    appendHiddenInput(name, value) {
        const hiddenInput = this.createElement('input', {
            type: 'hidden',
            name: name,
            value: value
        });
        this.element.appendChild(hiddenInput);
    }

    createElement(tag, attributes) {
        const element = document.createElement(tag);
        for (const key in attributes) {
            element[key] = attributes[key];
        }
        return element;
    }
}
