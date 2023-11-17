<?php
    $nombrePagina="reservar-cupo.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_encuestas
        INNER JOIN academico_matriculas ON mat_id=genc_estudiante $filtroMat
        INNER JOIN academico_grados ON gra_id=mat_grado
        INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
        WHERE genc_institucion=".$config['conf_id_institucion']." AND genc_year={$_SESSION["bd"]} $filtro ORDER BY genc_id DESC");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=base64_decode($_REQUEST["nume"]);
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }