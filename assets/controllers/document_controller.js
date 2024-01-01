import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    connect() {
        const cookies = JSON.parse(this.element.dataset.cookiesValue);
        const url = this.element.dataset.urlValue;
        console.log(url);
        this.checkAndSetLocalStorage(cookies,url);
    }
    checkAndSetLocalStorage(cookies,url) {
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
            credentials: 'include'
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
