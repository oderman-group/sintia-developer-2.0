<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0042';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "UPDATE usuarios SET 
    uss_celular='" . $_POST["celular"] . "', 
    uss_institucion='" . $_POST["institucion"] . "', 
    uss_institucion_municipio='" . $_POST["instMunicipio"] . "',
    uss_solicitar_datos=0, 
    uss_ultima_actualizacion=now()

    WHERE uss_id='" . $_SESSION["id"] . "'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

try{
    $datosUsuario = UsuariosPadre::sesionUsuario($_SESSION["id"]);
    $_SESSION["datosUsuario"] = $datosUsuario;
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
?>
<script>
    localStorage.setItem("vGuiada", 1);
</script>
<?php

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $_SERVER["HTTP_REFERER"] . '";</script>';
exit();