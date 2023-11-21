<?php
include("session.php");

try{
    mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_matriculas SET mat_eliminado=1 WHERE mat_estado_matricula!=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
    $columnasAfectadas = mysqli_affected_rows($conexion);
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}

echo '<script type="text/javascript">window.location.href="estudiantes.php?success=SC_DT_9&numRegistros='.base64_encode($columnasAfectadas).'";</script>';