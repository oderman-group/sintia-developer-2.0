<?php include("../modelo/conexion.php");?>
<?php include("../../../config-general/config.php");?>
<?php
$asig=mysql_query("SELECT * FROM academico_matriculas WHERE mat_grado='".$_GET["curso"]."' AND mat_grupo='".$_GET["grupo"]."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2) AND mat_eliminado=0 ORDER BY mat_primer_apellido",$conexion);			
$num_asg=mysql_num_rows($asig);
$grados = mysql_fetch_array(mysql_query("SELECT * FROM academico_grados, academico_grupos WHERE gra_id='".$_GET["curso"]."' AND gru_id='".$_GET["grupo"]."'",$conexion));
?>
<head>
	<title>Sabanas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../files/images/ico.png">
</head>
<body style="font-family:Arial;">
<div align="center" style="margin-bottom:20px;">
   <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" height="150" width="250"><br>
    <?=$informacion_inst["info_nombre"]?><br>
    INFORME DE SABANAS - PERIODO: <?=$_GET["per"];?></br>
    <b><?=strtoupper($grados["gra_nombre"]." ".$grados["gru_nombre"]);?></b><br>
</div>   
  <table bgcolor="#FFFFFF" width="80%" cellspacing="5" cellpadding="5" rules="all" border="<?php echo $config[13] ?>" style="border:solid; border-color:<?php echo $config[11] ?>;" align="center">
  <tr style="font-weight:bold; font-size:12px; height:30px; background:<?php echo $config[12] ?>;">
        <td align="center">No</b></td>
        <td align="center">C&oacute;digo</td>
        <td align="center">Estudiante</td>
        <!--<td align="center">Gru</td>-->
        <?php
		$materias1=mysql_query("SELECT * FROM academico_cargas WHERE car_curso=".$_GET["curso"]." AND car_grupo='".$_GET["grupo"]."'");
		while($mat1=mysql_fetch_array($materias1)){
			$nombresMat=mysql_query("SELECT * FROM academico_materias WHERE mat_id=".$mat1[4],$conexion);
			$Mat=mysql_fetch_array($nombresMat);
		?>
        	<td align="center"><?=strtoupper($Mat[3]);?></td>      
  		<?php
		}
		?>
        <td align="center" style="font-weight:bold;">PROM</td>
  </tr>
  <?php
  $cont=1;
  $mayor=0;
  $nombreMayor="";
  while($fila=mysql_fetch_array($asig))
  {
  		$cuentaest=mysql_query("SELECT * FROM academico_boletin WHERE bol_estudiante=".$fila[0]." AND bol_periodo=".$_GET["per"]." GROUP BY bol_carga",$conexion);
		$numero=mysql_num_rows($cuentaest);
		$def='0.0';
		
  ?>
  <tr style="font-size:13px;">
      <td align="center"> <?php echo $cont;?></td>
      <td align="center"> <?php echo $fila[1];?></td>
      <td><?=strtoupper($fila[3]." ".$fila[4]." ".$fila[5]);?></td> 
      <!--<td align="center"><?php if($fila[7]==1)echo "A"; else echo "B";?></td> -->
       <?php
		$suma=0;
		$materias1=mysql_query("SELECT * FROM academico_cargas WHERE car_curso=".$_GET["curso"]." AND car_grupo='".$_GET["grupo"]."'");
		while($mat1=mysql_fetch_array($materias1)){
			$notas=mysql_query("SELECT * FROM academico_boletin WHERE bol_estudiante=".$fila[0]." AND bol_carga=".$mat1[0]." AND bol_periodo=".$_GET["per"],$conexion);
			$nota=mysql_fetch_array($notas);
			$defini = $nota[4];
			if($defini<$config[5]) $color='red'; else $color='blue';
			$suma=($suma+$defini);
		?>
        	<td align="center" style="color:<?=$color;?>;"><?php echo $nota[4];?></td>      
  		<?php
		}
		if($numero>0) {
			$def=round(($suma/$numero),2);
		}
		if($def==1)	$def="1.0"; if($def==2)	$def="2.0"; if($def==3)	$def="3.0"; if($def==4)	$def="4.0"; if($def==5)	$def="5.0"; 	
		if($def<$cde[5]) $color='red'; else $color='blue'; 
		$notas1[$cont] = $def;
		$grupo1[$cont] = strtoupper($fila[3]." ".$fila[4]." ".$fila[5]);
		?>
      <td align="center" style="font-weight:bold; color:<?=$color;?>;"><?=$def;?></td>  
</tr>
  <?php
  $cont++;
  }//Fin mientras que
  ?>
  </table>
  
  <p>&nbsp;</p>
    <table bgcolor="#FFFFFF" width="80%" cellspacing="5" cellpadding="5" rules="all" border="<?php echo $config[13];?>" style="border:solid; border-color:<?php echo $config[11] ?>;" align="center">
  	 <tr style="font-weight:bold; font-size:12px; height:30px; background:<?php echo $config[12] ?>;">
        <td colspan="4" align="center" style="color:#FFFFFF;">PRIMEROS PUESTOS</td>
    </tr> 
    
    <tr style="font-weight:bold; font-size:14px; height:40px;">
        <td align="center">No</b></td>
        <td align="center">Estudiante</td>
        <td align="center">Promedio</td>
        <td align="center">Puesto</td>
    </tr> 
  <?php
  	$j=1;
	$cambios=0;
	$valor=0;
  	arsort($notas1); 
	foreach ($notas1 as $key => $val) { 
		if($val!=$valor){
			$valor = $val;
			$cambios++;
		}	
		if($cambios==1){
			$color = '#CCFFCC';
			$puesto = 'Primero';
		}	
		if($cambios==2){
			$color = '#CCFFFF';
			$puesto = 'Segundo';
		}	
		if($cambios==3){
			$color = '#FFFFCC';
			$puesto = 'Tercero';
		}	
		if($cambios==4)
			break;			
	?>	
    <tr style="font-weight:bold; font-size:12px; background:<?=$color;?>">
        <td align="center"><?=$j;?></td>
        <td><?=$grupo1[$key];?></td>
        <td align="center"><?=$val;?></td>
        <td align="center"><?=$puesto;?></td>
    </tr>
	<?php	
		$j++;
	}
  ?>
    
  </table>  
  
  
  
  </center>
	<div align="center" style="font-size:10px; margin-top:10px;">
      <img src="../files/images/sintia.png" height="50" width="100"><br>
      SINTIA -  SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL
    </div>
</body>
</html>


