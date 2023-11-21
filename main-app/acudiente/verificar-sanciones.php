<?php
$reportesD = mysqli_num_rows(mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disciplina_reportes dr 
INNER JOIN ".BD_ACADEMICA.".academico_matriculas mat ON mat.mat_id_usuario=dr.dr_estudiante AND mat.mat_acudiente='".$_SESSION["id"]."' AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}
WHERE dr.dr_aprobacion_acudiente=0 AND dr.institucion={$config['conf_id_institucion']} AND dr.year={$_SESSION["bd"]}"));
if($reportesD>0){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=216";</script>';
	exit();		
}
?>