<?php
    include("session.php");
    include("../compartido/sintia-funciones.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DV0063';
    include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(empty($_POST["nombre"]) || empty($_POST["descripcion"]) || empty($_POST["precio"]) || empty($_POST["categoria"]) || empty($_POST["empresa"])){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="mps-productos-agregar.php?error=ER_DT_4";</script>';
        exit();
    }

    if (!empty($_FILES['imagen']['name'])) {
        $explode=explode(".", $_FILES['imagen']['name']);
        $extension = end($explode);
        $foto = uniqid($_POST["empresa"] . '_prod_') . "." . $extension;
        $destino = "../files/marketplace/productos";
        move_uploaded_file($_FILES['imagen']['tmp_name'], $destino . "/" . $foto);
    }

    $findme   = '?v=';
    $pos = strpos($_POST["video"], $findme) + 3;
    $video = substr($_POST["video"], $pos, 11);

    try{
        mysqli_query($conexion, "INSERT INTO " . $baseDatosMarketPlace . ".productos(prod_nombre, prod_descripcion, prod_foto, prod_existencias, prod_precio, prod_empresa, prod_video, prod_keywords, prod_categoria)VALUES('" . mysqli_real_escape_string($conexion,$_POST["nombre"]) . "', '" . mysqli_real_escape_string($conexion,$_POST["descripcion"]) . "', '" . $foto . "', '" . $_POST["existencia"] . "', '" . $_POST["precio"] . "', '" . $_POST["empresa"] . "', '" . $video . "', '" . mysqli_real_escape_string($conexion,$_POST["keyw"]) . "', '" . $_POST["categoria"] . "')");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    $idRegistro = mysqli_insert_id($conexion);
    
    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="mps-productos.php?success=SC_DT_1&id='.$idRegistro.'";</script>';
    exit();