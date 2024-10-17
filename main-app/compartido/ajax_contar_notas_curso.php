<?php

$input = json_decode(file_get_contents('php://input'), true);
if (!empty($input)) {
    $_POST = $input;
}

include 'session-compartida.php';
require_once ROOT_PATH . '/main-app/class/Boletin.php';

$response = [];

try {
    $_POST['idEstudiante']=!empty($_POST['idEstudiante'])?$_POST['idEstudiante']:'';
    $listadoGrupos = Boletin::contarNotasCursoGrupo($_POST['curso'],$_POST['grupo'],$_POST['periodo'],$_POST['idEstudiante'],$_POST['year']);
    
    if ($listadoGrupos) {
        $response['result'] = $listadoGrupos;
    }

    $response['ok'] = true;
} catch (Exception $e) {
    $response['ok'] = false;
    $response['msg'] = $e;
    include ROOT_PATH . '/main-app/compartido/error-catch-to-report.php';
}

include ROOT_PATH . '/main-app/compartido/guardar-historial-acciones.php';

echo json_encode($response);
exit();
