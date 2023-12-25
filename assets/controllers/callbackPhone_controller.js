import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect() {
        //console.log('Hello Stimulus!', this.element);
    }
    onSubmit(event) {
        event.preventDefault();
        const formData = new FormData(this.element);
        //console.log(formData.get('form_captcha'));
        console.log(formData);
        this.element.submit();
    }
}
