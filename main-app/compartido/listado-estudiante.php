<?php 
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
include("../class/Estudiantes.php");
?>
<head>
	<title>LISTADO DE ESTUDIANTES</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="<?=$Plataforma->logo;?>">
</head>
<body style="font-family:Arial;">

<?php
$nombre_informe = "LISTADO ESTUDIANTES";
include("../compartido/head_informes.php") ?>

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
  if(isset($_POST["grado"])) { $filtro = " AND mat_grado = '".$_POST["grado"]."'";}
  if(isset($_GET["grado"]))  { $filtro = " AND mat_grado = '".$_GET["grado"]."'";}
  if(isset($_POST["grupo"]) AND $_POST["grupo"]!=""){ $filtro .= " AND mat_grupo='".$_POST["grupo"]."'";}
  $cont=1;
  $consulta = Estudiantes::listarEstudiantes(0, $filtro, '');
  while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
  $consultaAcudiente=mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$resultado[26]."'");
	$acudiente = mysqli_fetch_array($consultaAcudiente, MYSQLI_BOTH);
  ?>
  <tr style="border-color:<?=$Plataforma->colorDos;?>;">
      <td style="text-align:center"><?=$cont;?></td>  
      <td style="text-align:center"><?=$resultado['mat_id'];?></td>
      <td style="text-align:center"><?=$resultado["uss_id"];?></td>
      <td style="text-align:center"><?=$estadosMatriculasEstudiantes[$resultado['mat_estado_matricula']];?></td>
      <td><?=$resultado['mat_documento'];?></td>
      <td><?=strtoupper($resultado[3]." ".$resultado[4]." ".$resultado[5]);?></td>
      <td><?=$resultado["gra_nombre"];?></td>
      <td style="text-align:center"><?=$resultado["gru_nombre"];?></td>
      <td><?=$resultado["ogen_nombre"];?></td>
      <td><?=$resultado["mat_telefono"];?></td>
      <td><?=$resultado["mat_celular"];?></td>
     <td><?=strtoupper($acudiente['uss_nombre']." ".$acudiente['uss_nombre2']." ".$acudiente['uss_apellido1']." ".$acudiente['uss_apellido2']);?></td> 
</tr>
  <?php
  $cont++;
  }//Fin mientras que
  ?>
  </table>
	<?php include("../compartido/footer_informes.php") ?>;
	 

</body>
</html>


