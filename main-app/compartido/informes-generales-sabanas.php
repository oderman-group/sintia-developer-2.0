<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once("../class/Estudiantes.php");
$curso='';
if(!empty($_GET["curso"])) {
  $curso = base64_decode($_GET["curso"]);
}
$grupo='';
if(!empty($_GET["grupo"])) {
  $grupo = base64_decode($_GET["grupo"]);
}
$per='';
if(!empty($_GET["per"])) {
  $per = base64_decode($_GET["per"]);
}

$filtroAdicional= "AND mat_grado='".$curso."' AND mat_grupo='".$grupo."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
$asig =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"");	
$num_asg=mysqli_num_rows($asig);
$consultaGrados=mysqli_query($conexion, "SELECT * FROM academico_grados, academico_grupos WHERE gra_id='".$curso."' AND gru_id='".$grupo."'");
$grados = mysqli_fetch_array($consultaGrados, MYSQLI_BOTH);
?>
<head>
	<title>Sabanas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../files/images/ico.png">
</head>
<body style="font-family:Arial;">
	
<div style="margin: 10px;">
		<img src="../../files-general/instituciones/informes/sabanas.jpg" style="width: 100%;">
	</div>
	
<div align="center" style="margin-bottom:20px;">
    <?=$informacion_inst["info_nombre"]?><br>
    PERIODO: <?=$per;?></br>
    <b><?=strtoupper($grados["gra_nombre"]." ".$grados["gru_nombre"]);?></b><br>

    <?php if($informacion_inst["info_institucion"]==1){?>
    <p><a href="reportes-sabanas-indicador.php?curso=<?=$_GET["curso"];?>&grupo=<?=$_GET["grupo"];?>&per=<?=$_GET["per"];?>" target="_blank">VER SABANAS CON INDICADORES</a></p>
    <?php } ?>
</div>  
<div style="margin: 10px;">
  <table bgcolor="#FFFFFF" width="100%" cellspacing="5" cellpadding="5" rules="all" border="<?php echo $config[13] ?>" style="border:solid; border-color:<?php echo $config[11] ?>;" align="center">
  <tr style="font-weight:bold; font-size:12px; height:30px; background:#6017dc; color:white;">
        <td align="center">No</b></td>
        <td align="center">C&oacute;digo</td>
        <td align="center">Estudiante</td>
        <!--<td align="center">Gru</td>-->
        <?php
		$materias1=mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso=".$curso." AND car_grupo='".$grupo."'");
		while($mat1=mysqli_fetch_array($materias1, MYSQLI_BOTH)){
			$nombresMat=mysqli_query($conexion, "SELECT * FROM academico_materias WHERE mat_id=".$mat1[4]);
			$Mat=mysqli_fetch_array($nombresMat, MYSQLI_BOTH);
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
  while($fila=mysqli_fetch_array($asig, MYSQLI_BOTH)){
    $nombre = Estudiantes::NombreCompletoDelEstudiante($fila);	  
  		$cuentaest=mysqli_query($conexion, "SELECT * FROM academico_boletin WHERE bol_estudiante=".$fila[0]." AND bol_periodo=".$per." GROUP BY bol_carga");
		$numero=mysqli_num_rows($cuentaest);
		$def='0.0';
		
  ?>
  <tr style="font-size:13px;">
      <td align="center"> <?php echo $cont;?></td>
      <td align="center"> <?php echo $fila[1];?></td>
      <td><?=$nombre?></td> 
      <!--<td align="center"><?php if($fila[7]==1)echo "A"; else echo "B";?></td> -->
       <?php
		$suma=0;
		$materias1=mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso=".$curso." AND car_grupo='".$grupo."'");
		while($mat1=mysqli_fetch_array($materias1, MYSQLI_BOTH)){
			$notas=mysqli_query($conexion, "SELECT * FROM academico_boletin WHERE bol_estudiante=".$fila[0]." AND bol_carga=".$mat1[0]." AND bol_periodo=".$per);
			$nota=mysqli_fetch_array($notas, MYSQLI_BOTH);
      $defini = 0;
      if(!empty($nota[4])){$defini = $nota[4];$suma=($suma+$defini);}
			if($defini<$config[5]) $color='red'; else $color='blue';
		?>
        	<td align="center" style="color:<?=$color;?>;"><?php if(!empty($nota[4])){ echo $nota[4];}?></td>      
  		<?php
		}
		if($numero>0) {
			$def=round(($suma/$numero),2);
		}
		if($def==1)	$def="1.0"; if($def==2)	$def="2.0"; if($def==3)	$def="3.0"; if($def==4)	$def="4.0"; if($def==5)	$def="5.0"; 	
		if($def<$config[5]) $color='red'; else $color='blue'; 
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
  
<?php
$puestos = mysqli_query($conexion, "SELECT ROUND(AVG(bol_nota),2) AS prom, mat_primer_apellido, mat_segundo_apellido, mat_nombres FROM academico_boletin
INNER JOIN academico_matriculas ON mat_id=bol_estudiante
INNER JOIN academico_cargas ON car_id=bol_carga AND car_curso='".$curso."' AND car_grupo='".$grupo."'
WHERE bol_periodo='".$per."'
GROUP BY bol_estudiante
ORDER BY prom DESC
");
?>

  <p>&nbsp;</p>
    <table width="100%" border="1" rules="all" align="center">
  	 <tr style="font-weight:bold; font-size:12px; height:30px;">
        <td colspan="3" align="center">PUESTOS</td>
    </tr> 
    
    <tr style="font-weight:bold; font-size:14px; height:40px;">
        <td align="center">Puesto</b></td>
        <td align="center">Estudiante</td>
        <td align="center">Promedio</td>
    </tr> 
  <?php
	$j=1;
  	while($ptos = mysqli_fetch_array($puestos, MYSQLI_BOTH)){		
	?>	
    <tr style="font-weight:bold; font-size:12px;">
        <td align="center"><?=$j;?></td>
        <td><?=strtoupper($ptos[1]." ".$ptos[2]." ".$ptos[3]);?></td>
        <td align="center"><?=$ptos[0];?></td>
    </tr>
	<?php	
		$j++;
	}
  ?>
    
  </table>  
</div>

</body>
</html>


