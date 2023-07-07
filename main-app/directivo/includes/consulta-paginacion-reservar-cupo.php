<?php
    $nombrePagina="reservar-cupo.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = "1";}
    try{
        $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_encuestas
        INNER JOIN academico_matriculas ON mat_id=genc_estudiante $filtroMat
        INNER JOIN academico_grados ON gra_id=mat_grado
        INNER JOIN academico_grupos ON gru_id=mat_grupo
        WHERE genc_id=genc_id $filtro");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }