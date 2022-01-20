import './styles/carousel.scss';
import Swiper, { Navigation, Pagination, Lazy } from 'swiper';

Swiper.use([Navigation, Pagination, Lazy]);

new Swiper('.swiper', {
    // Optional parameters
    direction: 'horizontal',
    loop: true,
    autoHeight: true,
    preloadImages: false,
    // Enable lazy loading
    lazy: {
        loadPrevNext: true,
    },

    // If we need pagination
    pagination: {
        el: '.swiper-pagination',
    },

    // Navigation arrows
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    // And if we need scrollbar
    scrollbar: {
        el: '.swiper-scrollbar',
    },
});

