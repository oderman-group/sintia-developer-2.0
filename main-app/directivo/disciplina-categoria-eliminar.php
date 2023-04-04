<?php 
include("session.php"); 
include("../modelo/conexion.php"); 

mysqli_query($conexion, "DELETE FROM disciplina_faltas WHERE dfal_id_categoria='".$_GET["id"]."'");
mysqli_query($conexion, "DELETE FROM disciplina_categorias WHERE dcat_id='".$_GET["id"]."'");

echo '<script type="text/javascript">window.location.href="disciplina-categorias.php?error=ER_DT_3";</script>';
exit();