<?php 
include("session.php");

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

	$folio = 1;
	while($datos = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
		mysqli_query($conexion,"UPDATE ".$BD.".academico_matriculas SET mat_folio='".$folio."' 
		WHERE mat_id='".$datos['mat_id']."'");
		$folio ++ ;
	}

	echo '<script type="text/javascript">window.location.href="estudiantes.php?success=SC_DT_12";</script>';
	exit();
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}	