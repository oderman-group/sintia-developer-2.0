<?php
session_start();
include("../../../config-general/config.php");
include("../../../config-general/consulta-usuario-actual.php");

    $consultaExistencia= mysql_query("SELECT * FROM ".$baseDatosServicios.".terminos_tratamiento_politicas_usuarios WHERE ttpxu_id_termino_tratamiento_politicas='".$_POST["id"]."' AND ttpxu_id_usuario='".$_POST["idUsuario"]."' AND ttpxu_id_institucion='".$config['conf_id_institucion']."'",$conexion);
    $numDatos = mysql_num_rows($consultaExistencia);

    if($numDatos==0){
        mysql_query("INSERT INTO ".$baseDatosServicios.".terminos_tratamiento_politicas_usuarios(ttpxu_id_termino_tratamiento_politicas, ttpxu_id_usuario, ttpxu_id_institucion, ttpxu_fecha_aceptacion)VALUES('".$_POST["id"]."', '".$_POST["idUsuario"]."', '".$config['conf_id_institucion']."', now())", $conexion);
        if(mysql_errno()!=0){echo mysql_error(); exit();}
    }else{
        mysql_query("UPDATE ".$baseDatosServicios.".terminos_tratamiento_politicas_usuarios SET ttpxu_fecha_aceptacion=now() WHERE ttpxu_id_termino_tratamiento_politicas='".$_POST["id"]."' AND ttpxu_id_usuario='".$_POST["idUsuario"]."' AND ttpxu_id_institucion='".$config['conf_id_institucion']."'",$conexion);
        if(mysql_errno()!=0){echo mysql_error(); exit();}
    }
    echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
    exit();