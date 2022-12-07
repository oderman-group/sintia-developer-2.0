<?php
include("../directivo/session.php");
include("../modelo/conexion.php");

    $consultaExistencia= mysql_query("SELECT * FROM terminos_tratamiento_politicas_usuarios WHERE ttpxu_id_termino_tratamiento_politicas='".$_POST["id"]."' AND ttpxu_id_usuario='".$_POST["idUsuario"]."'",$conexion);
    $numDatos = mysql_num_rows($consultaExistencia);

    if($numDatos==0){
        mysql_query("INSERT INTO terminos_tratamiento_politicas_usuarios(ttpxu_id_termino_tratamiento_politicas, ttpxu_id_usuario, ttpxu_fecha_aceptacion)VALUES('".$_POST["id"]."', '".$_POST["idUsuario"]."', now())", $conexion);
        if(mysql_errno()!=0){echo mysql_error(); exit();}
    }else{
        mysql_query("UPDATE terminos_tratamiento_politicas_usuarios SET ttpxu_fecha_aceptacion=now() WHERE ttpxu_id_termino_tratamiento_politicas='".$_POST["id"]."' AND ttpxu_id_usuario='".$_POST["idUsuario"]."'",$conexion);
        if(mysql_errno()!=0){echo mysql_error(); exit();}
    }
    echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
    exit();