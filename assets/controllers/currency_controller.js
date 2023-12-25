
import { Controller } from '@hotwired/stimulus';
export default class extends Controller {
    connect() {
        this.element.querySelectorAll("input[data-type='currency']").forEach(input => {
            input.addEventListener('keyup', () => {
                this.formatCurrency(input);
            });

            input.addEventListener('blur', () => {
                this.formatCurrency(input, 'blur');
            });
        });
    }

    formatNumber(n) {
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    formatCurrency(input, blur) {
        let inputVal = input.value;
        if (inputVal === "") { return; }

        let originalLen = inputVal.length;
        let caretPos = input.selectionStart;

        // Replace any commas with dots for internal processing
        inputVal = inputVal.replace(/,/g, '.');

        if (inputVal.indexOf(".") >= 0) {
            let decimalPos = inputVal.indexOf(".");

            let leftSide = inputVal.substring(0, decimalPos);
            let rightSide = inputVal.substring(decimalPos);

            leftSide = this.formatNumber(leftSide);
            rightSide = this.formatNumber(rightSide);

            if (blur === "blur") {
                rightSide += "00";
            }

            // Limit decimal to only 2 digits and use comma
            rightSide = rightSide.substring(0, 2);
            inputVal = leftSide + "," + rightSide;

        } else {
            inputVal = this.formatNumber(inputVal);

            if (blur === "blur") {
                inputVal += ",00";
            }
        }

        input.value = inputVal;

        let updatedLen = inputVal.length;
        caretPos = updatedLen - originalLen + caretPos;
        input.setSelectionRange(caretPos, caretPos);
    }


}
