<?php
include('session.php');
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
if($_REQUEST['tipoUsuario']!=""){
$tipoUsuario = $_REQUEST['tipoUsuario'];
/** Cambiar estos datos por consulta a planes y paquetes para ver cantidad de usuarios permitidos**/
switch ($datosUnicosInstitucion['ins_id_plan']) {
    case 1:
        $cantDirectivos = 10;
        $cantDocentes = 2000;
        $cantEstudianteAcudientes = 500;
    break;
    case 2:
        $cantDirectivos = 50;
        $cantDocentes = 100;
        $cantEstudianteAcudientes = 1000;
    break;
    case 3:
        $cantEstudianteAcudientes = 2000;
    break;
}
/**HASTA AQUI**/

$jsonData = array();
$totalCliente = UsuariosPadre::contarUsuariosPorTipo($conexion, $tipoUsuario);

$limite = 0;
switch ($tipoUsuario) {
    case TIPO_DIRECTIVO:
        if ($datosUnicosInstitucion['ins_id_plan'] != 3 && $totalCliente >= $cantDirectivos) {
            $limite = 1;
        }
    break;
    case TIPO_DOCENTE:
        if ($datosUnicosInstitucion['ins_id_plan'] != 3 && $totalCliente >= $cantDocentes) {
            $limite = 1;
        }
    break;
    case TIPO_ACUDIENTE:
        if ($totalCliente >= $cantEstudianteAcudientes) {
            $limite = 1;
        }
    break;
    case TIPO_ESTUDIANTE:
        if ($totalCliente >= $cantEstudianteAcudientes) {
            $limite = 1;
        }
    break;
}

if( $limite == 0 ){
    $jsonData['success'] = 0;
    $jsonData['message'] = '';
} else{
    
    $jsonData['success'] = 1;
    $jsonData['message'] = '<div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="icon-exclamation-sign"></i>Lo sentimos no puedes crear este usuario,<br> haz llegado al limete de usuarios de este tipo,<br> puedes adquirir un paquete de usuarios para continiar.</div>';
}

header('Content-type: application/json; charset=utf-8');
echo json_encode( $jsonData );
}