<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
include("../class/Estudiantes.php");?>

<head>
	<title>Estudiantes</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" href="<?=$Plataforma->logo;?>">
</head>
<body style="font-family:Arial;">
<?php
$nombreInforme = "INFORME DE ESTUDIANTES";
include("../compartido/head-informes.php") ?> 
    <?php
  $condicionw="";
  $condicion="";
  if($_POST["cursosR"]!=""){
	  $condicion="gra_id=".$_POST["cursosR"];
	  }
	  
	 if($_POST["gruposR"]!=""){
	  if($condicion!=""){
	  $condicion=$condicion." AND gru_id=".$_POST["gruposR"];
	  }else{
		  $condicion=$condicion." gru_id=".$_POST["gruposR"];
		  }
	  } 
	  if($_POST["estadoR"]!=""){
	  if($condicion!=""){
	  $condicion=$condicion." AND mat_estado_matricula=".$_POST["estadoR"];
	  }else{
	  $condicion=$condicion." mat_estado_matricula=".$_POST["estadoR"];  
		  }
	  }  
	  if($_POST["tipoR"]!=""){
	  if($condicion!=""){
	  $condicion=$condicion." AND ogen_id=".$_POST["tipoR"];
	  }else{
	  $condicion=$condicion." ogen_id=".$_POST["tipoR"];  
		  }
	  }
	  
	   if($_POST["acudienteR"]!=""){
	  if($condicion!=""){
		  if($_POST["acudienteR"]==1){
			  $condicion=$condicion." AND mat_acudiente IS NOT NULL";
			  }else{
				  $condicion=$condicion." AND mat_acudiente IS NULL";
				  }
	 
	  }else{
	  if($_POST["acudienteR"]==1){
			  $condicion=$condicion." mat_acudiente IS NOT NULL";
			  }else{
				  $condicion=$condicion." mat_acudiente IS NULL";
				  }
		  }
	  }
	  
	    if($_POST["fotoR"]!=""){
	  if($condicion!=""){
	   if($_POST["fotoR"]==1){
			  $condicion=$condicion." AND mat_foto IS NOT NULL";
			  }else{
				  $condicion=$condicion." AND mat_foto IS NULL";
				  }
	  }else{
	  if($_POST["fotoR"]==1){
			  $condicion=$condicion." mat_foto IS NOT NULL";
			  }else{
				  $condicion=$condicion." mat_foto IS NULL";
				  }
		  }
	  }
	    if($_POST["inclu"]!=""){
	  if($condicion!=""){
	  $condicion=$condicion." AND mat_inclusion=".$_POST["inclu"];
	  }else{
	  $condicion=$condicion." mat_inclusion=".$_POST["inclu"];  
		  }
	  }
	    if($_POST["extra"]!=""){
	  if($condicion!=""){
	  $condicion=$condicion." AND mat_extranjero=".$_POST["extra"];
	  }else{
	  $condicion=$condicion." mat_extranjero=".$_POST["extra"];  
		  }
	  }
	    if($_POST["generoR"]!=""){
	  if($condicion!=""){
	  $condicion=$condicion." AND mat_genero=".$_POST["generoR"];
	  }else{
	  $condicion=$condicion." mat_genero=".$_POST["generoR"];  
		  }
	  }
	  
	    if($_POST["religionR"]!=""){
	  if($condicion!=""){
	  $condicion=$condicion." AND mat_religion=".$_POST["religionR"];
	  }else{
	  $condicion=$condicion." mat_religion=".$_POST["religionR"];  
		  }
	  }
	    if($_POST["estratoE"]!=""){
	  if($condicion!=""){
	  $condicion=$condicion." AND mat_estrato=".$_POST["estratoE"];
	  }else{
	  $condicion=$condicion." mat_estrato=".$_POST["estratoE"];  
		  }
	  }
	    if($_POST["tdocumentoR"]!=""){
	  if($condicion!=""){
	  $condicion=$condicion." AND mat_tipo_documento=".$_POST["tdocumentoR"];
	  }else{
	  $condicion=$condicion." mat_tipo_documento=".$_POST["tdocumentoR"];  
		  }
	  }
	  
	if($condicion!=""){
		$condicionw="WHERE ";
		}
		$c_matricEst=mysqli_query($conexion, "SELECT mat_matricula, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_inclusion, mat_extranjero, mat_documento, uss_usuario, uss_email, uss_celular, uss_telefono, gru_nombre, gra_nombre, og.ogen_nombre as Tipo_est, mat_id,
IF(mat_acudiente is null,'No',uss_nombre) as nom_acudiente,
IF(mat_foto is null,'No','Si') as foto, 
og2.ogen_nombre as genero, og3.ogen_nombre as religion, og4.ogen_nombre as estrato, og5.ogen_nombre as tipoDoc,
CASE mat_estado_matricula 
	WHEN 1 THEN 'Matriculado' 
	WHEN 2 THEN 'Asistente' 
	WHEN 3 THEN 'Cancelado' 
	WHEN 4 
	THEN 'No matriculado' 
END AS estado
FROM academico_matriculas am 
INNER JOIN academico_grupos ag ON am.mat_grupo=ag.gru_id
INNER JOIN academico_grados agr ON agr.gra_id=am.mat_grado
INNER JOIN $baseDatosServicios.opciones_generales og ON og.ogen_id=am.mat_tipo
INNER JOIN $baseDatosServicios.opciones_generales og2 ON og2.ogen_id=am.mat_genero
INNER JOIN $baseDatosServicios.opciones_generales og3 ON og3.ogen_id=am.mat_religion
INNER JOIN $baseDatosServicios.opciones_generales og4 ON og4.ogen_id=am.mat_estrato
INNER JOIN $baseDatosServicios.opciones_generales og5 ON og5.ogen_id=am.mat_tipo_documento
INNER JOIN usuarios u ON u.uss_id=am.mat_acudiente or am.mat_acudiente is null
".$condicionw.$condicion."
GROUP BY mat_id
ORDER BY mat_primer_apellido,mat_estado_matricula;");
 $numE=mysqli_num_rows($c_matricEst);
 ?>
 <div style="width:100%; margin-left:auto; margin-right:auto;">
 Total Estudiantes: <?=$numE;?>
 </div>
  <table width="100%" cellspacing="5" cellpadding="5" rules="all" 
  style="border:solid; border-color:<?=$Plataforma->colorUno;?>;font-size:11px;">
  <tr style="font-weight:bold; height:30px; background:<?=$Plataforma->colorUno;?>; color:#FFF;">
        <th>ID</th>
        <th>Nombre</th>
        <th>Curso</th>
        <th>Grupo</th>
        <th>Estado</th>
        <th>Tipo Estudainte</th>
        <th>Foto</th>
        <th>Genero</th>
        <th>Religion</th>
        <th>Inclusión</th>
        <th>Extranjero</th>
        <th>Estrato</th>
        <th>Tipo Documento</th>
        <th>Documento</th>
        <th>Acudiente</th>
        <th>Cedula Acudiente</th>
        <th>Celular</th>
        <th>Telefono</th>
        <th>Correo</th>
  </tr>
<?php
 while($resultado=mysqli_fetch_array($c_matricEst, MYSQLI_BOTH)){
	switch ($resultado["mat_inclusion"]) {
    	case 0:
        	$inclusion="No";
        break;
		case 1:
        	$inclusion="Si";
        break;
	}
	switch ($resultado["mat_extranjero"]) {
    	case 0:
        	$extran="No";
        break;
		case 1:
        	$extran="Si";
        break;
	}
	if ($resultado["mat_inclusion"]==1){
		$color="#00F";
	}else{
		$color="#000";
	}
  ?>
 <tr style="border-color:<?=$Plataforma->colorDos;?>;">
      <td><?=$resultado["mat_id"];?></td>
      <td><?=Estudiantes::NombreCompletoDelEstudiante($resultado);?></td>
      <td><?=$resultado["gra_nombre"];?></td>
      <td><?=$resultado["gru_nombre"];?></td>
      <td><?=$resultado["estado"];?></td>
     <td><?=$resultado["Tipo_est"];?></td>
     <td><?=$resultado["foto"];?></td>
     <td><?=$resultado["genero"];?></td>
     <td><?=$resultado["religion"];?></td>
     <td><?=$inclusion;?></td>
     <td><?=$extran;?></td>
     <td><?=$resultado["estrato"];?></td>
     <td><?=$resultado["tipoDoc"];?></td> 
     <td><?=$resultado["mat_documento"];?></td> 
     <td><?=$resultado["nom_acudiente"];?></td>
     <td><?=$resultado["uss_usuario"];?></td>
     <td><?=$resultado["uss_celular"];?></td>
     <td><?=$resultado["uss_telefono"];?></td>
     <td><?=$resultado["uss_email"];?></td>
</tr>
  <?php
   }//Fin mientras que
  ?>
  </table>

  <?php include("../compartido/footer-informes.php") ?>;
</body>
</html>


