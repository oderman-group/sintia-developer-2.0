<?php
include("session-compartida.php");
$idPaginaInterna = 'DT0247';

if($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && !Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once("../class/Estudiantes.php");
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<?php

$id="";
if(!empty($_GET["id"])){ $id=base64_decode($_GET["id"]);}
//contador materias
$contPeriodos=0;
$contadorIndicadores=0;
$materiasPerdidas=0;
$contadorMaterias=0;
//======================= DATOS DEL ESTUDIANTE MATRICULADO =========================
$numUsr=Estudiantes::validarExistenciaEstudiante($id);
$datosUsr=Estudiantes::obtenerDatosEstudiante($id);
$nombre = Estudiantes::NombreCompletoDelEstudiante($datosUsr);
if($numUsr==0)
{
?>
	<script type="text/javascript">
		window.close();
	</script>
<?php
	exit();
}

$contadorPeriodos=0;
?>
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
</head>

<body style="font-family:Arial;">

<?php
//CONSULTA QUE ME TRAE EL DESEMPEÑO
$consultaDesempeno=mysqli_query($conexion, "SELECT notip_id, notip_nombre, notip_desde, notip_hasta FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_categoria=".$config["conf_notas_categoria"]." AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]};");	
//CONSULTA QUE ME TRAE LAS areas DEL ESTUDIANTE
$consultaMatAreaEst=mysqli_query($conexion, "SELECT ar_id FROM ".BD_ACADEMICA.".academico_cargas car
INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car.car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_areas ar ON ar.ar_id= am.mat_area AND ar.institucion={$config['conf_id_institucion']} AND ar.year={$_SESSION["bd"]}
WHERE  car_curso='".$datosUsr["mat_grado"]."' AND car_grupo='".$datosUsr["mat_grupo"]."' AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]} GROUP BY ar.ar_id ORDER BY ar.ar_posicion ASC;");
 ?>
 <div align="center" style="margin-bottom:20px;">
<img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" height="150" width="250"><br>
    <?=$informacion_inst["info_nombre"]?><br>
</div>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="font-size:12px;">
    <tr style="font-weight:bold;height:10px; color:#000; font-size:22px;">
    <td colspan="6" height="30" align="center">REGISTRO ESCOLAR DE VALORACIÓN</td>
    </tr>
    <tr>
    	<td colspan="3" height="30">C&oacute;digo: <b><?=$datosUsr["mat_matricula"];?></b></td>
        <td width="3%">&nbsp;</td>
        <td width="3%">&nbsp;</td>
        <td>Año: <b><?=date("Y");?></b></td>   
    </tr>
    
    <tr>
    	<td width="30%" height="30" style="border-top-width:2;border-top-style:solid;border-bottom-width:2;border-bottom-style:solid"><b><?=$nombre?></b></td>
    	<td width="40%" style="border-top-width:2;border-top-style:solid;border-bottom-width:2;border-bottom-style:solid"><b><?=$datosUsr["gra_nombre"]." ".$datosUsr["gru_nombre"];?></b></td>
   	  <td width="20%" style="border-top-width:2;border-top-style:solid;border-bottom-width:2;border-bottom-style:solid">&nbsp;</td>
        <td colspan="2" style="border-top-width:2;border-top-style:solid;border-bottom-width:2;border-bottom-style:solid">Matrícula: <b>71075</b></td>
        <td width="4%" style="border-top-width:2;border-top-style:solid;border-bottom-width:2;border-bottom-style:solid">Folio: <b>95</b></td>    
    </tr>
</table>


