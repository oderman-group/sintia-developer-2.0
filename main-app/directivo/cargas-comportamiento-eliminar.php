<?php 
include("session.php"); 

	mysqli_query($conexion, "DELETE FROM disiplina_nota WHERE dn_id='".$_GET['id']."'");
	
	echo '<script type="text/javascript">window.location.href="cargas-comportamiento.php?error=ER_DT_3&periodo='.$_GET["periodo"].'&carga='.$_GET["carga"].'&grado='.$_GET["grado"].'&grupo='.$_GET["grupo"].'";</script>';
	exit();