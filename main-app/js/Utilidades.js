$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

/**
 * Esta función hace una petición asincrona y recibe una respuesta.
 * Pueden enviarse parametros POST o GET de ser necesario.
 * @param {String} url 
 * @param {String} title
 * @param {String} method = 'POST'
 * @param {String} paramsJSON 
 */
function fetchGeneral(url, title, method='POST', paramsJSON=null) {

    document.getElementById("overlay").style.display = "block";
    
    const formData = new FormData();

    for (const clave in paramsJSON) {
        if (paramsJSON.hasOwnProperty(clave)) {
            const valor = paramsJSON[clave];
            formData.append(clave, valor);
        }
    }

    fetch(url, {
        method: method,
        body: formData
    })
    .then(response => response.text()) // Convertir la respuesta a texto
    .then(data => {

        document.getElementById("overlay").style.display = "none";
        $("#modalGeneral").modal("show");
        document.getElementById("respuestaTituloGeneral").innerHTML = title;
        document.getElementById("respuestaGeneral").innerHTML = data;

    })
    .catch(error => {
        // Manejar errores
        console.error('Error:', error);

        document.getElementById("overlay").style.display = "none";
    });

}


/**
 * Realiza una solicitud a un servidor para obtener información y la muestra en una sección del documento.
 *
 * Esta función muestra un esqueleto de carga, realiza una solicitud al servidor y reemplaza el esqueleto con la información obtenida.
 *
 * @param {string} url - La URL a la que se realizará la solicitud.
 * @param {string} idResponseTag - El ID del elemento HTML donde se mostrará la información.
 * @param {string} method - El método de solicitud HTTP (por defecto, 'POST').
 * @param {Object} paramsJSON - Un objeto con los datos que se enviarán en la solicitud (opcional).
 */
function listarInformacion(url, idResponseTag, method='POST', paramsJSON=null) {

    const skeletonContent = `
        <div id="skeleton" class="skeleton">
            <div class="skeleton-header"></div>
            <div class="skeleton-content"></div>
            <div class="skeleton-content"></div>
            <div class="skeleton-content"></div>
            <div class="skeleton-content"></div>
            <div class="skeleton-content"></div>
        </div>
    `;

    document.getElementById(idResponseTag).innerHTML = skeletonContent;

    const formData = new FormData();

    for (const clave in paramsJSON) {
        if (paramsJSON.hasOwnProperty(clave)) {
            const valor = paramsJSON[clave];
            formData.append(clave, valor);
        }
    }

    fetch(url, {
        method: method,
        body: formData
    })
    .then(response => response.text()) // Convertir la respuesta a texto
    .then(data => {
        document.getElementById(idResponseTag).innerHTML = data;

    })
    .catch(error => {
        // Manejar errores
        console.error('Error:', error);
    });

}

/**
 * Valida el peso de un archivo seleccionado en un campo de entrada de tipo "file".
 *
 * Esta función compara el peso del archivo seleccionado con un peso máximo permitido
 * y muestra un mensaje de error si el archivo excede ese límite.
 *
 * @returns {boolean} Devuelve "false" si el archivo excede el peso máximo permitido, de lo contrario, "true".
 */
function validarPesoArchivo(archivoInput) {
    const maxPeso = 5 * 1024 * 1024; // Peso máximo permitido (en bytes), por ejemplo, 5 MB

    const archivo = archivoInput.files[0]; // Obtiene el archivo seleccionado
    const pesoArchivoMB = ((archivo.size / 1024) / 1024).toFixed(2);

    if (archivo) {
        if (archivo.size > maxPeso) {
            Swal.fire(`Este archivo pesa ${pesoArchivoMB} MB Lo ideal es que pese menos de 5 MB. Intente comprimirlo o busque reducir su peso.`);
            archivoInput.value = ""; // Borra la selección del archivo
            return false;
        }
    }
}

function guardarAjaxGeneral(datos){ 
    var idR = datos.id;
    var valor = 0;
    if(document.getElementById(idR).checked){
        valor = 1;
    }
    var operacion = 3;	

$('#respuestaGuardar').empty().hide().html("").show(1);
    datos = "idR="+(idR)+
            "&valor="+(valor)+
            "&operacion="+(operacion);
            $.ajax({
                type: "POST",
                url: "ajax-guardar.php",
                data: datos,
                success: function(data){
                    $('#respuestaGuardar').empty().hide().html(data).show(1);
                }
        });
}
