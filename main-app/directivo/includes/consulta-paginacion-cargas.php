<?php
    $nombrePagina="cargas.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta=mysqli_query($conexion,"SELECT * FROM academico_cargas
        INNER JOIN academico_grados ON gra_id=car_curso
        INNER JOIN academico_grupos ON gru_id=car_grupo
        INNER JOIN academico_materias ON mat_id=car_materia
        INNER JOIN usuarios ON uss_id=car_docente
        WHERE car_id=car_id $filtro
        ORDER BY car_id;");
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