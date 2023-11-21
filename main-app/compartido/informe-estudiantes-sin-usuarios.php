<?php
include("../directivo/session.php");
require_once("../class/Estudiantes.php");
require_once("../class/Usuarios.php");

$consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat 
LEFT JOIN usuarios ON uss_id=mat.mat_id_usuario 
INNER JOIN academico_grados ON gra_id=mat.mat_grado 
INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat.mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
WHERE mat.mat_eliminado=0 AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}
ORDER BY mat.mat_primer_apellido, mat.mat_segundo_apellido, mat.mat_nombres");

?>
<!doctype html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Informes SINTIA</title>
  <link rel="shortcut icon" href="../files/images/ico.png">
	<link href="../../config-general/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
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
        <td width="3%">Nº</td>
        <td width="3%">ID Matricula</td>
        <td width="3%">Documento</td>
        <td width="20%">Nombre</td>
        <td width="3%">Curso</td>
        <td width="3%">Estado Matricula</td>
      </tr>
      <?php
        $i = 1;
        while ($datos = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

          if($datos['uss_id']==NULL || $datos['uss_id']==""){
            $nombreCompleto=Estudiantes::NombreCompletoDelEstudiante($datos);
      ?>
        <tr>
          <td align="center"><?= $i; ?></td>
          <td><?= $datos['mat_id']; ?></td>
          <td><?= $datos['mat_documento']; ?></td>
          <td><?= $nombreCompleto; ?></td>
          <td><?= $datos['gra_nombre']." ".$datos['gru_nombre']; ?></td>
          <td><?= $estadosMatriculasEstudiantes[$datos['mat_estado_matricula']]; ?></td>
        </tr>
      <?php
          $i++;
          }
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