<?php
include("../directivo/session.php");

Modulos::validarAccesoPaginas();
$idPaginaInterna = 'CM0003';
include("../compartido/historial-acciones-guardar.php");

    try{
        $consultaExistencia= mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".contratos_usuarios WHERE cxu_id_contrato='".$_POST["id"]."'  AND cxu_id_institucion='".$config['conf_id_institucion']."'");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    $numDatos = mysqli_num_rows($consultaExistencia);

    if($numDatos==0){
        try{
            mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".contratos_usuarios(cxu_id_usuario, cxu_id_contrato, cxu_fecha_aceptacion, cxu_id_institucion)VALUES('".$_POST["idUsuario"]."', '".$_POST["id"]."', now(), '".$config['conf_id_institucion']."')");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        
    }else{
        try{
            mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".contratos_usuarios SET cxu_fecha_aceptacion=now() WHERE cxu_id_contrato='".$_POST["id"]."'  AND cxu_id_institucion='".$config['conf_id_institucion']."'");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        
    }

	include("../compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
    exit();