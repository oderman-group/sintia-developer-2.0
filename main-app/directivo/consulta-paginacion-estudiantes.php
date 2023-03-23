<?php
    $nombrePagina="estudiantes.php";
    if($_REQUEST["nume"] == "" ){$_REQUEST["nume"] = "1";}
    $consulta = Estudiantes::listarEstudiantes(0, $filtro, '');
    $numRegistros=mysqli_num_rows($consulta);
    $registros= 20;
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }