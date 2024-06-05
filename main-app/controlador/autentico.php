<?php
session_start();
$idPaginaInterna = 'GN0001';
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
//include(ROOT_PATH."/conexion-datos.php");
$conexionBaseDatosServicios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);

if(!empty($_GET)){
	$_POST["Usuario"]		=	base64_decode($_GET["Usuario"]);
	$_POST["Clave"] 		= 	base64_decode($_GET["Clave"]);

	$_POST["suma"] 			= 	base64_decode($_GET["suma"]);
	$_POST["sumaReal"] 		= 	base64_decode($_GET["sumaReal"]);
	
	$_POST["urlDefault"] 	= 	base64_decode($_GET["urlDefault"]);
	$_POST["directory"] 	= 	base64_decode($_GET["directory"]);
}

$sql="SELECT id_nuevo, uss_usuario, uss_id,institucion, uss_intentos_fallidos FROM ".BD_GENERAL.".usuarios 
WHERE uss_usuario='".trim($_POST["Usuario"])."' AND TRIM(uss_usuario)!='' AND uss_clave=SHA1('".$_POST["Clave"]."')  AND uss_usuario IS NOT NULL  ORDER BY uss_ultimo_ingreso DESC LIMIT 1";
$rst_usrE = mysqli_query($conexionBaseDatosServicios, $sql);
$usrE = mysqli_fetch_array($rst_usrE, MYSQLI_BOTH);

$_POST["bd"]=$usrE["institucion"];
$institucionConsulta = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM ".$baseDatosServicios.".instituciones 
WHERE ins_estado = 1 AND ins_id='".$_POST["bd"]."' AND ins_enviroment='".ENVIROMENT."'");

$numInsti = mysqli_num_rows($institucionConsulta);
if($numInsti==0){
	header("Location:".REDIRECT_ROUTE."/index.php?error=9&inst=".base64_encode($_POST["bd"]));
	exit();
}

$institucion = mysqli_fetch_array($institucionConsulta, MYSQLI_BOTH);
$yearArray = explode(",", $institucion['ins_years']);
$yearStart = $yearArray[0];
$yearEnd = $yearArray[1];

$_SESSION["inst"] = $institucion['ins_bd'];
$_SESSION["idInstitucion"] = $institucion['ins_id'];

if( !empty($institucion['ins_year_default']) && is_numeric($institucion['ins_year_default']) ) {
	$_SESSION["bd"] = $institucion['ins_year_default'];
} elseif( isset($yearEnd) && is_numeric($yearEnd) ) {
	$_SESSION["bd"] = $yearEnd;
} else {
	$_SESSION["bd"] = date("Y");
}

include("../modelo/conexion.php");
require_once("../class/Plataforma.php");
require_once("../class/UsuariosPadre.php");
require_once(ROOT_PATH."/main-app/class/Modulos.php");


$rst_usrE = UsuariosPadre::obtenerTodosLosDatosDeUsuarios("AND uss_usuario='".trim($_POST["Usuario"])."' AND TRIM(uss_usuario)!='' AND uss_usuario IS NOT NULL");

$numE = mysqli_num_rows($rst_usrE);
if($numE==0){
	header("Location:".REDIRECT_ROUTE."/index.php?error=1&inst=".base64_encode($_POST["bd"]));
	exit();
}
$usrE = mysqli_fetch_array($rst_usrE, MYSQLI_BOTH);

if($usrE['uss_intentos_fallidos']>=3 and md5($_POST["suma"])!=$_POST["sumaReal"]){
	header("Location:".REDIRECT_ROUTE."/index.php?error=3&inst=".base64_encode($_POST["bd"]));
	exit();
}

$rst_usr = UsuariosPadre::obtenerTodosLosDatosDeUsuarios(" AND uss_usuario='".trim($_POST["Usuario"])."' AND uss_clave=SHA1('".$_POST["Clave"]."') AND TRIM(uss_usuario)!='' AND uss_usuario IS NOT NULL AND TRIM(uss_clave)!='' AND uss_clave IS NOT NULL");

