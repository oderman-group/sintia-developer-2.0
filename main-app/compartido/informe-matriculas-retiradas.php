<?php
include("session-compartida.php");
$idPaginaInterna = 'DT0306';

if($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && !Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once("../class/Estudiantes.php");
require_once("../class/UsuariosPadre.php");
$year=$_SESSION['bd'];
if(!empty($_REQUEST['year'])){
	$year=$_REQUEST['year'];
}
$id = "";
if (!empty($_REQUEST["estudiante"])) {
    $id = $_REQUEST["estudiante"];
}

$datosEstudiante = Estudiantes::obtenerDatosEstudiante($id, $year);

?>
<head>
	<title>Notas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../files/images/ico.png">
</head>
<body style="font-family:Arial;">
<?php
$nombreInforme = "HISORIAL MATRICULA RETIRADA<br>".Estudiantes::NombreCompletoDelEstudiante($datosEstudiante)."<br>".$datosEstudiante['gra_nombre']." ".$datosEstudiante['gru_nombre'];
include("../compartido/head-informes.php") ?>
  <table width="100%" border="1" style=" border:solid;border-color:<?=$Plataforma->colorUno;?>;" rules="all" align="center">    
			<tr style="font-weight:bold; height:30px; background:<?=$Plataforma->colorUno;?>; color:#FFF;">
        <th>#</th>
        <th>Fecha</th>
        <th>Motivo</th>
        <th>Responsable</th>
  </tr>
  <?php
  $consultaHistorial = Estudiantes::listarDatosEstudiantesretirados($conexion, $config, $id, $year);
  $cont=1;
  while($resultado = mysqli_fetch_array($consultaHistorial, MYSQLI_BOTH)){
  ?>
  <tr>
      <td align="center"><?=$cont;?></td>
      <td align="center"><?=$resultado['matret_fecha'];?></td>
      <td><?=$resultado['matret_motivo'];?></td>
      <td><?=UsuariosPadre::nombreCompletoDelUsuario($resultado);?></td> 
</tr>
  <?php
  $cont++;
  }//Fin mientras que
  ?>
  </table>
  <?php include("../compartido/footer-informes.php");
include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
?>
</body>
</html>


