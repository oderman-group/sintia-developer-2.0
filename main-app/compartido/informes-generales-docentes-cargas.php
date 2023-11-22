<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");?>

<?php
$filtro = '';
if(!empty($_GET["docente"])){$filtro .=" AND car_docente='".$_GET["docente"]."'";}
if(!empty($_GET["grado"])){$filtro .=" AND car_curso='".$_GET["grado"]."'";}
if(!empty($_GET["asignatura"])){$filtro .=" AND car_materia='".$_GET["asignatura"]."'";}

$consulta = mysqli_query($conexion, "SELECT car_id, uss_nombre, gra_nombre, gru_nombre, mat_nombre, car_director_grupo, car_ih FROM academico_cargas
INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=car_docente AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
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
$nombreInforme = "CARGA GENERAL DE DOCENTES";
include("../compartido/head-informes.php") ?>
	
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
	<?php include("../compartido/footer-informes.php") ?>;

</div>	

</body>
</html>