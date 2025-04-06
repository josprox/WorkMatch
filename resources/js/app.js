import '../sass/tabler.scss';
import './bootstrap';
import './tabler-init';
import EasyMDE from "easymde";
import "easymde/dist/easymde.min.css";

// Inicializar el editor (espera a que el DOM esté listo)
document.addEventListener("DOMContentLoaded", function () {
    const textarea = document.querySelector("#md"); // Asegúrate que tenga este ID
    if (textarea) {
        new EasyMDE({ element: textarea });
    }
});
