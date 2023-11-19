<?php
include("../directivo/session.php");
require_once("../class/Estudiantes.php");
require_once("../class/Plataforma.php");
require_once("../class/Usuarios.php");
require_once("../class/UsuariosPadre.php");
require_once("../class/servicios/GradoServicios.php");
$Plataforma = new Plataforma;

if(empty($_REQUEST["periodo"])){
	$periodoActual = 4;
}else{
	$periodoActual = $_REQUEST["periodo"];
}
//$periodoActual=2;
if($periodoActual==1) $periodoActuales = "Primero";
if($periodoActual==2) $periodoActuales = "Segundo";
if($periodoActual==3) $periodoActuales = "Tercero";
if($periodoActual==4) $periodoActuales = "Final";
$year=$_SESSION["bd"];
if(isset($_POST["year"])){
$year=$_POST["year"];
}
$BD=$_SESSION["inst"]."_".$year;
$bdConsulta = $BD.".";
//CONSULTA ESTUDIANTES MATRICULADOS
$curso='';
if(isset($_POST["curso"])){
	$curso=$_POST["curso"];
}
if(isset($_GET["curso"])){
	$curso=base64_decode($_GET["curso"]);
}

$filtro = 'AND (mat_estado_matricula=1 OR mat_estado_matricula=2)';
if(!empty($_REQUEST["curso"])){$filtro .= " AND mat_grado='".$curso."'";}

$grupo="";
if(!empty($_REQUEST["grupo"])){$filtro .= " AND mat_grupo='".$_REQUEST["grupo"]."'"; $grupo=$_REQUEST["grupo"];}
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<?php

