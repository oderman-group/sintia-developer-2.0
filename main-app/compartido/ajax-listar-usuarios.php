<?php
session_start();
include("../../config-general/config.php");
require_once("../class/UsuariosPadre.php");
if(isset($_GET['term']))
{ 
	try {
		$lista=UsuariosPadre::listarUsuariosCompartir($_GET['term']);
		// $valores = json_encode($lista);
		while($dato=mysqli_fetch_array($lista, MYSQLI_BOTH)){
			$nombre=UsuariosPadre::nombreCompletoDelUsuario($dato)." - ".$dato["pes_nombre"];
			$response[] = ["value"=>$dato["uss_id"],"label"=>$nombre];
		}
		 echo json_encode($response);		
	} catch (Exception $e) {
		echo "Excepción catpurada: " . $e->getMessage();
		exit();
	}
 
}