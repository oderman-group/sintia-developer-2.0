<?php
$reportesD = mysqli_num_rows(mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disciplina_reportes WHERE dr_estudiante='".$_SESSION["id"]."' AND dr_aprobacion_estudiante=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}"));
if($reportesD>0){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=215";</script>';
	exit();		
}
?>