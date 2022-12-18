<?php
include("session.php");
include("../modelo/conexion.php");
        
    mysql_query("INSERT INTO academico_matriculas_retiradas (matret_estudiante, matret_fecha, matret_motivo, matret_responsable)VALUES('".$_POST["estudiante"]."', now(), '".$_POST["motivo"]."', '".$_SESSION["id"]."')",$conexion);
    if(mysql_errno()!=0){echo mysql_error(); exit();}

    mysql_query("UPDATE academico_matriculas SET mat_estado_matricula=3 WHERE mat_id='".$_POST["estudiante"]."'",$conexion);
    if(mysql_errno()!=0){echo mysql_error(); exit();}
    echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
    exit();