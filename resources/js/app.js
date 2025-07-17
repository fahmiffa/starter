import Alpine from 'alpinejs';
import { layout, dataTable, imageCropper, dataSelect, posApp,  Crud, Crudcat, Crudunit, Cruditem, Crudstok, salesChart } from './custom.js';



window.Alpine = Alpine;

Alpine.data('imageCropper', imageCropper);
Alpine.data('layout', layout);
Alpine.data('dataTable', dataTable);
Alpine.data('dataSelect', dataSelect);
Alpine.data('posApp', posApp);
Alpine.data('Crud', Crud);
Alpine.data('Crudcat', Crudcat);
Alpine.data('Crudunit', Crudunit);
Alpine.data('Cruditem', Cruditem);
Alpine.data('Crudstok', Crudstok);
Alpine.data('salesChart', salesChart);
Alpine.store("unit");

Alpine.start();
