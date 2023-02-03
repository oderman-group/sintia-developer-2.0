<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	mysqli_query($conexion, "DELETE FROM academico_areas WHERE ar_id=".$_GET["id"].";");
	
	echo '<script type="text/javascript">window.location.href="areas.php?error=ER_DT_3";</script>';
	exit();