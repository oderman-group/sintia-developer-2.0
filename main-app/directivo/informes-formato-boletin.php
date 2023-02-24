<?php
    include("session.php");
    include("../modelo/conexion.php");
    
    $year=$agnoBD;
    if(isset($_POST["year"])){
    $year=$_POST["year"];
    }
    $BD=$_SESSION["inst"]."_".$year;

    $curso="";
    if(isset($_POST["curso"])){$curso=$_POST["curso"];}
    $grupo="";
    if(isset($_POST["grupo"])){$grupo=$_POST["grupo"];}
    $periodo="";
    if(isset($_POST["periodo"])){$periodo=$_POST["periodo"];}
    $id="";
    if(isset($_POST["estudiante"])){$id=$_POST["estudiante"];}

    $consulta="";
    if(isset($_POST["curso"]) AND $_POST["curso"]!=""){
    $consulta = mysqli_query($conexion, "SELECT * FROM $BD.academico_grados WHERE gra_id='".$curso."' ");
    }

    if(isset($_POST["estudiante"]) AND $_POST["estudiante"]!=""){
    $consulta = mysqli_query($conexion, "SELECT * FROM $BD.academico_matriculas INNER JOIN $BD.academico_grados ON gra_id=mat_grado WHERE mat_id=$id");
    }

    $boletin = mysqli_fetch_array($consulta, MYSQLI_BOTH);
    $numDatos=mysqli_num_rows($consulta);

    $ruta="informes-boletines.php?error=ER_DT_9";
    if($numDatos>0){
        $ruta="../compartido/matricula-boletin-curso-".$boletin['gra_formato_boletin'].".php?id=".$id."&periodo=".$periodo."&curso=".$curso."&grupo=".$grupo."&year=".$year;
    }
	echo '<script type="text/javascript">window.location.href="'.$ruta.'";</script>';
	exit();