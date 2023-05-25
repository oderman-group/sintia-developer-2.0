<?php
    $nombrePagina="dev-terminos-usuarios.php";
    if($_REQUEST["nume"] == "" ){$_REQUEST["nume"] = "1";}
    $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".terminos_tratamiento_politicas_usuarios
    LEFT JOIN ".$baseDatosServicios.".instituciones ON ins_id=ttpxu_id_institucion
    WHERE ttpxu_id_termino_tratamiento_politicas='".$id."' AND YEAR(ttpxu_fecha_aceptacion) =".$year." ".$filtro."
    ORDER BY ttpxu_id DESC;");
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }