<?php
/*session_start();
$_SESSION["bd"] = date("Y");*/
include("../modelo/conexion.php");
$rst_usr = mysql_query("SELECT * FROM usuarios WHERE uss_id='".$_GET["usuario"]."'",$conexion);
$num = mysql_num_rows($rst_usr);
$fila = mysql_fetch_array($rst_usr);
if($num>0)
{
	//session_destroy();
	session_start();
	$_SESSION["idO"] = $fila[0];
	switch($fila[3]){
		case 1:
		  $url = '../admin';
		break;
		
		case 2:
		  setcookie("carga","",-3600);
		  $_SESSION["carga"] = "";
		  $url = '../docente';
		break;
		
		case 3:
		  $url = '../acudiente';
		break;
		
		case 4:
		  $url = '../estudiante';
		break;
		
		case 5:
		  $url = '../directivo';
		break;
	}
	/*include("navegador.php");
	include("ip.php");
	mysql_query("UPDATE usuarios SET uss_estado=1 WHERE uss_id='".$fila[0]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error();exit();}
	mysql_query("INSERT INTO seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha)VALUES('".$fila[0]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', 'Ingreso al sistema', now())",$conexion);*/	
	header("Location:".$url);	
	exit();
}else{
	header("Location:../index.php?error=2");
	exit();
}
?>
