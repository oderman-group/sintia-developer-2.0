<?php
    include("session.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DV0021';
    include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["nombrePagina"])=="" || trim($_POST["rutaPagina"])==""){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="dev-paginas-editar.php?error=ER_DT_4&idP='.$_POST["codigoPagina"].'";</script>';
        exit();
    }

    //COMPROBAMOS QUE NO EXISTA LA RUTA
    try{
        $verificar=mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".paginas_publicidad WHERE pagp_id!='".$_POST["codigoPagina"]."' AND pagp_ruta='".$_POST["rutaPagina"]."' AND pagp_tipo_usuario='".$_POST["tipoUsuario"]."'");
    } catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
    $numIdPaginas=mysqli_num_rows($verificar);
    
    if($numIdPaginas>0){
        $datosPaginas=mysqli_fetch_array($verificar, MYSQLI_BOTH);
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="dev-paginas-editar.php?error=ER_DT_14&idP='.$_POST["codigoPagina"].'&id='.$datosPaginas['pagp_id'].'&nombrePagina='.$datosPaginas['pagp_pagina'].'";</script>';
        exit();
    }

    $paginaDependencia=!empty($_POST["paginaDependencia"])?implode(',',$_POST["paginaDependencia"]):NULL;

    try{
        mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".paginas_publicidad SET pagp_pagina='".$_POST["nombrePagina"]."', pagp_tipo_usuario='".$_POST["tipoUsuario"]."', pagp_modulo='".$_POST["modulo"]."', pagp_ruta='".$_POST["rutaPagina"]."', pagp_palabras_claves='".$_POST["palabrasClaves"]."', pagp_navegable='".$_POST["navegable"]."', pagp_crud='".$_POST["crud"]."', pagp_pagina_padre='".$_POST["paginaPadre"]."', pagp_url_youtube='".$_POST["urlYoutube"]."', pagp_descripcion='".$_POST["descripcion"]."', pagp_paginas_dependencia='".$paginaDependencia."' WHERE pagp_id='".$_POST["codigoPagina"]."'");
    } catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
    
    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="dev-paginas.php?success=SC_DT_2&id='.base64_encode($_POST["codigoPagina"]).'";</script>';
    exit();