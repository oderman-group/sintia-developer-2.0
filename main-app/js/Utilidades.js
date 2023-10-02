$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

/**
 * Esta función hace una petición asincrona y recibe una respuesta.
 * Pueden enviarse parametros POST o GET de ser necesario.
 * @param {String} url 
 * @param {String} title 
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