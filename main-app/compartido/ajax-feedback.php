<?php
session_start();
include("../../config-general/config.php");
require_once '../class/Tables/BDT_clases_feedback.php';

$tableName = BDT_ClasesFeedback::getTableName();

$respuesta = [
    'estado'  => 'success',
    'mensaje' => 'El registro fue guardado'
];

try {
    // Preparar la consulta SQL
    $sql = "INSERT INTO ".BD_ACADEMICA.".{$tableName}(fcls_id_clase, fcls_id_institucion, fcls_usuario, fcls_comentario, fcls_star) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE
	fcls_comentario = VALUES(fcls_comentario),
	fcls_star = VALUES(fcls_star)";
    
    // Preparar la sentencia
    $stmt = mysqli_prepare($conexion, $sql);

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta.");
    }

    // Vincular los parámetros
    mysqli_stmt_bind_param($stmt, "iiisi", $_POST["claseId"], $config['conf_id_institucion'], $_POST["usuarioActual"], $_POST["comment"], $_POST["star"]);

    // Ejecutar la consulta
    $resultado = mysqli_stmt_execute($stmt);

    if (!$resultado) {
        throw new Exception("Error al ejecutar la consulta.");
    }

    // La inserción se realizó con éxito
    $respuesta = [
        'titulo'  => 'Excelente',
        'estado'  => 'success',
        'mensaje' => 'El feedback fue guardado correctamente.'
    ];

    echo json_encode($respuesta);

} catch (Exception $e) {
    // Manejar errores
    $logError = Plataforma::soloRegistroErrores($e);
    //echo $logError;
    $respuesta = [
        'titulo'  => 'Error',
        'estado'  => 'error',
        'mensaje' => 'Ha ocurrido un error mientras se intenta guardar el feedback. <br> Código del registro de error: <b>'.$logError.'</b>'
    ];

    echo json_encode($respuesta);

}
