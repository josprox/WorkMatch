import '../sass/tabler.scss';
import './bootstrap';
import './tabler-init';
import EasyMDE from "easymde";
import "easymde/dist/easymde.min.css";
import $ from 'jquery';
import 'select2';

// Inicializar Select2 en todos los selects con la clase 'select2'
$(document).ready(function() {
    $('.select2').select2();
});

// Inicializar el editor (espera a que el DOM esté listo)
document.addEventListener("DOMContentLoaded", function () {
    const textarea = document.querySelector("#md"); // Asegúrate que tenga este ID
    if (textarea) {
        new EasyMDE({ element: textarea });
    }
});
