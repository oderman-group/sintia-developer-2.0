<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");?>

<?php
$filtro = '';
if(is_numeric($_GET["docente"])){$filtro .=" AND car_docente='".$_GET["docente"]."'";}
if(is_numeric($_GET["grado"])){$filtro .=" AND car_curso='".$_GET["grado"]."'";}
if(is_numeric($_GET["asignatura"])){$filtro .=" AND car_materia='".$_GET["asignatura"]."'";}

$consulta = mysqli_query($conexion, "SELECT car_id, uss_nombre, gra_nombre, gru_nombre, mat_nombre, car_director_grupo, car_ih FROM academico_cargas
INNER JOIN usuarios ON uss_id=car_docente
INNER JOIN academico_grados ON gra_id=car_curso
INNER JOIN academico_grupos ON gru_id=car_grupo
INNER JOIN academico_materias ON mat_id=car_materia
WHERE car_id=car_id $filtro
GROUP BY car_id
ORDER BY car_docente");
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Informes SINTIA</title>
	<link rel="shortcut icon" href="<?=$Plataforma->logo;?>">
</head>

<body style="font-family:Arial; font-size: 10px;">
	
<?php
$nombre_informe = "CARGA GENERAL DE DOCENTES";
include("../compartido/head_informes.php") ?>
	
	<div style="margin: 10px;">
		<table width="100%" border="1" style=" border:solid;border-color:<?=$Plataforma->colorUno;?>;" rules="all" align="center">    
			<tr style="font-weight:bold; height:30px; background:<?=$Plataforma->colorUno;?>; color:#FFF;">
				<td>No</td>
				<td>#CARGA</td>
				<td>Docente</td>
				<td>Grado</td>
				<td>Grupo</td>
				<td>Asignatura</td>
				<td>D.G</td>
				<td>I.H</td>
			</tr> 
			<?php
			$i=1;
			while($datos = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
				$colorFondo = '';
				if($datos[5]==1) $colorFondo = '#4086f48a';
			?>
			<tr style="text-transform: uppercase; border-color: <?=$Plataforma->colorDos;?>">
				<td align="center"><?=$i;?></td>
				<td align="center"><?=$datos[0];?></td>
				<td><?=$datos[1];?></td>
				<td><?=$datos[2];?></td>
				<td  align="center"><?=$datos[3];?></td>
				<td><?=$datos[4];?></td>
				<td align="center"><?=$opcionSINO[$datos[5]];?></td>
				<td align="center"><?=$datos[6];?></td>
			</tr>
			<?php	
				$i++;
			}
			?>  
		</table>
	</div>
	<?php include("../compartido/footer_informes.php") ?>;

</div>	

</body>
</html>