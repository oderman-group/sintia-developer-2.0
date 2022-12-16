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
	<title>Movimientos Financieros</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../files/images/ico.png">
</head>
<body style="font-family:Arial;">
<div align="center" style="margin-bottom:20px;">
    <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" height="150" width="250"><br>
    <?=$informacion_inst["info_nombre"]?><br>
    INFORME DE MOVIMIENTOS</br>
</div>   
  <table bgcolor="#FFFFFF" width="100%" cellspacing="5" cellpadding="5" rules="all" border="<?php echo $config[13] ?>" style="border:solid; border-color:<?php echo $config[11] ?>;" align="center">
  <tr style="font-weight:bold; font-size:12px; height:30px; background:<?php echo $config[12] ?>;">
        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Fecha</th>
                                        <th>Detalle</th>
                                        <th>Valor</th>
                                        <th>Tipo</th>
                                        <th>Forma de pago</th>
                                        <th>Observaciones</th>
                                        <th>Cerrado</th>
  </tr>
  <?php
									 $consulta = mysql_query("SELECT * FROM finanzas_cuentas WHERE fcu_anulado=0",$conexion);
									 while($resultado = mysql_fetch_array($consulta)){
										 $u = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE uss_id='".$resultado[6]."'",$conexion));
										 $cerrado = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE uss_id='".$resultado[11]."'",$conexion));
										 $nombreCompleto = strtoupper($u[4]);
										 switch($resultado[4]){case 1: $tipo = "Ingreso"; break; case 2: $tipo = "Egreso"; break; case 3: $tipo = "Cuenta por cobrar"; break; case 4: $tipo = "Cuenta por pagar"; break;}
										 switch($resultado[8]){case 1: $forma = "Efectivo"; break; case 2: $forma = "Cheque"; break; case 3: $forma = "T. D&eacute;bito"; break; case 4: $forma = "T. Cr&eacute;dito"; break; case 5: $forma = "N/A"; break; default: $forma = "N/A"; break;}
									 ?>
  <tr style="font-size:13px;">
      <td><?=$resultado[0];?></td>
                                        <td><?=$nombreCompleto;?></td>
                                        <td><?=$resultado[1];?></td>
                                        <td><?=$resultado[2];?></td>
                                        <td>$<?=number_format($resultado[3],2,",",".");?></td>
                                        <td><?=$tipo;?></td>
                                        <td><?=$forma;?></td>
                                        
                                        <td><?=$resultado[5];?></td>
                                        <td><?=$resultado[10];?> <br> <?=strtoupper($cerrado[4]);?></td>
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


