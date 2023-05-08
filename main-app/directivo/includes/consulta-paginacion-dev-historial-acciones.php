<?php
    $nombrePagina="dev-historial-acciones.php";
    if($_REQUEST["nume"] == "" ){$_REQUEST["nume"] = "1";}
    $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".seguridad_historial_acciones
    LEFT JOIN ".$baseDatosServicios.".instituciones ON ins_id=hil_institucion
    LEFT JOIN ".$baseDatosServicios.".paginas_publicidad ON pagp_id=hil_titulo
    LEFT JOIN usuarios ON uss_id=hil_usuario
    WHERE hil_id=hil_id $filtro
    ORDER BY hil_id DESC;");
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }