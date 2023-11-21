<?php 
include("session.php");
mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_matriculas
SET mat_estado_matricula='".$_POST["nuevoEstado"]."'
WHERE mat_id ='".$_POST["idEstudiante"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
?>  
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <p>
        El estado de la matrícula fue cambiado a <b><?=$estadosMatriculasEstudiantes[$_POST["nuevoEstado"]];?></b> correctamente.
    </p>
</div>
