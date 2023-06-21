<?php
    $nombrePagina="movimientos.php";
    if($_REQUEST["nume"] == "" ){$_REQUEST["nume"] = "1";}
    $consulta = mysqli_query($conexion, "SELECT * FROM finanzas_cuentas
    INNER JOIN usuarios ON uss_id=fcu_usuario
    WHERE fcu_id=fcu_id $filtro
    ORDER BY fcu_id");
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }