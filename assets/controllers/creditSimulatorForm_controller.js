import {Controller} from '@hotwired/stimulus'

export default class extends Controller {

    connect() {
        let second = document.getElementById('credit_simulator_form_second');
        second.addEventListener('change', this.handleChange.bind(this));
        let secondrevenue = document.getElementById('credit_simulator_form_revenueSecond');
        secondrevenue.value = 0;
        secondrevenue.disabled = true;
    }
    handleChange(e) {
        const value = e.target.value;
        let secondrevenue = document.getElementById('credit_simulator_form_revenueSecond');
        secondrevenue.disabled = !e.target.checked;
    }


}