<br>
<p>
<table width="70%" id="tblBoletin" cellspacing="0" cellpadding="0" border="0" align="left">
<tr style="font-weight:bold; background:#ECECEC; height:10px; color:#000; font-size:12px;">
<td width="20%" height="30" align="center" style="border-bottom-style:solid;border-bottom-width:2">AREAS/ ASIGNATURAS</td>
<td width="2%" align="center" style="border-bottom-style:solid;border-bottom-width:2" >I.H</td>
<td width="4%" align="center" style="border-bottom-style:solid;border-bottom-width:2">DEF</td>
<td width="8%" align="center" style="border-bottom-style:solid;border-bottom-width:2">DESEMPE&Ntilde;O</td>   
<td width="5%" align="center" style="border-bottom-style:solid;border-bottom-width:2">AUS</td>
</tr>
<?php 
$numeroFilas=mysqli_num_rows($consultaMatAreaEst);
$contfilas=0;
while($fila = mysqli_fetch_array($consultaMatAreaEst, MYSQLI_BOTH)){
$contfilas++;
//CONSULTA QUE ME EL NOMBRE Y EL PROMEDIO DEL AREA
$consultaNotdefArea=mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre FROM ".BD_ACADEMICA.".academico_materias am
INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car.car_materia=am.mat_id AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_boletin bol ON bol.bol_carga=car.car_id AND bol.institucion={$config['conf_id_institucion']} AND bol.year={$_SESSION["bd"]}
WHERE bol_estudiante='".$id."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (1) AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
GROUP BY ar_id;");
//CONSULTA QUE ME TRAE LA DEFINITIVA POR MATERIA Y NOMBRE DE LA MATERIA
$consultaMat=mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre,mat_nombre,mat_id FROM ".BD_ACADEMICA.".academico_materias am
INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car.car_materia=am.mat_id AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_boletin bol ON bol.bol_carga=car.car_id AND bol.institucion={$config['conf_id_institucion']} AND bol.year={$_SESSION["bd"]}
WHERE bol_estudiante='".$id."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (1) AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
GROUP BY mat_id
ORDER BY mat_id;");
$consultaMat2=mysqli_query($conexion, "SELECT mat_id FROM ".BD_ACADEMICA.".academico_materias am
INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car.car_materia=am.mat_id AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_boletin bol ON bol.bol_carga=car.car_id AND bol.institucion={$config['conf_id_institucion']} AND bol.year={$_SESSION["bd"]}
WHERE bol_estudiante='".$id."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (1) AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
GROUP BY mat_id
ORDER BY mat_id;");
//CONSULTA QUE ME TRAE LAS DEFINITIVAS POR PERIODO
$consultaMatPer=mysqli_query($conexion, "SELECT bol_nota,bol_periodo,ar_nombre,mat_nombre,mat_id FROM ".BD_ACADEMICA.".academico_materias am
INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car.car_materia=am.mat_id AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_boletin bol ON bol.bol_carga=car.car_id AND bol.institucion={$config['conf_id_institucion']} AND bol.year={$_SESSION["bd"]}
WHERE bol_estudiante='".$id."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (1) AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
ORDER BY mat_id,bol_periodo
;");

$numMaterias=mysqli_num_rows($consultaMat);
$resultadoNotArea=mysqli_fetch_array($consultaNotdefArea, MYSQLI_BOTH);
if(!empty($resultadoNotArea["suma"])) $totalPromedio=round($resultadoNotArea["suma"],1);
if($totalPromedio==1)	$totalPromedio="1.0";	if($totalPromedio==2)	$totalPromedio="2.0";		if($totalPromedio==3)	$totalPromedio="3.0";	if($totalPromedio==4)	$totalPromedio="4.0";	if($totalPromedio==5)	$totalPromedio="5.0";
?> 
<tr style="background:#F3F3F3;">
<?php
if($numMaterias==1){
	$c_m1=mysqli_fetch_array($consultaMat2, MYSQLI_BOTH);
	} 
if($numeroFilas==$contfilas && $numMaterias==1){
?>
      <td class="area" height="30" id="" style="font-size:12px; border-bottom-style:solid;border-bottom-width:2;font-weight:<?php if($numMaterias>1){echo "bold";}?>"><?php echo $resultadoNotArea["ar_nombre"];?></td>
      <td align="center" style="font-size:11px;border-bottom-style:solid;border-bottom-width:2;"><?php
	   if($numMaterias==1){echo 1; 
	   }?></td>
       <td align='center' style='font-size:12px;border-bottom-style:solid;border-bottom-width:2;'><?php echo $totalPromedio;?></td>
        <td  align="center" style="border-bottom-style:solid;border-bottom-width:2;font-size:12px;"><?php //DESEMPEÑO
		while($rDesempeno=mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH)){
        if(!empty($rDesempeno["notip_desde"]) && !empty($rDesempeno["notip_hasta"])){
          if($totalPromedio>=$rDesempeno["notip_desde"] && $totalPromedio<=$rDesempeno["notip_hasta"]){
            echo $rDesempeno["notip_nombre"];
          
          }
        }
			}
			mysqli_data_seek($consultaDesempeno,0);
		 ?></td>
        <td align='center' style="border-bottom-style:solid;border-bottom-width:2;font-size:12px;"><?php echo 1;?></td>
<?php
}else{
// font-weight:bold;
?>
    
	  <td class="area" height="30" id="" style="font-size:12px;font-weight:<?php if($numMaterias>1){echo "bold";}?>"><?php echo $resultadoNotArea["ar_nombre"];?></td>
      <td align="center" style="font-size:11px;"><?php if($numMaterias==1){echo 1;
	  }?></td>
       <td align="center" style='font-size:12px;'><?php echo $totalPromedio;?></td>
        <td  align="center"  style="font-size:12px;"><?php //DESEMPEÑO
		while($rDesempeno=mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH)){
			if($totalPromedio>=$rDesempeno["notip_desde"] && $totalPromedio<=$rDesempeno["notip_hasta"]){
				echo $rDesempeno["notip_nombre"];
				
				}
			}
			mysqli_data_seek($consultaDesempeno,0);
		 ?></td>
        <td align='center' style="font-size:12px;"><?php echo 0;?></td>
   
    <?php
}
?>
</tr>
<?php if($numMaterias>1){
	$numMaterias=mysqli_num_rows($consultaMat);
	 while($fila2=mysqli_fetch_array($consultaMat, MYSQLI_BOTH)){
		 $contadorMaterias++;
	$fila3=mysqli_fetch_array($consultaMatPer, MYSQLI_BOTH);
	$notaPeriodo=round($fila3["bol_nota"],1);
	  if($notaPeriodo==1)	$notaPeriodo="1.0";	if($notaPeriodo==2)	$notaPeriodo="2.0";		if($notaPeriodo==3)	$notaPeriodo="3.0";	if($notaPeriodo==4)	$notaPeriodo="4.0";	if($notaPeriodo==5)	$notaPeriodo="5.0";
	if($contadorMaterias==$numMaterias){
	?>
<tr bgcolor="" style="font-size:12px;">
            <td style="font-size:12px; height:30px;border-bottom-style:solid;border-bottom-width:2;"><?php echo $fila2["mat_nombre"];?></td> 
            <td align="center" style="font-size:11px;border-bottom-style:solid;border-bottom-width:2;">0</td>
<td align='center' style='font-size:12px;border-bottom-style:solid;border-bottom-width:2;'><?php echo $notaPeriodo;?></td>
        <td align="center"  style="border-bottom-style:solid;border-bottom-width:2;"><?php //DESEMPEÑO
		while($rDesempeno=mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH)){
			if($notaPeriodo>=$rDesempeno["notip_desde"] && $notaPeriodo<=$rDesempeno["notip_hasta"]){
				echo $rDesempeno["notip_nombre"];
				}
			}
			mysqli_data_seek($consultaDesempeno,0);
		 ?></td>
        <td align='center' style="border-bottom-style:solid;border-bottom-width:2;"><?php echo 0;?></td>
        </tr>
   
       
<?php
	}//fin if 2
	else{
	?>
    <tr bgcolor="" style="font-size:12px;">
            <td style="font-size:12px; height:30px;"><?php echo $fila2["mat_nombre"];?></td> 
            <td align="center" style="font-size:11px;">0</td>
<td align='center' style='font-size:12px;'><?php echo $notaPeriodo;?></td>
        <td align="center"  style=""><?php //DESEMPEÑO
		while($rDesempeno=mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH)){
			if($notaPeriodo>=$rDesempeno["notip_desde"] && $notaPeriodo<=$rDesempeno["notip_hasta"]){
				echo $rDesempeno["notip_nombre"];
				}
			}
			mysqli_data_seek($consultaDesempeno,0);
		 ?></td>
        <td align='center' style=""><?php echo 0;?></td>
        </tr>
    

<?php
	}
}//fin while materias
}//fin if1
}//FIN WHILE AREAS
	 ?>	
      	
