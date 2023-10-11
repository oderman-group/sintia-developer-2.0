<?php
include("../directivo/session.php");
include("../compartido/sintia-funciones.php");

$archivoSubido = new Archivos;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$nombre_archivo = '';
	$tipo = $_POST["tipo"];
	$carpeta = $_POST["tipo"];
	switch ($tipo) {
		case CHAT_TIPO_IMAGEN:
			$carpeta="imagen";
			break;
		case CHAT_TIPO_DOCUMENTO:
			$carpeta="documento";
			break;
		case CHAT_TIPO_AUDIO:
			$carpeta="audio";
			break;
	}
	try {
		if (!empty($_FILES[$tipo]['name'])) {
			$archivoSubido->validarArchivo($_FILES[$tipo]['size'], $_FILES[$tipo]['name']);
			$explode = explode(".", $_FILES[$tipo]['name']);
			$extension = end($explode);
			$nombre_archivo = uniqid($_SESSION["inst"] . '_' . $_SESSION["id"] . '_chat_' . $carpeta . '_') . "." . $extension;
			$destino = "../files/chat/" . $carpeta . "/";
			move_uploaded_file($_FILES[$tipo]['tmp_name'], $destino . "/" . $nombre_archivo);
		}
		echo $nombre_archivo;
	} catch (Exception $e) {
		echo  $e;
	}
} else {
	echo "Acceso no válido.";
}