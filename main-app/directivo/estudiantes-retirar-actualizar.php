<?php
include("session.php");
include("../modelo/conexion.php");

     if ($_POST["estadoMatricula"]==1){
            mysqli_query($conexion, "INSERT INTO academico_matriculas_retiradas (matret_estudiante, matret_fecha, matret_motivo, matret_responsable)VALUES('".$_POST["estudiante"]."', now(), '".$_POST["motivo"]."', '".$_SESSION["id"]."')");

            mysqli_query($conexion, "UPDATE academico_matriculas SET mat_estado_matricula=3 WHERE mat_id='".$_POST["estudiante"]."'");

     } elseif ($_POST["estadoMatricula"]==3){
            mysqli_query($conexion, "UPDATE academico_matriculas_retiradas SET matret_motivo='El Estudiante Estaba Retirado, Pero Fue Restaurado Exitosamente!' WHERE matret_estudiante='".$_POST["estudiante"]."'");

            mysqli_query($conexion, "UPDATE academico_matriculas SET mat_estado_matricula=1 WHERE mat_id='".$_POST["estudiante"]."'");
            
     }

    echo '<script type="text/javascript">window.location.href="estudiantes-retirar.php?id='.$_POST["estudiante"].'"</script>';
    exit();
    