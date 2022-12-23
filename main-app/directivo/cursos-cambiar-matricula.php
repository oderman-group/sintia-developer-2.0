<?php
	include("session.php");
	include("../modelo/conexion.php");
	
	mysqli_query($conexion, "UPDATE academico_grados SET gra_valor_matricula=0");
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();