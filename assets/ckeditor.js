
import ClassicEditor from 'ckeditor5-build-classic-base64-upload';

ClassicEditor
    .create(document.querySelector('#article_contenu'))
    .then(editor => {
        console.debug('Editor was initialized', editor);
        window.editor = editor;
    })
    .catch(error => {
        console.error(error.stack);
    })
;


console.debug('ckeditor.js loaded');
