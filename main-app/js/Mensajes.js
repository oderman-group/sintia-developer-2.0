/**
 * Envía mensajes de correo electrónico a varios receptores.
 * @param {string} year - Año del mensaje.
 * @param {string} institucion - Institución relacionada con el mensaje.
 * @param {string} emisor - Emisor del mensaje.
 */
function enviarMensajes(year, institucion, emisor) {
    // Obtener el elemento del select de usuarios
    var selectUsuario = document.getElementById('select_usuario');
    
    // Obtener el asunto y contenido del mensaje
    var asunto = document.getElementById('asunto').value;
    var contenido = document.getElementById('editor1').value;
    
    // Almacenar los receptores seleccionados en el select múltiple
    var receptores = [];

    // Iterar sobre todas las opciones del select
    for (var i = 0; i < selectUsuario.options.length; i++) {
        var option = selectUsuario.options[i];
        
        // Verificar si la opción está seleccionada
        if (option.selected) {
            // Agregar el valor de la opción a la lista de receptores
            receptores.push(option.value);
        }
    }

    // Enviar un mensaje para cada receptor
    receptores.forEach(function (receptor) {
        // Emitir el evento para enviar el mensaje de correo al servidor
        socket.emit("enviar_mensaje_correo", {
            year: year,
            institucion: institucion,
            emisor: emisor,
            asunto: asunto,
            contenido: contenido,
            receptor: receptor
        });
    });
}