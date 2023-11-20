<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0133';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_calificaciones WHERE cal_id='".base64_decode($_GET["id"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'&tab=2&error=ER_DT_3";</script>';
exit();