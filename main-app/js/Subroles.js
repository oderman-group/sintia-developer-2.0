/**
 * Esta función recarga la pagina para mostrar solo las paginas selecionadas
 * @param idSubrol
 */
function mostrarActivas(check,idSubrol) {
    var nuevaURL = "sub-roles-editar.php?id="+idSubrol+"&activas="+(check?1:0);            
    window.location.href = nuevaURL;
    onclick="redireccionar()"
}

var select = document.getElementById('paginasSeleccionadas');
var checkboxes = document.querySelectorAll('.check');
contarPaginasSeleccionadas();

/**
 * Esta función es para marcar o desmarcar todas la paginas y tambien la agrega o elimina a la seleción
 */
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('all').addEventListener('change', function(e) {
        var isChecked = this.checked;
        checkboxes.forEach(function(checkElement) {
            var page = checkElement.value;  
            if (isChecked) {
                if(checkElement.checked){
                    checkElement.checked = false;
                    eliminarPagina(page);
                }
                checkElement.checked = true;
                agregarPagina(page);
            } else {
                checkElement.checked = false;
                eliminarPagina(page);
            }
        });
    });
});

/**
 * Esta función verifica si la pagina fue selecionada o no para agregar o eliminar de la seleción.
 * @param datos //Datos de la pagina selecionada
 */
function seleccionarPagina(datos) {
    var page = datos.value;
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
        agregarPagina(page);
    } else {
        if(all.checked){
            all.checked=false;
        }
        eliminarPagina(page);
    }
}

/**
 * Esta función agrega una pagina a la seleción cuando es selecionada.
 * @param page
 */
function agregarPagina(page) {
    var nuevaOpcion = document.createElement('option');
    nuevaOpcion.value = page;
    nuevaOpcion.textContent = page;
    nuevaOpcion.selected = true;
    select.appendChild(nuevaOpcion);
    contarPaginasSeleccionadas();
    actualizarSubRol();
}

/**
 * Esta función elimina una pagina de la seleción cuando deja de estar selecionada.
 * @param page 
 */
function eliminarPagina(page) {
    var opcionAEliminar = select.querySelector('option[value=' + page + ']');
    if (opcionAEliminar) {
        select.removeChild(opcionAEliminar);
    }
    contarPaginasSeleccionadas();
    actualizarSubRol();
}

/**
 * Esta función cuenta las paginas selecionadas.
 */
function contarPaginasSeleccionadas() {
    var labelCant = document.getElementById('cantSeleccionadas');
    var cantidadSeleccionadas = select.selectedOptions.length;
    labelCant.textContent = cantidadSeleccionadas;
}

/**
 * Esta función valida si la pagina selecionada tiene paginas de dependencias para mostrarle un alert al usuario.
 * @param datosPagina //Datos de la pagina selecionada
 */
function validarPaginasDependencia(datosPagina) {
    var id = datosPagina.value;
    var dependencias = datosPagina.id;
    if(dependencias.length>0 && datosPagina.checked){
        Swal.fire({
            title: 'Desea continuar?',
            text: "Esta pagina tiene otras paginas de dependencia que se añadirán automáticamente a este rol.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, deseo continuar!',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                seleccionarPagina(datosPagina);
            }else{
                datosPagina.checked=false;
            }
        });
    }else{
        seleccionarPagina(datosPagina);
    }
}

/**
 * Esta función me actualiza un sub rol
 */
function actualizarSubRol(datos) {
    var nombre  = document.getElementById('nombreSubrol').value;
    var id  = document.getElementById('idSubRol').value;
    
    var selectElementDirectivos = document.getElementById('directivos');
    var selectedOptionsDirectivos = [];
    for (var i = 0; i < selectElementDirectivos.options.length; i++) {
        if (selectElementDirectivos.options[i].selected) {
            selectedOptionsDirectivos.push(selectElementDirectivos.options[i].value);
        }
    }

    var directivos = encodeURIComponent(JSON.stringify(selectedOptionsDirectivos));
    
    var selectElementPaginas = document.getElementById('paginasSeleccionadas');
    var selectedOptionsPaginas = [];
    for (var i = 0; i < selectElementPaginas.options.length; i++) {
        if (selectElementPaginas.options[i].selected) {
            selectedOptionsPaginas.push(selectElementPaginas.options[i].value);
        }
    }

    var paginas = encodeURIComponent(JSON.stringify(selectedOptionsPaginas));

	fetch('../directivo/sub-roles-actualizar.php?nombre='+nombre+'&directivos='+directivos+'&paginas='+paginas+'&id='+id, {
		method: 'GET'
	})
	.then(response => response.text()) // Convertir la respuesta a texto
	.then(data => {
        $.toast({

            heading: 'Proceso completado', 
            text: 'El Sub Rol se actualizo correctamente...', 
            position: 'bottom-right',
            showHideTransition: 'slide',
            loaderBg:'#26c281', 
            icon: 'success', 
            hideAfter: 5000, 
            stack: 6

        });
	})
	.catch(error => {
		// Manejar errores
		console.error('Error:', error);
	});
}

// /**
//  * Esta función me los usuarios de una sub rol
//  */
// function actualizarUsuariosSubRol(datos) {
//     var directivos  = datos.value
//     var id          = datos.getAttribute('data-id-subRol');
// 	fetch('../directivo/sub-roles-actualizar.php?directivos='+directivos+'&id='+id, {
// 		method: 'GET'
// 	})
// 	.then(response => response.text()) // Convertir la respuesta a texto
// 	.then(data => {
//         $.toast({

//             heading: 'Proceso completado', 
//             text: 'El nombre se actualizo correctamente...', 
//             position: 'bottom-right',
//             showHideTransition: 'slide',
//             loaderBg:'#26c281', 
//             icon: 'success', 
//             hideAfter: 5000, 
//             stack: 6

//         });
// 	})
// 	.catch(error => {
// 		// Manejar errores
// 		console.error('Error:', error);
// 	});
// }