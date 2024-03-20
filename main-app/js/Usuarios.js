/**
 * Esta función hace una petición asincrona y recibe una respuesta.
 * @param {array} datos 
 */
function validarUsuario(datos) {
    var usuario = datos.value;
    var idUsuario = datos.getAttribute("data-id-usuario");
    
    if(usuario!=""){

        fetch('ajax-comprobar-usuario.php?usuario=' + usuario + '&idUsuario=' + idUsuario, {
            method: 'GET'
        })
        .then(response => response.json()) // Convertir la respuesta a objeto JSON
        .then(data => {
                if (data.success == 1) {
                    $("#respuestaUsuario").html(data.message);
                    $("input").attr('disabled', true); 
                    $("input#usuario").attr('disabled',false); 
                    $("#btnEnviar").attr('disabled', true); 
                } else {
                    $("#respuestaUsuario").html(data.message);
                    $("input").attr('disabled', false); 
                    $("#btnEnviar").attr('disabled', false); 
                }
        })
        .catch(error => {
            // Manejar errores
            console.error('Error:', error);
        });
    } else {
        $("#respuestaUsuario").html("");
        $("input").attr('disabled', false); 
        $("#btnEnviar").attr('disabled', false); 
    }
}

/**
 * Esta función me valida la cantidad de usurios permitido segun el tipo de usuario y el plan de la compañia.
 * @param {array} datos 
 */
function validarCantidadUsuarios(datos) {
    var tipoUsuario = datos.value;
    
    if(tipoUsuario!=""){

        fetch('ajax-comprobar-cantidad-usuario.php?tipoUsuario=' + tipoUsuario, {
            method: 'GET'
        })
        .then(response => response.json()) // Convertir la respuesta a objeto JSON
        .then(data => {
                if (data.success == 1) {
                    $("#respuestaUsuario").html(data.message);
                    $("input").attr('disabled', true); 
                    $("input#tipoUsuario").attr('disabled',false); 
                    $("#btnEnviar").attr('disabled', true);
                } else {
                    $("#respuestaUsuario").html(data.message);
                    $("input").attr('disabled', false); 
                    $("#btnEnviar").attr('disabled', false); 
                    mostrarSubroles(datos);
                }
        })
        .catch(error => {
            // Manejar errores
            console.error('Error:', error);
        });
    } else {
        $("#respuestaUsuario").html("");
        $("input").attr('disabled', false); 
        $("#btnEnviar").attr('disabled', false); 
    }
}