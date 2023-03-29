<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
?>
<?php
$year = $agnoBD;
if (isset($_POST["year"])) {
	$year = $_POST["year"];
}
$BD = $_SESSION["inst"] . "_" . $year;

$asig = mysqli_query($conexion, "SELECT * FROM $BD.academico_matriculas 
WHERE mat_grado='" . $_REQUEST["curso"] . "' 
AND mat_grupo='" . $_REQUEST["grupo"] . "' 
AND (mat_estado_matricula=1 OR mat_estado_matricula=2) 
AND mat_eliminado=0 
ORDER BY mat_primer_apellido");
$num_asg = mysqli_num_rows($asig);
$consultaGrados = mysqli_query($conexion, "SELECT * FROM $BD.academico_grados, academico_grupos 
WHERE gra_id='" . $_REQUEST["curso"] . "' 
AND gru_id='" . $_REQUEST["grupo"] . "'");
$grados = mysqli_fetch_array($consultaGrados, MYSQLI_BOTH);
?>

<head>
	<title>Sabanas</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" href="<?= $Plataforma->logo; ?>">
</head>

<body style="font-family:Arial;">
	<?php
	$nombreInforme = "INFORME DE SABANAS" . "<br>" . "PERIDODO " . $_REQUEST["per"] . "<br>" . $grados["gra_nombre"] . " " . $grados["gru_nombre"] . " " . $year;
	include("../compartido/head-informes.php") ?>


	<table width="100%" cellspacing="5" cellpadding="5" rules="all" style="
  border:solid; 
  border-color:<?= $Plataforma->colorUno; ?>; 
  font-size:11px;">
		<tr style="font-weight:bold; height:30px; background:<?= $Plataforma->colorUno; ?>; color:#FFF;">
			<td align="center">No</b></td>
			<td align="center">ID</td>
			<td align="center">Estudiante</td>
			<?php
			$materias1 = mysqli_query($conexion, "SELECT * FROM $BD.academico_cargas 
		WHERE car_curso=" . $_REQUEST["curso"] . " 
		AND car_grupo='" . $_REQUEST["grupo"] . "'");
			while ($mat1 = mysqli_fetch_array($materias1, MYSQLI_BOTH)) {
				$nombresMat = mysqli_query($conexion, "SELECT * FROM $BD.academico_materias 
			WHERE mat_id=" . $mat1[4]);
				$Mat = mysqli_fetch_array($nombresMat, MYSQLI_BOTH);
			?>
				<td align="center"><?= $Mat[3]; ?></td>
			<?php
			}
			?>
			<td align="center" style="font-weight:bold;">PROM</td>
		</tr>
		<?php
		$cont = 1;
		$mayor = 0;
		$nombreMayor = "";
		while ($fila = mysqli_fetch_array($asig, MYSQLI_BOTH)) {
			$cuentaest = mysqli_query($conexion, "SELECT * FROM $BD.academico_boletin 
		WHERE bol_estudiante=" . $fila[0] . " 
		AND bol_periodo=" . $_REQUEST["per"] . " 
		GROUP BY bol_carga");
			$numero = mysqli_num_rows($cuentaest);
			$def = '0.0';

		?>
			<tr style="border-color:<?= $Plataforma->colorDos; ?>;">
				<td align="center"> <?php echo $cont; ?></td>
				<td align="center"> <?php echo $fila['mat_id']; ?></td>
				<td><?= strtoupper($fila['mat_primer_apellido'] . " " . $fila['mat_segundo_apellido'] . " " . $fila['mat_nombres'] . " " . $fila['mat_nombre2']); ?></td>

				<?php
				$suma = 0;
				$materias1 = mysqli_query($conexion, "SELECT * FROM $BD.academico_cargas 
		WHERE car_curso=" . $_REQUEST["curso"] . " 
		AND car_grupo='" . $_REQUEST["grupo"] . "'");

				while ($mat1 = mysqli_fetch_array($materias1, MYSQLI_BOTH)) {
					$notas = mysqli_query($conexion, "SELECT * FROM $BD.academico_boletin 
			WHERE bol_estudiante=" . $fila[0] . " 
			AND bol_carga=" . $mat1[0] . " 
			AND bol_periodo=" . $_REQUEST["per"]);

					$nota = mysqli_fetch_array($notas, MYSQLI_BOTH);
					$defini = $nota[4];
					if ($defini < $config[5]) $color = 'red';
					else $color = '#417BC4';
					$suma = ($suma + $defini);
				?>
					<td align="center" style="color:<?= $color; ?>;"><?php echo $nota[4]; ?></td>
				<?php
				}
				if ($numero > 0) {
					$def = round(($suma / $numero), 2);
				}
				if ($def == 1)	$def = "1.0";
				if ($def == 2)	$def = "2.0";
				if ($def == 3)	$def = "3.0";
				if ($def == 4)	$def = "4.0";
				if ($def == 5)	$def = "5.0";
				if ($def < $cde[5]) $color = 'red';
				else $color = '#417BC4';
				$notas1[$cont] = $def;
				$grupo1[$cont] = strtoupper($fila[3] . " " . $fila[4] . " " . $fila[5]);
				?>
				<td align="center" style="font-weight:bold; color:<?= $color; ?>;"><?= $def; ?></td>
			</tr>
		<?php
			$cont++;
		} //Fin mientras que
		?>
	</table>

	<p>&nbsp;</p>
	<table width="100%" cellspacing="5" cellpadding="5" rules="all" style="
  border:solid; 
  border-color:<?= $Plataforma->colorUno; ?>; 
  font-size:11px;">
		<tr style="font-weight:bold; height:30px; background:<?=$Plataforma->colorUno;?>; color:#FFF;">
			<td colspan="4" align="center" style="color:#FFFFFF;">PRIMEROS PUESTOS</td>
		</tr>

		<tr style="font-weight:bold; font-size:14px; height:40px;">
			<td align="center">No</b></td>
			<td align="center">Estudiante</td>
			<td align="center">Promedio</td>
			<td align="center">Puesto</td>
		</tr>
		<?php
		$j = 1;
		$cambios = 0;
		$valor = 0;
		if (!empty($notas1)) {
			arsort($notas1);
			foreach ($notas1 as $key => $val) {
				if ($val != $valor) {
					$valor = $val;
					$cambios++;
				}
				if ($cambios == 1) {
					$color = '#CCFFCC';
					$puesto = 'Primero';
				}
				if ($cambios == 2) {
					$color = '#CCFFFF';
					$puesto = 'Segundo';
				}
				if ($cambios == 3) {
					$color = '#FFFFCC';
					$puesto = 'Tercero';
				}
				if ($cambios == 4)					
					break;
		?>
				<tr style="border-color:#41c4c4; background-color:<?= $color; ?>">
					<td align="center"><?= $j; ?></td>
					<td><?= $grupo1[$key]; ?></td>
					<td align="center"><?= $val; ?></td>
					<td align="center"><?= $puesto; ?></td>
				</tr>
		<?php
				$j++;
			}
		}
		?>

	</table>


	<?php include("../compartido/footer-informes.php") ?>;
</body>

</html>