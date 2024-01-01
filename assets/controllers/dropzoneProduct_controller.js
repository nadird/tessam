import { Controller } from '@hotwired/stimulus';
import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css';



export default class extends Controller {

    connect() {
        console.log('DropzoneProductController connected',this.element.dataset.dropzoneProductUrl);
        this.initializeDropzone();

    }

    initializeDropzone() {
        console.log('DropzoneProductController initializeDropzone');

        const uploadUrl = this.element.dataset.dropzoneProductUrl;
        console.log('DropzoneProductController initializeDropzone',uploadUrl);
        this.dropzone = new Dropzone(this.element, {
            url: uploadUrl,
            paramName: 'file', // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            dictDefaultMessage: 'Drop files here to upload',
            dictRemoveFile: 'Remove',
            dictCancelUpload: 'Cancel',
            dictCancelUploadConfirmation: 'Are you sure you want to cancel this upload?',
            init: function () {
                this.on('success', function (file, response) {
                    response = JSON.parse(response);
                   console.log('DropzoneProductController initializeDropzone success',response);
                    // Handle the responseText here. For example, add the text to the preview element:
                    const previewElement = file.previewElement;
                    previewElement.classList.add('dz-success');
                    previewElement.classList.add('dz-complete');
                    previewElement.querySelector('.dz-filename > span').textContent = response.filename;
                    previewElement.querySelector('.dz-size > span').textContent = response.size;
                    previewElement.querySelector('.dz-remove').setAttribute('data-dropzone-product-remove-url', response.remove_url);
                    previewElement.querySelector('img').setAttribute('src', response.path).setAttribute('alt', response.filename);
                    previewElement.querySelector('img').addStyle('width', '200px');
                    previewElement.querySelector('.data-dz-size').textContent = response.size;
                    previewElement.querySelector('.data-dz-name').textContent = response.name;

                });

                this.on('error', function (file, response) {
                    // Handle errors
                });
            }
        });
    }

    disconnect() {
        if (this.dropzone) {
            this.dropzone.destroy();
        }
    }
}
