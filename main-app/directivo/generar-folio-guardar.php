<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0193';

if(!Modulos::validarSubRol($idPaginaInterna)){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

$year=$agnoBD;
if(!empty($_REQUEST["year"])){
$year=$_REQUEST["year"];
}
$BD=$_SESSION["inst"]."_".$year;

$folio=1;
if(!empty($_REQUEST["numFolio"])){$folio=$_REQUEST["numFolio"];}

$filtro = '';
if(!empty($_REQUEST["tipoEstudiantes"]) && $_REQUEST["tipoEstudiantes"]!=0){$filtro .= " AND mat_estado_matricula='".$_REQUEST["tipoEstudiantes"]."'";}

try {
	$consulta = mysqli_query($conexion,"SELECT * FROM ".$BD.".academico_matriculas
	INNER JOIN ".$BD.".academico_grados ON gra_id=mat_grado
	WHERE mat_eliminado=0 $filtro
	ORDER BY gra_vocal, mat_grupo, mat_primer_apellido, mat_segundo_apellido, mat_nombres");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

	while($datos = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
		try{
			mysqli_query($conexion,"UPDATE ".$BD.".academico_matriculas SET mat_folio='".$folio."' 
			WHERE mat_id='".$datos['mat_id']."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$folio ++ ;
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="estudiantes.php?success=SC_DT_12";</script>';
	exit();
