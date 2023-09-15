<?php
    include("session.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DV0045';
    include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(empty($_POST["nombre"])){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="mps-categorias-productos-agregar.php?error=ER_DT_4";</script>';
        exit();
    }

    try{
        mysqli_query($conexion, "INSERT INTO " . $baseDatosMarketPlace . ".categorias_productos(catp_nombre) VALUES ('".$_POST["nombre"]."')");
    } catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
    $idRegistro = mysqli_insert_id($conexion);
    
    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="mps-categorias-productos.php?success=SC_DT_1&id='.$idRegistro.'";</script>';
    exit();