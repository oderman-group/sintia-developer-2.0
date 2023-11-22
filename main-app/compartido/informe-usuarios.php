<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");?>
<head>
	<title>SINTIA | Usuarios</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../files/images/ico.png">
</head>
<body style="font-family:Arial;">
<div align="center" style="margin-bottom:20px;">
    <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" height="150" width="250"><br>
    <?=$informacion_inst["info_nombre"]?><br>
    INFORME DE USUARIOS</br>
</div>   
<?php
									if(isset($_GET["tipo"]) and $_GET["tipo"]!="" and is_numeric($_GET["tipo"])){
										$SQL = "SELECT * FROM ".BD_GENERAL.".usuarios uss INNER JOIN ".$baseDatosServicios.".general_perfiles ON uss_tipo=pes_id WHERE uss_id!='".$_SESSION["id"]."' AND uss_tipo='".$_GET["tipo"]."' AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]} ORDER BY uss_nombre";
									}else{
										$SQL = "SELECT * FROM ".BD_GENERAL.".usuarios uss INNER JOIN ".$baseDatosServicios.".general_perfiles ON uss_tipo=pes_id WHERE uss_id!='".$_SESSION["id"]."' AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]} ORDER BY uss_nombre, uss_tipo";
									}
									//include("paginacion.php");
									?>
  <table bgcolor="#FFFFFF" width="80%" cellspacing="5" cellpadding="5" rules="all" border="<?php echo $config[13] ?>" style="border:solid; border-color:<?php echo $config[11] ?>;" align="center">
  <tr style="font-weight:bold; font-size:12px; height:30px; background:<?php echo $config[12] ?>;">
	<th>ID</th>
    <th>Usuario</th>
    <th>Nombre</th>
    <th>Tipo</th>
                                        <th>Foto</th>
                                        <th>Email</th>
                                        <th>Celular</th>
                                        <th>Ingreso</th>
                                        <th>Salida</th>
                                        <th style="text-align:center;">Clics<br> %</th>
                                        <th>Clics Total</th>
  </tr>
  <?php
									 $consulta = mysqli_query($conexion, $SQL);
									 while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
										 if($resultado['uss_estado']==1) $s='<img src="../files/iconos/on.png">'; elseif($resultado['uss_estado']==0) $s='<img src="../files/iconos/off.png">'; else $s="-";
                     $consultaClics=mysqli_query($conexion, "SELECT ROUND(((SELECT count(hil_id) FROM ".$baseDatosServicios.".seguridad_historial_acciones where hil_usuario='".$resultado['uss_id']."')/(SELECT count(hil_id) FROM ".$baseDatosServicios.".seguridad_historial_acciones))*100,2)");
										 $clics = mysqli_fetch_array($consultaClics, MYSQLI_BOTH);
                     $consultaClics2=mysqli_query($conexion, "SELECT (SELECT count(hil_id) FROM ".$baseDatosServicios.".seguridad_historial_acciones where hil_usuario='".$resultado['uss_id']."'),(SELECT count(hil_id) FROM ".$baseDatosServicios.".seguridad_historial_acciones)");
										 $clics2 = mysqli_fetch_array($consultaClics2, MYSQLI_BOTH);
                     $consultaEntrada=mysqli_query($conexion, "SELECT (DATEDIFF(uss_ultimo_ingreso, now())*-1) FROM ".BD_GENERAL.".usuarios WHERE uss_id='".$resultado['uss_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
										 $entrada = mysqli_fetch_array($consultaEntrada, MYSQLI_BOTH);
										 if($entrada[0]==0 and $entrada[0]!="") $entradaTexto = "Hoy";
										 elseif($entrada[0]>0 and $entrada[0]<7) $entradaTexto = "Hace ".$entrada[0]." d&iacute;a(s)";
										 elseif($entrada[0]>=7 and $entrada[0]<31) $entradaTexto = "Hace ".round(($entrada[0]/7),0)." semana(s)";
										 elseif($entrada[0]>=31 and $entrada[0]<365) $entradaTexto = "Hace ".round(($entrada[0]/31),0)." mes(es)";
										 else $entradaTexto = $entrada[0];
										 
                     $consultaSalida=mysqli_query($conexion, "SELECT (DATEDIFF(uss_ultima_salida, now())*-1) FROM ".BD_GENERAL.".usuarios WHERE uss_id='".$resultado['uss_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
										 $salida = mysqli_fetch_array($consultaSalida, MYSQLI_BOTH);
										 if($salida[0]==0 and $salida[0]!="") $salidaTexto = "Hoy";
										 elseif($salida[0]>0 and $salida[0]<7) $salidaTexto = "Hace ".$salida[0]." d&iacute;a(s)";
										 elseif($salida[0]>=7 and $salida[0]<31) $salidaTexto = "Hace ".round(($salida[0]/7),0)." semana(s)";
										 elseif($salida[0]>=31 and $salida[0]<365) $salidaTexto = "Hace ".round(($salida[0]/31),0)." mes(es)";
										 else $salidaTexto ="";
										 
										 if($resultado['uss_tipo']==5) $b = "bold"; else $b="";
										 if($resultado['uss_bloqueado']==1) $c = "#F00"; else $c="";
				
									 ?>
  <tr style="font-size:13px;">
      <td><?=$resultado['uss_id'];?></td>
                                        <td><?=$resultado['uss_usuario'];?></td>
                                        <td><?=$resultado['uss_nombre'];?></td>
                                         <td><?=$resultado["pes_nombre"];?></td>
                                        <td><img src="../files/fotos/<?=$resultado[6];?>" alt="<?=$resultado['uss_nombre'];?>" height="50" width="50"></td>
                                        <td><?=$resultado['uss_email'];?></td>
                                        <td><?=$resultado['uss_celular'];?></td>
                                        <td><?php //echo $entradaTexto;?><?=$resultado['uss_ultimo_ingreso'];?></td>
                                        <td><?php //echo $salidaTexto;?><?=$resultado['uss_ultima_salida'];?></td>
                                        <td><?=$clics[0];?>%</td>
                                        <td><?=$clics2[0]." de ".$clics2[1];?></td> 
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


