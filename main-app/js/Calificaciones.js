function def(enviada){
    var split = enviada.name.split('-');
    var nota = enviada.value;
    var codEst = enviada.id;
    var per = split[0];
    var notaAnterior = enviada.alt;
    var carga = split[1];

    var casilla = document.getElementById(codEst);

    if (alertValidarNota(nota)) {
        return false;
    }

    casilla.disabled="disabled";
    casilla.style.fontWeight="bold";

    $('#respRP').empty().hide().html("esperando...").show(1);
    datos = "nota="+(nota)+
                "&per="+(per)+
                "&codEst="+(codEst)+
                "&notaAnterior="+(notaAnterior)+
                "&carga="+(carga);
            $.ajax({
                type: "POST",
                url: "ajax-periodos-registrar.php",
                data: datos,
                success: function(data){
                $('#respRP').empty().hide().html(data).show(1);
                }
            });

}

function niv(enviada){
    var split = enviada.name.split('-');
    var nota = enviada.value;
    var codEst = enviada.id;
    var per = split[0];
    var carga = split[1];

    var casilla = document.getElementById(codEst);

    if (alertValidarNota(nota)) {
        return false;
    }

    casilla.disabled="disabled";
    casilla.style.fontWeight="bold";

    $('#respRP').empty().hide().html("esperando...").show(1);
    datos = "nota="+(nota)+
                "&per="+(per)+
                "&codEst="+(codEst)+
                "&carga="+(carga);
            $.ajax({
                type: "POST",
                url: "ajax-nivelaciones-registrar.php",
                data: datos,
                success: function(data){
                $('#respRP').empty().hide().html(data).show(1);
                }
            });

}

/**
 * Esta función sirve para registrar la notas de un estudiante
 * @param enviada //Datos enviados por imput
 */
function notasGuardar(enviada){

	var nota = enviada.value;
	var notaAnterior = enviada.name;	
	var codEst = enviada.id;
	var codNota = enviada.title;	 
	var nombreEst = enviada.alt;

	
	if (alertValidarNota(nota)) {		
		return false;
	}

	aplicarColorNota(nota, codEst);

	$('#respRCT').empty().hide().html("Guardando la nota, espere por favor...").show(1);

	datos = "nota="+(nota)+
			"&codNota="+(codNota)+
			"&notaAnterior="+(notaAnterior)+
			"&nombreEst="+(nombreEst)+
			"&codEst="+(codEst);
			$.ajax({
				type: "POST",
				url: "ajax-notas-guardar.php",
				data: datos,
				success: function(data){
					$('#respRCT').empty().hide().html(data).show(1);
				}
			});
}

/**
 * Esta función sirve para registrar una misma nota a todos los estudiantes
 * @param enviada //Datos enviados por imput
 */
function notasMasiva(enviada){
    var nota = enviada.value;
	var codNota = enviada.name;	
    var recargarPanel = enviada.title;

    if (alertValidarNota(nota)) {
        return false;
    }

    $('#respRCT').empty().hide().html("Guardando información, espere por favor...").show(1);
        datos = "nota="+(nota)+
                "&codNota="+(codNota)+
                "&recargarPanel="+(recargarPanel);
                $.ajax({
                    type: "POST",
                    url: "ajax-notas-masiva-guardar.php",
                    data: datos,
                    success: function(data){
                        $('#respRCT').empty().hide().html(data).show(1);
                    }  
                });
}

/**
 * Esta función sirve para registrar una nota de recuperacion a un estudiante
 * @param enviada //Datos enviados por input
 */
function notaRecuperacion(enviada){
    var codEst = enviada.id; 
    var nota = enviada.value;
    var notaAnterior = enviada.name;	
    var nombreEst = enviada.alt;
    var codNota = enviada.title;

    if (alertValidarNota(nota)) {
        return false;
    }

    $('#respRCT').empty().hide().html("Guardando información, espere por favor...").show(1);
        datos = "nota="+(nota)+
                "&codNota="+(codNota)+
                "&notaAnterior="+(notaAnterior)+
                "&nombreEst="+(nombreEst)+
                "&codEst="+(codEst);
                $.ajax({
                    type: "POST",
                    url: "ajax-nota-recuperacion-guardar.php",
                    data: datos,
                    success: function(data){
                        $('#respRCT').empty().hide().html(data).show(1);
                    }  
                });
}

