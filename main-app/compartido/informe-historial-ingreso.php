<?php
include("session-compartida.php");
$idPaginaInterna = 'DT0327';

if($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && !Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include("../compartido/head.php");

$id = !empty($_GET['id']) ? base64_decode($_GET['id']) : "";

mysqli_query($conexion, "SET lc_time_names = 'es_ES';");

$consulta = mysqli_query($conexion, "SELECT DATE_FORMAT(hil_fecha, '%W, %d-%b-%Y') AS fecha, TIME(hil_fecha) AS hora FROM ".BD_ADMIN.".seguridad_historial_acciones WHERE hil_usuario='".$id."' AND hil_titulo='GN0001' AND hil_institucion={$config['conf_id_institucion']} AND YEAR(hil_fecha)={$_SESSION['bd']} ORDER BY hil_id DESC");
$numDatos = mysqli_num_rows($consulta);

$datosUsuario = UsuariosPadre::sesionUsuario($id);
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
    <b>HISTORIAL DE INGRESO  DE <?=UsuariosPadre::nombreCompletoDelUsuario($datosUsuario)?></b>
    </br>
  </div>

  <div style="margin: 20px;">
    <table width="100%" border="1" rules="all" align="center" style="border-color:#6017dc;">
      <tr style="font-weight:bold; font-size:12px; height:30px; text-align: center; text-transform: uppercase; background:#6017dc; color:#FFF;">
        <td>Nº</td>
        <td>Fecha</td>
        <td>Hora</td>
      </tr>
      <?php
      if ($numDatos > 0) {
        $i = 1;
        while ($datos = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
      ?>
        <tr>
          <td align="center"><?= $i; ?></td>
          <td><?= $datos['fecha']; ?></td>
          <td><?= $datos['hora']; ?></td>
        </tr>
      <?php
          $i++;
        }
      } else {
      ?>
        <tr>
          <td colspan="3" align="center"><b>ESTE USUARIO NO TIENE REGISTROS DE INGRESOS</b></td>
        </tr>
      <?php
      }
      ?>
    </table>
  </div>
  <div style="font-size:10px; margin-top:10px; text-align:center;">
    <img src="https://main.plataformasintia.com/app-sintia/main-app/sintia-logo-2023.png" width="150"><br>
    PLATAFORMA EDUCATIVA SINTIA - <?= date("l, d-M-Y"); ?>
  </div>
</body>
<?php
include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
?>
</html>