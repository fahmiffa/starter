import Alpine from 'alpinejs';
import { layout, dataTable, imageCropper } from './custom.js';

window.Alpine = Alpine;

Alpine.data('imageCropper', imageCropper);
Alpine.data('layout', layout);
Alpine.data('dataTable', dataTable);

Alpine.start();
