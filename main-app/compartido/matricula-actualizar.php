<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0037';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;

try{
    mysqli_query($conexion, "UPDATE ".BD_GENERAL.".usuarios SET 
    uss_celular='" . $_POST["celular"] . "',
    uss_telefono='" . $_POST["telefono"] . "',
    uss_ultima_actualizacion=now()

    WHERE uss_id='" . $_SESSION["id"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

//Actualizar matricula a los estudiantes
try{
    mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_matriculas SET  mat_celular='" . $_POST["celular"] . "', mat_telefono='" . $_POST["telefono"] . "', mat_direccion='" . $_POST["dir"] . "', mat_barrio='" . $_POST["barrio"] . "', mat_estrato='" . $_POST["estrato"] . "', mat_actualizar_datos=1, mat_modalidad_estudio='" . $_POST["modalidad"] . "'
    WHERE mat_id_usuario='" . $_SESSION["id"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

//Actualizar datos del acudiente
try{
    mysqli_query($conexion, "UPDATE ".BD_GENERAL.".usuarios SET  uss_email='" . $_POST["emailA"] . "', uss_celular='" . $_POST["celularA"] . "', uss_ocupacion='" . $_POST["ocupacion"] . "', uss_direccion='" . $_POST["dir"] . "'
    WHERE uss_id='" . $_POST["idAcudiente"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'matricula.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
exit();