/**
 * Esta función sirve para registrar la observacion en una actividad de un estudiante
 * @param enviada //Datos enviados por input
 */
function guardarObservacion(enviada){
    var codEst = enviada.id; 
    var observacion = enviada.value;	
    var nombreEst = enviada.alt;
    var codObservacion = enviada.title;

    $('#respRCT').empty().hide().html("Guardando información, espere por favor...").show(1);
        datos = "observacion="+(observacion)+
                "&codObservacion="+(codObservacion)+
                "&nombreEst="+(nombreEst)+
                "&codEst="+(codEst);
                $.ajax({
                    type: "POST",
                    url: "ajax-observaciones-guardar.php",
                    data: datos,
                    success: function(data){
                        $('#respRCT').empty().hide().html(data).show(1);
                    }  
                });
}

/**
 * Esta función sirve para registrar una misma nota de disciplina a todos los estudiantes
 * @param enviada //Datos enviados por input
 */
function notasMasivaDisciplina(enviada){
    var nota = enviada.value;
	var carga = enviada.name;	
    var periodo = enviada.title;

    if (alertValidarNota(nota)) {
        return false;
    }

    $('#respRCT').empty().hide().html("Guardando información, espere por favor...").show(1);
        datos = "nota="+(nota)+
                "&carga="+(carga)+
                "&periodo="+(periodo);
                $.ajax({
                    type: "POST",
                    url: "ajax-notas-disciplina-masiva-guardar.php",
                    data: datos,
                    success: function(data){
                        $('#respRCT').empty().hide().html(data).show(1);
                    }  
                });
}

/**
 * Esta función sirve para registrar la nota de disciplina de un estudiante
 * @param enviada //Datos enviados por input
 */
function notasDisciplina(enviada){
    var nota = enviada.value;
	var carga = enviada.name;	
    var periodo = enviada.title;
	var codEst = enviada.id;
	var nombreEst = enviada.alt;

    if (alertValidarNota(nota)) {
        return false;
    }

    $('#respRCT').empty().hide().html("Guardando información, espere por favor...").show(1);
        datos = "nota="+(nota)+
                "&carga="+(carga)+
                "&periodo="+(periodo)+
                "&codEst="+(codEst)+
                "&nombreEst="+(nombreEst);
                $.ajax({
                    type: "POST",
                    url: "ajax-nota-disciplina-guardar.php",
                    data: datos,
                    success: function(data){
                        $('#respRCT').empty().hide().html(data).show(1);
                    }  
                });
}

/**
 * Esta función sirve para registrar una observación disciplinaria a un estudiante
 * @param enviada //Datos enviados por textarea
 */
function observacionDisciplina(enviada){
    var periodo = enviada.title;
    var observacion = enviada.value;
	var carga = enviada.step;	
	var codEst = enviada.id;
	var multiple = enviada.alt;

    if(multiple == 1){
        var nameId = enviada.name;
        var observaciones = document.getElementById(nameId);
        var observacion = [];
        for (let i = 0; i < observaciones.options.length; i++) {
            if (observaciones.options[i].selected) {
                observacion.push(observaciones.options[i].value);
            }
        }
    }

    $('#respRCT').empty().hide().html("Guardando información, espere por favor...").show(1);
        datos = "observacion="+(observacion)+
                "&carga="+(carga)+
                "&periodo="+(periodo)+
                "&codEst="+(codEst);
                $.ajax({
                    type: "POST",
                    url: "ajax-observacion-disciplina-guardar.php",
                    data: datos,
                    success: function(data){
                        $('#respRCT').empty().hide().html(data).show(1);
                    }  
                });
}

/**
 * Esta función sirve para registrar el aspecto academico de un estudiante
 * @param enviada //Datos enviados por textarea
 */
