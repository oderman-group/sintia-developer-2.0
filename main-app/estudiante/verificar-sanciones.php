<?php
$reportesD = mysql_num_rows(mysql_query("SELECT * FROM disciplina_reportes WHERE dr_estudiante='".$_SESSION["id"]."' AND dr_aprobacion_estudiante=0",$conexion));
if($reportesD>0){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=215";</script>';
	exit();		
}
?>