<?php 
if (strpos($_SERVER['PHP_SELF'], 'salir.php')) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");

if(isset($_SESSION["id"]) and $_SESSION["id"]!=""){
	$_SESSION["id"] = $_SESSION["id"];
}

//include(ROOT_PATH."/conexion-datos.php");

//seleccionamos la base de datos
if($_SESSION["inst"]==""){
	session_destroy();
	header("Location:".REDIRECT_ROUTE."?error=4");
	exit();
}else{
	
	//seleccionamos el año de la base de datos
	$agnoBD = date("Y");
	if($_SESSION["bd"]!=""){
		$agnoBD = $_SESSION["bd"];
	}

	$bdActual = $_SESSION["inst"]."_".$agnoBD;
	$bdApasar = $_SESSION["inst"]."_".($agnoBD+1);
	try{
	//Conexion con el Servidor
	$conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $_SESSION["inst"]."_".$agnoBD);
	
	// Crear una instancia de PDO
    $conexionPDO = new PDO("mysql:host=$servidorConexion;dbname=$bdActual", $usuarioConexion, $claveConexion);

    // Establecer el modo de error PDO a excepciones
    $conexionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	} catch(Exception $e){

		switch($e->getCode()){
			case 1044:
				$exception = "error=7&inst=".$_POST["bd"];
			break;

			default:
				$exception = "error=".$e->getMessage()."&inst=".$_POST["bd"];
			break;	
		}

		session_destroy();
		header("Location:".REDIRECT_ROUTE."/index.php?".$exception);
		exit();
	}
	if (!mysqli_set_charset($conexion, "utf8")) 
    {
      printf("Error cargando el conjunto de caracteres utf8: %s\n", mysqli_error($link));
      exit();
    }

}