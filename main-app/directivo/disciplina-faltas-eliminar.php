<?php 
include("session.php"); 
include("../modelo/conexion.php"); 

mysqli_query($conexion, "DELETE FROM disciplina_faltas WHERE dfal_id='".$_GET["id"]."'");

echo '<script type="text/javascript">window.location.href="disciplina-faltas.php?error=ER_DT_3";</script>';
exit();