function aspectosAcademicos(enviada){
    var aspecto = enviada.value;
	var carga = enviada.name;	
    var periodo = enviada.title;
	var codEst = enviada.id;

    $('#respRCT').empty().hide().html("Guardando información, espere por favor...").show(1);
        datos = "aspecto="+(aspecto)+
                "&carga="+(carga)+
                "&periodo="+(periodo)+
                "&codEst="+(codEst);
                $.ajax({
                    type: "POST",
                    url: "ajax-aspectos-academicos-guardar.php",
                    data: datos,
                    success: function(data){
                        $('#respRCT').empty().hide().html(data).show(1);
                    }  
                });
}

/**
 * Esta función sirve para registrar el aspecto convivencial de un estudiante
 * @param enviada //Datos enviados por textarea
 */
function aspectosConvivencial(enviada){
    var aspecto = enviada.value;
	var carga = enviada.name;	
    var periodo = enviada.title;
	var codEst = enviada.id;

    $('#respRCT').empty().hide().html("Guardando información, espere por favor...").show(1);
        datos = "aspecto="+(aspecto)+
                "&carga="+(carga)+
                "&periodo="+(periodo)+
                "&codEst="+(codEst);
                $.ajax({
                    type: "POST",
                    url: "ajax-aspectos-convivencional-guardar.php",
                    data: datos,
                    success: function(data){
                        $('#respRCT').empty().hide().html(data).show(1);
                    }  
                });
}

/**
 * Esta función sirve para registrar las observaciones que se ven reflejadas en el boletín de un estudiante
 * @param enviada //Datos enviados por textarea
 */
function observacionesBoletin(enviada){
    var observacion = enviada.value;
	var carga = enviada.name;	
    var periodo = enviada.title;
	var codEst = enviada.id;
    console.log(carga);

    $('#respOBS').empty().hide().html("Guardando información, espere por favor...").show(1);
        datos = "observacion="+(observacion)+
                "&carga="+(carga)+
                "&periodo="+(periodo)+
                "&codEst="+(codEst);
                $.ajax({
                    type: "POST",
                    url: "ajax-observacion-boletin-guardar.php",
                    data: datos,
                    success: function(data){
                        $('#respOBS').empty().hide().html(data).show(1);
                    }  
                });
}

/**
 * Esta función sirve para registrar la recuperación en un indicador de un estudiante
 * @param enviada //Datos enviados por input
 */
function recuperarIndicador(enviada){
    var split = enviada.step.split('-');
    var carga = split[0];
    var periodo = split[1];

    var nota = enviada.value;
    var notaAnterior = enviada.name;	
    var codEst = enviada.id;
    var codNota = enviada.alt;
    var valorDecimalIndicador = enviada.title;
        
    var casilla = document.getElementById(codEst);

    var notaAnteriorTransformada = (notaAnterior/valorDecimalIndicador);
    notaAnteriorTransformada = Math.round(notaAnteriorTransformada * 10) / 10;

    if(isNaN(nota)){
        Swal.fire('Esto no es un valor numérico: '+nota+'. Si estás usando comas, reemplacelas por un punto.'); 
        casilla.value="";
        casilla.focus();
        return false;	
    }	

    if (alertValidarNota(nota)) {
        casilla.value="";
        casilla.focus();
        return false;
    }
    if(nota==notaAnteriorTransformada){
        Swal.fire(`No es permitido colocar una nota de recuperación igual: ${nota} a la nota anterior: ${notaAnteriorTransformada}.`);
        casilla.value="";
        casilla.focus();
        return false;
    }	
        
        
    casilla.disabled="disabled";
    casilla.style.fontWeight="bold";
            
    $('#respRC').empty().hide().html("Guardando información, espere por favor...").show(1);
        datos = "nota="+(nota)+
                "&codNota="+(codNota)+
                "&notaAnterior="+(notaAnterior)+
                "&carga="+(carga)+
                "&periodo="+(periodo)+
                "&codEst="+(codEst);
                $.ajax({
                    type: "POST",
                    url: "ajax-recuperacion-indicadores-guardar.php",
                    data: datos,
                    success: function(data){
                        $('#respRC').empty().hide().html(data).show(1);
                    }
                });
}