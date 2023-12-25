import { Controller } from '@hotwired/stimulus';


export default class extends Controller {



    connect() {
        let wilaya = document.getElementById('c_project_wilaya');
        wilaya.addEventListener('change', this.handleChange.bind(this));

    }
    handleChange(e) {
        const wilayaId = e.target.value;
        const url = "/public/api/addresses/commune/wilaya/" + wilayaId;
        fetch(url)
            .then(response => response.json())
            .then(data => {

                const communes = document.getElementById('c_project_commune');
                communes.innerHTML = "";
                data[0].forEach(commune => {
                    const option = document.createElement("option");
                    option.value = commune.id;
                    option.textContent = commune.name;
                    communes.appendChild(option);
                });
            });

    }
     loadCommunes = (wilayaId) => {


    }


}
