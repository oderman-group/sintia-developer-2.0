<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<?php 
if (strpos($_SERVER['PHP_SELF'], 'salir.php')) {
    session_start();
}
if(isset($_SESSION["id"]) and $_SESSION["id"]!=""){$_SESSION["id"] = $_SESSION["id"];}
include("../../../conexion-datos.php");

//PRUEBA DE CONSULTA PHP 8
/*
const SERVER = 'sintia.co';
const USER = 'mobiliar_enuarlara';
const PASS = 'CiUKh?V=_%b(';
const MAINBD = 'mobiliar_dev_2022';

$conexionBd = new mysqli(SERVER, USER, PASS, MAINBD);*/

//Conexion con el Servidor
$conexion = mysql_connect($servidorConexion, $usuarioConexion, $claveConexion);
//seleccionamos la base de datos
if($_SESSION["inst"]==""){
	session_destroy();
	header("Location:http://localhost/plataformasintia.com/instituciones/developer_2.0/");
	exit();
}else{
	//seleccionamos la base de datos
	$guion="_";
	if($_SESSION["bd"]==""){
		$agno= date("Y");
	}else{
		//peguntamos cuando es DEMO porque ésta no tiene año. Ejemplo: _2020
		if($_SESSION["inst"]=='mobiliar_sintiademo'){
			$guion='';
			$agno= '';
		}else{
			$agno= $_SESSION["bd"];
		}
	}
	mysql_select_db($_SESSION["inst"].$guion.$agno, $conexion);
	$bdActual= $_SESSION["inst"].$guion.$agno;
	$bdApasar= $_SESSION["inst"].$guion.($agno+1);
	/*echo $agno."-".$bdActual."-".$bdApasar;
	exit();
	if($_SESSION["bd"]==""){
		mysql_select_db($_SESSION["inst"]."_".date("Y"), $conexion);
	}else{
		//peguntamos cuando es DEMO porque ésta no tiene año. Ejemplo: _2020
		if($_SESSION["inst"]=='mobiliar_sintiademo'){
			mysql_select_db($_SESSION["inst"], $conexion);
		}else{
			mysql_select_db($_SESSION["inst"]."_".$_SESSION["bd"], $conexion);
		}
		
	}*/
}
?>