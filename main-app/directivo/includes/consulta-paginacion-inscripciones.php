<?php
    $nombrePagina="inscripciones.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = "1";}
    try{
        $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
        INNER JOIN ".$baseDatosAdmisiones.".aspirantes ON asp_id=mat_solicitud_inscripcion
        LEFT JOIN academico_grados ON gra_id=asp_grado
        WHERE mat_estado_matricula=5  $filtro
        ORDER BY mat_primer_apellido");
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