import { Controller } from '@hotwired/stimulus';


export default class extends Controller {



    connect() {
        let project = document.getElementById('bien_additional_project');
        project.addEventListener('change', this.handleChangeType.bind(this));

        let building = document.getElementById('bien_additional_Building');
        building.addEventListener('change', this.handleChangeBuilding.bind(this));
        let biens = document.getElementById('bien_additional_bien');
        building.hidden = true;
        biens.hidden = true;
    }
    handleChangeType(e) {
        const projectId = e.target.value;
        const url = "/public/api/bien/project/building/" + projectId;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const building = document.getElementById('bien_additional_Building');
                const biens = document.getElementById('bien_additional_bien');
                building.innerHTML = "";
                console.log('data buildings',data.buildings);
                data.buildings.forEach(product => {
                    const option = document.createElement("option");
                    option.value = product.id;
                    option.textContent = product.name;
                    building.appendChild(option);
                });
                building.hidden = false;
                biens.innerHTML = "";
                data.biens.forEach(product => {
                    const option = document.createElement("option");
                    option.value = product.id;
                    option.textContent = product.name;
                    biens.appendChild(option);
                }
                );
                biens.hidden = false;
            });

    }
    handleChangeBuilding(e) {
        const buildingId = e.target.value;
        const url = "/public/api/bien/buidling/bien/" + buildingId;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const biens = document.getElementById('bien_additional_bien');
                biens.innerHTML = "";
                data.biens.forEach(product => {
                        const option = document.createElement("option");
                        option.value = product.id;
                        option.textContent = product.name;
                        biens.appendChild(option);
                    }
                );
                biens.hidden = false;
            });

    }




}
