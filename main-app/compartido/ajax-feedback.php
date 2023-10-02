<?php
session_start();
include("../../config-general/config.php");

try {
    // Preparar la consulta SQL
    $sql = "INSERT INTO {$baseDatosServicios}.clases_feedback(fcls_id_clase, fcls_id_institucion, fcls_usuario, fcls_comentario, fcls_star) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE
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
    echo "Inserción exitosa.";
} catch (Exception $e) {
    // Manejar errores
    echo "Excepción capturada: " . $e->getMessage();
}
