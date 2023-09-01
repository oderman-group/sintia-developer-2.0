<?php include("session.php"); ?>
<?php $idPaginaInterna = 'DT0081'; ?>
<?php include("../compartido/historial-acciones-guardar.php"); ?>
<?php include("../compartido/head.php"); ?>
<?php
require_once("../class/Estudiantes.php");
$year = $agnoBD;
?>

<?php
$consultaCurso = Grados::obtenerDatosGrados($_POST["curso"]);
$curso = mysqli_fetch_array($consultaCurso, MYSQLI_BOTH);

$consultaGrupo = Grupos::obtenerDatosGrupos($_POST["grupo"]);
$grupo = mysqli_fetch_array($consultaGrupo, MYSQLI_BOTH);

$disabledPermiso = "";
if (!Modulos::validarPermisoEdicion()) {
	$disabledPermiso = "disabled";
}
?>

<!-- data tables -->
<link href="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	function def(enviada) {
		var nota = enviada.value;
		var codEst = enviada.id;
		var carga = enviada.name;
		var per = enviada.alt;
		if (nota > <?= $config[4]; ?> || isNaN(nota) || nota < <?= $config[3]; ?>) {
			alert('Ingrese un valor numerico entre <?= $config[3]; ?> y <?= $config[4]; ?>');
			return false;
		}
		$('#resp').empty().hide().html("Esperando...").show(1);
		datos = "nota=" + (nota) +
			"&carga=" + (carga) +
			"&codEst=" + (codEst) +
			"&per=" + (per);
		$.ajax({
			type: "POST",
			url: "../compartido/ajax-periodos-registrar.php",
			data: datos,
			success: function(data) {
				$('#resp').empty().hide().html(data).show(1);
			}
		});

	}
</script>
<style>
	/* table {
  border-collapse: separate;
  border-spacing: 0;
  border-top: 1px solid grey;
} */

	/* td,
th {
  margin: 0;
  border: 1px solid grey;
  white-space: nowrap;
  border-top-width: 0px;
} */

	/* div {
  width: 500px;
  overflow-x: scroll;
  margin-left: 5em;
  overflow-y: visible;
  padding: 0;
} */
	/* 
.headcol {
  position: absolute;
  width: 5em;
  left: 0;
  top: auto;
  border-top-width: 1px;
  /*only relevant for first row*/
	/* margin-top: -1px; */
	/*compensate for top border*/
	/* }  */
	/* 
.headcol:before {
  content: 'Row ';
} */
	/* 
.long {
  background: yellow;
  letter-spacing: 1em;
} */
</style>
</head>