</table>
<table table width="30%" id="tblBoletin" cellspacing="0" cellpadding="0" border="0" align="left">
  <tr style="font-weight:bold; height:10px; color:#000; font-size:12px;">
    <td  height="30" colspan="3" align="center"><b>OBSERVACIONES</b></td>
  </tr>
  <tr>
    <td width="13%" height="30">&nbsp;</td>
    <td width="75%" style="border-bottom-style:solid;border-bottom-width:1" >&nbsp;</td>
    <td width="12%">&nbsp;</td>
  </tr>
  <tr>
    <td width="13%" height="30">&nbsp;</td>
    <td width="75%" style="border-bottom-style:solid;border-bottom-width:1" >&nbsp;</td>
    <td width="12%">&nbsp;</td>
  </tr>
  <tr>
    <td width="13%" height="30">&nbsp;</td>
    <td width="75%" style="border-bottom-style:solid;border-bottom-width:1" >&nbsp;</td>
    <td width="12%">&nbsp;</td>
  </tr>
  <tr>
    <td width="13%" height="30">&nbsp;</td>
    <td width="75%" style="border-bottom-style:solid;border-bottom-width:1" >&nbsp;</td>
    <td width="12%">&nbsp;</td>
  </tr>
  <tr>
    <td width="13%" height="30">&nbsp;</td>
    <td width="75%" style="border-bottom-style:solid;border-bottom-width:1" >&nbsp;</td>
    <td width="12%">&nbsp;</td>
  </tr>
  <tr>
    <td width="13%" height="30">&nbsp;</td>
    <td width="75%" style="border-bottom-style:solid;border-bottom-width:1" >&nbsp;</td>
    <td width="12%">&nbsp;</td>
  </tr>
