<?php
include("session.php");
require_once("../class/servicios/GradoServicios.php");
require_once("../class/servicios/MediaTecnicaServicios.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0173';

if (!Modulos::validarSubRol([$idPaginaInterna])) {
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (trim($_POST["nombreC"]) == "" or trim($_POST["formatoB"]) == "" or trim($_POST["valorM"]) == "" or trim($_POST["valorP"]) == "") {
	echo '<script type="text/javascript">window.location.href="cursos-editar.php?error=ER_DT_4&id=' . base64_encode($_POST["id_curso"]) . '";</script>';
	exit();
}

if (empty($_POST["estado"])) {
	$_POST["estado"] = 1;
}
$esMediaTecnica = !is_null($_POST["tipoG"]);
if (!$esMediaTecnica) {
	$resultadoCurso = GradoServicios::consultarCurso($_POST["id_curso"]);
	$_POST["tipoG"] = $resultadoCurso['gra_tipo'];
}
if (empty($_POST["tipoG"])) {
	$_POST["tipoG"] = GRADO_GRUPAL;
}
if (empty($_POST["estado"])) {
	$_POST["estado"] = 1;
}
if (empty($_POST["imagen"])) {
	$_POST["imagen"] = '';
}
if (empty($_POST["descripcion"])) {
	$_POST["descripcion"] = '';
}
if (empty($_POST["contenido"])) {
	$_POST["contenido"] = '';
}
if (empty($_POST["precio"])) {
	$_POST["precio"] = '0';
}
if (empty($_POST["minEstudiantes"])) {
	$_POST["minEstudiantes"] = '0';
}
if (empty($_POST["maxEstudiantes"])) {
	$_POST["maxEstudiantes"] = '0';
}
if (empty($_POST["horas"])) {
	$_POST["horas"] = '0';
}
$_POST["autoenrollment"] = empty($_POST["autoenrollment"]) ? 0 : 1;
$_POST["activo"] = empty($_POST["activo"]) ? 0 : 1;

try {
	mysqli_query($conexion, "UPDATE " . BD_ACADEMICA . ".academico_grados SET 
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
	gra_tipo='" . $_POST["tipoG"] . "',
	gra_cover_image = '" . $_POST["imagen"] . "',
	gra_overall_description = '" . $_POST["descripcion"] . "',
	gra_course_content = '" . $_POST["contenido"] . "',
	gra_price = '" . $_POST["precio"] . "',
	gra_minimum_quota = '" . $_POST["minEstudiantes"] . "',
	gra_maximum_quota = '" . $_POST["maxEstudiantes"] . "',
	gra_duration_hours = '" . $_POST["horas"] . "',
	gra_auto_enrollment = '" . $_POST["autoenrollment"] . "',
	gra_active = '" . $_POST["activo"] . "'
	WHERE gra_id='" . $_POST["id_curso"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

if ($_POST["tipoG"] == GRADO_INDIVIDUAL) {
	if (!empty($_POST["estudiantesMT"])) {
		$arrayDatos = json_decode($_POST["estudiantesMT"], true);
		$parametros = [
			'matcur_id_curso' => $_POST["id_curso"],
			'matcur_id_institucion' => $config['conf_id_institucion'],
			'arreglo' => true
		];
		$consulta = MediaTecnicaServicios::listarEstudiantes($parametros);
		// como la lista esta vacia procedemos a registrar todo lo que viene de la vista
		if ($consulta == null) {
			foreach ($arrayDatos as $estudiante) {
				try {
					MediaTecnicaServicios::guardarJson($arrayDatos, $config);
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
			}
		} else {
			$idEstudianteMT = array();
			$idEstudianteMTNuevos = array();
			foreach ($consulta as $dato) {
				$idEstudianteMT[] = $dato['matcur_id_matricula'];
			}
			//Agregamos o editamos los estudiantes 
			foreach ($arrayDatos as $estudiante) {
				$idEstudianteMTNuevos[] = $estudiante["matricula"];
				$editar = in_array($estudiante["matricula"], $idEstudianteMT);
				if ($editar) {
					try {
						MediaTecnicaServicios::editarporCurso($estudiante["matricula"], $estudiante["curso"], $config, $estudiante["grupo"], $estudiante["estado"]);
					} catch (Exception $e) {
						include("../compartido/error-catch-to-report.php");
					}
				} else {
					try {
						MediaTecnicaServicios::guardarPorCurso($estudiante["matricula"], $estudiante["curso"], $config, $estudiante["grupo"], $estudiante["estado"]);
					} catch (Exception $e) {
						include("../compartido/error-catch-to-report.php");
					}
				}
			}
			$estudaintesEliminar = array_diff($idEstudianteMT, $idEstudianteMTNuevos);
			//Eliminamos los estudiantes que ya no vayan a paertenecer a este curso
			foreach ($estudaintesEliminar as $matricula) {
				try {
					MediaTecnicaServicios::eliminarExistenciaMT($matricula, $_POST["id_curso"], $config);
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
			}
		}
	} else {
		try {
			MediaTecnicaServicios::eliminarExistenciaEnCursoMT($_POST["id_curso"], $config);
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="cursos-editar.php?success=SC_DT_2&id=' . base64_encode($_POST["id_curso"]) . '";</script>';
exit();
