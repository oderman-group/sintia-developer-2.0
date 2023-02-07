<?php 
include("session.php");
$consultaDoc=mysqli_query($conexion, "SELECT mat_documento FROM academico_matriculas
WHERE mat_documento ='".$_POST["nDoct"]."'");
$numDotos=mysqli_num_rows($consultaDoc);
if ($numDotos > 0) { 
?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="icon-exclamation-sign"></i>Este Usuario ya se encuentra registrado
    </div>
<?php
    exit();
}
?>

