function notas(enviada){
    const idSplit = enviada.id.split('-');
    var codNota = enviada.name;	 
    var nota = enviada.value;
    var codEst = idSplit[0];    
    var carga = idSplit[2];
    var periodo = idSplit[3];
    var operacion = enviada.title;
    var notaAnterior = enviada.step;

    //Para operaciones que vienen de textarea
    if(operacion == 8) {
        var nombreEst = idSplit[1];
        var idResponse = "#respOBS";
    } else {
        var nombreEst = enviada.alt;
        var idResponse = "#respRCT";
    }

    if(operacion == 1 || operacion == 3){
        if (alertValidarNota(nota)) {
            return false;
        }
    }

    if(operacion == 1) {
        aplicarColorNota(nota, enviada.id);
    }

    if(operacion == 3) {
        var recargarPanel=1;
    }
        
    $(idResponse).empty().hide().html("Guardando información, espere por favor...").show(1);
    datos = "nota="+(nota)+
            "&codNota="+(codNota)+
            "&operacion="+(operacion)+
            "&nombreEst="+(nombreEst)+
            "&carga="+(carga)+
			"&periodo="+(periodo)+
            "&notaAnterior="+(notaAnterior)+
            "&recargarPanel="+(recargarPanel)+
            "&codEst="+(codEst);
            $.ajax({
                type: "POST",
                url: "ajax-calificaciones-registrar.php",
                data: datos,
                success: function(data){
                $(idResponse).empty().hide().html(data).show(1);
                }
            });
}


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