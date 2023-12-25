/**
 * Actualiza el subtotal según el precio y la cantidad especificados.
 * @param {string} id - Identificador del elemento o 'idNuevo' para un nuevo item.
 */
function actualizarSubtotal(id) {
    var idItem=document.getElementById('idItemNuevo').innerText;
    // Obtener los elementos
    var precioElement = document.getElementById('precioNuevo');
    var cantidadElement = document.getElementById('cantidadItemNuevo');
    var subtotalElement = document.getElementById('subtotalNuevo');
    if(id !== 'idNuevo'){
        var idItem=id
        // Obtener los elementos
        var precioElement = document.getElementById('precio'+id);
        var cantidadElement = document.getElementById('cantidadItems'+id);
        var subtotalElement = document.getElementById('subtotal'+id);
    }

    // Obtener los valores
    var precio = parseFloat(precioElement.innerText);
    var cantidad = parseFloat(cantidadElement.value);

    // Calcular el subtotal
    var subtotal = precio * cantidad;
    
    fetch('../directivo/ajax-cambiar-subtotal.php?subtotal='+(subtotal)+'&cantidad='+(cantidad)+'&idItem='+(idItem), {
        method: 'GET'
    })
    .then(response => response.text()) // Convertir la respuesta a texto
    .then(data => {
        subtotalElement.innerHTML = '';
        subtotalElement.appendChild(document.createTextNode(subtotal));
    })
    .catch(error => {
         // Manejar errores
        console.error('Error:', error);
    });
}

/**
 * Trae y actualiza la lista de items asociados a una transacción.
 */
function traerItems(){
    // Obtener el valor del ID de transacción desde el elemento HTML
    var idTransaction = document.getElementById('idTransaction').value;

    // Mostrar un mensaje de carga mientras se obtienen los items
    $('#mostrarItems').empty().hide().html("Cargando Items...").show(1);
    
    // Realizar una solicitud fetch para obtener los items asociados a la transacción
    fetch('../directivo/ajax-traer-items.php?idTransaction=' + idTransaction, {
        method: 'GET'
    })
    .then(response => response.text()) // Convertir la respuesta a texto
    .then(data => {
        // Actualizar el contenido de 'mostrarItems' con la respuesta obtenida
        $('#mostrarItems').empty().hide().html(data).show(1);
    })
    .catch(error => {
        // Manejar errores
        console.error('Error:', error);
    });
}

/**
 * Guarda un nuevo item asociado a una transacción.
 * @param {HTMLSelectElement} selectElement - El elemento select que contiene la opción seleccionada.
 */
function guardarNuevoItem(selectElement) {
    // Obtener los elementos del DOM
    var itemElement = document.getElementById('idItemNuevo');
    var precioElement = document.getElementById('precioNuevo');
    var subtotalElement = document.getElementById('subtotalNuevo');

    // Obtener el ID de la transacción desde el elemento del DOM
    var idTransaction = document.getElementById('idTransaction').value;

    // Obtener la opción seleccionada del elemento select
    var itemSelecionado = selectElement.options[selectElement.selectedIndex];

    // Obtener el ID del item, el precio y calcular el subtotal
    var idItem = itemSelecionado.value;
    var precio = itemSelecionado.getAttribute('name');
    var subtotal = precio * 1;

    // Realizar una solicitud fetch para guardar el nuevo item
    fetch('../directivo/ajax-guardar-items.php?idTransaction=' + idTransaction + '&idItem=' + idItem + '&precio=' + precio, {
        method: 'GET'
    })
    .then(response => response.json()) // Convertir la respuesta a objeto JSON
    .then(data => {
        // Actualizar los elementos del DOM con los datos recibidos
        itemElement.innerHTML = '';
        itemElement.appendChild(document.createTextNode(data.idInsercion));
        precioElement.innerHTML = '';
        precioElement.appendChild(document.createTextNode(precio));
        subtotalElement.innerHTML = '';
        subtotalElement.appendChild(document.createTextNode(subtotal));
    })
    .catch(error => {
        // Manejar errores
        console.error('Error:', error);
    });
}

/**
 * Realiza la acción de añadir un nuevo item.
 * Limpia y actualiza los elementos relacionados con la información del nuevo item.
 */
function nuevoItem() {
    // Realizar la acción de traer nuevos items
    traerItems();

    // Obtener elementos del DOM
    var idItemNuevo = document.getElementById('idItemNuevo');
    var precioNuevo = document.getElementById('precioNuevo');
    var subtotalNuevo = document.getElementById('subtotalNuevo');
    var items = document.getElementById('items');

    // Limpiar y reiniciar los elementos del DOM relacionados con el nuevo item
    idItemNuevo.innerHTML = '';
    precioNuevo.innerHTML = '';
    subtotalNuevo.innerHTML = '';
    items.innerHTML = '';
}