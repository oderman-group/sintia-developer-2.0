var select = document.getElementById('preguntasSeleccionadas');
var checkboxes = document.querySelectorAll('.check');
contarPreguntasSeleccionadas();

/**
 * Esta función es para marcar o desmarcar todas la paginas y tambien la agrega o elimina a la seleción
 */
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('all').addEventListener('change', function(e) {
        var isChecked = this.checked;
        checkboxes.forEach(function(checkElement) {
            var pregunta = checkElement.value;  
            if (isChecked) {
                if(checkElement.checked){
                    checkElement.checked = false;
                    eliminarPreguntas(pregunta);
                }
                checkElement.checked = true;
                agregarPreguntas(pregunta);
            } else {
                checkElement.checked = false;
                eliminarPreguntas(pregunta);
            }
        });
    });
});

/**
 * Esta función verifica si la pagina fue selecionada o no para agregar o eliminar de la seleción.
 * @param datos //Datos de la pagina selecionada
 */
function seleccionarPreguntas(datos) {
    var pregunta = datos.value;
    var all = document.getElementById('all'); 
    if (datos.checked) {
        var cont=0;
        checkboxes.forEach(function(checkElement) {
            if(checkElement.checked){
                cont=cont+1;
            }
        });
        if(cont==checkboxes.length){
            all.checked=true;
        }
        agregarPreguntas(pregunta);
    } else {
        if(all.checked){
            all.checked=false;
        }
        eliminarPreguntas(pregunta);
    }
}

/**
 * Esta función agrega una pagina a la seleción cuando es selecionada.
 * @param pregunta
 */
function agregarPreguntas(pregunta) {
    var nuevaOpcion = document.createElement('option');
    nuevaOpcion.value = pregunta;
    nuevaOpcion.textContent = pregunta;
    nuevaOpcion.selected = true;
    select.appendChild(nuevaOpcion);
    contarPreguntasSeleccionadas();
}

/**
 * Esta función elimina una pagina de la seleción cuando deja de estar selecionada.
 * @param pregunta 
 */
function eliminarPreguntas(pregunta) {
    var opcionAEliminar = select.querySelector('option[value="' + pregunta + '"]');
    if (opcionAEliminar) {
        select.removeChild(opcionAEliminar);
    }
    contarPreguntasSeleccionadas();
}

/**
 * Esta función cuenta las paginas selecionadas.
 */
function contarPreguntasSeleccionadas() {
    var labelCant = document.getElementById('cantSeleccionadasPreguntas');
    var cantidadSeleccionadas = select.selectedOptions.length;
    labelCant.textContent = cantidadSeleccionadas;
}