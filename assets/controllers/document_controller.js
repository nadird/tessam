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
        const cookies = JSON.parse(this.element.dataset.cookiesValue);
        const url = this.element.dataset.urlValue;
        console.log(url);
        this.checkAndSetLocalStorage(cookies,url);
    }
    checkAndSetLocalStorage(cookies,url) {
        // Assuming cookies is an object where keys are cookie names
        if (cookies.EVSSID && !localStorage.getItem('EVSSID')) {
            localStorage.setItem('EVSSID', cookies.EVSSID);
        }else if( localStorage.getItem('EVSSID')&& cookies.EVSSID && localStorage.getItem('EVSSID') !== cookies.EVSSID) {
            this.updateEVSSIDOnServer(localStorage.getItem('EVSSID'),cookies.EVSSID,url)
            console.log(localStorage.getItem('EVSSID'),cookies.EVSSID);
        }
    }
    updateEVSSIDOnServer(newEVSSID,EVSSID,url) {
        fetch(url+'/internal/api/changeevssid/'+newEVSSID+'/'+EVSSID, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
            //body: JSON.stringify({ EVSSID: newEVSSID, oldEVSSID: EVSSID }),
            credentials: 'include' // Include credentials for cookie handling
        })
            .then(response => response.json())
            .then(data => {
                console.log('EVSSID updated on server:', data);
            })
            .catch(error => {
                console.error('Error updating EVSSID:', error);
            });
    }
}
