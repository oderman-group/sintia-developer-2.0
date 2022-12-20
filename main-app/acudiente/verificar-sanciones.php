<?php
$reportesD = mysqli_num_rows(mysqli_query($conexion, "SELECT * FROM disciplina_reportes 
INNER JOIN academico_matriculas ON mat_id_usuario=dr_estudiante AND mat_acudiente='".$_SESSION["id"]."'
WHERE dr_aprobacion_acudiente=0"));
if($reportesD>0){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=216";</script>';
	exit();		
}
?>