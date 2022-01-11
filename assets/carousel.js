import './styles/carousel.scss';
import 'slick-carousel/slick/slick';

const $ = require('jquery');
global.$ = global.jQuery = $;

$('.image-carousel').slick({
    infinite: true,
    dots: true,
    speed: 500
});

console.debug('carousel.js loaded');