</table>

</p>
<p style="margin-top:50px;">
<?php 
	if($materiasPerdidas>=3)
		$msj = "EL (LA) ESTUDIANTE ".strtoupper($datosUsr['mat_primer_apellido']." ".$datosUsr['mat_segundo_apellido']." ".$datosUsr['mat_nombres']." ".$datosUsr['mat_nombre2'])." NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE";
	elseif($materiasPerdidas<3 and $materiasPerdidas>0)
		$msj = "EL (LA) ESTUDIANTE ".strtoupper($datosUsr['mat_primer_apellido']." ".$datosUsr['mat_segundo_apellido']." ".$datosUsr['mat_nombres']." ".$datosUsr['mat_nombre2'])." DEBE NIVELAR LAS MATERIAS PERDIDAS";
	else
		$msj = "EL (LA) ESTUDIANTE ".strtoupper($datosUsr['mat_primer_apellido']." ".$datosUsr['mat_segundo_apellido']." ".$datosUsr['mat_nombres']." ".$datosUsr['mat_nombre2'])." FUE PROMOVIDO(A) AL GRADO SIGUIENTE";	
?>
<br><br><br><br><br><br><br><br><br>
<div style="width:70%;font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-style:italic; font-size:12px; margin-top:60px;" align="left"><?=$msj;?></div>

</p>					                   
  


                         
</body>
</html>

<?php
include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
?>
<script>
print();
</script>