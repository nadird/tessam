import { Controller } from '@hotwired/stimulus';
import Carousel from 'stimulus-carousel'

export default class extends Carousel {

    connect() {
        super.connect()
        //console.log('Do what you want here.')

        // The swiper instance.
        this.swiper

        // Default options for every carousels.
        this.defaultOptions
    }

    // You can set default options in this getter.
    get defaultOptions() {
        return {
            slidesPerView: 2,
            spaceBetween: 5,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                480: {
                    slidesPerView: 2,
                    spaceBetween: 5,
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 5,

                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 5,

                },
                1024: {
                    slidesPerView: 2,
                    spaceBetween: 5,
                },
            },
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            loop: true,
            effect: 'slide', // "slide", "fade", "cube", "coverflow" or "flip"
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },
            createElements: true,
            grabCursor: true,
            lazy: true,

            //hashNavigation: false,



            }//end of return


    }// End of defaultOptions
}