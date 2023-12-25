import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        storeURLAndPageData();
        const parameters = getStoredData();
        //console.log(parameters);
    }
}

function storeURLAndPageData() {
    const urlParams = new URLSearchParams(window.location.search);
    const urlParamsObject = {};
    const timeStamp = new Date().toISOString();

    // Loop through all the URL parameters and store them in urlParamsObject
    for (const [key, value] of urlParams.entries()) {
        urlParamsObject[key] = value;
    }

    // Get existing data from localStorage and sessionStorage
    let existingDataLocalStorage = localStorage.getItem('data');
    let existingDataSessionStorage = sessionStorage.getItem('data');
    existingDataLocalStorage = existingDataLocalStorage ? JSON.parse(existingDataLocalStorage) : { pageUrl: {} };
    existingDataSessionStorage = existingDataSessionStorage ? JSON.parse(existingDataSessionStorage) : {};

    // Add the new URL parameters to the existing urlParams
    existingDataLocalStorage['urlParams'] = {...existingDataLocalStorage['urlParams'], ...urlParamsObject};
    existingDataSessionStorage['urlParams'] = {...existingDataSessionStorage['urlParams'], ...urlParamsObject};

    // Add the current page URL and timestamp to pageUrl in localStorage
    existingDataLocalStorage['pageUrl'][timeStamp] = window.location.href;

    // Store the updated data object in both localStorage and sessionStorage
    localStorage.setItem('data', JSON.stringify(existingDataLocalStorage));
    sessionStorage.setItem('data', JSON.stringify(existingDataSessionStorage));
}

function getStoredData() {
    const storedData = localStorage.getItem('data');


    if (storedData) {
        return JSON.parse(storedData);
    } else {
        return {};
    }
}