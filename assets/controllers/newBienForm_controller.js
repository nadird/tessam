import { Controller } from '@hotwired/stimulus';
import {createElement} from "../phenixVendor/fullcalendar/main";


export default class extends Controller {



    connect() {
        let project = document.getElementById('cbien_project');
        project.addEventListener('change', this.handleChangeType.bind(this));
        let types = document.getElementById('cbien_type');
        types.addEventListener('change', this.handleChangeTypeFormat.bind(this));
        let formatBien = document.getElementById('cbien_format');
        formatBien.addEventListener('change', this.handleChangeFormat.bind(this));


    }
    handleChangeType(e) {
        const projectId = e.target.value;
        const url = "/public/api/bien/project/all/" + projectId;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const building = document.getElementById('cbien_building');
                const formats = document.getElementById('cbien_format');
                const types = document.getElementById('cbien_type');
                building.innerHTML = "";
                formats.innerHTML = "";
                types.innerHTML = "";
                console.log('data buildings',data.buildings, 'data formats',data.formats, 'data types',data.types);
                data.buildings.forEach(product => {
                    const option = document.createElement("option");
                    option.value = product.id;
                    option.textContent = product.name;
                    building.appendChild(option);
                });
                data.formats.forEach(product => {
                    const option = document.createElement("option");
                    option.value = product.id;
                    option.textContent = product.name;
                    formats.appendChild(option);
                });
                data.types.forEach(product => {
                    const option = document.createElement("option");
                    option.value = product.id;
                    option.textContent = product.name;
                    types.appendChild(option);
                });
            });

    }
    handleChangeTypeFormat(e) {
        const type = e.target.value;
        const projectId = document.getElementById('cbien_project').value;
        const url = "/public/api/bien/format/type/project/" + projectId+"/"+type;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const formatsB = document.getElementById('cbien_format');
                formatsB.innerHTML = "";
                data.formatsB.forEach(product => {
                        const option = document.createElement("option");
                        option.value = product.id;
                        option.textContent = product.name;
                        formatsB.appendChild(option);
                    }
                );
            });

    }

    handleChangeFormat(e) {
        const formatId = e.target.value;
        const url = "/public/api/bien/format/infos/" + formatId;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const area= document.getElementById('cbien_exactArea');
                const price= document.getElementById('cbien_finalPrice');
                area.value = data.formatBien.area;
                price.value = data.formatBien.price;
                //console.log('product',data.formatBien.additional);
                let BienAdditionals = data.formatBien.additional;
                let additionals= document.getElementById('additionalBiens');
                additionals.innerHTML = "";
                additionals.classList.remove('d-none');

                BienAdditionals.forEach(product => {
                        const newdiv = document.createElement("div");
                            newdiv.classList.add('form-check');
                            newdiv.classList.add('form-switch');
                        const newinput = document.createElement("input");
                        newinput.value = product.number;
                        newinput.name = 'Additional_'+product.id;
                        newinput.id = product.slug + "_" + product.number;
                        newinput.type = 'checkbox';
                        newinput.dataset.number = product.number;
                        newinput.classList.add('form-check-input');
                        newdiv.appendChild(newinput);
                        const label = document.createElement("label");
                        label.textContent = "Créer "+product.number + " " + product.name;
                        label.htmlFor = product.name + "_" + product.number;
                        label.classList.add('form-check-label');
                        newdiv.appendChild(label);

                    additionals.appendChild(newdiv);
                    }
                );
                console.log('data',data.formatBien.additionals);
            });

    }




}
