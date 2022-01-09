import './styles/ckeditor.scss';

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Upload from '@ckeditor/ckeditor5-upload';

ClassicEditor
    .create(document.querySelector('textarea.js-editor'));

console.debug('ckeditor.js loaded');
