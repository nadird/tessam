import { Controller } from '@hotwired/stimulus';


export default class extends Controller {



    connect() {
        let Category = document.getElementById('order_additionnal_products_form_additionnalCategory');
        Category.addEventListener('change', this.handleChange.bind(this));

        let products = document.getElementById('order_additionnal_products_form_additionalProduct');
        products.hidden = true;
        products.addEventListener('change', this.handleProductChange.bind(this));

    }
    handleChange(e) {
        const categoryId = e.target.value;
        const url = "/public/api/additional/category/" + categoryId;
        fetch(url)
            .then(response => response.json())
            .then(data => {

                const products = document.getElementById('order_additionnal_products_form_additionalProduct');
                products.innerHTML = "";
                data[0].forEach(product => {
                    const option = document.createElement("option");
                    option.value = product.id;
                    option.textContent = product.name;
                    option.dataset.price = product.price;
                    products.appendChild(option);
                });
                products.hidden = false;
                const price = document.getElementById('order_additionnal_products_form_price');
                price.value = products.selectedOptions[0].dataset.price;
            });

    }
    handleProductChange(e) {
        const productPrice = e.target.options[e.target.selectedIndex].dataset.price;
        const price = document.getElementById('order_additionnal_products_form_price');
        price.value = productPrice;
    }



}
