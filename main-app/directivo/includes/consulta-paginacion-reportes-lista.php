<?php
    $nombrePagina="reportes-lista.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta = mysqli_query($conexion, "SELECT * FROM disciplina_reportes
        INNER JOIN disciplina_faltas ON dfal_id=dr_falta
        INNER JOIN disciplina_categorias ON dcat_id=dfal_id_categoria
        INNER JOIN academico_matriculas ON mat_id_usuario=dr_estudiante
        LEFT JOIN academico_grados ON gra_id=mat_grado
        LEFT JOIN academico_grupos ON gru_id=mat_grupo
        LEFT JOIN usuarios ON uss_id=dr_usuario
        WHERE dr_id=dr_id $filtro");
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