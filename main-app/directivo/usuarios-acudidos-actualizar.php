<?php 
include("session.php");
$idPaginaInterna = 'DT0138';
include("../compartido/guardar-historial-acciones.php");

try {
    mysqli_query($conexion, "DELETE FROM usuarios_por_estudiantes WHERE upe_id_usuario='".$_POST["id"]."'");
    mysqli_query($conexion, "UPDATE academico_matriculas SET mat_acudiente=NULL  WHERE mat_acudiente='".$_POST["id"]."'");
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    exit();
}

$numero = (count($_POST["acudidos"]));
$contador = 0;
while ($contador < $numero) {

    try {
        mysqli_query($conexion, "UPDATE academico_matriculas SET mat_acudiente='".$_POST["id"]."' WHERE mat_id='".$_POST["acudidos"][$contador]."'");
    } catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        exit();
    }		

    try {
        mysqli_query($conexion, "INSERT INTO usuarios_por_estudiantes(upe_id_usuario, upe_id_estudiante)VALUES('".$_POST["id"]."', '".$_POST["acudidos"][$contador]."')");
    } catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        exit();
    }
    $contador++;
}

echo '<script type="text/javascript">window.location.href="usuarios-acudidos.php?id='.$_POST["id"].'&success=SC_DT_2";</script>';
exit();