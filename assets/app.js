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
import 'bootstrap';

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

console.debug('app.js loaded');
