<?php
include("../directivo/session.php");
include("../modelo/conexion.php");

    mysql_query("UPDATE mobiliar_sintia_admin.contratos_usuarios SET cxu_fecha_aceptacion=now() WHERE cxu_id='".$_POST["id"]."'",$conexion);
    if(mysql_errno()!=0){echo mysql_error(); exit();}
    echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
    exit();