<?php
include('session.php');
require_once("../class/servicios/MatriculaServicios.php");
if(isset($_GET['term']))
{ 
	try {
		$lista=MatriculaServicios::listarEstudianteNombre($_GET['term']);
		$valores = json_encode($lista);
		foreach($lista as $clave=> $dato){
			$response[] = ["value"=>$dato["mat_id"],"label"=>MatriculaServicios::nombreCompleto($dato),"title"=>$dato["mat_nombres"].' '.$dato["mat_primer_apellido"]];
		}
		 echo json_encode($response);		
	} catch (Exception $e) {
		echo "Excepción catpurada: " . $e->getMessage();
		exit();
	}
 
}