$cursoActual=GradoServicios::consultarCurso($curso);
$matriculadosPorCurso =Estudiantes::listarEstudiantesEnGrados($filtro,"",$cursoActual,$bdConsulta,$grupo);
while($matriculadosDatos = mysqli_fetch_array($matriculadosPorCurso, MYSQLI_BOTH)){
//contador materias
$contPeriodos=0;
$contadorIndicadores=0;
$materiasPerdidas=0;
//======================= DATOS DEL ESTUDIANTE MATRICULADO =========================
$usr =Estudiantes::obtenerDatosEstudiantesParaBoletin($matriculadosDatos['mat_id'],$BD);
$numUsr=mysqli_num_rows($usr);
if($numUsr==0)
{
?>
	<script type="text/javascript">
		window.close();
	</script>
<?php
	exit();
}
$datosUsr = mysqli_fetch_array($usr, MYSQLI_BOTH);
$idGrado=$datosUsr["mat_grado"];
$idGrupo=$datosUsr["mat_grupo"];
if($cursoActual["gra_tipo"]==GRADO_INDIVIDUAL){
	$idGrado=$matriculadosDatos["matcur_id_curso"];
	$idGrupo=$matriculadosDatos["matcur_id_grupo"];
}
$nombre = Estudiantes::NombreCompletoDelEstudiante($datosUsr);

$contadorPeriodos=0;
?>
<!doctype html>
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
	<link rel="shortcut icon" href="<?=$Plataforma->logo;?>">
	<link href="../../config-general/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#saltoPagina
{
	PAGE-BREAK-AFTER: always;
}
@media print {
	@page {
		size: landscape;
	}
}
</style>
</head>

<body style="font-family:Arial;">
<?php
//CONSULTA QUE ME TRAE EL DESEMPEÑO
$consultaDesempeno=mysqli_query($conexion, "SELECT notip_id, notip_nombre, notip_desde, notip_hasta FROM $BD.academico_notas_tipos WHERE notip_categoria=".$config["conf_notas_categoria"].";");	
//CONSULTA QUE ME TRAE LAS areas DEL ESTUDIANTE
$consultaMatAreaEst=mysqli_query($conexion, "SELECT ar_id, car_ih FROM $BD.academico_cargas ac
INNER JOIN $BD.academico_materias am ON am.mat_id=ac.car_materia
INNER JOIN $BD.academico_areas ar ON ar.ar_id= am.mat_area
WHERE  car_curso=".$idGrado." AND car_grupo=".$idGrupo." GROUP BY ar.ar_id ORDER BY ar.ar_posicion ASC;");
$numeroPeriodos=$config["conf_periodo"];

$nombreInforme = "REGISTRO DE VALORACIÓN";
if($config['conf_id_institucion']!=1){
	include("../compartido/head-informes.php");
}else{
?>
<div align="center" style="margin-bottom:10px; font-weight:bold;">
		<img class="img-thumbnail" src="../files/images/logo/<?= $informacion_inst["info_logo"] ?>" width="100%"><br><br>
		<b><?=$nombreInforme?></b>
</div>
<?php } ?>
<div>&nbsp;</div>



<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="font-size:10px;">
    <tr>
    	<td>C&oacute;digo: <b><?=$datosUsr["mat_matricula"];?></b></td>
        <td>Nombre: <b><?=$nombre?></b></td>   
        <td>Matricula: <b><?=$datosUsr["mat_numero_matricula"];?></b></td>   
    </tr>
    
    <tr>
    	<td>Grado: <b><?=$matriculadosDatos["gra_nombre"]." ".$matriculadosDatos["gru_nombre"];?></b></td>
        <td>Periodo: <b><?=strtoupper($periodoActuales);?></b></td>
        <td>Folio: <b><?=$datosUsr["mat_folio"];?></b></td>
    </tr>
</table>
<br>
<table width="100%" align="left">
<tr style="border:solid; font-weight:bold; color:#000; font-size:10px;border-color:<?=$Plataforma->colorUno;?>;">
    <td width="20%" align="center">AREAS/ ASIGNATURAS</td>
    <td width="2%" align="center">I.H</td>
    <td width="4%" align="center">DEF</td>
    <td width="8%" align="center">DESEMPE&Ntilde;O</td>   
    <td width="2%" align="center">AUS</td>
    <td width="15%" align="center">OBSERVACIONES</td>
</tr> 

    <tr>
    	<td class="area" colspan="<?=$columnas;?>" style="font-size:10px; font-weight:bold;"></td>
    </tr>

        <?php while($fila = mysqli_fetch_array($consultaMatAreaEst, MYSQLI_BOTH)){
		
		$condicion="1,2,3,4";
		$condicion2="4";
		
//CONSULTA QUE ME TRAE EL NOMBRE Y EL PROMEDIO DEL AREA
$consultaNotdefArea=mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre FROM $BD.academico_materias am
INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$matriculadosDatos['mat_id']."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (".$condicion.")
GROUP BY ar_id;");
//CONSULTA QUE ME TRAE LA DEFINITIVA POR MATERIA Y NOMBRE DE LA MATERIA
$consultaAMat=mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre,mat_nombre,mat_id,car_id,car_ih FROM $BD.academico_materias am
INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$matriculadosDatos['mat_id']."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (".$condicion.")
GROUP BY mat_id
ORDER BY mat_id;");
//CONSULTA QUE ME TRAE LAS DEFINITIVAS POR PERIODO
$consultaAMatPer=mysqli_query($conexion, "SELECT bol_nota,bol_periodo,ar_nombre,mat_nombre,mat_id FROM $BD.academico_materias am
INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$matriculadosDatos['mat_id']."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (".$condicion.")
ORDER BY mat_id,bol_periodo
;");


$resultadoNotArea=mysqli_fetch_array($consultaNotdefArea, MYSQLI_BOTH);
$numfilasNotArea=mysqli_num_rows($consultaNotdefArea);
$totalPromedio = 0;
if(!empty($resultadoNotArea["suma"])){
	$totalPromedio = round($resultadoNotArea["suma"],1);
}


if($totalPromedio==1)	$totalPromedio="1.0";	if($totalPromedio==2)	$totalPromedio="2.0";		if($totalPromedio==3)	$totalPromedio="3.0";	if($totalPromedio==4)	$totalPromedio="4.0";	if($totalPromedio==5)	$totalPromedio="5.0";
	if($numfilasNotArea>0){
			?>
  <tr style="font-size:10px;">
            <td style="font-size:10px; font-weight:bold;"><?php echo $resultadoNotArea["ar_nombre"];?></td> 
            <td align="center" style="font-weight:bold; font-size:10px;"></td>
        <td align="center" style="font-weight:bold;"><?php 
		
		if($datosUsr["mat_grado"]>11){
				$notaFA = ceil($totalPromedio);
				switch($notaFA){
					case 1: echo "D"; break;
					case 2: echo "I"; break;
					case 3: echo "A"; break;
					case 4: echo "S"; break;
					case 5: echo "E"; break;
				}
				}else{
		echo $totalPromedio;
				}
		
		?></td>
         <td align="center" style="font-weight:bold;"></td>
          <td align="center" style="font-weight:bold;"></td>
	</tr>
<?php

while($fila2=mysqli_fetch_array($consultaAMat, MYSQLI_BOTH)){ 
	$contadorPeriodos=0;
	mysqli_data_seek($consultaAMatPer,0);
	//CONSULTAR NOTA POR PERIODO
	while($fila3=mysqli_fetch_array($consultaAMatPer, MYSQLI_BOTH)){
		if($fila2["mat_id"]==$fila3["mat_id"]){
			$contadorPeriodos++;
			$notaBoletin=0;
			if(!empty($fila3["bol_nota"])){
				$notaBoletin=$fila3["bol_nota"];
			}
			$notaPeriodo=round($notaBoletin,1);
			if($notaPeriodo==1)$notaPeriodo="1.0";	if($notaPeriodo==2)$notaPeriodo="2.0";	if($notaPeriodo==3)$notaPeriodo="3.0";	if($notaPeriodo==4)$notaPeriodo="4.0";	if($notaPeriodo==5)$notaPeriodo="5.0";
			$notas[$contadorPeriodos] =$notaPeriodo;
		}
	}//FIN FILA3
?>
 <tr style="font-size:10px;">
            <td style="font-size:10px;"><?php echo $fila2["mat_nombre"];?></td> 
            <td align="center" style="font-weight:bold; font-size:10px;"><?php echo $fila2["car_ih"];?></td>
<?php
	  $totalPromedio2=round( $fila2["suma"],1);
	   
	   if($totalPromedio2==1)	$totalPromedio2="1.0";	if($totalPromedio2==2)	$totalPromedio2="2.0";		if($totalPromedio2==3)	$totalPromedio2="3.0";	if($totalPromedio2==4)	$totalPromedio2="4.0";	if($totalPromedio2==5)	$totalPromedio2="5.0";
	   //if($totalPromedio2<$rDesempeno["desbasdesde"]){$materiasPerdidas++;}
	    $msj='';
	   if($totalPromedio2<$config[5]){
			$consultaNivelaciones=mysqli_query($conexion, "SELECT * FROM  ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_id_asg='".$fila2['car_id']."' AND niv_cod_estudiante='".$matriculadosDatos['mat_id']."' AND institucion={$config['conf_id_institucion']} AND year={$year}");
			$numNiv=mysqli_num_rows($consultaNivelaciones);
			if($numNiv>0){
				$nivelaciones = mysqli_fetch_array($consultaNivelaciones, MYSQLI_BOTH);
				if($nivelaciones['niv_definitiva']<$config[5]){
					$materiasPerdidas++;
				}else{
					$totalPromedio2 = $nivelaciones['niv_definitiva'];
					$msj='Niv';
				}
			}		   
		}
	   ?>
       
        <td align="center" style="font-weight:bold; "><?php 
		
					if($datosUsr["mat_grado"]>11){
				$notaFI = ceil($totalPromedio2);
				switch($notaFI){
					case 1: echo "D"; break;
					case 2: echo "I"; break;
					case 3: echo "A"; break;
					case 4: echo "S"; break;
					case 5: echo "E"; break;
				}
				}else{
					echo $totalPromedio2;
				}
		
		?></td>
        <td align="center" style="font-weight:bold;"><?php //DESEMPEÑO
		while($rDesempeno=mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH)){
			if($totalPromedio2>=$rDesempeno["notip_desde"] && $totalPromedio2<=$rDesempeno["notip_hasta"]){
				if($datosUsr["mat_grado"]>11){
					$notaFD = ceil($totalPromedio2);
				switch($notaFD){
					case 1: echo "BAJO"; break;
					case 2: echo "BAJO"; break;
					case 3: echo "B&Aacute;SICO"; break;
					case 4: echo "ALTO"; break;
					case 5: echo "SUPERIOR"; break;
				}

				}else{
					
						echo $rDesempeno["notip_nombre"];
					}
				}
			}
			mysqli_data_seek($consultaDesempeno,0);
			$matmaxaus=0;
			if(!empty($fila2["matmaxaus"])){
				$matmaxaus=$fila2["matmaxaus"];
			}
		 ?></td>
        <td align="center" style="font-weight:bold; "><?php if(!empty($rAusencias[0]) && $rAusencias[0]>0){ echo $rAusencias[0]."/".$matmaxaus;} else{ echo "0.0/".$matmaxaus;}?></td>
        
        <td align="center">_______________________________________</td>
	
	</tr>
<?php
}//while fin materias
?>  
<?php 
}}//while fin areas

//MEDIA TECNICA
if (array_key_exists(10, $_SESSION["modulos"]) && $cursoActual["gra_tipo"]!=GRADO_INDIVIDUAL){
	$consultaEstudianteActualMT = MediaTecnicaServicios::existeEstudianteMT($config,$year,$matriculadosDatos['mat_id']);
	while($datosEstudianteActualMT = mysqli_fetch_array($consultaEstudianteActualMT, MYSQLI_BOTH)){
		if(!empty($datosEstudianteActualMT)){

//CONSULTA QUE ME TRAE LAS areas DEL ESTUDIANTE
$consultaMatAreaEst=mysqli_query($conexion, "SELECT ar_id, car_ih FROM $BD.academico_cargas ac
INNER JOIN $BD.academico_materias am ON am.mat_id=ac.car_materia
INNER JOIN $BD.academico_areas ar ON ar.ar_id= am.mat_area
WHERE  car_curso='" . $datosEstudianteActualMT["matcur_id_curso"] . "' AND car_grupo='" . $datosEstudianteActualMT["matcur_id_grupo"] . "' GROUP BY ar.ar_id ORDER BY ar.ar_posicion ASC;");
while($fila = mysqli_fetch_array($consultaMatAreaEst, MYSQLI_BOTH)){

$condicion="1,2,3,4";
$condicion2="4";

//CONSULTA QUE ME TRAE EL NOMBRE Y EL PROMEDIO DEL AREA
$consultaNotdefArea=mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre FROM $BD.academico_materias am
INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$matriculadosDatos['mat_id']."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (".$condicion.")
GROUP BY ar_id;");
//CONSULTA QUE ME TRAE LA DEFINITIVA POR MATERIA Y NOMBRE DE LA MATERIA
$consultaAMat=mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre,mat_nombre,mat_id,car_id,car_ih FROM $BD.academico_materias am
INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$matriculadosDatos['mat_id']."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (".$condicion.")
GROUP BY mat_id
ORDER BY mat_id;");
//CONSULTA QUE ME TRAE LAS DEFINITIVAS POR PERIODO
$consultaAMatPer=mysqli_query($conexion, "SELECT bol_nota,bol_periodo,ar_nombre,mat_nombre,mat_id FROM $BD.academico_materias am
INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$matriculadosDatos['mat_id']."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (".$condicion.")
ORDER BY mat_id,bol_periodo
;");


$resultadoNotArea=mysqli_fetch_array($consultaNotdefArea, MYSQLI_BOTH);
$numfilasNotArea=mysqli_num_rows($consultaNotdefArea);
$totalPromedio = 0;
if(!empty($resultadoNotArea["suma"])){
$totalPromedio = round($resultadoNotArea["suma"],1);
}


if($totalPromedio==1)	$totalPromedio="1.0";	if($totalPromedio==2)	$totalPromedio="2.0";		if($totalPromedio==3)	$totalPromedio="3.0";	if($totalPromedio==4)	$totalPromedio="4.0";	if($totalPromedio==5)	$totalPromedio="5.0";
if($numfilasNotArea>0){
	?>
<tr style="font-size:10px;">
	<td style="font-size:10px; font-weight:bold;"><?php echo $resultadoNotArea["ar_nombre"];?></td> 
	<td align="center" style="font-weight:bold; font-size:10px;"></td>
<td align="center" style="font-weight:bold;"><?php 

if($datosUsr["mat_grado"]>11){
		$notaFA = ceil($totalPromedio);
		switch($notaFA){
			case 1: echo "D"; break;
			case 2: echo "I"; break;
			case 3: echo "A"; break;
			case 4: echo "S"; break;
			case 5: echo "E"; break;
		}
		}else{
echo $totalPromedio;
		}

?></td>
 <td align="center" style="font-weight:bold;"></td>
  <td align="center" style="font-weight:bold;"></td>
</tr>
<?php

while($fila2=mysqli_fetch_array($consultaAMat, MYSQLI_BOTH)){ 
$contadorPeriodos=0;
mysqli_data_seek($consultaAMatPer,0);
//CONSULTAR NOTA POR PERIODO
while($fila3=mysqli_fetch_array($consultaAMatPer, MYSQLI_BOTH)){
if($fila2["mat_id"]==$fila3["mat_id"]){
	$contadorPeriodos++;
	$notaPeriodo=round($fila3["bol_nota"],1);
	if($notaPeriodo==1)$notaPeriodo="1.0";	if($notaPeriodo==2)$notaPeriodo="2.0";	if($notaPeriodo==3)$notaPeriodo="3.0";	if($notaPeriodo==4)$notaPeriodo="4.0";	if($notaPeriodo==5)$notaPeriodo="5.0";
	$notas[$contadorPeriodos] =$notaPeriodo;
}
}//FIN FILA3
?>
<tr style="font-size:10px;">
	<td style="font-size:10px;"><?php echo $fila2["mat_nombre"];?></td> 
	<td align="center" style="font-weight:bold; font-size:10px;"><?php echo $fila2["car_ih"];?></td>
<?php 
$totalPromedio2=round( $fila2["suma"],1);

if($totalPromedio2==1)	$totalPromedio2="1.0";	if($totalPromedio2==2)	$totalPromedio2="2.0";		if($totalPromedio2==3)	$totalPromedio2="3.0";	if($totalPromedio2==4)	$totalPromedio2="4.0";	if($totalPromedio2==5)	$totalPromedio2="5.0";
//if($totalPromedio2<$rDesempeno["desbasdesde"]){$materiasPerdidas++;}
$msj='';
if($totalPromedio2<$config[5]){
	$consultaNivelaciones=mysqli_query($conexion, "SELECT * FROM  ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_id_asg='".$fila2['car_id']."' AND niv_cod_estudiante='".$matriculadosDatos['mat_id']."' AND institucion={$config['conf_id_institucion']} AND year={$year}");
	$numNiv=mysqli_num_rows($consultaNivelaciones);
	if($numNiv>0){
		$nivelaciones = mysqli_fetch_array($consultaNivelaciones, MYSQLI_BOTH);
		if($nivelaciones['niv_definitiva']<$config[5]){
			$materiasPerdidas++;
		}else{
			$totalPromedio2 = $nivelaciones['niv_definitiva'];
			$msj='Niv';
		}
	}		   
}
?>

<td align="center" style="font-weight:bold; "><?php 

			if($datosUsr["mat_grado"]>11){
		$notaFI = ceil($totalPromedio2);
		switch($notaFI){
			case 1: echo "D"; break;
			case 2: echo "I"; break;
			case 3: echo "A"; break;
			case 4: echo "S"; break;
			case 5: echo "E"; break;
		}
		}else{
			echo $totalPromedio2;
		}

?></td>
<td align="center" style="font-weight:bold;"><?php //DESEMPEÑO
while($rDesempeno=mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH)){
	if($totalPromedio2>=$rDesempeno["notip_desde"] && $totalPromedio2<=$rDesempeno["notip_hasta"]){
		if($datosUsr["mat_grado"]>11){
			$notaFD = ceil($totalPromedio2);
		switch($notaFD){
			case 1: echo "BAJO"; break;
			case 2: echo "BAJO"; break;
			case 3: echo "B&Aacute;SICO"; break;
			case 4: echo "ALTO"; break;
			case 5: echo "SUPERIOR"; break;
		}

		}else{
			
				echo $rDesempeno["notip_nombre"];
			}
		}
	}
	mysqli_data_seek($consultaDesempeno,0);
	$matmaxaus=0;
	if(!empty($fila2["matmaxaus"])){
		$matmaxaus=$fila2["matmaxaus"];
	}
 ?></td>
<td align="center" style="font-weight:bold; "><?php if(!empty($rAusencias[0]) && $rAusencias[0]>0){ echo $rAusencias[0]."/".$matmaxaus;} else{ echo "0.0/".$matmaxaus;}?></td>

<td align="center">_______________________________________</td>

</tr>
<?php
}//while fin materias
?>  
<?php 
}}//while fin areas
}}}
?>	 

    
</table>

<p>&nbsp;</p>


</div>
<?php 
if($periodoActual==4){
	if($materiasPerdidas>=$config["conf_num_materias_perder_agno"]){
		$msj = "EL (LA) ESTUDIANTE ".$nombre." NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE";
	}elseif($materiasPerdidas<$config["conf_num_materias_perder_agno"] and $materiasPerdidas>0){
		$msj = "EL (LA) ESTUDIANTE ".$nombre." DEBE NIVELAR LAS MATERIAS PERDIDAS";
	}else{
		$msj = "EL (LA) ESTUDIANTE ".$nombre." FUE PROMOVIDO(A) AL GRADO SIGUIENTE";
	}
}
?>
<p align="left">
	<div style="font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-style:italic; font-size:10px;"><?=$msj;?></div>
</p>


<table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">
	<tr>
		<td align="center">
			<?php
				$consultaRector= mysqli_query($conexion, "SELECT * FROM ".$BD.".usuarios WHERE uss_id='".$informacion_inst["info_rector"]."'");
				$rector = mysqli_fetch_array($consultaRector, MYSQLI_BOTH);
				// $rector = Usuarios::obtenerDatosUsuario($informacion_inst["info_rector"]);
				$nombreRector = UsuariosPadre::nombreCompletoDelUsuario($rector);
				if(!empty($rector["uss_firma"])){
					echo '<img src="../files/fotos/'.$rector["uss_firma"].'" width="200"><br>';
				}else{
					echo '<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>';
				}
			?>
			_________________________________<br>
			<p>&nbsp;</p>
			<?=$nombreRector?><br>
			Rector(a)
		</td>
		<td align="center">
			<?php
				$consultaSecretario= mysqli_query($conexion, "SELECT * FROM ".$BD.".usuarios WHERE uss_id='".$informacion_inst["info_secretaria_academica"]."'");
				$secretario = mysqli_fetch_array($consultaSecretario, MYSQLI_BOTH);
				// $secretario = Usuarios::obtenerDatosUsuario($informacion_inst["info_secretaria_academica"]);
				$nombreScretario = UsuariosPadre::nombreCompletoDelUsuario($secretario);
				if(!empty($secretario["uss_firma"])){
					echo '<img src="../files/fotos/'.$secretario["uss_firma"].'" width="100"><br>';
				}else{
					echo '<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>';
				}
			?>
			_________________________________<br>
			<p>&nbsp;</p>
			<?=$nombreScretario?><br>
			Secretario(a) Académico
		</td>
    </tr>
</table> 

</div>	

<div id="saltoPagina"></div>

<?php
	}// FIN DE TODOS LOS MATRICULADOS
?>
<script type="application/javascript">
print();
</script>
</body>
</html>