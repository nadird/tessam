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
        //console.log('Hello from lead_contact_affectation_controller.js');
        //console.log(this.element);
        this.element.addEventListener('change', this.handleChange.bind(this));
    }
    handleChange() {
       // console.log('Change handled');
        this.element.submit();
    }

}
