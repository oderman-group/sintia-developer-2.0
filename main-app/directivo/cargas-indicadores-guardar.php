<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0098';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

include("verificar-carga.php");

try{
    $consultaSumaIndicadores=mysqli_query($conexion, "SELECT
    (SELECT sum(ipc_valor) FROM academico_indicadores_carga 
    WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=0),
    (SELECT sum(ipc_valor) FROM academico_indicadores_carga 
    WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1),
    (SELECT count(*) FROM academico_indicadores_carga 
    WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1)");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$sumaIndicadores = mysqli_fetch_array($consultaSumaIndicadores, MYSQLI_BOTH);

$porcentajePermitido = 100 - $sumaIndicadores[0];
$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);

if ($sumaIndicadores[2] >= $datosCargaActual['car_maximos_indicadores']) {
    include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=209";</script>';
    exit();
}


try{
    mysqli_query($conexion, "INSERT INTO academico_indicadores(ind_nombre, ind_obligatorio, ind_publico) VALUES('" . $_POST["contenido"] . "', '" . $_POST["creado"] . "', 0)");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$idRegistro = mysqli_insert_id($conexion);
//Si decide poner los valores porcentuales de los indicadores de forma manual
if ($datosCargaActual['car_valor_indicador'] == 1) {
    if ($porcentajeRestante <= 0) {
        include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=210&restante=' . $porcentajeRestante . '";</script>';
        exit();
    }
    if (!is_numeric($_POST["valor"])) {
        $_POST["valor"] = 1;
    }
    //Si el valor es mayor al adecuado lo ajustamos al porcentaje restante; Siempre que este último sea mayor a 0.
    if ($_POST["valor"] > $porcentajeRestante and $porcentajeRestante > 0) {
        $_POST["valor"] = $porcentajeRestante;
    }
    try{
        mysqli_query($conexion, "INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado, ipc_evaluacion)
        VALUES('" . $cargaConsultaActual . "', '" . $idRegistro . "', '" . $_POST["valor"] . "', '" . $periodoConsultaActual . "', 1, '" . $_POST["saberes"] . "')");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
}
//El sistema reparte los porcentajes automáticamente y equitativamente.
else {
    $valorIgualIndicador = ($porcentajePermitido / ($sumaIndicadores[2] + 1));
    try{
        mysqli_query($conexion, "INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_periodo, ipc_creado, ipc_evaluacion)
        VALUES('" . $cargaConsultaActual . "', '" . $idRegistro . "', '" . $periodoConsultaActual . "', 1, '" . $_POST["saberes"] . "')");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
    //Actualiza todos valores de la misma carga y periodo; incluyendo el que acaba de crear.
    try{
        mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='" . $valorIgualIndicador . "' WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
}

//Si las calificaciones son de forma automática.
if ($datosCargaActual['car_configuracion'] == 0) {
    //Repetimos la consulta de los indicadores porque los valores fueron actualizados
    try{
        $indicadoresConsultaActualizado = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga 
        WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
    //Actualizamos todas las actividades por cada indicador
    while ($indicadoresDatos = mysqli_fetch_array($indicadoresConsultaActualizado, MYSQLI_BOTH)) {
        try{
            $consultaNumActividades=mysqli_query($conexion, "SELECT * FROM academico_actividades 
            WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $actividadesNum = mysqli_num_rows($consultaNumActividades);
        //Si hay actividades relacionadas al indicador, actualizamos su valor.
        if ($actividadesNum > 0) {
            $valorIgualActividad = ($indicadoresDatos['ipc_valor'] / $actividadesNum);
            try{
                mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='" . $valorIgualActividad . "' 
                WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
        }
    }
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="cargas-indicadores.php?carga=' . base64_encode($cargaConsultaActual) . '&periodo=' . base64_encode($periodoConsultaActual) . '&docente=' . $_GET["docente"] . '";</script>';
exit();