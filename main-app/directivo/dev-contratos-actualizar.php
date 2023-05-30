<?php
    include("session.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DV0024';
    include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["titulo"])=="" || trim($_POST["descripcion"])==""){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="dev-contratos.php?error=ER_DT_4";</script>';
        exit();
    }
    
    try{
        mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".contratos SET cont_nombre='".$_POST["titulo"]."', cont_descripcion='".$_POST["descripcion"]."', cont_fecha_modificacion=now(), cont_visible='".$_POST["visible"]."' WHERE cont_id=1");
    } catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
    
    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="dev-contratos.php?success=SC_DT_2&id=1";</script>';
    exit();