<?php
session_start();
//Si otro usuario de mayor rango entra como él
if(isset($_SESSION["idO"]) and $_SESSION["idO"]!=""){$idSession = $_SESSION["idO"];}else{$idSession = $_SESSION["id"];}
if($idSession==""){
	header("Location:../controlador/salir.php");
}
else
{
	include("../../config-general/config.php");
	include("../../config-general/idiomas.php");
	include("../../config-general/consulta-usuario-actual.php");
	include("../../config-general/verificar-usuario-bloqueado.php");

	if($datosUsuarioActual[3]!=5 && $datosUsuarioActual[3]!=1)
	{
		if(isset($_SESSION["yearAnterior"])){
			$_SESSION["cambioYear"]=$_SESSION["bd"];
			$_SESSION["bd"]=$_SESSION["yearAnterior"];
		}
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=304";</script>';
		exit();		
	}
}

