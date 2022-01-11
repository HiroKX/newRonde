import './styles/carousel.scss';
import 'slick-carousel/slick/slick';

const $ = require('jquery');
global.$ = global.jQuery = $;

$('.image-carousel').slick();

console.debug('carousel.js loaded');
