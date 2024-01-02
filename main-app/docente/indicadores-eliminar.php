<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0144';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Indicadores.php");
require_once(ROOT_PATH."/main-app/class/Calificaciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
    $actividadesRelacionadasConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividades 
    WHERE act_id_tipo='".base64_decode($_GET["idIndicador"])."' AND act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

while($actividadesRelacionadasDatos = mysqli_fetch_array($actividadesRelacionadasConsulta, MYSQLI_BOTH)){
    try{
        mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividades SET act_estado=0, act_fecha_eliminacion=now(), act_motivo_eliminacion='Eliminar indicadores de carga: ".$cargaConsultaActual.", del P: ".$periodoConsultaActual."' WHERE act_id='".$actividadesRelacionadasDatos['act_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
}

try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_indicadores_carga WHERE ipc_id='".base64_decode($_GET["idR"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$sumaIndicadores = Indicadores::consultarSumaIndicadores($conexion, $config, $cargaConsultaActual, $periodoConsultaActual);
$porcentajePermitido = 100 - $sumaIndicadores[0];
$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);

//Si decide poner los valores porcentuales de los indicadores de forma manual
if($datosCargaActual['car_valor_indicador']==1){}else{
//El sistema reparte los porcentajes automáticamente y equitativamente.
    $valorIgualIndicador = ($porcentajePermitido/($sumaIndicadores[2]));
    //Actualiza todos valores de la misma carga y periodo.
    Indicadores::actualizarValorIndicadores($conexion, $config, $cargaConsultaActual, $periodoConsultaActual, $valorIgualIndicador);
}

//Si los valores de las calificaciones son de forma automática.
if($datosCargaActual['car_configuracion']==0){
	Calificaciones::actualizarValorCalificacionesDeUnaCarga($conexion, $config, $cargaConsultaActual, $periodoConsultaActual);
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="indicadores.php?error=ER_DT_3";</script>';
exit();