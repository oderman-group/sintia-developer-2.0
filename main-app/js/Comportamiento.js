/**
 * Esta función me actualiza un sub rol
 */
function comportamientoPeriodo(datosPeriodo) {
    var periodo  = datosPeriodo.value;
    var id  = datosPeriodo.id;
    
    if(periodo <= 4){
        fetch('../directivo/comportamiento-actualizar-periodo.php?periodo='+periodo+'&id='+id, {
            method: 'GET'
        })
        .then(response => response.text()) // Convertir la respuesta a texto
        .then(data => {
            $.toast({
    
                heading: 'Proceso completado', 
                text: 'El periodo del comportamiento se actualizo correctamente...', 
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
    }else{
        $.toast({

            heading: 'Periodo Invalido', 
            text: 'El periodo que ingreso no es valido...', 
            position: 'bottom-right',
            showHideTransition: 'slide',
            loaderBg:'#26c281', 
            icon: 'warning', 
            hideAfter: 5000, 
            stack: 6

        });
    }
}