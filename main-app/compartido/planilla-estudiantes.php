<?php
include("../directivo/session.php");
require_once("../class/Estudiantes.php");
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
	if(is_numeric($_REQUEST["grado"]) and is_numeric($_REQUEST["grupo"])){
    $consultaGrados=mysqli_query($conexion, "SELECT * FROM $BD.academico_grados, $BD.academico_grupos WHERE gra_id='".$_REQUEST["grado"]."' AND gru_id='".$_REQUEST["grupo"]."'");
		$grados = mysqli_fetch_array($consultaGrados, MYSQLI_BOTH);
	}elseif(is_numeric($_REQUEST["grado"])){
    $consultaGrados=mysqli_query($conexion, "SELECT * FROM $BD.academico_grados, $BD.academico_grupos WHERE gra_id='".$_REQUEST["grado"]."'");
		$grados = mysqli_fetch_array($consultaGrados, MYSQLI_BOTH);
	}else{
    $consultaGrados=mysqli_query($conexion, "SELECT * FROM $BD.academico_grados, $BD.academico_grupos");
		$grados = mysqli_fetch_array($consultaGrados, MYSQLI_BOTH);
	}
?>
<?php
$subNombre="";
 if(is_numeric($_REQUEST["grado"]) and is_numeric($_REQUEST["grupo"])){
$subNombre=$grados["gra_nombre"]." ".$grados["gru_nombre"]."<br>".$year;
}elseif(is_numeric($_REQUEST["grado"])) {
  $subNombre=$grados["gra_nombre"]."<br>".$year;
}
$nombreInforme =  "PLANILLA DE ESTUDIANTES ".$subNombre;
include("../compartido/head-informes.php") ?>

<table width="100%" cellspacing="5" cellpadding="5" rules="all" 
  style="
  border:solid; 
  border-color:#6017dc; 
  font-size:11px;
  ">
  <tr style="font-weight:bold; height:30px; background:#6017dc; color:#FFF;">
        <th rowspan="2">Documento</th>
        <th rowspan="2">Estudiante</th>
        <th rowspan="2">Grado</th>
        <th rowspan="2">Grupo</th>
        <th rowspan="2">Detalles</th>
  </tr>

  <tr style="font-weight:bold; font-size:12px; height:30px; background:<?php echo $config[12] ?>;">
  </tr>

  <?php
  if(is_numeric($_REQUEST["grado"]) and is_numeric($_REQUEST["grupo"])){
		$adicional = "AND mat_grado='".$_REQUEST["grado"]."' AND mat_grupo='".$_REQUEST["grupo"]."'";
  }elseif(is_numeric($_REQUEST["grado"])) {
		$adicional = "AND mat_grado='".$_REQUEST["grado"]."'";
	}else{
		$adicional = "";
	}
  $cont=1;
  $filtroAdicional= $adicional." AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
  $consulta =Estudiantes::listarEstudiantesParaPlanillas(0,$filtroAdicional,$BD);
  $numE=mysqli_num_rows($consulta);
  while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
    $nombre = Estudiantes::NombreCompletoDelEstudiante($resultado);
  ?>
  <tr style="
  border-color:#41c4c4;
  ">
      <td><?=$resultado[12];?></td>
      <td><?=$nombre?></td>
      <td><?=$resultado["gra_nombre"];?></td>
      <td><?=$resultado["gru_nombre"];?></td>
      <td>&nbsp;</td>
</tr>
  <?php
  $cont++;
  }//Fin mientras que
  ?>
  </table>
  Total Estudiantes:<?=$numE;?>
  <?php include("../compartido/footer-informes.php") ?>;
</body>
<script type="application/javascript">
print();
</script> 
</html>


