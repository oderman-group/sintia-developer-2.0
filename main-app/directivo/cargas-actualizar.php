<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0167';

if(!Modulos::validarSubRol($idPaginaInterna)){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "UPDATE academico_cargas SET 
	car_docente='" . $_POST["docente"] . "', 
	car_curso='" . $_POST["curso"] . "', 
	car_grupo='" . $_POST["grupo"] . "', 
	car_materia='" . $_POST["asignatura"] . "', 
	car_periodo='" . $_POST["periodo"] . "', 
	car_director_grupo='" . $_POST["dg"] . "', 
	car_ih=" . $_POST["ih"] . ", 
	car_activa='" . $_POST["estado"] . "', 
	car_maximos_indicadores='" . $_POST["maxIndicadores"] . "', 
	car_maximas_calificaciones='" . $_POST["maxActividades"] . "', 
	car_configuracion='" . $_POST["valorActividades"] . "', 
	car_valor_indicador='" . $_POST["valorIndicadores"] . "', 
	car_permiso1='" . $_POST["permiso1"] . "', 
	car_permiso2='" . $_POST["permiso2"] . "', 
	car_indicador_automatico='" . $_POST["indicadorAutomatico"] . "',
	car_observaciones_boletin='" . $_POST["observacionesBoletin"] . "' 
	WHERE car_id='" . $_POST["idR"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

try{
	mysqli_query($conexion, "DELETE FROM academico_intensidad_curso WHERE ipc_curso='" . $_POST["curso"] . "' AND ipc_materia='" . $_POST["asignatura"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

try{
	mysqli_query($conexion, "INSERT INTO academico_intensidad_curso(ipc_curso, ipc_materia, ipc_intensidad)VALUES('" . $_POST["curso"] . "','" . $_POST["asignatura"] . "','" . $_POST["ih"] . "')");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="cargas-editar.php?idR='.$_POST["idR"].'&success=SC_DT_2&id='.$_POST["idR"].'";</script>';
exit();