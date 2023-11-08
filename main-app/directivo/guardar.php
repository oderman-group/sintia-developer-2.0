<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0130';
include("../compartido/historial-acciones-guardar.php");
include("../compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");

if ($_GET["get"] == 12) {//No se llama de ningun lado
    try{
        mysqli_query($conexion, "DELETE FROM ".BD_DISCIPLINA.".disciplina_reportes WHERE dr_id='" . $_GET["idR"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    include("../compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
    exit();
}