<?php
    include("session.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DV0053';
    include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(empty($_POST["nombre"])){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="mps-categorias-servicios-aditar.php?error=ER_DT_4&idR='.base64_encode($_POST["idR"]).'";</script>';
        exit();
    }

    try{
        mysqli_query($conexion, "UPDATE " . $baseDatosMarketPlace . ".servicios_categorias SET svcat_nombre='".$_POST["nombre"]."', svcat_icon='".$_POST["icon"]."' WHERE svcat_id='".$_POST["idR"]."'");
    } catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
    
    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="mps-categorias-servicios.php?success=SC_DT_2&id='.base64_encode($_POST["idR"]).'";</script>';
    exit();