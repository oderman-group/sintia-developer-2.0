<?php
session_start();
include("../../../config-general/config.php");
include("../../../config-general/consulta-usuario-actual.php");?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<?php
//contador materias
$cont_periodos=0;
$contador_indicadores=0;
$materiasPerdidas=0;
$contador_materias=0;
//======================= DATOS DEL ESTUDIANTE MATRICULADO =========================
$usr=mysql_query("SELECT * FROM academico_matriculas am
INNER JOIN academico_grupos ON mat_grupo=gru_id
INNER JOIN academico_grados ON mat_grado=gra_id WHERE mat_id=".$_GET["id"],$conexion);
$num_usr=mysql_num_rows($usr);
$datos_usr=mysql_fetch_array($usr);
if($num_usr==0)
{
?>
	<script type="text/javascript">
		window.close();
	</script>
<?php
	exit();
}

$contador_periodos=0;
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
if($periodoActual==1) $periodoActuales = "Primero";
if($periodoActual==2) $periodoActuales = "Segundo";
if($periodoActual==3) $periodoActuales = "Tercero";
if($periodoActual==4) $periodoActuales = "Final";
//if($periodoActual==5) $periodoActuales = "Final";
?>

<?php
//CONSULTA QUE ME TRAE EL DESEMPEÑO
$consulta_desempeno=mysql_query("SELECT notip_id, notip_nombre, notip_desde, notip_hasta FROM academico_notas_tipos WHERE notip_categoria=".$config["conf_notas_categoria"].";",$conexion);	
//CONSULTA QUE ME TRAE LAS areas DEL ESTUDIANTE
$consulta_mat_area_est=mysql_query("SELECT ar_id FROM academico_cargas ac
INNER JOIN academico_materias am ON am.mat_id=ac.car_materia
INNER JOIN academico_areas ar ON ar.ar_id= am.mat_area
WHERE  car_curso=".$datos_usr["mat_grado"]." AND car_grupo=".$datos_usr["mat_grupo"]." GROUP BY ar.ar_id ORDER BY ar.ar_posicion ASC;",$conexion);
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
    	<td colspan="3" height="30">C&oacute;digo: <b><?=$datos_usr["mat_matricula"];?></b></td>
        <td width="3%">&nbsp;</td>
        <td width="3%">&nbsp;</td>
        <td>Año: <b><?=date("Y");?></b></td>   
    </tr>
    
    <tr>
    	<td width="30%" height="30" style="border-top-width:2;border-top-style:solid;border-bottom-width:2;border-bottom-style:solid"><b><?=strtoupper($datos_usr["mat_primer_apellido"]." ".$datos_usr["mat_segundo_apellido"]." ".$datos_usr["mat_nombres"]);?></b></td>
    	<td width="40%" style="border-top-width:2;border-top-style:solid;border-bottom-width:2;border-bottom-style:solid"><b><?=$datos_usr["gra_nombre"]." ".$datos_usr["gru_nombre"];?></b></td>
   	  <td width="20%" style="border-top-width:2;border-top-style:solid;border-bottom-width:2;border-bottom-style:solid">&nbsp;</td>
        <td colspan="2" style="border-top-width:2;border-top-style:solid;border-bottom-width:2;border-bottom-style:solid">Matrícula: <b>71075</b></td>
        <td width="4%" style="border-top-width:2;border-top-style:solid;border-bottom-width:2;border-bottom-style:solid">Folio: <b>95</b></td>    
    </tr>
</table>


<br>
<p>
<table width="70%" id="tblBoletin" style="" cellspacing="0" cellpadding="0" border="0" align="left">
<tr style="font-weight:bold; background:#ECECEC; height:10px; color:#000; font-size:12px;">
<td width="20%" height="30" align="center" style="border-bottom-style:solid;border-bottom-width:2">AREAS/ ASIGNATURAS</td>
<td width="2%" align="center" style="border-bottom-style:solid;border-bottom-width:2" >I.H</td>
<td width="4%" align="center" style="border-bottom-style:solid;border-bottom-width:2">DEF</td>
<td width="8%" align="center" style="border-bottom-style:solid;border-bottom-width:2">DESEMPE&Ntilde;O</td>   
<td width="5%" align="center" style="border-bottom-style:solid;border-bottom-width:2">AUS</td>
</tr>
<?php 
$numero_filas=mysql_num_rows($consulta_mat_area_est);
while($fila = mysql_fetch_array($consulta_mat_area_est)){
$contfilas++;
//CONSULTA QUE ME EL NOMBRE Y EL PROMEDIO DEL AREA
$consulta_notdef_area=mysql_query("SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre FROM academico_materias am
INNER JOIN academico_areas a ON a.ar_id=am.mat_area
INNER JOIN academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$_GET["id"]."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (1)
GROUP BY ar_id;",$conexion);
//CONSULTA QUE ME TRAE LA DEFINITIVA POR MATERIA Y NOMBRE DE LA MATERIA
$consulta_a_mat=mysql_query("SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre,mat_nombre,mat_id FROM academico_materias am
INNER JOIN academico_areas a ON a.ar_id=am.mat_area
INNER JOIN academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$_GET["id"]."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (1)
GROUP BY mat_id
ORDER BY mat_id;",$conexion);
$consulta_a_mat2=mysql_query("SELECT mat_id FROM academico_materias am
INNER JOIN academico_areas a ON a.ar_id=am.mat_area
INNER JOIN academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$_GET["id"]."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (1)
GROUP BY mat_id
ORDER BY mat_id;",$conexion);
//CONSULTA QUE ME TRAE LAS DEFINITIVAS POR PERIODO
$consulta_a_mat_per=mysql_query("SELECT bol_nota,bol_periodo,ar_nombre,mat_nombre,mat_id FROM academico_materias am
INNER JOIN academico_areas a ON a.ar_id=am.mat_area
INNER JOIN academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$_GET["id"]."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (1)
ORDER BY mat_id,bol_periodo
;",$conexion);

$num_materias=mysql_num_rows($consulta_a_mat);
$resultado_not_area=mysql_fetch_array($consulta_notdef_area);
//$total_promedio=$resultado_not_area["suma"];
//$total_promedio=round($total_promedio/4,1);
	$total_promedio=round($resultado_not_area["suma"],1);
if($total_promedio==1)	$total_promedio="1.0";	if($total_promedio==2)	$total_promedio="2.0";		if($total_promedio==3)	$total_promedio="3.0";	if($total_promedio==4)	$total_promedio="4.0";	if($total_promedio==5)	$total_promedio="5.0";
?> 
<tr style="background:#F3F3F3;">
<?php
if($num_materias==1){
	$c_m1=mysql_fetch_array($consulta_a_mat2);
	} 
if($numero_filas==$contfilas && $num_materias==1){
?>
      <td class="area" height="30" id="" style="font-size:12px; border-bottom-style:solid;border-bottom-width:2;font-weight:<?php if($num_materias>1){echo "bold";}?>"><?php echo $resultado_not_area["ar_nombre"];?></td>
      <td align="center" style="font-size:11px;border-bottom-style:solid;border-bottom-width:2;"><?php
	   if($num_materias==1){echo 1; 
	   }?></td>
       <td align='center' style='font-size:12px;border-bottom-style:solid;border-bottom-width:2;'><?php echo $total_promedio;?></td>
        <td  align="center" style="border-bottom-style:solid;border-bottom-width:2;font-size:12px;"><?php //DESEMPEÑO
		while($r_desempeno=mysql_fetch_array($consulta_desempeno)){
			if($total_promedio>=$r_desempeno["notip_desde"] && $total_promedio<=$r_desempeno["notip_hasta"]){
				echo $r_desempeno["notip_nombre"];
				
				}
			}
			mysql_data_seek($consulta_desempeno,0);
		 ?></td>
        <td align='center' style="border-bottom-style:solid;border-bottom-width:2;font-size:12px;"><?php echo 1;?></td>
      <td ></td>
      <td ></td>
<?php
}else{
// font-weight:bold;
?>
    
	  <td class="area" height="30" id="" style="font-size:12px;font-weight:<?php if($num_materias>1){echo "bold";}?>"><?php echo $resultado_not_area["ar_nombre"];?></td>
      <td align="center" style="font-size:11px;"><?php if($num_materias==1){echo 1;
	  }?></td>
       <td align="center" style='font-size:12px;'><?php echo "hola";?></td>
        <td  align="center"  style="font-size:12px;"><?php //DESEMPEÑO
		while($r_desempeno=mysql_fetch_array($consulta_desempeno)){
			if($total_promedio>=$r_desempeno["notip_desde"] && $total_promedio<=$r_desempeno["notip_hasta"]){
				echo $r_desempeno["notip_nombre"];
				
				}
			}
			mysql_data_seek($consulta_desempeno,0);
		 ?></td>
        <td align='center' style="font-size:12px;"><?php echo 0;?></td>
      <td align="center"></td>
      <td align="center"></td>
   
    <?php
}
?>
</tr>
<?php if($num_materias>1){
	$num_materias=mysql_num_rows($consulta_a_mat);
	 while($fila2=mysql_fetch_array($consulta_a_mat)){
		 $contador_materias++;
	$fila3=mysql_fetch_array($consulta_a_mat_per);
	$nota_periodo=round($fila3["bol_nota"],1);
	  if($nota_periodo==1)	$nota_periodo="1.0";	if($nota_periodo==2)	$nota_periodo="2.0";		if($nota_periodo==3)	$nota_periodo="3.0";	if($nota_periodo==4)	$nota_periodo="4.0";	if($nota_periodo==5)	$nota_periodo="5.0";
	if($contador_materias==$num_materias){
	?>
<tr bgcolor="" style="font-size:12px;">
            <td style="font-size:12px; height:30px;border-bottom-style:solid;border-bottom-width:2;"><?php echo $fila2["mat_nombre"];?></td> 
            <td align="center" style="font-size:11px;border-bottom-style:solid;border-bottom-width:2;">0</td>
<td align='center' style='font-size:12px;border-bottom-style:solid;border-bottom-width:2;'><?php echo $nota_periodo;?></td>
        <td align="center"  style="border-bottom-style:solid;border-bottom-width:2;"><?php //DESEMPEÑO
		while($r_desempeno=mysql_fetch_array($consulta_desempeno)){
			if($nota_periodo>=$r_desempeno["notip_desde"] && $nota_periodo<=$r_desempeno["notip_hasta"]){
				echo $r_desempeno["notip_nombre"];
				}
			}
			mysql_data_seek($consulta_desempeno,0);
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
<td align='center' style='font-size:12px;'><?php echo $nota_periodo;?></td>
        <td align="center"  style=""><?php //DESEMPEÑO
		while($r_desempeno=mysql_fetch_array($consulta_desempeno)){
			if($nota_periodo>=$r_desempeno["notip_desde"] && $nota_periodo<=$r_desempeno["notip_hasta"]){
				echo $r_desempeno["notip_nombre"];
				}
			}
			mysql_data_seek($consulta_desempeno,0);
		 ?></td>
        <td align='center' style=""><?php echo 0;?></td>
      <td align="center"></td>
      <td align="center"></td>
        </tr>
    

<?php
	}
}//fin while materias
}//fin if1
}//FIN WHILE AREAS
	 ?>	
      	
</table>
<table table width="30%" id="tblBoletin" style="" cellspacing="0" cellpadding="0" border="0" align="left">
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
		$msj = "EL (LA) ESTUDIANTE ".strtoupper($datos_usr['mat_primer_apellido']." ".$datos_usr['mat_segundo_apellido']." ".$datos_usr['mat_nombres']." ".$datos_usr['mat_nombre2'])." NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE";
	elseif($materiasPerdidas<3 and $materiasPerdidas>0)
		$msj = "EL (LA) ESTUDIANTE ".strtoupper($datos_usr['mat_primer_apellido']." ".$datos_usr['mat_segundo_apellido']." ".$datos_usr['mat_nombres']." ".$datos_usr['mat_nombre2'])." DEBE NIVELAR LAS MATERIAS PERDIDAS";
	else
		$msj = "EL (LA) ESTUDIANTE ".strtoupper($datos_usr['mat_primer_apellido']." ".$datos_usr['mat_segundo_apellido']." ".$datos_usr['mat_nombres']." ".$datos_usr['mat_nombre2'])." FUE PROMOVIDO(A) AL GRADO SIGUIENTE";	
?>
<br><br><br><br><br><br><br><br><br>
<div style="width:70%;font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-style:italic; font-size:12px; margin-top:60px;" align="left"><?=$msj;?></div>

</p>					                   
  


                         
</body>
</html>

<script>
//print();
</script>