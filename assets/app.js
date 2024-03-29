/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';
const $ = require('jquery');
global.$ = global.jQuery = $;

import '@popperjs/core';
require('bootstrap');



const routes = require('../public/js/fos_js_routes.json');
import Routing from '../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);


let nbLoad=1;
let scrollPos = 0;
const mainNav = document.getElementById('mainNav');
const headerHeight = mainNav.clientHeight;

$(function() {
    $('.img_nogallery').on('click', function() {
        $('#imagemodal').attr('src', $(this).attr('src'));
        $('#imagemodal').addClass('bg-opacity-25');
        $('.modal').addClass('d-block');
    });
    $('#my_modal').on('click',function(){
        $('#imagemodal').removeClass('bg-opacity-25');
        $('.modal').removeClass('d-block');
    })
});

window.addEventListener('scroll', function() {
    const currentTop = document.body.getBoundingClientRect().top * -1;
    if ( currentTop < scrollPos) {
        // Scrolling Up
        if (currentTop > 0 && mainNav.classList.contains('is-fixed')) {
            mainNav.classList.add('is-visible');
        } else {
            mainNav.classList.remove('is-visible', 'is-fixed');
        }
    } else {
        // Scrolling Down
        mainNav.classList.remove(['is-visible']);
        if (currentTop > headerHeight && !mainNav.classList.contains('is-fixed')) {
            mainNav.classList.add('is-fixed');
        }
    }
    scrollPos = currentTop;
});

updateAttachments();
registerAttachmentsEvents();
registerAttachmentsForm();

// Collection Article Images
function updateAttachments() {
    $('.collection-attachments .attachment').each(function () {
        let fileName = $(this).data('fileName');
        $(this).find('label').html(fileName);
    });
}

function registerAttachmentsEvents() {
    // Ajout PJ
    $('.add-attachment').one('click', function (e) {
        let $collectionHolder = $('.collection-attachments').first();

        // CHECK limite MAX nombre de fichiers atteinte
        /*if ($($collectionHolder).find('.attachment').length === 4) {
            $('#collapseAttachement .invalid-feedback').show();
            return;
        }*/

        let prototype = $collectionHolder.data('prototype');
        let index = $collectionHolder.data('index');

        let newForm = prototype.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);

        let $newForm = $(newForm);
        $collectionHolder.append($newForm);

        registerAttachmentsEvents();
    });

    // Suppression PJ
    $('.remove-attachment').one('click', function (e) {
        $(this).closest('.attachment').remove();
    });
}

function registerAttachmentsForm() {
    $('.collection-attachments').closest('form').submit(function (event) {
        $('.collection-attachments input[type="file"]').each(function () {
            // Suppression champ PJ ajouté mais sans upload associé
            // car provoquerait une erreur côté serveur si submit
            let filename = $(this).closest('.attachment').data('fileName');
            if (this.files.length === 0 && filename === '') {
                $(this).closest('.attachment').remove();
            }
        });
    });
}



$('#btn-load').click(loadArticle);

$('#selector-type').change(load)

let explicitData = {}
let reload=false;

function load(){
    if($('#selector-type option:checked') !== 'all'){
        explicitData.type=$('#selector-type option:checked').val()
    }
    else{
        explicitData = {}
    }
    nbLoad=0
    reload=true
    loadArticle()
}


function loadArticle() {
    explicitData.offset = nbLoad
    $.ajax({
        url: Routing.generate('ajax_article'),
        type: 'POST',
        async: true,
        data: explicitData,
        success: function (dataReturned, status) {
            if (reload) {
                $('#containerArticle').html(dataReturned);
                reload=false;
            } else {
                $('#containerArticle').append(dataReturned);
            }
            nbLoad++;
        },
        error: function (xhr, textStatus, errorThrown) {
            console.error('Erreur de chargement...');
        }
    });
}


console.debug('app.js loaded');
