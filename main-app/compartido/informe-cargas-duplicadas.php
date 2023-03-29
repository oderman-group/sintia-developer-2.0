<?php
session_start();
$idPaginaInterna = 'DT0146';
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");?>
<?php include("../compartido/head.php");?>
<?php
$consulta = mysqli_query($conexion, "SELECT GROUP_CONCAT( car_id SEPARATOR ', ')as car_id, uss_nombre, gra_nombre, gru_nombre, mat_nombre, COUNT(*) as duplicados
FROM academico_cargas
INNER JOIN usuarios ON uss_id=car_docente
INNER JOIN academico_grados ON gra_id=car_curso
INNER JOIN academico_grupos ON gru_id=car_grupo
INNER JOIN academico_materias ON mat_id=car_materia
GROUP BY car_docente, car_curso, car_grupo, car_materia
HAVING COUNT(*) > 1 
ORDER BY car_id ASC");

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
    <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" height="100" width="200"><br>
    <?=$informacion_inst["info_nombre"]?><br>
    CARGAS REPETIDAS
    </br>
  </div>

  <div style="margin: 20px;">
    <table width="100%" border="1" rules="all" align="center" style="border-color:#6017dc;">
      <tr
        style="font-weight:bold; font-size:12px; height:30px; text-align: center; text-transform: uppercase; background:#6017dc; color:#FFF;">
        <td>No</td>
        <td>Docente</td>
        <td>Curso</td>
        <td>Grupo</td>
        <td>Asignatura</td>
        <td>Cantidad (ID Cargas)</td>
      </tr>
      <?php
			$i=1;
			while($datos = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
       
			?>
      <tr>
        <td align="center"><?=$i;?></td>
        <td><?=$datos['uss_nombre'];?></td>
        <td align="center"><?=$datos['gra_nombre'];?></td>
        <td align="center"><?=$datos['gru_nombre'];?></td>
        <td><?=$datos['mat_nombre'];?></td>
        <td> <?=$datos['duplicados'];?> (<?=$datos['car_id'];?>)</td>
      </tr>
      <?php	
				$i++;
			}
			?>
    </table>
  </div>
  <div style="font-size:10px; margin-top:10px; text-align:center;">
    <img src="https://main.plataformasintia.com/app-sintia/main-app/sintia-logo-2023.png" width="150"><br>
    PLATAFORMA EDUCATIVA SINTIA - <?=date("l, d-M-Y");?>
  </div>
</body>

</html>