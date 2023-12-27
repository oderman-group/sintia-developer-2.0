/**
 * Formatea un número con separadores de miles y decimales personalizables.
 * @param {number} number - El número a formatear.
 * @param {number} [decimals=0] - La cantidad de decimales a mostrar (por defecto, 0).
 * @param {string} [decPoint=','] - El separador decimal (por defecto, ',').
 * @param {string} [thousandsSep='.'] - El separador de miles (por defecto, '.').
 * @returns {string} - El número formateado como cadena.
 */
function numberFormat(number, decimals = 0, decPoint = ',', thousandsSep = '.') {
    // Validar que number sea un número
    if (isNaN(number) || number === '' || number === null) {
        return '';
    }

    // Redondear el número al número especificado de decimales
    number = parseFloat(number.toFixed(decimals));

    // Convertir el número a una cadena y separar los miles
    var parts = number.toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);

    // Unir la parte entera y decimal con el separador decimal
    var result = parts.join(decPoint);

    return result;
}

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
    var idSubtotal = document.getElementById('subtotal');
    var idTotalNeto = document.getElementById('totalNeto');

    // Obtener los valores
    var precio = parseFloat(precioElement.getAttribute("data-precio"));
    var cantidad = parseFloat(cantidadElement.value);
    var subtotalAnterior = parseFloat(subtotalElement.getAttribute("data-subtotal-anterior"));
    var subtotalNeto = parseFloat(idSubtotal.getAttribute("data-subtotal"));
    var total = parseFloat(idTotalNeto.getAttribute("data-total-neto"));

    // Calcular el subtotal
    var subtotal = precio * cantidad;
    var subtotalFormat = "$"+numberFormat(subtotal, 0, ',', '.');

    var subtotalNetoFinal= (subtotalNeto-subtotalAnterior)+subtotal;
    var subtotalNetoFormat = "$"+numberFormat(subtotalNetoFinal, 0, ',', '.');

    var totalNetoFinal= (total-subtotalAnterior)+subtotal;
    var totalFormat = "$"+numberFormat(totalNetoFinal, 0, ',', '.');
    
    fetch('../directivo/ajax-cambiar-subtotal.php?subtotal='+(subtotal)+'&cantidad='+(cantidad)+'&idItem='+(idItem), {
        method: 'GET'
    })
    .then(response => response.text()) // Convertir la respuesta a texto
    .then(data => {
        subtotalElement.innerHTML = '';
        subtotalElement.appendChild(document.createTextNode(subtotalFormat));
        subtotalElement.dataset.subtotalAnterior = subtotal;

        idSubtotal.innerHTML = '';
        idSubtotal.appendChild(document.createTextNode(subtotalNetoFormat));
        idSubtotal.dataset.subtotal = subtotalNetoFinal;

        idTotalNeto.innerHTML = '';
        idTotalNeto.appendChild(document.createTextNode(totalFormat));
        idTotalNeto.dataset.totalNeto = totalNetoFinal;
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
    var vlrAdicional = document.getElementById('vlrAdicional').value;

    // Mostrar un mensaje de carga mientras se obtienen los items
    $('#mostrarItems').empty().hide().html("Cargando Items...").show(1);
    
    // Realizar una solicitud fetch para obtener los items asociados a la transacción
    fetch('../directivo/ajax-traer-items.php?idTransaction=' + idTransaction + '&vlrAdicional=' + vlrAdicional, {
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
    var cantidadElement = document.getElementById('cantidadItemNuevo');
    var subtotalElement = document.getElementById('subtotalNuevo');
    var idSubtotal = document.getElementById('subtotal');
    var idTotalNeto = document.getElementById('totalNeto');

    var itemModificar = '';
    var cantidad = 1;
    // Verificar si el contenido del idItemNuevo no esta vacio
    if (itemElement.innerHTML.trim() !== '') {
        var itemModificar = itemElement.innerHTML;
        var cantidad = cantidadElement.value;
    }

    // Obtener el ID de la transacción desde el elemento del DOM
    var idTransaction = document.getElementById('idTransaction').value;

    // Obtener la opción seleccionada del elemento select
    var itemSelecionado = selectElement.options[selectElement.selectedIndex];

    // Obtener el ID del item, el precio y calcular el subtotal
    var idItem = itemSelecionado.value;
    var precio = parseFloat(itemSelecionado.getAttribute('name'));
    var subtotalNeto = parseFloat(idSubtotal.getAttribute("data-subtotal-anterior-sub"));
    var total = parseFloat(idTotalNeto.getAttribute("data-total-neto-anterior"));

    var precioFormat = "$"+numberFormat(precio, 0, ',', '.');
    var subtotal = precio * cantidad;
    var subtotalFormat = "$"+numberFormat(subtotal, 0, ',', '.');

    var subtotalNetoFinal= subtotalNeto+subtotal;
    var subtotalNetoFormat = "$"+numberFormat(subtotalNetoFinal, 0, ',', '.');

    var totalNetoFinal= total+subtotal;
    var totalFormat = "$"+numberFormat(totalNetoFinal, 0, ',', '.');

    // Realizar una solicitud fetch para guardar el nuevo item
    fetch('../directivo/ajax-guardar-items.php?idTransaction=' + idTransaction + '&idItem=' + idItem + '&itemModificar=' + itemModificar + '&subtotal=' + subtotal + '&cantidad=' + cantidad, {
        method: 'GET'
    })
    .then(response => response.json()) // Convertir la respuesta a objeto JSON
    .then(data => {
        // Actualizar los elementos del DOM con los datos recibidos
        itemElement.innerHTML = '';
        itemElement.appendChild(document.createTextNode(data.idInsercion));

        precioElement.innerHTML = '';
        precioElement.appendChild(document.createTextNode(precioFormat));
        precioElement.dataset.precio = precio;

        cantidadElement.disabled = false;

        subtotalElement.innerHTML = '';
        subtotalElement.appendChild(document.createTextNode(subtotalFormat));
        subtotalElement.dataset.subtotalAnterior = subtotal;

        idSubtotal.innerHTML = '';
        idSubtotal.appendChild(document.createTextNode(subtotalNetoFormat));
        idSubtotal.dataset.subtotal = subtotalNetoFinal;

        idTotalNeto.innerHTML = '';
        idTotalNeto.appendChild(document.createTextNode(totalFormat));
        idTotalNeto.dataset.totalNeto = totalNetoFinal;
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
    var cantidadNuevo = document.getElementById('cantidadItemNuevo');
    var subtotalNuevo = document.getElementById('subtotalNuevo');
    var items = document.getElementById('items');
    var itemsContainer = document.getElementById('select2-items-container');

    // Limpiar y reiniciar los elementos del DOM relacionados con el nuevo item
    idItemNuevo.innerHTML = '';
    precioNuevo.innerHTML = '$0';
    precioNuevo.dataset.precio = 0;
    cantidadNuevo.value = 1;
    cantidadNuevo.disabled = true;
    subtotalNuevo.innerHTML = '$0';
    subtotalNuevo.dataset.subtotalAnterior = 0;
    items.value = '';
    itemsContainer.innerHTML = 'Seleccione una opción';
}

/**
 * Realiza la acción de añadir o modificar el valor adiconal.
 * Limpia y actualiza los elementos relacionados con la información de valor adicional.
 */
function cambiarAdiconal(data) {
    var idValorAdicional = document.getElementById('valorAdicional');
    var idTotalNeto = document.getElementById('totalNeto');

    var vlrAdicional= parseFloat(data.value);
    var vlrAdicionalAnteriorValor= parseFloat(data.getAttribute('data-vlr-adicional-anterior'));
    var totalNeto= parseFloat(idTotalNeto.getAttribute('data-total-neto'));

    var total= (totalNeto-vlrAdicionalAnteriorValor)+vlrAdicional;

    var vlrAdicionalFinal = "$"+numberFormat(vlrAdicional, 0, ',', '.');
    var totalFinal = "$"+numberFormat(total, 0, ',', '.');

    // Limpiar y reiniciar los elementos del DOM relacionados con el nuevo item
    idValorAdicional.innerHTML = '';
    idValorAdicional.appendChild(document.createTextNode(vlrAdicionalFinal));
    idValorAdicional.dataset.valorAdicional = vlrAdicional;
    data.dataset.vlrAdicionalAnterior = vlrAdicional;
    
    idTotalNeto.innerHTML = '';
    idTotalNeto.appendChild(document.createTextNode(totalFinal));
    idTotalNeto.dataset.totalNeto = total;
}