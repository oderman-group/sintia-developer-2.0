<?php
    include("session.php");
    require_once("../class/Estudiantes.php");
    
    $year=$_SESSION["bd"];
    if(isset($_POST["year"])){
    $year=$_POST["year"];
    }
    
    $curso="";
    if(isset($_POST["curso"])){$curso=base64_encode($_POST["curso"]);}
    $grupo="";
    if(isset($_POST["grupo"])){$grupo=base64_encode($_POST["grupo"]);}
    $periodo="";
    if(isset($_POST["periodo"])){$periodo=base64_encode($_POST["periodo"]);}
    $id="";
    if(isset($_POST["estudiante"])){$id=base64_encode($_POST["estudiante"]);}

    $consulta="";
    if(isset($_POST["curso"]) AND $_POST["curso"]!=""){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados WHERE gra_id='".$_POST["curso"]."' AND institucion={$config['conf_id_institucion']} AND year={$year}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    if(isset($_POST["estudiante"]) AND $_POST["estudiante"]!=""){
    $consulta =Estudiantes::obtenerDatosEstudiantesParaBoletin($_POST["estudiante"],$year);
    }

    $boletin = mysqli_fetch_array($consulta, MYSQLI_BOTH);
    $numDatos=mysqli_num_rows($consulta);

    $ruta="informes-todos.php?error=ER_DT_9";
    if($numDatos>0){
        $ruta="../compartido/matricula-boletin-curso-".$boletin['gra_formato_boletin'].".php?id=".$id."&periodo=".$periodo."&curso=".$curso."&grupo=".$grupo."&year=".base64_encode($year);
    }
	echo '<script type="text/javascript">window.location.href="'.$ruta.'";</script>';
	exit();