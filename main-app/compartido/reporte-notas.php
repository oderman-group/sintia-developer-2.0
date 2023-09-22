<?php
include("../directivo/session.php");
require_once("../class/Estudiantes.php");
$carga='';
$grado='';
$grupo='';
$periodo='';
if(!empty($_GET['carga'])){
	$carga=base64_decode($_GET['carga']);
	$grado=base64_decode($_GET['grado']);
	$grupo=base64_decode($_GET['grupo']);
	$periodo=base64_decode($_GET['per']);
}
if(!empty($_POST['carga'])){
	$carga=$_POST['carga'];
	$grado=$_POST['grado'];
	$grupo=$_POST['grupo'];
	$periodo=$_POST['per'];
}
?>
<head>
	<title>Notas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../files/images/ico.png">
</head>
<body style="font-family:Arial;">
<?php
$nombreInforme = "INFORME DE NOTAS - PERIODO:";
include("../compartido/head-informes.php") ?>
  <table width="100%" border="1" style=" border:solid;border-color:<?=$Plataforma->colorUno;?>;" rules="all" align="center">    
			<tr style="font-weight:bold; height:30px; background:<?=$Plataforma->colorUno;?>; color:#FFF;">
        <th>Cod</th>
        <th>Des</th>
        <th>Fecha</th>
        <th>Valor</th>
        <th>Indicador</th>
        <th>Estado</th>
        <th>#ET/#EC.</th>
  </tr>
  <?php
  //ESTUDIANTES ACTUALES
  $filtroAdicional= "AND mat_grado='".$grado."' AND mat_grupo='".$grupo."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
  $consultaNumEstudiantes =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"");
	$numEstudiantes = mysqli_num_rows($consultaNumEstudiantes);
  $cont=1;
  $consulta = mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_carga='".$carga."' AND act_estado=1 AND act_periodo='".$periodo."'");
  while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
  $consultaInd=mysqli_query($conexion, "SELECT * FROM academico_indicadores WHERE ind_id='".$resultado[4]."'");
	$ind = mysqli_fetch_array($consultaInd, MYSQLI_BOTH);
	if($resultado[6]==1) $estado = "REGISTRADA"; else $estado = "PENDIENTE";
  $consultaNumCalificados=mysqli_query($conexion, "SELECT * FROM academico_calificaciones WHERE cal_id_actividad='".$resultado[0]."'");
	$numCalificados = mysqli_num_rows($consultaNumCalificados);
	if($numEstudiantes!=$numCalificados) $bg = '#FCC'; else $bg = '#FFF';
  ?>
  <tr style="text-transform: uppercase; border-color: <?=$Plataforma->colorDos;?>">
      <td align="center"><?=$resultado[0];?></td>
      <td><?=$resultado[1];?></td>
      <td align="center"><?=$resultado[2];?></td>
      <td align="center"><?=$resultado[3];?>%</td>
      <td><?=$ind[1];?></td>
      <td><?=$estado;?></td> 
      <td style="text-align:center; background:<?=$bg;?>"><?=$numEstudiantes;?>/<?=$numCalificados;?></td>  
</tr>
  <?php
  $cont++;
  }//Fin mientras que
  ?>
  </table>
  <?php include("../compartido/footer-informes.php") ?>;
</body>
</html>


