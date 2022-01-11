
import "slick-carousel";

const $ = require('jquery');
$(document).ready(function(){
    $('.image-carousel').slick();
});

$('.img-carousel').click(function(e){
    $('.big-carousel').removeClass('d-none');
    $('.big-carousel').slick();
});



console.debug('carousel.js loaded');
