<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once("../class/UsuariosPadre.php");
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
									$consulta = mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".finanzas_cuentas WHERE fcu_anulado=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                  $cont=0;
									while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
                    $u = UsuariosPadre::sesionUsuario($resultado['fcu_usuario']);
                    $cerrado = UsuariosPadre::sesionUsuario($resultado['fcu_cerrado_usuario']);
                    $nombreCompleto = UsuariosPadre::nombreCompletoDelUsuario($u);
									?>
  <tr style="font-size:13px;">
      <td><?=$resultado['fcu_id'];?></td>
                                        <td><?=$nombreCompleto;?></td>
                                        <td><?=$resultado['fcu_fecha'];?></td>
                                        <td><?=$resultado['fcu_detalle'];?></td>
                                        <td>$<?=number_format($resultado['fcu_valor'],2,",",".");?></td>
                                        <td><?=$tipoEstadoFinanzas[$resultado['fcu_tipo']];?></td>
                                        <td><?=$formasPagoFinanzas[$resultado['fcu_forma_pago']];?></td>
                                        
                                        <td><?=$resultado['fcu_observaciones'];?></td>
                                        <td><?=$resultado['fcu_cerrado'];?> <br> <?php if(isset($cerrado[4])) echo strtoupper($cerrado['uss_nombre']);?></td>
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


