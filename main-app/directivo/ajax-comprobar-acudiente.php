<?php
include('session.php');
if(isset($_POST['buscar']))
{ 
	$doc = $_POST['uss_usuario'];
	$valores = array();
	$valores['existe'] = "0"; 

	try{
		$resultados = mysqli_query($conexion,"SELECT * FROM usuarios WHERE uss_usuario = '$doc' or uss_documento = '$doc'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	while($consulta = mysqli_fetch_array($resultados))
	{
		$valores['existe'] = "1"; 
		$valores['nombre1'] = $consulta['uss_nombre'];
		$valores['nombre2'] = $consulta['uss_nombre2'];
		$valores['apellido1'] = $consulta['uss_apellido1'];
		$valores['apellido2'] = $consulta['uss_apellido2'];	
		$valores['lugardE'] = $consulta['uss_lugar_expedicion'];		    
	}
	
	$valores = json_encode($valores);
	echo $valores;
}