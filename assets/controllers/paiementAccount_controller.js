import { Controller } from '@hotwired/stimulus';


export default class extends Controller {



    connect() {
        let Type = document.getElementById('order_paiement_type');
        Type.addEventListener('change', this.handleChangeType.bind(this));

        let account = document.getElementById('order_paiement_compte');
        account.hidden = true;

    }
    handleChangeType(e) {
        const paiementTypeId = e.target.value;
        const url = "/public/api/account/paiement/" + paiementTypeId;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const products = document.getElementById('order_paiement_compte');
                products.innerHTML = "";
                data[0].forEach(product => {
                    const option = document.createElement("option");
                    option.value = product.id;
                    option.textContent = product.name;
                    products.appendChild(option);
                });
                let account = document.getElementById('order_paiement_compte');
                account.hidden = false;
            });

    }




}
