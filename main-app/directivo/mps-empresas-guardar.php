<?php
    include("session.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DV0057';
    include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(empty($_POST["nombre"]) || empty($_POST["email"]) || empty($_POST["telefono"]) || empty($_POST["sector"]) || empty($_POST["institucion"])){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="mps-empresas-agregar.php?error=ER_DT_4";</script>';
        exit();
    }

    $responsable=''; if(!empty($_POST["responsable"])){ $responsable=$_POST["responsable"]; }

    try{
        mysqli_query($conexion, "INSERT INTO " . $baseDatosMarketPlace . ".empresas(emp_nombre,emp_email,emp_telefono,emp_web,emp_usuario,emp_institucion) VALUES ('".$_POST["nombre"]."','".$_POST["email"]."','".$_POST["telefono"]."','".$_POST["web"]."','".$responsable."','".$_POST["institucion"]."')");
    } catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
    $idRegistro = mysqli_insert_id($conexion);

    if(!empty($_POST["sector"])){
        $cont = count($_POST["sector"]);
        $i = 0;
        while ($i < $cont) {
            try{
                mysqli_query($conexion, "INSERT INTO " . $baseDatosMarketPlace . ".empresas_categorias(excat_empresa, excat_categoria)VALUES('" . $idRegistro . "', '" . $_POST["sector"][$i] . "')");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
            $i++;
        }
    }
    
    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="mps-empresas.php?success=SC_DT_1&id='.base64_encode($idRegistro).'";</script>';
    exit();