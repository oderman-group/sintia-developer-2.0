<?php
include("session-compartida.php");
$idPaginaInterna = 'DT0235';

if ($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && !Modulos::validarSubRol([$idPaginaInterna])) {
	echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH . "/main-app/compartido/historial-acciones-guardar.php");
require_once("../class/Estudiantes.php");
require_once("../class/Boletin.php");
require_once("../class/servicios/GradoServicios.php");
require_once(ROOT_PATH . "/main-app/class/Boletin.php");
require_once(ROOT_PATH . "/main-app/class/Asignaturas.php");
require_once(ROOT_PATH . "/main-app/class/Grados.php");
require_once(ROOT_PATH . "/main-app/class/CargaAcademica.php");

$year = $_SESSION["bd"];
if (isset($_POST["year"])) {
	$year = $_POST["year"];
}

$grados = Grados::traerGradosGrupos($config, $_REQUEST["curso"], $_REQUEST["grupo"], $year);
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


	<table width="100%" cellspacing="5" cellpadding="5" rules="all" style="border:solid; border-color:#6017dc; font-size:11px;">
		<tr style="font-weight:bold; height:30px; background:#6017dc; color:#FFF;">
			<td align="center">No</b></td>
			<td align="center">ID</td>
			<td align="center">Estudiante</td>
			<?php
			$numero = 0;
			$materias1 = CargaAcademica::traerCargasMateriasPorCursoGrupo($config, $_REQUEST["curso"], $_REQUEST["grupo"], $year);
			while ($mat1 = mysqli_fetch_array($materias1, MYSQLI_BOTH)) {
			?>
				<td align="center"><?= $mat1['mat_siglas']; ?></td>
			<?php
				$numero++;
			}
			?>
			<td align="center" style="font-weight:bold;">PROM</td>
		</tr>
		<?php
		$cont = 1;
		$filtroAdicional= "AND mat_grado='".$_REQUEST["curso"]."' AND mat_grupo='".$_REQUEST["grupo"]."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
		$asig =Estudiantes::listarEstudiantesEnGrados($filtroAdicional, "", $grados, $_REQUEST["grupo"], $year);
		while ($fila = mysqli_fetch_array($asig, MYSQLI_BOTH)) {
			$nombre = Estudiantes::NombreCompletoDelEstudiante($fila);
			$def = '0.0';

		?>
			<tr style="border-color:#41c4c4;">
				<td align="center"> <?=$cont; ?></td>
				<td align="center"> <?=$fila['mat_id']; ?></td>
				<td><?= $nombre ?></td>

				<?php
				$suma = 0;
				$materias1 = CargaAcademica::traerCargasMateriasPorCursoGrupo($config, $_REQUEST["curso"], $_REQUEST["grupo"], $year);
				while ($mat1 = mysqli_fetch_array($materias1, MYSQLI_BOTH)) {

					$materias2 = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_boletin 
					WHERE bol_carga='". $mat1['car_id']. "' 
					AND bol_estudiante='". $fila['mat_id']. "' 
					AND year={$year} 
					AND institucion={$config['conf_id_institucion']} 
					AND bol_periodo='". $_REQUEST["per"]. "'
					");

					$materias2Data = mysqli_fetch_array($materias2, MYSQLI_BOTH);

					$defini = 0;
					if ($config['conf_reporte_sabanas_nota_indocador'] == '0') {
						if (!empty($materias2Data['bol_nota'])) {
							$defini = $materias2Data['bol_nota'];
						}
					} else {
						//CONSULTA QUE ME TRAE LOS INDICADORES DE CADA MATERIA POR PERIODO
						$consultaNotaMateriaIndicadoresxPeriodo = mysqli_query($conexion, "SELECT mat_nombre,mat_area,mat_id,ind_nombre,ipc_periodo,
						ROUND(SUM(cal_nota*(act_valor/100)) / SUM(act_valor/100),2) as nota, ind_id FROM ".BD_ACADEMICA.".academico_materias am
						INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$year}
						INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car.car_materia=am.mat_id AND car.institucion={$config['conf_id_institucion']} AND car.year={$year}
						INNER JOIN ".BD_ACADEMICA.".academico_indicadores_carga aic ON aic.ipc_carga=car.car_id AND aic.institucion={$config['conf_id_institucion']} AND aic.year={$year}
						INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON aic.ipc_indicador=ai.ind_id AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$year}
						INNER JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id_tipo=aic.ipc_indicador AND act_id_carga=car_id AND act_estado=1 AND act_registrada=1 AND aa.institucion={$config['conf_id_institucion']} AND aa.year={$year}
						INNER JOIN ".BD_ACADEMICA.".academico_calificaciones aac ON aac.cal_id_actividad=aa.act_id AND aac.institucion={$config['conf_id_institucion']} AND aac.year={$year}
						WHERE car_curso='".$_REQUEST["curso"]."'  and car_grupo='".$_REQUEST["grupo"]."' and mat_id='".$mat1['car_materia']."'  AND ipc_periodo='".$_REQUEST["per"]."' AND cal_id_estudiante='".$fila['mat_id']."' and act_periodo='".$_REQUEST["per"]."' AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}
						group by act_id_tipo, act_id_carga
						order by mat_id,ipc_periodo,ind_id;");

						$numIndicadoresPorPeriodo = mysqli_num_rows($consultaNotaMateriaIndicadoresxPeriodo);
						$sumaNotaEstudiante = 0;
						while ($datosIndicadores = mysqli_fetch_array($consultaNotaMateriaIndicadoresxPeriodo, MYSQLI_BOTH)) {
							if ($datosIndicadores["mat_id"] == $mat1['car_materia']) {
								$notaMateria = $datosIndicadores["nota"];
							}

							$sumaNotaEstudiante += $notaMateria;
						}

						$estudianteNota = 0;
						if ($numIndicadoresPorPeriodo != 0) {
							$estudianteNota = ($sumaNotaEstudiante / $numIndicadoresPorPeriodo);
						}
						$defini = round($estudianteNota, 2);

						$defini = Boletin::agregarDecimales($defini);
					}

					$notaFinal = $defini;
					if ($defini < $config[5]) $color = 'red';
					else $color = '#417BC4';
					$suma = ($suma + $defini);

					$notaFinalTotal = $notaFinal;
					$title = '';
					if ($config['conf_forma_mostrar_notas'] == CUALITATIVA) {
						$title = 'title="Nota Cuantitativa: ' . $notaFinal . '"';
						$notaFinalTotal = !empty($mat1['notip_nombre']) ? $mat1['notip_nombre'] : "";
					}
				?>
					<td align="center" style="color:<?= $color; ?>;" <?= $title; ?>><?= $notaFinalTotal; ?></td>
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
				if ($def < $config[5]) $color = 'red';
				else $color = '#417BC4';
				$notas1[$cont] = $def;
				$grupo1[$cont] = $nombre;

				$defTotal = $def;
				$title = '';
				if ($config['conf_forma_mostrar_notas'] == CUALITATIVA) {
					$title = 'title="Nota Cuantitativa: ' . $def . '"';
					$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $def, $year);
					$defTotal = !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
				}
				?>
				<td align="center" style="font-weight:bold; color:<?= $color; ?>;" <?= $title; ?>><?= $defTotal; ?></td>
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
		<tr style="font-weight:bold; height:30px; background:<?= $Plataforma->colorUno; ?>; color:#FFF;">
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
				if ($cambios == 4) {
					break;
				}

				$valTotal = $val;
				$title = '';
				if ($config['conf_forma_mostrar_notas'] == CUALITATIVA) {
					$title = 'title="Nota Cuantitativa: ' . $val . '"';
					$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $val, $year);
					$valTotal = !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
				}
		?>
				<tr style="border-color:#41c4c4; background-color:<?= $color; ?>">
					<td align="center"><?= $j; ?></td>
					<td><?= $grupo1[$key]; ?></td>
					<td align="center" <?= $title; ?>><?= $valTotal; ?></td>
					<td align="center"><?= $puesto; ?></td>
				</tr>
		<?php
				$j++;
			}
		}
		?>

	</table>


	<?php include("../compartido/footer-informes.php");
	include(ROOT_PATH . "/main-app/compartido/guardar-historial-acciones.php"); ?>
</body>

</html>