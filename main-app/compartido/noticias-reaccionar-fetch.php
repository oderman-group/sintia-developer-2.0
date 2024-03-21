<?php
include("session-compartida.php");
$input = json_decode(file_get_contents("php://input"), true);
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0022';
include(ROOT_PATH . "/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH . "/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;
$idr = $input['id'];
$r = $input['reaccion'];
$postname = $input['postname'];
$usrname = $input['usrname'];
$postowner = $input['postowner'];
$response = array();
try {
    $reaccion = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM " . $baseDatosServicios . ".social_noticias_reacciones WHERE npr_usuario='" . $_SESSION["id"] . "' AND npr_noticia='" . base64_decode($idr) . "'"), MYSQLI_BOTH);

    if (empty($reaccion['npr_id'])) {
        mysqli_query($conexion, "INSERT INTO " . $baseDatosServicios . ".social_noticias_reacciones(npr_usuario, npr_noticia, npr_reaccion, npr_fecha, npr_estado, npr_institucion, npr_year)VALUES('" . $_SESSION["id"] . "', '" . base64_decode($idr) . "','" . base64_decode($r) . "',now(),1,'" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
    } else {
        mysqli_query($conexion, "UPDATE " . $baseDatosServicios . ".social_noticias_reacciones SET npr_reaccion='" . base64_decode($r) . "' WHERE npr_usuario='" . $_SESSION["id"] . "' AND npr_noticia='" . base64_decode($idr) . "'");
    }
    mysqli_query($conexion, "INSERT INTO " . $baseDatosServicios . ".general_alertas (alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_categoria, alr_importancia, alr_vista, alr_institucion, alr_year)
    VALUES('<b>" . base64_decode($usrname) . "</b> ha reaccionado a tu publicación', '<b>" . base64_decode($usrname) . "</b> ha reaccionado a tu publicación " . base64_decode($postname) . ".', 2, '" . base64_decode($postowner) . "', now(), 3, 2, 0,'" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");

    $idNotify = mysqli_insert_id($conexion);

    mysqli_query($conexion, "UPDATE " . $baseDatosServicios . ".general_alertas SET alr_url_acceso='noticias.php?idNotify=" . $idNotify . "#PUB" . $idr . "' WHERE alr_id='" . $idNotify . "'");


    $url = $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'], 'noticias.php');

    $numReacciones = mysqli_fetch_array(mysqli_query($conexion, "SELECT COUNT(*) AS cantidad FROM " . $baseDatosServicios . ".social_noticias_reacciones
												INNER JOIN " . BD_GENERAL . ".usuarios uss ON uss_id=npr_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
												WHERE npr_noticia='" .base64_decode($idr). "'
												ORDER BY npr_id DESC
												"));
    $response["ok"] = true;
    $response["reaccion"] = base64_decode($r);
    $response["msg"] = 'Reaccion registrada con exito!';
    $response["id"] = base64_decode($idr);
    $response["cantidad"] =  $numReacciones["cantidad"];
} catch (Exception $e) {
    $response["ok"] = false;
    $response["msg"] = $e;
    include(ROOT_PATH . "/main-app/compartido/error-catch-to-report.php");
}
echo json_encode($response);
