<?php
include("../directivo/session.php");
include("../compartido/head.php");

$consulta = mysqli_query($conexion, "SELECT GROUP_CONCAT( uss_id SEPARATOR ', ') as uss_id, uss_usuario, pes_nombre, uss_apellido1, uss_apellido2, uss_nombre2, uss_nombre, COUNT(*) as duplicados FROM usuarios 
INNER JOIN ".$baseDatosServicios.".general_perfiles ON pes_id=uss_tipo
GROUP BY uss_usuario
HAVING COUNT(*) > 1 
ORDER BY uss_id ASC");

?>
<!doctype html>
<html>

<head></head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Informes SINTIA</title>
<link rel="shortcut icon" href="../files/images/ico.png">
</head>

<body style="font-family:Arial; font-size: 13px;">
  <div align="center" style="margin-bottom:20px; margin-top: 20px;">
    <img src="../files/images/logo/<?= $informacion_inst["info_logo"] ?>" width="200"><br>
    <div>&nbsp;</div>
    <?= $informacion_inst["info_nombre"] ?><br>
    <b>USUARIOS REPETIDOS</b>
    </br>
  </div>

  <div style="margin: 20px;">
    <table width="100%" border="1" rules="all" align="center" style="border-color:#6017dc;">
      <tr style="font-weight:bold; font-size:12px; height:30px; text-align: center; text-transform: uppercase; background:#6017dc; color:#FFF;">
        <td>Nº</td>
        <td>Usuario</td>
        <td>Nombre</td>
        <td>Tipo Usuario</td>
        <td>Cantidad (ID)</td>
      </tr>
      <?php
        $i = 1;
        while ($datos = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
          $nombreCompleto=UsuariosPadre::nombreCompletoDelUsuario($datos);
      ?>
        <tr>
          <td align="center"><?= $i; ?></td>
          <td><?= $datos['uss_usuario']; ?></td>
          <td><?= $nombreCompleto; ?></td>
          <td><?= $datos['pes_nombre']; ?></td>
          <td><?= $datos['duplicados']; ?> (<?= $datos['uss_id']; ?>)</td>
        </tr>
      <?php
          $i++;
        }
      ?>
    </table>
  </div>
  <div style="font-size:10px; margin-top:10px; text-align:center;">
    <img src="https://main.plataformasintia.com/app-sintia/main-app/sintia-logo-2023.png" width="150"><br>
    PLATAFORMA EDUCATIVA SINTIA - <?= date("l, d-M-Y"); ?>
  </div>
</body>

</html>