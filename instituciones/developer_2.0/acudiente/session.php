<?php 
session_start();
//Si otro usuario de mayor rango entra como él
if(isset($_SESSION["idO"]) and $_SESSION["idO"]!=""){$idSession = $_SESSION["idO"];}else{$idSession = $_SESSION["id"];}
if($idSession==""){
	header("Location:../controlador/salir.php");
}
else
{
	include("../../../config-general/config.php");
	include("../../../config-general/idiomas.php");
	include("../../../config-general/consulta-usuario-actual.php");
	include("../../../config-general/verificar-usuario-bloqueado.php");
	
	if($datosUsuarioActual[3]!=3)
	{
		echo "Usted no tiene permisos para acceder a esta opci&oacute;n";
		exit();		
	}
}