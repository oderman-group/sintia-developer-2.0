<?php
if($_POST["sesionUsuario"]!="" and $_POST["idClase"]!=""){
	header('Content-Type: application/json');
	//header('Access-Control-Allow-Origin: *');
	
	$servidorConexion = 'localhost';
	$usuarioConexion = 'mobiliar';
	$claveConexion = 'M1X32znd9l';

	$baseDatosServicios = 'mobiliar_sintia_admin';

	if($_POST["bdConsulta"]== 'mobiliar_sintiademo'){
		$daseDatosInstitucion = 'mobiliar_sintiademo';
	}else{
		$daseDatosInstitucion = $_POST["bdConsulta"]."_".$_POST["agnoConsulta"];
	}


	$conexion = mysql_connect($servidorConexion, $usuarioConexion, $claveConexion);
	mysql_select_db($daseDatosInstitucion,$conexion);

	

	$entrada = "Not";
	if($_POST["contenido"] !="" and $_POST["envia"] == "SI"){
		$entrada = "Yes";
		mysql_query("INSERT INTO academico_clases_preguntas(cpp_usuario, cpp_fecha, cpp_id_clase, cpp_contenido)VALUES('".$_POST["sesionUsuario"]."', now(), '".$_POST["idClase"]."', '".mysql_real_escape_string($_POST["contenido"])."')",$conexion);
	}


	$consulta = mysql_query("SELECT * FROM academico_clases_preguntas
	INNER JOIN usuarios ON uss_id=cpp_usuario
	WHERE cpp_id_clase='".$_POST["idClase"]."'
	ORDER BY cpp_id ASC
	",$conexion);
	$numItems = mysql_num_rows($consulta);
	$datos = array();
	while($resultado = mysql_fetch_array($consulta, MYSQL_ASSOC)){
		$datos["info"][] = $resultado;
	}

	$tipoEnvia = gettype($_POST["envia"]);

	$datos["adicional"]["usuario"] .= $_POST["sesionUsuario"];
	$datos["adicional"]["idClase"] .= $_POST["idClase"];
	$datos["adicional"]["accion"] .= $_POST["envia"];
	$datos["adicional"]["entrada"] .= $entrada;
	$datos["adicional"]["tipoEnvia"] .= $tipoEnvia;
	$datos["adicional"]["institucion"] .= $_POST["idInstitucion"];
	$datos["adicional"]["numItems"] .= $numItems;




	echo json_encode($datos);
}
?>
											
