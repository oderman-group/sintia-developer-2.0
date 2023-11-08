<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0028';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;
$archivoSubido = new Archivos;

if (!empty($_FILES['imagen']['name'])) {
    $archivoSubido->validarArchivo($_FILES['imagen']['size'], $_FILES['imagen']['name']);
    $explode=explode(".", $_FILES['imagen']['name']);
    $extension = end($explode);
    $foto = uniqid($_SESSION["empresa"] . '_prod_') . "." . $extension;
    $destino = "../files/marketplace/productos";
    move_uploaded_file($_FILES['imagen']['tmp_name'], $destino . "/" . $foto);
}

$findme   = '?v=';
$pos = strpos($_POST["video"], $findme) + 3;
$video = substr($_POST["video"], $pos, 11);

$stock = 1;
if( !empty($_POST['stock']) && $_POST['stock'] > 0 ) {
    $stock = $_POST['stock']; 
}

try{
    mysqli_query($conexion, "INSERT INTO " . $baseDatosMarketPlace . ".productos(prod_nombre, prod_descripcion, prod_foto, prod_precio, prod_activo, prod_estado, prod_empresa, prod_video, prod_keywords, prod_categoria, prod_existencias)VALUES('" . mysqli_real_escape_string($conexion,$_POST["nombre"]) . "', '" . mysqli_real_escape_string($conexion,$_POST["descripcion"]) . "', '" . $foto . "', '" . $_POST["precio"] . "', 1, 0, '" . $_SESSION["empresa"] . "', '" . $video . "', '" . mysqli_real_escape_string($conexion,$_POST["keyw"]) . "', '" . $_POST["categoria"] . "', '" . $stock . "')");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$idRegistro = mysqli_insert_id($conexion);

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'marketplace.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
exit();