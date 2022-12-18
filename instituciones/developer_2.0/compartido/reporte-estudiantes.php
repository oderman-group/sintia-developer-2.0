<?php
session_start();
include("../../../config-general/config.php");
include("../../../config-general/consulta-usuario-actual.php");?>
<?php
/*
 $conf_reporte=mysql_query("SELECT * FROM configuracion WHERE conf_id=2",$conexion);
  $num_config_reporte=mysql_num_rows($conf_reporte);
		  if($num_config_reporte>0){
		  $config_reporte_actual=mysql_fetch_array($conf_reporte);
		  $nom_boton="Actualizar";
		  }
*/
?>
<head>
	<title>Estudiantes</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../files/images/ico.png">
</head>
<body style="font-family:Arial;">
<div align="center" style="margin-bottom:20px;">
    <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" height="150" width="250"><br>
    <?=$informacion_inst["info_nombre"]?><br>
    INFORME DE ESTUDIANTES</br>
</div>   
  <table bgcolor="#FFFFFF" width="80%" cellspacing="5" cellpadding="5" rules="all" border="<?php echo $config[13] ?>" style="border:solid; border-color:<?php echo $config[11] ?>;" align="center">
  <tr style="font-weight:bold; font-size:12px; height:30px; background:<?php echo $config[12] ?>;">
        <th>Matr&iacute;cula</th>
        <th>Documento</th>
        <th>Estudiante</th>
        <th>Grado</th>
        <th>Grupo</th>
        <th>Fecha de nacimiento</th>
        <th>Acudiente</th>
  </tr>
  <?php
  if(isset($_GET["grado"]) and isset($_GET["grupo"])) $adicional = "mat_grado='".$_GET["grado"]."' AND mat_grupo='".$_GET["grupo"]."' AND "; else $adicional = "";
  $cont=1;
  $consulta = mysql_query("SELECT * FROM academico_matriculas WHERE ".$adicional." (mat_estado_matricula=1 OR mat_estado_matricula=2) AND mat_eliminado=0 ORDER BY mat_primer_apellido",$conexion);
  while($resultado = mysql_fetch_array($consulta)){
	$acudiente = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE uss_id='".$resultado[26]."'",$conexion));
	$grados = mysql_fetch_array(mysql_query("SELECT * FROM academico_grados, academico_grupos WHERE gra_id='".$resultado[6]."' AND gru_id='".$resultado[7]."'",$conexion));	
  ?>
  <tr style="font-size:13px;">
      <td><?=$resultado[1];?></td>
      <td><?=$resultado[12];?></td>
      <td><?=strtoupper($resultado["mat_primer_apellido"].' '.$resultado["mat_segundo_apellido"].' '.$resultado["mat_nombres"].' '.$resultado["mat_nombre2"]);?></td>
      <td><?=$grados["gra_nombre"];?></td>
      <td><?=$grados["gru_nombre"];?></td>
      <td><?=$resultado[9];?></td>
     <td><?=strtoupper($acudiente[4].' '.$acudiente["uss_nombre2"].' '.$acudiente["uss_apellido1"].' '.$acudiente["uss_apellido2"]);?></td> 
</tr>
  <?php
  $cont++;
  }//Fin mientras que
  ?>
  </table>
  </center>
	<div align="center" style="font-size:10px; margin-top:10px;">
      <img src="../files/images/sintia.png" height="50" width="100"><br>
      SINTIA -  SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL - <?=date("l, d-M-Y");?>
     </div>
</body>
</html>


