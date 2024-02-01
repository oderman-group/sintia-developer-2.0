var select = document.getElementById('cargasSeleccionadas');
var checkboxes = document.querySelectorAll('.check');
contarCargasSeleccionadas();

/**
 * Esta función es para marcar o desmarcar todos los estudiantes y tambien la agrega o elimina a la seleción
 */
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('all').addEventListener('change', function(e) {
        var isChecked = this.checked;
        checkboxes.forEach(function(checkElement) {
            var idCarga = checkElement.value;  
            if (isChecked) {
                if(checkElement.checked){
                    checkElement.checked = false;
                    eliminarCarga(idCarga);
                }
                checkElement.checked = true;
                agregarCarga(idCarga);
            } else {
                checkElement.checked = false;
                eliminarCarga(idCarga);
            }
        });
    });
});

/**
 * Esta función verifica si el estudiante fue selecionado o no para agregar o eliminar de la seleción.
 * @param datos //Datos del estudiante selecionado
 */
function seleccionarCargas(datos) {
    var idCarga = datos.value;
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
        agregarCarga(idCarga);
    } else {
        if(all.checked){
            all.checked=false;
        }
        eliminarCarga(idCarga);
    }
}

/**
 * Esta función agrega un estudiante a la seleción cuando es selecionado.
 * @param idCarga
 */
function agregarCarga(idCarga) {
    var nuevaOpcion = document.createElement('option');
    nuevaOpcion.value = idCarga;
    nuevaOpcion.textContent = idCarga;
    select.appendChild(nuevaOpcion);
    nuevaOpcion.selected = true;
    contarCargasSeleccionadas();
}

/**
 * Esta función elimina un estudiante de la seleción cuando deja de estar selecionado.
 * @param idCarga 
 */
function eliminarCarga(idCarga) {
    var opcionAEliminar = select.querySelector('option[value="' + idCarga + '"]');
    if (opcionAEliminar) {
        select.removeChild(opcionAEliminar);
    }
    contarCargasSeleccionadas();
}

/**
 * Esta función cuenta los estudiantes selecionados.
 */
function contarCargasSeleccionadas() {
    var labelCant = document.getElementById('cantSeleccionadas');
    var cantidadSeleccionadas = select.selectedOptions.length;
    labelCant.textContent = cantidadSeleccionadas;
}