<?php
    include("session.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DV0047';
    include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(empty($_POST["nombre"])){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="mps-categorias-productos-aditar.php?error=ER_DT_4&idR='.$_POST["idR"].'";</script>';
        exit();
    }

    try{
        mysqli_query($conexion, "UPDATE " . $baseDatosMarketPlace . ".categorias_productos SET catp_nombre='".$_POST["nombre"]."' WHERE catp_id='".$_POST["idR"]."'");
    } catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
    
    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="mps-categorias-productos.php?success=SC_DT_2&id='.$_POST["idR"].'";</script>';
    exit();