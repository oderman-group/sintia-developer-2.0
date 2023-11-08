<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once("../class/UsuariosPadre.php");
require_once("../class/Estudiantes.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0002';
include("../compartido/historial-acciones-guardar.php");

require '../../librerias/phpmailer/PHPMailer.php';
require '../../librerias/phpmailer/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;

include("../compartido/sintia-funciones.php");
$archivoSubido = new Archivos;
$usuariosClase = new Usuarios;

if (!empty($_POST["id"])) {
	//GUARDAR EN CHAT GRUPAL
	if ($_POST["id"] == 13) {
		try{
			mysqli_query($conexion, "INSERT INTO academico_chat_grupal(chatg_emisor, chatg_carga, chatg_fecha, chatg_mensaje)VALUES('" . $_SESSION["id"] . "', '" . $_POST["carga"] . "', now(), '" . mysqli_real_escape_string($conexion,$_POST["mensaje"]) . "')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="' . $_SERVER["HTTP_REFERER"] . '";</script>';
		exit();
	}
}



##############################################
if (!empty($_GET["get"])) {
	//ELIMINAR MENSAJES DEL CHAT GRUPAL
	if ($_GET["get"] == 18) {
		try{
			mysqli_query($conexion, "DELETE FROM academico_chat_grupal WHERE chatg_id='" . $_GET["idR"] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="' . $_SERVER["HTTP_REFERER"] . '";</script>';
		exit();
	}
}

$_GET["get"] == 0;
include("../compartido/guardar-historial-acciones.php");
//EN CASO DE QUE NO ENTRE POR NINGUNA DE LAS ANTERIORES
echo '<script type="text/javascript">window.location.href="https://plataformasintia.com?error=1";</script>';
exit();
?>