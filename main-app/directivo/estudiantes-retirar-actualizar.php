<?php
include("session.php");
include("../modelo/conexion.php");
include("../class/Estudiantes.php");

     if ($_POST["estadoMatricula"]==1){
        Estudiantes::ActualizarEstadoMatricula();
     } elseif ($_POST["estadoMatricula"]==3){
            mysqli_query($conexion, "UPDATE academico_matriculas_retiradas SET matret_motivo='El Estudiante Estaba Retirado, Pero Fue Restaurado Exitosamente!' WHERE matret_estudiante='".$_POST["estudiante"]."'");

            mysqli_query($conexion, "UPDATE academico_matriculas SET mat_estado_matricula=1 WHERE mat_id='".$_POST["estudiante"]."'");
            
     }

    echo '<script type="text/javascript">window.location.href="estudiantes-retirar.php?id='.$_POST["estudiante"].'"</script>';
    exit();
    