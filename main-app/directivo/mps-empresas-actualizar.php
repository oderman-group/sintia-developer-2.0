<?php
    include("session.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DV0059';
    include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(empty($_POST["nombre"]) || empty($_POST["email"]) || empty($_POST["telefono"]) || empty($_POST["sector"]) || empty($_POST["institucion"])){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="mps-empresas-aditar.php?error=ER_DT_4&idR='.$_POST["idR"].'";</script>';
        exit();
    }

    $responsable=''; if(!empty($_POST["responsable"])){ $responsable=$_POST["responsable"]; }

    try{
        mysqli_query($conexion, "UPDATE " . $baseDatosMarketPlace . ".empresas SET 
        emp_nombre='".$_POST["nombre"]."', 
        emp_email='".$_POST["email"]."', 
        emp_telefono='".$_POST["telefono"]."', 
        emp_web='".$_POST["web"]."', 
        emp_usuario='".$responsable."', 
        emp_institucion='".$_POST["institucion"]."'
        WHERE emp_id='".$_POST["idR"]."'");
    } catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

    if(!empty($_POST["sector"])){
        $consultaCat = mysqli_query($conexion, "SELECT excat_categoria FROM ".$baseDatosMarketPlace.".empresas_categorias WHERE excat_empresa='".$_POST["idR"]."'");
		$idCategoriasSector = array();
        if (mysqli_num_rows($consultaCat)>0) { 
            $catSector = mysqli_fetch_all($consultaCat, MYSQLI_ASSOC);
            $idCategoriasSector = array_column($catSector, 'excat_categoria');
        }

        $resultadoAgregar= array_diff($_POST["sector"],$idCategoriasSector);
        if(!empty($resultadoAgregar)){
            foreach ($resultadoAgregar as $idSectorGuardar) {
                try{
                    mysqli_query($conexion, "INSERT INTO ".$baseDatosMarketPlace.".empresas_categorias(excat_empresa, excat_categoria)VALUES('".$_POST["idR"]."', '".$idSectorGuardar."')");
                } catch (Exception $e) {
                    include("../compartido/error-catch-to-report.php");
                }
            }
        }

        $resultadoEliminar= array_diff($idCategoriasSector,$_POST["sector"]);
        if(!empty($resultadoEliminar)){
            foreach ($resultadoEliminar as $idSectorEliminar) {
                try{
                    mysqli_query($conexion,"DELETE FROM ".$baseDatosMarketPlace.".empresas_categorias WHERE excat_categoria='".$idSectorEliminar."' AND excat_empresa='".$_POST["idR"]."'");
                } catch (Exception $e) {
                    include("../compartido/error-catch-to-report.php");
                }
            }
        }
    }else{
        try{
            mysqli_query($conexion,"DELETE FROM ".$baseDatosMarketPlace.".empresas_categorias WHERE excat_empresa='".$_POST["idR"]."'");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }
    
    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="mps-empresas.php?success=SC_DT_2&id='.$_POST["idR"].'";</script>';
    exit();