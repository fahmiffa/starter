import Alpine from 'alpinejs';
import { layout, dataTable } from './custom.js';

window.Alpine = Alpine;

Alpine.data('layout', layout);
Alpine.data('dataTable', dataTable);

Alpine.start();
