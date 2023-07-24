<?php
include("session.php");
require_once("../class/servicios/GradoServicios.php");
require_once("../class/servicios/MediaTecnicaServicios.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0173';
include("../compartido/historial-acciones-guardar.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (trim($_POST["nombreC"]) == "" or trim($_POST["formatoB"]) == "" or trim($_POST["valorM"]) == "" or trim($_POST["valorP"]) == "") {
	echo '<script type="text/javascript">window.location.href="cursos-editar.php?error=ER_DT_4";</script>';
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
	mysqli_query($conexion, "UPDATE academico_grados SET 
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
	WHERE gra_id='" . $_POST["id_curso"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

if ($_POST["tipoG"]==GRADO_INDIVIDUAL) { 
	if(!empty($_POST["estudiantesMT"])){
		$numEstudiantesMT = (count($_POST["estudiantesMT"]));
		if($numEstudiantesMT>0) {
			try{
				MediaTecnicaServicios::eliminarExistenciaEnCursoMT($_POST["id_curso"],$config);
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$contEstudiantes = 0;
			while ($contEstudiantes < $numEstudiantesMT) {
				$idEstudiante=$_POST["estudiantesMT"][$contEstudiantes];
				try{
					MediaTecnicaServicios::guardarPorCurso($idEstudiante,$_POST["id_curso"],$config,$_POST["grupo".$idEstudiante]);
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
				$contEstudiantes++;
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

echo '<script type="text/javascript">window.location.href="cursos.php?success=SC_DT_2&id='.$_POST["id_curso"].'";</script>';
exit();