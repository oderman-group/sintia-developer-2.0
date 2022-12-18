<?php
	include("session.php");
	include("../modelo/conexion.php");
	
	mysql_query("UPDATE academico_grados SET gra_formato_boletin=1",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();