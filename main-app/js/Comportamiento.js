/**
 * Esta función me actualiza un sub rol
 */
function comportamientoPeriodo(datosPeriodo) {
    var idInput         = datosPeriodo.id;
    var periodo         = datosPeriodo.value;
    var idRegistro      = datosPeriodo.getAttribute("data-id-registro");
    var periodoActual   = datosPeriodo.getAttribute("data-periodo-actual");
    var periodoTotal    = datosPeriodo.getAttribute("data-periodo-total");

    var inputElement    = document.getElementById(idInput);

    if (periodo <= periodoTotal) {
        fetch('../directivo/comportamiento-actualizar-periodo.php?periodo=' + periodo + '&id=' + idRegistro, {
            method: 'GET'
        })
            .then(response => response.text()) // Convertir la respuesta a texto
            .then(data => {

                inputElement.dataset.periodoActual = periodo;

                $.toast({

                    heading: 'Proceso completado',
                    text: 'El periodo del comportamiento se actualizo correctamente...',
                    position: 'bottom-right',
                    showHideTransition: 'slide',
                    loaderBg: '#26c281',
                    icon: 'success',
                    hideAfter: 5000,
                    stack: 6

                });
            })
            .catch(error => {
                // Manejar errores
                console.error('Error:', error);
            });
    } else {

        inputElement.value = periodoActual;

        $.toast({

            heading: 'Periodo Invalido',
            text: 'El periodo que ingreso no es valido...',
            position: 'bottom-right',
            showHideTransition: 'slide',
            loaderBg: '#26c281',
            icon: 'warning',
            hideAfter: 5000,
            stack: 6

        });
    }
}

/**
 * Alterna entre mostrar el texto truncado o completo de un elemento.
 * 
 * La función `toggleFullText` se activa cuando un usuario hace clic en un elemento
 * con un título completo en su atributo `title`. Cambia entre mostrar una versión truncada
 * y la versión completa del texto. Si el texto ya está expandido, lo trunca a 20 caracteres 
 * y añade puntos suspensivos (`...`). De lo contrario, muestra el texto completo.
 * 
 * @param {HTMLElement} element - El elemento en el cual se alternará el texto.
 */
function toggleFullText(element) {
    const fullText = element.getAttribute('data-observacion'); // Obtiene el texto completo del atributo 'data-observacion'.
    const isExpanded = element.getAttribute('data-expanded') === 'true'; // Verifica si el texto ya está expandido.

    if (isExpanded) {
        element.innerHTML = fullText.substring(0, 20) + '...'; // Trunca el texto a 20 caracteres y añade '...'.
        element.setAttribute('data-expanded', 'false'); // Marca el elemento como no expandido.
    } else {
        element.innerHTML = fullText; // Muestra el texto completo.
        element.setAttribute('data-expanded', 'true'); // Marca el elemento como expandido.
    }
}