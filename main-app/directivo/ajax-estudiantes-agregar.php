<?php include("session.php");?>


<?php

$consultaDoc=mysqli_query($conexion, "SELECT mat_documento FROM academico_matriculas
WHERE mat_documento ='".$_POST["nDoct"]."'");
if ($_POST["documento"]==1) { echo "<span style='color:red; font: size 16px;'>El estudiante ya existe
    </span>";exit();
    if ($_POST["documento"]=="") {
       echo "<span style='color:red; font: size 16px;'>Nuevo estudiante ingresado
    </span>";exit();
    }
}
$mensajeDoc = 'Se ha guardado un nuevo estudiante'

?>

