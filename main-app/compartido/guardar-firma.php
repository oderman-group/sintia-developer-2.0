<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0050';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$archivoSubido = new Archivos;
$usuariosClase = new Usuarios;


if (!empty($_FILES['archivo']['name'])) {
    $archivoSubido->validarArchivo($_FILES['archivo']['size'], $_FILES['archivo']['name']);
    $explode=explode(".", $_FILES['archivo']['name']);
    $extension = end($explode);
    $archivo = uniqid($_SESSION["inst"] . '_' . $_SESSION["id"] . '_firma_') . "." . $extension;
    $destino = "../files/comprobantes";
    move_uploaded_file($_FILES['archivo']['tmp_name'], $destino . "/" . $archivo);
}

try{
    mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_matriculas SET  mat_firma_adjunta='".$archivo."', mat_hoja_firma=1
    WHERE mat_id_usuario='" . $_SESSION["id"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'matricula.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
exit();