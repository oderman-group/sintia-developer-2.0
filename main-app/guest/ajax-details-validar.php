<?php
header("Content-type: application/json; charset=utf-8");
$input = json_decode(file_get_contents("php://input"), true);

require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");
if (isset($_SESSION["id"]) and $_SESSION["id"] != "") {
    require_once(ROOT_PATH . "/main-app/modelo/conexion.php");
} else {
    $conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
}
if (!empty($input['tipo']) && !empty($input['valor'])) {
    try {
        $tipo = $input['tipo'];
        $valor = $input['valor'];

        switch ($tipo) {
            case IDENTIFICAION:
                $consultaDoc = mysqli_query($conexion, "SELECT mat_documento FROM " . BD_ACADEMICA . ".academico_matriculas
                 WHERE mat_documento ='" . $valor . "' AND mat_eliminado=0 AND institucion={$input['institucion']} AND year={$input["year"]}");
                break;
            case USUARIO:
                $consultaDoc = mysqli_query($conexion, "SELECT uss_usuario FROM " . BD_GENERAL . ".usuarios
                WHERE uss_usuario ='" . $valor . "' AND institucion={$input['institucion']} AND year={$input["year"]}");
                break;
            case CORREO:
                $consultaDoc = mysqli_query($conexion, "SELECT mat_email FROM " . BD_ACADEMICA . ".academico_matriculas
                 WHERE mat_email ='" . $valor . "' AND mat_eliminado=0 AND institucion={$input['institucion']} AND year={$input["year"]}");
                break;
            default:
                echo "Opción no reconocida";
        }



        if (mysqli_num_rows($consultaDoc) > 0) {
            $response["ok"] = "true";
        } else {
            $response["Error"] = "false";
        }
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
} else {
    $response["Error"] = "Error";
}
echo json_encode($response);
