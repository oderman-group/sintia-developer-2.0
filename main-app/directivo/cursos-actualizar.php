<?php
include("session.php");
require_once("../class/servicios/GradoServicios.php");
require_once("../class/servicios/MediaTecnicaServicios.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0173';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombreC"]) == "" or trim($_POST["formatoB"]) == "" or trim($_POST["valorM"]) == "" or trim($_POST["valorP"]) == "") {
		echo '<script type="text/javascript">window.location.href="cursos-editar.php?error=ER_DT_4&id='.base64_encode($_POST["id_curso"]).'";</script>';
		exit();
	}

if(empty($_POST["estado"])){$_POST["estado"]=1;}
$esMediaTecnica=!is_null($_POST["tipoG"]);
if(!$esMediaTecnica){
	$resultadoCurso=GradoServicios::consultarCurso($_POST["id_curso"]);
	$_POST["tipoG"]=$resultadoCurso['gra_tipo'];
}
if(empty($_POST["tipoG"])) {$_POST["tipoG"] = GRADO_GRUPAL;}
if(empty($_POST["estado"])){$_POST["estado"]=1;}

try{
	mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_grados SET 
	gra_codigo='" . $_POST["codigoC"] . "', 
	gra_nombre='" . $_POST["nombreC"] . "', 
	gra_formato_boletin='" . $_POST["formatoB"] . "', 
	gra_valor_matricula='" . $_POST["valorM"] . "', 
	gra_valor_pension='" . $_POST["valorP"] . "', 
	gra_grado_siguiente='" . $_POST["graSiguiente"] . "', 
	gra_grado_anterior='" . $_POST["graAnterior"] . "', 
	gra_nota_minima='" . $_POST["notaMin"] . "', 
	gra_periodos='" . $_POST["periodosC"] . "', 
	gra_nivel='" . $_POST["nivel"] . "', 
	gra_estado='" . $_POST["estado"] . "',
	gra_tipo='" . $_POST["tipoG"] . "'
	WHERE gra_id='" . $_POST["id_curso"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

if ($_POST["tipoG"]==GRADO_INDIVIDUAL) { 
	if(!empty($_POST["estudiantesMT"])){
		$parametros = [
			'matcur_id_curso'=>$_POST["id_curso"],
			'matcur_id_institucion'=>$config['conf_id_institucion'],
			'arreglo'=>true
		];
		$consulta = MediaTecnicaServicios::listarEstudiantes($parametros);
		$idEstudianteMT = array();
		foreach ($consulta as $subarreglo) {
			$idEstudianteMT[] = $subarreglo['matcur_id_matricula'];
		}
		//Agregamos los estudiantes que no esten en registrados en la BD
		$resultadoAgregar= array_diff($_POST["estudiantesMT"],$idEstudianteMT);
		if($resultadoAgregar){
			foreach ($resultadoAgregar as $idMatriculaGuardar) {
				try{
					MediaTecnicaServicios::guardarPorCurso($idMatriculaGuardar,$_POST["id_curso"],$config,$_POST["grupo".$idMatriculaGuardar]);
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
			}
		}

		//Eliminamos los estudiantes que ya no vayan a paertenecer a este curso
		$resultadoEliminar= array_diff($idEstudianteMT,$_POST["estudiantesMT"]);
		if($resultadoEliminar){
			foreach ($resultadoEliminar as $idMatriculaEliminar) {
				try{
					mysqli_query($conexion,"DELETE FROM ".$baseDatosServicios.".mediatecnica_matriculas_cursos WHERE matcur_id_curso='".$_POST["id_curso"]."' AND matcur_id_matricula='".$idMatriculaEliminar."' AND matcur_id_institucion='".$config['conf_id_institucion']."' AND matcur_years='".$config['conf_agno']."'");
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
			}
		}
	}else{
		try{
			MediaTecnicaServicios::eliminarExistenciaEnCursoMT($_POST["id_curso"],$config);
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="cursos.php?success=SC_DT_2&id='.base64_encode($_POST["id_curso"]).'";</script>';
exit();
