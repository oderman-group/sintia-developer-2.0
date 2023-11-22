<?php
    $nombrePagina="reportes-lista.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disciplina_reportes
        INNER JOIN ".BD_DISCIPLINA.".disciplina_faltas ON dfal_id=dr_falta AND dfal_institucion={$config['conf_id_institucion']} AND dfal_year={$_SESSION["bd"]}
        INNER JOIN ".BD_DISCIPLINA.".disciplina_categorias ON dcat_id=dfal_id_categoria AND dcat_institucion={$config['conf_id_institucion']} AND dcat_year={$_SESSION["bd"]}
        INNER JOIN ".BD_ACADEMICA.".academico_matriculas mat ON mat_id_usuario=dr_estudiante AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}
        LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=mat_grado AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
        LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
        LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=dr_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
        WHERE dr_id=dr_id AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} $filtro");
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