<?php
include("../directivo/session.php");
require_once("../class/Estudiantes.php");
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
  $_GET["carga"]=is_null($_POST["carga"])? $_GET["carga"]: $_POST["carga"];
  $_GET["grado"]=is_null($_POST["grado"])? $_GET["grado"]: $_POST["grado"];
  $_GET["grupo"]=is_null($_POST["grupo"])? $_GET["grupo"]: $_POST["grupo"];
  $_GET["per"]=is_null($_POST["periodo"])? $_GET["per"]: $_POST["periodo"];
  $filtroAdicional= "AND mat_grado='".$_GET["grado"]."' AND mat_grupo='".$_GET["grupo"]."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
  $consultaNumEstudiantes =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"");
	$numEstudiantes = mysqli_num_rows($consultaNumEstudiantes);
  $cont=1;
  $consulta = mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_carga='".$_GET["carga"]."' AND act_estado=1 AND act_periodo='".$_GET["per"]."'");
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


