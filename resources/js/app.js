import Alpine from 'alpinejs';
import { layout, dataTable, imageCropper, dataSelect, posApp, formHandler } from './custom.js';


window.Alpine = Alpine;

Alpine.data('imageCropper', imageCropper);
Alpine.data('layout', layout);
Alpine.data('dataTable', dataTable);
Alpine.data('dataSelect', dataSelect);
Alpine.data('posApp', posApp);
Alpine.data('formHandler', formHandler);
Alpine.store("unit");

Alpine.start();
