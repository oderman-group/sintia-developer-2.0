<?php
    $nombrePagina="inscripciones.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat
        INNER JOIN ".$baseDatosAdmisiones.".aspirantes ON asp_id=mat_solicitud_inscripcion
        LEFT JOIN academico_grados ON gra_id=asp_grado
        WHERE mat_estado_matricula=5 AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]} $filtro
        ORDER BY mat_primer_apellido");
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