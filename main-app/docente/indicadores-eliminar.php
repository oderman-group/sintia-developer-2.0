<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0144';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
    $actividadesRelacionadasConsulta = mysqli_query($conexion, "SELECT * FROM academico_actividades 
    WHERE act_id_tipo='".base64_decode($_GET["idIndicador"])."' AND act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

while($actividadesRelacionadasDatos = mysqli_fetch_array($actividadesRelacionadasConsulta, MYSQLI_BOTH)){
    try{
        mysqli_query($conexion, "UPDATE academico_actividades SET act_estado=0, act_fecha_eliminacion=now(), act_motivo_eliminacion='Eliminar indicadores de carga: ".$cargaConsultaActual.", del P: ".$periodoConsultaActual."' WHERE act_id='".$actividadesRelacionadasDatos['act_id']."'");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
}

try{
    mysqli_query($conexion, "DELETE FROM academico_indicadores_carga WHERE ipc_id='".base64_decode($_GET["idR"])."'");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

try{
    $consultaSumaIndicadores=mysqli_query($conexion, "SELECT
    (SELECT sum(ipc_valor) FROM academico_indicadores_carga 
    WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=0),
    (SELECT sum(ipc_valor) FROM academico_indicadores_carga 
    WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1),
    (SELECT count(*) FROM academico_indicadores_carga 
    WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1)
    ");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$sumaIndicadores = mysqli_fetch_array($consultaSumaIndicadores, MYSQLI_BOTH);
$porcentajePermitido = 100 - $sumaIndicadores[0];
$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);

//Si decide poner los valores porcentuales de los indicadores de forma manual
if($datosCargaActual['car_valor_indicador']==1){}else{
//El sistema reparte los porcentajes automáticamente y equitativamente.
    $valorIgualIndicador = ($porcentajePermitido/($sumaIndicadores[2]));
    //Actualiza todos valores de la misma carga y periodo.
    try{
        mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='".$valorIgualIndicador."' 
        WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }

    //Si decide que los valores de las calificaciones son de forma automática.
    if($datosCargaActual['car_configuracion']==0){
        //Repetimos la consulta de los indicadores porque los valores fueron actualizados
        try{
            $indicadoresConsultaActualizado = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga 
            WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }

        //Actualizamos todas las actividades por cada indicador
        while($indicadoresDatos = mysqli_fetch_array($indicadoresConsultaActualizado, MYSQLI_BOTH)){
            try{
                $consultaActividadesNum=mysqli_query($conexion, "SELECT * FROM academico_actividades 
                WHERE act_id_tipo='".$indicadoresDatos['ipc_indicador']."' AND act_periodo='".$periodoConsultaActual."' AND act_id_carga='".$cargaConsultaActual."' AND act_estado=1");
            } catch (Exception $e) {
                include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
            }
            $actividadesNum = mysqli_num_rows($consultaActividadesNum);
            //Si hay actividades relacionadas al indicador, actualizamos su valor.
            if($actividadesNum>0){
                $valorIgualActividad = ($indicadoresDatos['ipc_valor']/$actividadesNum);
                try{
                    mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='".$valorIgualActividad."' 
                    WHERE act_id_tipo='".$indicadoresDatos['ipc_indicador']."' AND act_periodo='".$periodoConsultaActual."' AND act_id_carga='".$cargaConsultaActual."' AND act_estado=1");
                } catch (Exception $e) {
                    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
                }
            }
        }
    }
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="indicadores.php?error=ER_DT_3";</script>';
exit();