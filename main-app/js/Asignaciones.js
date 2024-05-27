/**
 * Esta función imprime los option en el select de los evaluado.
 */
function selectEvaluado(datos){
    var evaluado = datos.getAttribute('data-id-evaluado');
    $('#evaluado').empty().hide().html("").show(1);
    fetch('../directivo/ajax-evaluado-option.php?tipoEncuesta=' + datos.value +'&idEvaluado=' + evaluado, {
        method: 'GET'
    })
    .then(response => response.text())
    .then(data => {
        $('#evaluado').empty().hide().html(data).show(1);
    })
    .catch(error => {
        // Manejar errores
        console.error('Error:', error);
    });
}

/**
 * Esta función mustra el select de los cursos.
 */
function mostrarSelectCurso(datos){
    var elementCurso = document.getElementById('elementSelectCurso');
    if (datos.value === 'CURSO' || datos.value === 'ACUDIENTE') {
        elementCurso.style.display = 'contents';
    } else {
        elementCurso.style.display = 'none';
    }
}