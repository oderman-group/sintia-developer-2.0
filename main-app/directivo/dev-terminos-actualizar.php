<?php
    include("session.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DV0028';
    include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["titulo"])=="" || trim($_POST["descripcion"])==""){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="dev-terminos.php?error=ER_DT_4";</script>';
        exit();
    }
    
    try{
        mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".terminos_tratamiento_politica SET ttp_nombre='".$_POST["titulo"]."', ttp_descripcion='".$_POST["descripcion"]."', ttp_fecha_modificacion=now(), ttp_visible='".$_POST["visible"]."' WHERE ttp_id='".$_POST['id']."'");
    } catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
    
    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="dev-terminos.php?success=SC_DT_2&id='.$_POST['id'].'";</script>';
    exit();