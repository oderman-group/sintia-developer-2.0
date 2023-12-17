<?php
include("session.php");
require_once("../class/Sysjobs.php");
require_once("../class/CargaAcademica.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0167';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

	$existeCarga=false;
	if($_POST["docente"]!=$_POST["docenteActual"] || $_POST["curso"]!=$_POST["cursoActual"] || $_POST["grupo"]!=$_POST["grupoActual"] || $_POST["asignatura"]!=$_POST["asignaturaActual"]){
		$existeCarga = CargaAcademica::validarExistenciaCarga($_POST["docente"], $_POST["curso"], $_POST["grupo"], $_POST["asignatura"]);
	}

	if(!$existeCarga){
		try{
			mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_cargas SET 
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
			WHERE car_id='" . $_POST["idR"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		try{
			mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_intensidad_curso WHERE ipc_curso='" . $_POST["curso"] . "' AND ipc_materia='" . $_POST["asignatura"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		$codigo=Utilidades::generateCode("IPC");
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_intensidad_curso(ipc_id, ipc_curso, ipc_materia, ipc_intensidad, institucion, year)VALUES('".$codigo."', '" . $_POST["curso"] . "','" . $_POST["asignatura"] . "','" . $_POST["ih"] . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		if($_POST["periodo"] != $_POST["periodoActual"]){
			$parametros = array(
				"carga" 	=>$_POST["idR"],
				"periodo" 	=>$_POST["periodo"],
				"grado" 	=> $_POST["curso"],
				"grupo"		=>$_POST["grupo"]
			);

			$parametrosBuscar = array(
				"tipo" 			=>JOBS_TIPO_GENERAR_INFORMES,
				"responsable" 	=> $_POST["docente"],
				"parametros" 	=> json_encode($parametros),
				"agno"			=>$config['conf_agno'],
				"estado"		=>JOBS_ESTADO_FINALIZADO
			);

			$buscarJobs=SysJobs::consultar($parametrosBuscar);
			if(mysqli_num_rows($buscarJobs)>0){
				$jobsEncontrado = mysqli_fetch_array($buscarJobs, MYSQLI_BOTH);

				try{
					mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".sys_jobs WHERE job_id='".$jobsEncontrado["job_id"]."'");
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
			}
		}
		$mensaje='success=SC_DT_2';
	}else{
		$mensaje='error=ER_DT_20';
	}

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="cargas-editar.php?idR='.base64_encode($_POST["idR"]).'&'.$mensaje.'&id='.base64_encode($_POST["idR"]).'";</script>';
exit();