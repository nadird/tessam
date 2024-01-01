import { Controller } from '@hotwired/stimulus';



export default class extends Controller {
    connect() {
        this.priceHt = document.getElementById('wizard_new_product_form_prix_priceHt');
        this.taxRate = document.getElementById('wizard_new_product_form_prix_tax');
        this.supplyPriceHt = document.getElementById('wizard_new_product_form_prix_supplyPriceHt');
        this.wholesalePriceHt = document.getElementById('wizard_new_product_form_prix_wholeSalePriceHt');
        this.priceTtc = document.getElementById('wizard_new_product_form_prix_priceTtc');
        this.priceWholesaleTtc = document.getElementById('wizard_new_product_form_prix_wholeSalePriceTtc');
        this.priceSupplyTtc = document.getElementById('wizard_new_product_form_prix_supplyPriceTtc');
        this.priceHt.addEventListener('change', this.handlePriceChange.bind(this));
        this.taxRate.addEventListener('change', this.handleTaxChange.bind(this));
        this.supplyPriceHt.addEventListener('change', this.handlePriceChange.bind(this));
        this.wholesalePriceHt.addEventListener('change', this.handlePriceChange.bind(this));
    }

    handlePriceChange() {
        this.updatePrices();
    }

    handleTaxChange() {
        this.updatePrices();
    }

    updatePrices() {
        const taxRateValue = this.taxRate.value ? parseFloat((parseFloat(this.taxRate.value) / 100).toFixed(2)) : 0;
        this.priceTtc.value = this.calculateTtc(this.priceHt.value, taxRateValue);
        this.priceWholesaleTtc.value = this.calculateTtc(this.wholesalePriceHt.value, taxRateValue);
        this.priceSupplyTtc.value = this.calculateTtc(this.supplyPriceHt.value, taxRateValue);
    }

    calculateTtc(htValue, taxRate) {
        if (!htValue) return 0;
        return parseFloat(htValue) * (1 + taxRate);
    }
}
