import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    connect() {
        this.element.addEventListener('change', this.handleChange.bind(this));
        const orderType = document.getElementById('order_customer_orderType');
        orderType.addEventListener('change', this.handleOrderChange.bind(this));
        const credit = document.getElementById('order_customer_credit');
        const bank = document.getElementById('order_customer_bank');
        credit.classList.add('d-none');
        bank.classList.add('d-none');
    }
    handleChange() {
        const bienSelect = document.getElementById('order_customer_bien');
        const finalPriceField = document.getElementById('order_customer_finalPrice');
        fetch('/public/api/bien/getFinalPrice/' + bienSelect.value)
            .then(response => response.json())
            .then(data => {
                if (data.finalPrice !== undefined) {
                    finalPriceField.value = data.finalPrice;
                }
            })
            .catch(error => console.error('Error:', error));
    }
    handleOrderChange(e) {
        const credit = document.getElementById('order_customer_credit');
        const bank = document.getElementById('order_customer_bank');
        fetch('/public/api/account/credit/' + e.target.value)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if(data === true){
                    credit.classList.remove('d-none');
                    bank.classList.remove('d-none');
                }else{
                    credit.classList.add('d-none');
                    bank.classList.add('d-none');
                }
            })
            .catch(error => console.error('Error:', error));
    }


}
function formatPrice(value) {
    return new Intl.NumberFormat('fr-FR', { style: 'decimal', minimumFractionDigits: 2 }).format(value);
}
