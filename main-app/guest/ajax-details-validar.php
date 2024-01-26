<?php
header("Content-type: application/json; charset=utf-8");
$input = json_decode(file_get_contents("php://input"), true);
include("session.php");
if(!empty($input['nDoct'])){
    try{
        $consultaDoc=mysqli_query($conexion, "SELECT mat_documento FROM ".BD_ACADEMICA.".academico_matriculas
        WHERE mat_documento ='".$input["nDoct"]."' AND mat_eliminado=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    $response["ok"]="true";
}else{
    $response["ok"]="Error";
}
echo json_encode($response);
