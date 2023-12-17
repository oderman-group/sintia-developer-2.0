<?php
include("session.php");
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=notas".$_GET["idR"].".xls");
include("../modelo/conexion.php");
?>
<?php include("verificar-carga.php");?>
<?php
require_once("../class/Estudiantes.php");
?>
<head>
	<title>Planilla de notas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<?php
$consulta = Estudiantes::listarEstudiantesParaDocentes($filtroDocentesParaListarEstudiantes);
?>



<div align="center">  
<table  width="100%" border="1" rules="all">
    <thead
    	<tr>
            <th>#</th>
			<th>ID</th>
			<th>Estudiante</th>
			<th>Nota</th>
			<th>Observaciones</th>
        </tr>
    </thead>
    <tbody>
<?php 
$contReg = 1;
$colorNota = "black";
while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
	if($calificacion['act_registrada']==1){
		//Consulta de calificaciones si ya la tienen puestas.
		$consultaNotas=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_calificaciones WHERE cal_id_estudiante='".$resultado['mat_id']."' AND cal_id_actividad='".$_GET["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		$notas = mysqli_fetch_array($consultaNotas, MYSQLI_BOTH);
		if($notas['cal_nota']<$config[5] and $notas['cal_nota']!="") $colorNota = $config[6]; elseif($notas['cal_nota']>=$config[5]) $colorNota = $config[7];
	}	
?>    
    	<tr>	
            <td><?=$contReg;?></td>
			<td><?=$resultado['mat_id'];?></td>
			<td><?=Estudiantes::NombreCompletoDelEstudiante($resultado);?></td>
			<td style="text-align: center; color:<?=$colorNota;?>"><?=$notas['cal_nota'];?></td>
			<td><?=$notas['cal_observaciones'];?></td>
        </tr>   

<?php
	$contReg++;
}
?>        
    </tbody>
</table>