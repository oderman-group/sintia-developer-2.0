/**
 * Esta función permite verifica si hay conexión a internet cada segundo
 * y muestra un mensaje al usuario si se perdió o volvió dicha conexión
 */
function hayInternet() {

    if(navigator.onLine) {

		if(localStorage.getItem("internet") == 0){
			document.getElementById("siInternet").style.display="block";

            $.toast({
                heading: 'La conexión ha vuelto!',  
                text: 'La conexión a internet ha vuelto. Puedes continuar trabajando en la plataforma.',
                position: 'mid-center',
                loaderBg:'#ff6849',
                icon: 'success',
                hideAfter: 10000, 
                stack: 6
            });

		}

		localStorage.setItem("internet", 1);
		document.getElementById("noInternet").style.display="none";
        setTimeout(function() {
            document.getElementById("siInternet").style.display="none";
        }, 10000);
        

	} else {

		if(localStorage.getItem("internet") == 1 || localStorage.getItem("internet") == null) {
            $.toast({
                heading: 'Se ha perdido la conexión!',  
                text: 'Se ha perdido tu conexión a internet. Por favor verifica antes de continuar trabajando en la plataforma.',
                position: 'mid-center',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 10000, 
                stack: 6
            });
        }
        

        localStorage.setItem("internet", 0);
		document.getElementById("noInternet").style.display="block";

	}

}

setInterval('hayInternet()', 1000);

/**
 * Esta función permite que el usuario confirme antes de regresar a una pagina anterior
 * para evitar que vaya a perder cambios sin guardar
 * @param {Array} dato 
 */
function deseaRegresar(dato){	
	var url = dato.name;

    Swal.fire({
        title: 'Desea regresar?',
        text: "Si va a regresar verifique que no haya hecho cambios en esta página y estén sin guardar. Desea regresar de todas formas?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, deseo regresar!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href=url;
        }
    })

}

/**
 * Esta funcion genera una confimacion de warning personalizada 
 * 
 * @param titulo
 * @param mensaje
 * @param tipos  [success,error,warning,info,question]
 * @return boolean 
 */
function sweetConfirmacion(titulo, mensaje, tipo ='question', varHeref) {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: tipo,
        showCancelButton: true,
        confirmButtonText: 'Si!',
        cancelButtonText: 'No!'
    }).then((result) => {
        if (result.isConfirmed) {
            if(varHeref === null) {
                return true;
            } else {
                window.location.href=varHeref;
            }

        } else {

            if(varHeref === null) {
                return false;
            } else {
                window.location.href='#';
            }

        }
    })

}

function axiosAjax(datos){

    let url = datos.id;
    let divRespuestaNombre = 'RESP_'+datos.title;
    let divRespuesta = document.getElementById(divRespuestaNombre);

    axios.get(url)
    .then(function (response) {
        // handle success
        console.log(response.data);
        divRespuesta.innerHTML = response.data;
    })
    .catch(function (error) {

        // handle error
        console.log(error);
    })
    .then(function () {
        // always executed
    });

}

/**
 * Esta función la solicitó en su momento el nuevo ghandy, pero creemos que no llegó a usarse.
 */
function deseaGenerarIndicadores(dato) {
	document.getElementById('agregarNuevo').style.display="none";
	document.getElementById('preestablecidos').style.display="none";

	var respuesta = sweetConfirmacion('Eliminar indiacdores','Al ejecutar esta acción se eliminaran los indicadores y actividades ya creados. Desea continuar bajo su responsabilidad?');
	var url = dato.name;

	if(respuesta == true){

		$.toast({

			heading: 'Acción en proceso', 
			text: 'Estamos creando los indicadores y actividades para ti, te avisaremos encuanto esten creados.', 
			position: 'mid-center',
			loaderBg:'#26c281', 
			icon: 'warning', 
			hideAfter: 5000, 
			stack: 6

		});

		document.getElementById('msjPree').style.display="block";

		axios.get(url).then(function (response) {
			document.getElementById('msjPree').style.display="none";
			$.toast({

				heading: 'Acción realizada', text: 'Los indicadores y actividades fueron creados correctamente, racargaremos la página.', 
				position: 'mid-center',
				loaderBg:'#26c281', 
				icon: 'success', 
				hideAfter: 5000, 
				stack: 6

			});
			location.reload();
		})
	}
}

/**
 * Esta función pide confirmación al usuario antes de eliminar un registro
 * o ejecutar alguna acción de eliminar algo
 * @param {Array} dato 
 */
function deseaEliminar(dato) {

    if (dato.title !== '') {

        let variable = (dato.title);
        var varObjet = JSON.parse(variable);
        var input = document.getElementById(parseInt(varObjet.idInput));

    }

    var url = dato.name;
    var id = dato.id;
    var registro = document.getElementById("reg" + id);
    var evaPregunta = document.getElementById("pregunta" + id);
    var publicacion = document.getElementById("PUB" + id);

    Swal.fire({
        title: 'Desea eliminar?',
        text: "Al eliminar este registro es posible que se eliminen otros registros que estén relacionados. Desea continuar bajo su responsabilidad?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, deseo eliminar!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            if (typeof id !== "undefined" && id !== "") {

                if (typeof varObjet !== "undefined") {
                    var input = document.getElementById(parseInt(varObjet.idInput));
                }

                axios.get(url).then(function(response) {
                    if (typeof varObjet !== "undefined") {
                        // handle success
                        if (varObjet.tipo === 1) {
                            registro.style.display = "none";
                        }

                        if (varObjet.tipo === 2) {
                            document.getElementById(id).style.display = "none";
                            input.value = "";
                        }

                        if (varObjet.tipo === 3) {
                            evaPregunta.style.display = "none";
                        }

                        if (varObjet.tipo === 4) {
                            publicacion.style.display = "none";
                        }
                    }

                        $.toast({
                            heading: 'Acción realizada',
                            text: 'El registro fue eliminado correctamente.',
                            position: 'mid-center',
                            loaderBg: '#26c281',
                            icon: 'success',
                            hideAfter: 5000,
                            stack: 6
                        });

                }).catch(function(error) {
                    // handle error
                    console.error(error);
                });
            } else {
                window.location.href = url;
            }
            
        }else{
            return false;
        }
    })

}

function inhabilitarBotones(idBtn) {
    // Obtener el botón por su ID
    const miBoton = document.getElementById(idBtn);

    // Deshabilitar el botón
    miBoton.disabled = true;
}

function ejecutarOtrasFunciones(params) {
    // Esperar a que el documento esté listo (DOMContentLoaded)
    document.addEventListener("DOMContentLoaded", function() {
        // Después de que el documento esté listo, esperar 1 milisegundo y luego llamar a la función
        setTimeout(function() {
            inhabilitarBotones(params);
        }, 100);
    });
}

function minimoUno(data) {
    if( data.value <= 0 ) {
        data.value = 1;
    }
}