<?php
    include("session.php");
    include("../modelo/conexion.php");

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
    $consulta = mysqli_query($conexion, "SELECT * FROM academico_grados WHERE gra_id='".$curso."' ");
    }

    if(isset($_POST["estudiante"]) AND $_POST["estudiante"]!=""){
    $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas INNER JOIN academico_grados ON gra_id=mat_grado WHERE mat_id=$id");
    }

    $boletin = mysqli_fetch_array($consulta, MYSQLI_BOTH);

	echo '<script type="text/javascript">window.location.href="../compartido/matricula-boletin-curso-'.$boletin['gra_formato_boletin'].'.php?id='.$id.'&periodo='.$periodo.'&curso='.$curso.'&grupo='.$grupo.'";</script>';
	exit();