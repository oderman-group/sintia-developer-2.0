var select = document.getElementById('respuestasSeleccionadas');
var checkboxes = document.querySelectorAll('.check');
contarRespuestasSeleccionadas();

/**
 * Esta función es para marcar o desmarcar todas la paginas y tambien la agrega o elimina a la seleción
 */
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('all').addEventListener('change', function(e) {
        var isChecked = this.checked;
        checkboxes.forEach(function(checkElement) {
            var respuesta = checkElement.value;  
            if (isChecked) {
                if(checkElement.checked){
                    checkElement.checked = false;
                    eliminarRespuesta(respuesta);
                }
                checkElement.checked = true;
                agregarRespuesta(respuesta);
            } else {
                checkElement.checked = false;
                eliminarRespuesta(respuesta);
            }
        });
    });
});

/**
 * Esta función verifica si la pagina fue selecionada o no para agregar o eliminar de la seleción.
 * @param datos //Datos de la pagina selecionada
 */
function seleccionarRespuesta(datos) {
    var respuesta = datos.value;
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
        agregarRespuesta(respuesta);
    } else {
        if(all.checked){
            all.checked=false;
        }
        eliminarRespuesta(respuesta);
    }
}

/**
 * Esta función agrega una pagina a la seleción cuando es selecionada.
 * @param respuesta
 */
function agregarRespuesta(respuesta) {
    var nuevaOpcion = document.createElement('option');
    nuevaOpcion.value = respuesta;
    nuevaOpcion.textContent = respuesta;
    nuevaOpcion.selected = true;
    select.appendChild(nuevaOpcion);
    contarRespuestasSeleccionadas();
}

/**
 * Esta función elimina una pagina de la seleción cuando deja de estar selecionada.
 * @param respuesta 
 */
function eliminarRespuesta(respuesta) {
    var opcionAEliminar = select.querySelector('option[value="' + respuesta + '"]');
    if (opcionAEliminar) {
        select.removeChild(opcionAEliminar);
    }
    contarRespuestasSeleccionadas();
}

/**
 * Esta función cuenta las paginas selecionadas.
 */
function contarRespuestasSeleccionadas() {
    var labelCant = document.getElementById('cantSeleccionadasRespuesta');
    var cantidadSeleccionadas = select.selectedOptions.length;
    labelCant.textContent = cantidadSeleccionadas;
}