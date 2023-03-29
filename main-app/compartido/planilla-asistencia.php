<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");?>
<?php
include("../class/Estudiantes.php");
?>
<head>
	<title>PLANILLA DE ESTUDIANTES</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="<?=$Plataforma->logo;?>">
</head>
<body style="font-family:Arial;">
<?php
  $year=$agnoBD;
  $BD=$_SESSION["inst"]."_".$agnoBD;
  if(isset($_POST["agno"])){
$year=$_POST["agno"];
$BD=$_SESSION["inst"]."_".$_POST["agno"];
}

$consultaGrados=mysqli_query($conexion, "SELECT * FROM $BD.academico_grados, $BD.academico_grupos WHERE gra_id='".$_REQUEST["grado"]."' AND gru_id='".$_REQUEST["grupo"]."'");
$grados = mysqli_fetch_array($consultaGrados, MYSQLI_BOTH);	
?>
<?php
$nombreInforme = "PLANILLA DE ESTUDIANTES"."<br>".$grados["gra_nombre"]." ".$grados["gru_nombre"]."<br>".$year;
include("../compartido/head-informes.php") ?>
 
<table width="100%" cellspacing="5" cellpadding="5" rules="all" 
  style="
  border:solid; 
  border-color:#6017dc; 
  font-size:11px;
  ">
  <tr style="font-weight:bold; height:30px; background:#6017dc; color:#FFF;">
  <th rowspan="2">ID</th>      
  <th rowspan="2">Documento</th>
        <th rowspan="2">Estudiante</th>
        <th colspan="20">NOTAS</th>
  </tr>
  <tr style="font-weight:bold; font-size:12px; height:30px; background:#6017dc;">
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
  </tr>
  <?php
  if(isset($_REQUEST["grado"]) and isset($_REQUEST["grupo"])) $adicional = " AND mat_grado='".$_REQUEST["grado"]."' AND mat_grupo='".$_REQUEST["grupo"]."'"; else $adicional = "";
  $cont=1;
  $consulta = Estudiantes::listarEstudiantesParaPlanillas(0, $adicional, $BD);
  while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
  ?>
  <tr style="
  border-color:#41c4c4;
  ">
      <td><?=$resultado['mat_id'];?></td>
      <td><?=$resultado[12];?></td>
      <td><?=Estudiantes::NombreCompletoDelEstudiante($resultado);?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
</tr>
  <?php
  $cont++;
  }//Fin mientras que
  ?>
  </table>
  <?php include("../compartido/footer-informes.php") ?>;
</body>
</html>


