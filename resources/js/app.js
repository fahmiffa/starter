import Alpine from 'alpinejs';
import { layout, dataTable, imageCropper, dataSelect } from './custom.js';


window.Alpine = Alpine;

Alpine.data('imageCropper', imageCropper);
Alpine.data('layout', layout);
Alpine.data('dataTable', dataTable);
Alpine.data('dataSelect', dataSelect);
Alpine.store("unit");

Alpine.start();
