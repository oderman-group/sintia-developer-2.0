<?php
    include("session.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DV0051';
    include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(empty($_POST["nombre"])){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="mps-categorias-servicios-agregar.php?error=ER_DT_4";</script>';
        exit();
    }

    try{
        mysqli_query($conexion, "INSERT INTO " . $baseDatosMarketPlace . ".servicios_categorias(svcat_nombre,svcat_icon) VALUES ('".$_POST["nombre"]."','".$_POST["icon"]."')");
    } catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
    $idRegistro = mysqli_insert_id($conexion);
    
    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="mps-categorias-servicios.php?success=SC_DT_1&id='.$idRegistro.'";</script>';
    exit();