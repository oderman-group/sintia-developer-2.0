/**
 * Esta función permite verifica si hay conexión a internet cada segundo
 * y muestra un mensaje al usuario si se perdió o volvió dicha conexión
 */
function hayInternet() {

    if(navigator.onLine) {

		if(localStorage.getItem("internet") == 0){
			document.getElementById("siInternet").style.display="block";

            Swal.fire(
                'AVISPATE que la conexión ha vuelto!', 
                'La conexión a internet ha vuelto. Puedes continuar trabajando en la plataforma.', 
                'success'
                );

		}

		localStorage.setItem("internet", 1);
		document.getElementById("noInternet").style.display="none";
        setTimeout(function() {
            document.getElementById("siInternet").style.display="none";
        }, 10000);
        

	} else {

		if(localStorage.getItem("internet") == 1 || localStorage.getItem("internet") == null) {
            Swal.fire(
                'AVISPATE que se ha perdido la conexión!', 
                'Se ha perdido tu conexión a internet. Por favor verifica antes de continuar trabajando en la plataforma.', 
                'error'
                );
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
                    if (varObjet.tipo === 5) {
                        var input = document.getElementById(varObjet.idInput);
                    }
                }

                axios.get(url).then(function(response) {
                    if (typeof varObjet !== "undefined") {
                        // handle success
                        if (varObjet.tipo === 1) {
                            registro.style.display = "none";
                        }

                        if (varObjet.tipo === 2 || varObjet.tipo === 5) {
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


function ocultarNoticia(datos) {
    arrayInfo   = datos.id.split('|');
    var url     = datos.name;
    var pub     = document.getElementById("PANEL"+arrayInfo[0]);
    var accion  = arrayInfo[1];
    var mensaje = null;

    axios.get(url).then(function(response) {

        //Ocultar
        if(accion == 2) {
            pub.style.backgroundColor="#999";
            pub.style.opacity="0.7";
            mensaje = 'La noticia fue ocultada correctamente.';
        } 
        // Mostrar
        else {
            pub.style.backgroundColor="#fff";
            pub.style.opacity="1";
            mensaje = 'La noticia fue mostrada correctamente.';
        }

        $.toast({
            heading: 'Acción realizada',
            text: mensaje,
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

}

/**
 * Esta función crea una publicación rápida
 * @param {Array} datos 
 */
function crearNoticia() {

    var campoContenido = document.getElementById("contenido");
    const contenedor   = document.getElementById("nuevaPublicacion");
    const nuevoDiv     = document.createElement("div");
    var infoGeneral    = document.getElementById("infoGeneral");
    var arrayInfo      = infoGeneral.value.split('|');
    var idUsuario      = arrayInfo[0];
    var fotoUsuario    = arrayInfo[1]; 
    var nombreUsuario  = arrayInfo[2]; 

    const url = "../compartido/noticia-rapida-guardar.php?contenido="+campoContenido.value;

    axios.get(url)
    .then(function(response) {

        console.log(response.data);

        nuevoDiv.id = btoa(response.data);
        nuevoDiv.className = "row";

    // Establece el contenido HTML en el nuevo div
        nuevoDiv.innerHTML = `
            <div class="col-sm-12">
                <div class="panel">
                    <div class="card-head">
                        <header></header>
                        <button id="panel-${response.data}"
                            class="mdl-button mdl-js-button mdl-button--icon pull-right"
                            data-upgraded=",MaterialButton">
                            <i class="material-icons">more_vert</i>
                        </button>
                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                                        data-mdl-for="panel-${response.data}">

                                    <li class="mdl-menu__item">
                                        <a
                                        href="javascript:void(0);"
                                        id="${btoa(response.data)}|1"  
                                        name="../compartido/guardar.php?get=${btoa(6)}?>&e=${btoa(1)}&idR=${btoa(response.data)}"
                                        onClick="ocultarNoticia(this)"
                                        >
                                        <i class="fa fa-eye"></i>Mostrar</a>
                                    </li>
                                    <li class="mdl-menu__item">
                                    <a
                                    href="javascript:void(0);"
                                    id="${btoa(response.data)}|2"  
                                    name="../compartido/guardar.php?get=${btoa(6)}&e=${btoa(0)}&idR=${btoa(response.data)}"
                                    onClick="ocultarNoticia(this)"
                                    >
                                        <i class="fa fa-eye-slash"></i>Ocultar
                                    </a>
                                    </li>
                        </ul>
                    </div>

                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="${fotoUsuario}" class="img-circle user-img-circle" alt="User Image"
                                height="50" width="50" />
                        </div>
                        <div class="pull-left info">
                            <p><a href="<?=$_SERVER['PHP_SELF'];?>?usuario=${nombreUsuario}">${nombreUsuario}</a><br>
                                    <span style="font-size: 11px;">Ahora mismo</span></p>
                        </div>
                    </div>

                    <div class="card-body">
                        ${campoContenido.value}
                    </div>
                </div>
            </div>
        `;

        contenedor.insertBefore(nuevoDiv, contenedor.firstChild);

        campoContenido.value="";

        $.toast({
            heading: 'Acción realizada',
            text: 'La noticia se publicado correctamente',
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
}

const estudiantesPorEstados = {};

function cambiarEstadoMatricula(data) {
    let idHref = 'estadoMatricula'+data.id_estudiante;
    let href   = document.getElementById(idHref);
    
    if (!estudiantesPorEstados.hasOwnProperty(data.id_estudiante)) {
        estudiantesPorEstados[data.id_estudiante] = data.estado_matricula;
    }

    if(estudiantesPorEstados[data.id_estudiante] == 1) {
        href.innerHTML = `<span class="text-warning">No Matriculado</span>`;
        estudiantesPorEstados[data.id_estudiante] = 4;
    } else {
        href.innerHTML = `<span class="text-success">Matriculado</span>`;
        estudiantesPorEstados[data.id_estudiante] = 1;
    }

    let datos = "nuevoEstado="+estudiantesPorEstados[data.id_estudiante]+
                "&idEstudiante="+data.id_estudiante;

    $.ajax({
        type: "POST",
        url: "ajax-cambiar-estado-matricula.php",
        data: datos,
        success: function(data){
            $('#respuestaCambiarEstado').empty().hide().html(data).show(1);
        }

    });
}


const estudiantesPorEstadosBloqueo = {};

function cambiarBloqueo(data) {

    let tr   = document.getElementById("EST"+data.id_estudiante);
    
    if (!estudiantesPorEstadosBloqueo.hasOwnProperty(data.id_estudiante)) {
        estudiantesPorEstadosBloqueo[data.id_estudiante] = data.bloqueado;
    }

    var numero = 17;
    var estadoFinal = estudiantesPorEstadosBloqueo[data.id_estudiante];
    let datos = "get="+btoa(numero.toString())+
                "&idR="+btoa(data.id_usuario.toString())+
                "&lock="+btoa(estadoFinal.toString())
                ;

    if(estudiantesPorEstadosBloqueo[data.id_estudiante] == 0) {
        tr.style.backgroundColor="#ff572238";
        estudiantesPorEstadosBloqueo[data.id_estudiante] = 1;
    } else {
        tr.style.backgroundColor="";
        estudiantesPorEstadosBloqueo[data.id_estudiante] = 0;
    }

    $.ajax({
        type: "GET",
        url: "guardar.php",
        data: datos,
        success: function(data){
            var mensaje = 'El estudiante fue desbloqueado.';
            if(data == 1) {
                mensaje = 'El estudiante fue bloqueado';
            }

            $.toast({
                heading: 'Acción realizada',
                text: mensaje,
                position: 'mid-center',
                loaderBg: '#26c281',
                icon: 'success',
                hideAfter: 5000,
                stack: 6
            });
        }

    });
}

function minimoUno(data) {
    if( parseInt(data.value) <= 0 ) {
        data.value = 1;
    }
}