$num = mysqli_num_rows($rst_usr);
$fila = mysqli_fetch_array($rst_usr, MYSQLI_BOTH);
if($num>0)
{	
	if($fila['uss_bloqueado'] == 1){
		header("Location:".REDIRECT_ROUTE."/index.php?error=6&inst=".base64_encode($_POST["bd"]));
		exit();
	}

	$URLdefault = null;
	if (!empty($_POST["urlDefault"])) { 
		$URLdefault = base64_decode($_POST["urlDefault"]); 
	}
	
	$url = null;
	if (!empty($_POST["directory"])) {
		$directoriosPorUsuario = [
			'directivo'  => TIPO_DIRECTIVO,
			'docente'    => TIPO_DOCENTE,
			'acudiente'  => TIPO_ACUDIENTE,
			'estudiante' => TIPO_ESTUDIANTE
		];
		$directory      = base64_decode($_POST["directory"]);
		$tipoDirectorio = $directoriosPorUsuario[$directory];

		if($tipoDirectorio == $fila['uss_tipo'] || ($tipoDirectorio == TIPO_DIRECTIVO && $fila['uss_tipo'] == TIPO_DEV)) {
			$url = "../".$directory."/".$URLdefault;
		}

	}
	
	if (empty($url)) {
		switch($fila['uss_tipo']){
			case 1:
				$url = '../directivo/noticias.php';
			break;
			
			case 2:
				$url = '../docente/noticias.php';
			break;
			
			case 3:
				$url = '../acudiente/estudiantes.php';
			break;
			
			case 4:
				$url = '../estudiante/matricula.php';
			break;
			
			case 5:
				$url = '../directivo/noticias.php';
			break;
			
			default:
				$url = 'salir.php';
			break;
		}
	}
	
	$config = Plataforma::sesionConfiguracion();
	$_SESSION["configuracion"] = $config;

	$informacionInstConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_informacion
	LEFT JOIN ".$baseDatosServicios.".localidad_ciudades ON ciu_id=info_ciudad
	LEFT JOIN ".$baseDatosServicios.".localidad_departamentos ON dep_id=ciu_departamento
	WHERE info_institucion='" . $config['conf_id_institucion'] . "' AND info_year='" . $_SESSION["bd"] . "'");
	$informacion_inst = mysqli_fetch_array($informacionInstConsulta, MYSQLI_BOTH);
	$_SESSION["informacionInstConsulta"] = $informacion_inst;

	$datosUnicosInstitucionConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones 
	WHERE ins_id='".$config['conf_id_institucion']."' AND ins_enviroment='".ENVIROMENT."'");
	$datosUnicosInstitucion = mysqli_fetch_array($datosUnicosInstitucionConsulta, MYSQLI_BOTH);
	$_SESSION["datosUnicosInstitucion"] = $datosUnicosInstitucion;

    
	$arregloModulos = Modulos::consultarModulosIntitucion($conexion, $config['conf_id_institucion']);
	$_SESSION["modulos"] = $arregloModulos;

	//INICIO SESION
	$_SESSION["id"] = $fila['uss_id'];
	$_SESSION["datosUsuario"] = $fila;

	include("navegador.php");

	$urlActual = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];

	
	if( !empty($_POST['guest']) ) {
		$url = $_SERVER['HTTP_REFERER'];
	} 

	mysqli_query($conexion, "UPDATE ".BD_GENERAL.".usuarios SET uss_estado=1, uss_ultimo_ingreso=now(), uss_intentos_fallidos=0 WHERE uss_id='".$fila['uss_id']."' AND institucion={$_SESSION["idInstitucion"]} AND year={$_SESSION["bd"]}");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Autenticando | Plataforma sintia</title>

        <!-- favicon -->
        <link rel="shortcut icon" href="../sintia-icono.png" />
    
	<script>
	document.addEventListener('DOMContentLoaded', function() {

		var urlRedireccion = "<?=$url;?>";

		fetch("https://ipinfo.io/json?token=<?=TOKEN_IP_INFO;?>")
			.then((response) => response.json())
			.then((jsonResponse) => {
				var countryCity = jsonResponse.city + ' | ' + jsonResponse.country + ' | ' + jsonResponse.region + ' | ' + jsonResponse.postal;
				var usuario='<?=$fila['uss_id'];?>';
				var urlActual = "<?=$urlActual;?>";
				var idPaginaInterna = "<?=$idPaginaInterna;?>";
				var institucion = <?=$institucion['ins_id'];?>;

				// Enviar los datos a PHP usando otra solicitud fetch
				fetch("ip.php?countryCity=" + countryCity + 
							"&usuario=" + usuario +
							"&urlActual=" + urlActual +
							"&idPaginaInterna=" + idPaginaInterna +
							"&institucion=" + institucion
							, {
					method: "GET"
				})
				.then(response => response.text())
				.then(data => {
					window.location.href = urlRedireccion;
				}).catch(error => {
					// Manejar errores
					console.error('Error:', error);
				})
				;

				
			}).catch(error => {
				// Manejar errores
				console.error('Error:', error);
				window.location.href = urlRedireccion;
			});
	});
	</script>
	<style>
		body {
		background-image: url('./../../config-general/assets-login-2023/img/bg-login.png');
		display: grid;
		grid-template-columns: 100%;
		height: 100vh;
		width: 100vw;
	}
	/* Estilo del contenedor del mensaje */
	.espera-container {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		height: 100vh;
	}

	/* Estilo del mensaje */
	.espera-mensaje {
		font-size: 24px;
		font-weight: bold;
		text-align: center;
		padding: 20px;
		background-color: #5846d2;
		color:#fff;
		border-radius: 10px;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
	}
	</style>
</head>
<body>
	
	<div class="espera-container">
		<div class="espera-mensaje">
		Estoy verificando tus datos, dame un momento...
		</div>
	</div>
	</body>
</html>
<?php
	exit();
}else{
	mysqli_query($conexion, "UPDATE ".BD_GENERAL.".usuarios SET uss_intentos_fallidos=uss_intentos_fallidos+1 WHERE uss_id='".$usrE['uss_id']."' AND institucion={$_SESSION["idInstitucion"]} AND year={$_SESSION["bd"]}");


	mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".usuarios_intentos_fallidos(uif_usuarios, uif_ip, uif_clave, uif_institucion, uif_year)VALUES('".$usrE['uss_id']."', '".$_SERVER['REMOTE_ADDR']."', '".$_POST["Clave"]."', '".$_POST["bd"]."', '".$_SESSION["bd"]."')");


	header("Location:".REDIRECT_ROUTE."/index.php?error=2&inst=".base64_encode($_POST["bd"]));
	exit();
}