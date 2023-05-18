<?php
include('session.php');
require_once("../class/servicios/MatriculaServicios.php");
if(isset($_GET['nombre']))
{ 
	try {
		$lista=MatriculaServicios::listarEstudianteNombre($_GET['nombre']);
		$valores = json_encode($lista);
		foreach($lista as $clave=> $dato){
			$response[] = ["value"=>$dato["mat_id"],"label"=>MatriculaServicios::nombreCompleto($dato)];
		}
		 echo json_encode($response);
		
	} catch (Exception $e) {
		echo "Excepción catpurada: " . $e->getMessage();
		exit();
	}
 
}