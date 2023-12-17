<?php 
include("session-compartida.php");
$idPaginaInterna = 'DT0228';

if($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && !Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once("../class/Estudiantes.php");
require_once("../class/UsuariosPadre.php");
require_once("../class/servicios/GradoServicios.php");
?>
<head>
	<title>LISTADO DE ESTUDIANTES</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="<?=$Plataforma->logo;?>">
</head>
<body style="font-family:Arial;">

<?php
$nombreInforme = "LISTADO ESTUDIANTES";
include("../compartido/head-informes.php") ?>

  <table width="100%" cellspacing="5" cellpadding="5" rules="all" 
  style="
  border:solid; 
  border-color:<?=$Plataforma->colorUno;?>; 
  font-size:11px;
  ">
  <tr style="font-weight:bold; height:30px; background:<?=$Plataforma->colorUno;?>; color:#FFF;">
        <th>NO.</th>      
        <th>ID Estudiante</th>
        <th>ID Usuario</th>
        <th>Estado</th>
        <th>Documento</th>
        <th>Estudiante</th>
        <th>Grado</th>
        <th>Grupo</th>
        <th>Género</th>
        <th>Telefono</th>
        <th>Celular</th>
        <th>Acudiente</th>
  </tr>
  <?php
  $filtro = "";
  if(!empty($_REQUEST["grado"])) { $filtro .= " AND mat_grado = '".$_REQUEST["grado"]."'";}
  $idGrupo="";
  if(!empty($_REQUEST["grupo"]) AND $_REQUEST["grupo"]!=""){ $filtro .= " AND mat_grupo='".$_REQUEST["grupo"]."'";$idGrupo=$_REQUEST["grupo"];}
  if(!empty($_REQUEST["estadoM"]) AND $_REQUEST["estadoM"]==1){ $filtro .= " AND mat_estado_matricula=1";}

  $cont=1;
  $cursoActual=GradoServicios::consultarCurso($_REQUEST["grado"]);
  $consulta =Estudiantes::listarEstudiantesEnGrados($filtro,"",$cursoActual,$idGrupo);
  while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
  $nombre = Estudiantes::NombreCompletoDelEstudiante($resultado);
	$acudiente = UsuariosPadre::sesionUsuario($resultado['mat_acudiente']);
  ?>
  <tr style="border-color:<?=$Plataforma->colorDos;?>;">
      <td style="text-align:center"><?=$cont;?></td>  
      <td style="text-align:center"><?=$resultado['mat_id'];?></td>
      <td style="text-align:center"><?=$resultado["uss_id"];?></td>
      <td style="text-align:center"><?=$estadosMatriculasEstudiantes[$resultado['mat_estado_matricula']];?></td>
      <td><?=$resultado['mat_documento'];?></td>
      <td><?=$nombre;?></td>
      <td><?=$resultado["gra_nombre"];?></td>
      <td style="text-align:center"><?=$resultado["gru_nombre"];?></td>
      <td><?=$resultado["ogen_nombre"];?></td>
      <td><?=$resultado["mat_telefono"];?></td>
      <td><?=$resultado["mat_celular"];?></td>
     <td><?=UsuariosPadre::nombreCompletoDelUsuario($acudiente);?></td> 
</tr>
  <?php
  $cont++;
  }//Fin mientras que
  ?>
  </table>
	<?php include("../compartido/footer-informes.php");
include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php"); ?>
	 

</body>
</html>