<body style="font-family:Arial;">



	<div class="row">

		<div class="col-md-8 col-lg-12">
			<div class="card card-topline-purple">
				<div class="card-head">
					<header>Consolidado Final</header>

				</div>
				<div class="card-body">
					<div class="alert alert-block alert-info">
						<h4 class="alert-heading">Información importante!</h4>
						<p>Digite la nota para cada estudiante en el periodo y materia correspondiente y pulse Enter o simplemente cambie de casilla paraQue los cambios se guarden automaticamente.</p>
						<p style="font-weight:bold;">Por favor despu&eacute;s de digitar una nota, espere un momento aQue el sistema le indiqueQue la nota se guard&oacute; y prosiga con la siguiente.</p>
					</div>
					<div class="alert alert-block alert-warning">
						<h4 class="alert-heading">Información importante!</h4>
						<p>La definitiva de cada materia se obtiene del promedio de los periodos. ParaQue esta definitiva pueda ser correcta debe estar la nota de todos los periodos registada.</p>
					</div>
					<span id="resp"></span>
					<input type="hidden" name="periodo" value="<?php if (isset($_POST["periodo"])) {
																	echo $_POST["periodo"];
																} ?>" id="periodo">

					<div class="row" style="margin-bottom: 10px;">
						<div class="col-sm-4">
							<div class="btn-group">
								<a href="../compartido/informe-consolidad-final.php?curso=<?= $_POST["curso"]; ?>&grupo=<?= $_POST["grupo"]; ?>" id="addRow" class="btn deepPink-bgcolor" target="_blank">
									Sacar Informe
								</a>
							</div>
						</div>
						<div class="col-sm-8">
							<b>Curso:</b> <?php if (isset($curso['gra_nombre'])) {
												echo $curso['gra_nombre'];
											} ?>&nbsp;&nbsp;&nbsp; <b>Grupo:</b> <?php if (isset($grupo['gru_nombre'])) {
																						echo $grupo['gru_nombre'];
																					} ?>
						</div>

					</div>

					<div class="table-scrollable">
						<?php try { ?>
							<table width="100%" cellspacing="5" cellpadding="5" rules="all" style="
 							 border:solid; 
 							 border-color:<?= $Plataforma->colorUno; ?>; 
 						 	font-size:11px;">
								<thead>
									<tr style="font-weight:bold; height:30px; background:<?= $Plataforma->colorUno; ?>; color:#FFF;">
										<th rowspan="2" class="header" scope="row" style="font-size:9px;">Doc</th>
										<th rowspan="2" class="header" scope="row" style="font-size:9px;">Estudiante</th>
										<?php
										$cargas = mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso='" . $_POST["curso"] . "' AND car_grupo='" . $_POST["grupo"] . "' AND car_activa=1");
										//SACAMOS EL NUMERO DE CARGAS O MATERIASQUE TIENE UN CURSO PARAQUE SIRVA DE DIVISOR EN LA DEFINITIVA POR ESTUDIANTE
										$numCargasPorCurso = mysqli_num_rows($cargas);
										while ($carga = mysqli_fetch_array($cargas, MYSQLI_BOTH)) {
											$consultaMateria = mysqli_query($conexion, "SELECT * FROM academico_materias WHERE mat_id='" . $carga[4] . "'");
											$materia = mysqli_fetch_array($consultaMateria, MYSQLI_BOTH);
										?>
											<th style="font-size:9px; text-align:center; border:groove;" colspan="<?= $config[19] + 1; ?>" width="5%"><?= $materia[2]; ?></th>
										<?php
										}
										?>
										<th rowspan="2" class="header" scope="col" style="text-align:center;" width="60px">PROM</th>
									</tr>

									<tr>
										<?php

										$cargas = mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso='" . $_POST["curso"] . "' AND car_grupo='" . $_POST["grupo"] . "' AND car_activa=1");

										while ($carga = mysqli_fetch_array($cargas)) {
											$p = 1;
											//PERIODOS DE CADA MATERIA
											while ($p <= $config[19]) {
												echo '<th style="text-align:center;">' . $p . '</th>';
												$p++;
											}
											//DEFINITIVA DE CADA MATERIA
											echo '<th style="text-align:center; background:#FFC">DEF</th>';
										}
										?>
									</tr>

								</thead>
								<!-- END -->
								<!-- BEGIN -->
								<tbody>
									<?php
									$filtro = " AND mat_grado='" . $_POST["curso"] . "' AND mat_grupo='" . $_POST["grupo"] . "'";
									$consulta = Estudiantes::listarEstudiantes(0, $filtro, 'limit 2');
									//PRIMER PUESTO
									$primerPuestoNota = 0;
									$primerPuestoNombre = '';
									$primerPuestoID = 0;
									//SEGUNDO PUESTO
									$segundoPuestoNota = 0;
									$segundoPuestoNombre = '';
									$segundoPuestoID = 0;
									while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
										$defPorEstudiante = 0;
									?>
										<tr style="border-color:<?= $Plataforma->colorDos; ?>;">
											<td style="font-size:9px;" scope="row" width="100px"><?= $resultado[12]; ?></td>
											<td style="font-size:9px;" scope="row"><?= Estudiantes::NombreCompletoDelEstudiante($resultado); ?></td>
											<?php
											$cargas = mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso='" . $_POST["curso"] . "' AND car_grupo='" . $_POST["grupo"] . "' AND car_activa=1");

											while ($carga = mysqli_fetch_array($cargas, MYSQLI_BOTH)) {

												$consultaMateria = mysqli_query($conexion, "SELECT * FROM academico_materias WHERE mat_id='" . $carga[4] . "'");

												$materia = mysqli_fetch_array($consultaMateria, MYSQLI_BOTH);
												$p = 1;
												$defPorMateria = 0;
												//PERIODOS DE CADA MATERIA
												while ($p <= $config[19]) {
													$consultaBoletin = mysqli_query($conexion, "SELECT * FROM academico_boletin WHERE bol_carga='" . $carga[0] . "' AND bol_estudiante='" . $resultado[0] . "' AND bol_periodo='" . $p . "'");
													$boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);
													if (isset($boletin[4]) and $boletin[4] < $config[5] and $boletin[4] != "") $color = $config[6];
													elseif (isset($boletin[4]) and $boletin[4] >= $config[5]) $color = $config[7];
													if (isset($boletin[4])) {
														$defPorMateria += $boletin[4];
													}
													if (isset($boletin[5]) and $boletin[5] == 1) $tipo = '<span style="color:blue; font-size:9px;">Normal</span>';
													elseif (isset($boletin[5]) and $boletin[5] == 2) $tipo = '<span style="color:red; font-size:9px;">Recuperaci&oacute;n Per.</span>';
													elseif (isset($boletin[5]) and $boletin[5] == 3) $tipo = '<span style="color:red; font-size:9px;">Recuperaci&oacute;n Ind.</span>';
													elseif (isset($boletin[5]) and $boletin[5] == 4) $tipo = '<span style="color:red; font-size:9px;">Directivo</span>';

													else $tipo = '';
													//DEFINITIVA DE CADA PERIODO

													$disabled = "";
													if ((isset($boletin[4]) and ($boletin[4] != "" or $carga['car_periodo'] <= $p)) and $config['conf_editar_definitivas_consolidado'] != true) {
														$disabled = "disabled";
													}
											?>
													<td style="text-align:center;">
														<input style="text-align:center; width:40px; color:<?= $color; ?>" value="<?php if (isset($boletin[4])) {
																																		echo $boletin[4];
																																	} ?>" name="<?= $carga[0]; ?>" id="<?= $resultado[0]; ?>" onChange="def(this)" alt="<?= $p; ?>" title="Materia: <?= $materia[2]; ?> - Periodo: <?= $p; ?>" <?= $disabled; ?> <?= $disabledPermiso; ?>><br><?= $tipo; ?>
													</td>
												<?php
													$p++;
												}
												$defPorMateria = round($defPorMateria / $config[19], 2);
												//DEFINITIVA DE CADA MATERIA
												if ($defPorMateria < $config[5] and $defPorMateria != "") $color = $config[6];
												elseif ($defPorMateria >= $config[5]) $color = $config[7];
												//CONSULTAR NIVELACIONES
												$consultaNiv = mysqli_query($conexion, "SELECT * FROM academico_nivelaciones WHERE niv_cod_estudiante='" . $resultado[0] . "' AND niv_id_asg='" . $carga[0] . "'");

												$cNiv = mysqli_fetch_array($consultaNiv, MYSQLI_BOTH);
												if (isset($cNiv[3]) and $cNiv[3] > $defPorMateria) {
													$defPorMateria = $cNiv[3];
													$msj = 'Nivelación';
												} else {
													$defPorMateria = $defPorMateria;
													$msj = '';
												}
												?>
												<td style="text-align:center; background:#FFC;"><input style="text-align:center; width:40px; font-weight:bold; color:<?= $color; ?>" value="<?php if (isset($defPorMateria)) {
																																																echo $defPorMateria;
																																															} ?>" disabled><br><span style="font-size:10px; color:rgb(255,0,0); font-weight:bold;"><?php if (isset($msj)) {
																																																																					echo $msj;
																																																																				} ?><br><?php if (isset($cNiv[5]) and isset($cNiv[6])) {
																																																																						echo "Acta " . $cNiv[5] . " de " . $cNiv[6];
																																																																					} ?></span></td>
											<?php
												//DEFINITIVA POR CADA ESTUDIANTE DE TODAS LAS MATERIAS Y PERIODOS
												$defPorEstudiante += $defPorMateria;
											}
											$defPorEstudiante = round($defPorEstudiante / $numCargasPorCurso, 2);
											if ($defPorEstudiante < $config[5] and $defPorEstudiante != "") $color = $config[6];
											elseif ($defPorEstudiante >= $config[5]) $color = $config[7];
											?>
											<td style="text-align:center; font-weight:bold; color:<?= $color; ?>"><?= $defPorEstudiante; ?></td>
										</tr>
									<?php
									}
									?>

								</tbody>
							</table>

						<?php
						} catch (Exception $e) {
							include("../compartido/error-catch-to-report.php");
						}
						?>
					</div>
				</div>
			</div>
		</div>






	</div>
</body>

</html>