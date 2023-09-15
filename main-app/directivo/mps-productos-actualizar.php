<?php
    include("session.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DV0065';
    include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(empty($_POST["nombre"]) || empty($_POST["descripcion"]) || $_POST["precio"]=='' || empty($_POST["categoria"]) || empty($_POST["empresa"])){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="mps-productos-aditar.php?error=ER_DT_4&idR='.$_POST["idR"].'";</script>';
        exit();
    }
    
	if (!empty($_FILES['imagen']['name'])) {
        $explode = explode(".", $_FILES['imagen']['name']);
        $extension = end($explode);
        $archivo = uniqid($_POST["empresa"] . '_prod_') . "." . $extension;
        $destino = "../files/marketplace/productos";
        move_uploaded_file($_FILES['imagen']['tmp_name'], $destino . "/" . $archivo);

        try{
            mysqli_query($conexion, "UPDATE " . $baseDatosMarketPlace . ".productos SET  prod_foto='".$archivo."' WHERE prod_id='".$_POST["idR"]."'");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
	}

    $findme   = '?v=';
    $pos = strpos($_POST["video"], $findme) + 3;
    $video = substr($_POST["video"], $pos, 11);

    try{
        mysqli_query($conexion, "UPDATE " . $baseDatosMarketPlace . ".productos SET 
        prod_nombre='".mysqli_real_escape_string($conexion,$_POST["nombre"])."', 
        prod_descripcion='".mysqli_real_escape_string($conexion,$_POST["descripcion"])."', 
        prod_existencias='".$_POST["existencia"]."', 
        prod_precio='".$_POST["precio"]."', 
        prod_empresa='".$_POST["empresa"]."', 
        prod_video='".$video."', 
        prod_keywords='".mysqli_real_escape_string($conexion,$_POST["keyw"])."', 
        prod_categoria='".$_POST["categoria"]."', 
        prod_activo='".$_POST["estado"]."'
        WHERE prod_id='".$_POST["idR"]."'");
    } catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
    
    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="mps-productos.php?success=SC_DT_2&id='.$_POST["idR"].'";</script>';
    exit();