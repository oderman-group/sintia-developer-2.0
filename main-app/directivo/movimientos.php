<?php include("session.php"); ?>
<?php $idPaginaInterna = 'DT0104'; ?>
<?php include("../compartido/historial-acciones-guardar.php"); ?>
<?php include("../compartido/head.php");
require_once(ROOT_PATH . "/main-app/class/Movimientos.php");
$queryString = $_SERVER['QUERY_STRING'];// Parsear la cadena de consulta y almacenar los parámetros en un array
parse_str($queryString, $parametros);// Convertir el array a JSON
$filtros_json = json_encode($parametros);
if (!Modulos::validarSubRol([$idPaginaInterna])) {
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
} ?>
<!-- data tables -->
<link href="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="../../config-general/assets/css/cargando.css" rel="stylesheet" type="text/css"/>

</head>

<!-- END HEAD -->
<?php include("../compartido/body.php"); ?>
<div class="page-wrapper">
	<?php include("../compartido/encabezado.php"); ?>

	<?php include("../compartido/panel-color.php"); ?>
	<!-- start page container -->
	<div class="page-container">
		<?php include("../compartido/menu.php"); ?>
		<!-- start page content -->
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="page-bar">
					<div class="page-title-breadcrumb">
						<div class=" pull-left">
							<div class="page-title"><?= $frases[95][$datosUsuarioActual['uss_idioma']]; ?></div>
							<?php include("../compartido/texto-manual-ayuda.php"); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-9">

						<?php include("../../config-general/mensajes-informativos.php"); ?>

						<?php include("includes/barra-superior-movimientos-financieros-componente.php");



						$consultaEstadisticas = mysqli_query($conexion, "SELECT
										(SELECT sum(fcu_valor) FROM " . BD_FINANCIERA . ".finanzas_cuentas WHERE fcu_tipo=1 AND fcu_anulado='0' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}),
										(SELECT sum(fcu_valor) FROM " . BD_FINANCIERA . ".finanzas_cuentas WHERE fcu_tipo=2 AND fcu_anulado='0' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}),
										(SELECT sum(fcu_valor) FROM " . BD_FINANCIERA . ".finanzas_cuentas WHERE fcu_tipo=3 AND fcu_anulado='0' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}),
										(SELECT sum(fcu_valor) FROM " . BD_FINANCIERA . ".finanzas_cuentas WHERE fcu_tipo=4 AND fcu_anulado='0' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]})");
						$estadisticasCuentas = mysqli_fetch_array($consultaEstadisticas, MYSQLI_BOTH);

						if ($estadisticasCuentas[2] > 0) {
							$porcentajeIngreso = round(($estadisticasCuentas[0] / $estadisticasCuentas[2]) * 100, 2);
						}

						if ($estadisticasCuentas[3] > 0) {
							$porcentajeEgreso = round(($estadisticasCuentas[1] / $estadisticasCuentas[3]) * 100, 2);
						}
						if (empty($estadisticasCuentas[0])) {
							$estadisticasCuentas[0] = 0;
						}

						?>

						<?php include("../compartido/publicidad-lateral.php"); ?>

						<div class="card card-topline-purple">
							<div class="card-head">
								<header><?= $frases[95][$datosUsuarioActual['uss_idioma']]; ?></header>
								<div class="tools">
									<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
									<a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
									<a class="t-close btn-color fa fa-times" href="javascript:;"></a>
								</div>
							</div>
							<div class="card-body">

								<div class="row" style="margin-bottom: 10px;">
									<div class="col-sm-12">
										<div class="btn-group">
											<?php if (Modulos::validarPermisoEdicion() &&  Modulos::validarSubRol(['DT0106'])) { ?>
												<a href="movimientos-agregar.php" id="addRow" class="btn deepPink-bgcolor">
													Agregar nuevo <i class="fa fa-plus"></i>
												</a>
											<?php } ?>
										</div>
									</div>
								</div>

								<table class="display" style="width:100%;" id="tablaItems">
									<div id="gifCarga" class="gif-carga">
										<img  alt="Cargando...">
									</div>
									<thead>
										<tr>
											<th>#</th>
											<th><?= $frases[49][$datosUsuarioActual['uss_idioma']]; ?></th>
											<th>Fecha</th>
											<th>Detalle</th>
											<th><?= $frases[107][$datosUsuarioActual['uss_idioma']]; ?></th>
											<th><?= $frases[417][$datosUsuarioActual['uss_idioma']]; ?></th>
											<th><?= $frases[418][$datosUsuarioActual['uss_idioma']]; ?></th>
											<th>Tipo</th>
											<th>Usuario</th>
											<th><?= $frases[246][$datosUsuarioActual['uss_idioma']]; ?></th>
											<?php if (Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0128', 'DT0089'])) { ?>
												<th><?= $frases[54][$datosUsuarioActual['uss_idioma']]; ?></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody id="movimientos_result">
										<?php
										include("includes/consulta-paginacion-movimientos.php");

										try {
											$consulta = mysqli_query($conexion, "SELECT fc.*, uss.*, fc.id_nuevo AS id_nuevo_movimientos FROM " . BD_FINANCIERA . ".finanzas_cuentas fc
														INNER JOIN " . BD_GENERAL . ".usuarios uss ON uss_id=fcu_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
														WHERE fcu_id=fcu_id AND fc.institucion={$config['conf_id_institucion']} AND fc.year={$_SESSION["bd"]} $filtro
														ORDER BY fcu_id
														LIMIT $inicio,$registros");
										} catch (Exception $e) {
											include("../compartido/error-catch-to-report.php");
										}
										$data =$barraSuperior->builderArray($consulta);
										include("../class/componentes/result/movimientos-tbody.php");
										
										?>
									</tbody>
									<script>
										$(document).ready(totalizarMovimientos);
									</script>
								</table>
							</div>
						</div>
						<?php include("enlaces-paginacion.php"); ?>
					</div>

					<div class="col-sm-3">
						<div class="panel">
							<header class="panel-heading panel-heading-blue">TOTAL FACT. VENTA</header>
							<div class="panel-body">
								<table style="width: 100%;" align="center">
									<tr>
										<td style="padding-right: 20px;">TOTAL FACTURAS:</td>
										<td align="left" id="totalNetoVenta">$0</td>
									</tr>
									<tr>
										<td style="padding-right: 20px;">TOTAL COBRADO:</td>
										<td align="left" id="abonosNetoVenta">$0</td>
									</tr>
									<tr style="font-size: 15px; font-weight:bold;">
										<td style="padding-right: 20px;">TOTAL POR COBRAR:</td>
										<td align="left" id="porCobrarNetoVenta">$0</td>
									</tr>
								</table>
							</div>
						</div>

						<div class="panel">
							<header class="panel-heading panel-heading-blue">TOTAL FACT. COMPRA</header>
							<div class="panel-body">
								<table style="width: 100%;" align="center">
									<tr>
										<td style="padding-right: 20px;">TOTAL FACTURAS:</td>
										<td align="left" id="totalNetoCompra">$0</td>
									</tr>
									<tr>
										<td style="padding-right: 20px;">TOTAL PAGADO:</td>
										<td align="left" id="abonosNetoCompra">$0</td>
									</tr>
									<tr style="font-size: 15px; font-weight:bold;">
										<td style="padding-right: 20px;">TOTAL POR PAGAR:</td>
										<td align="left" id="porCobrarNetoCompra">$0</td>
									</tr>
								</table>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function mostrarResultado(dato) {
		console.log(dato);
		$(document).ready(totalizarMovimientos);
	};
</script>
<!-- end page content -->
<?php // include("../compartido/panel-configuracion.php");
?>
</div>
<!-- end page container -->
<?php include("../compartido/footer.php"); ?>
</div>
<!-- start js include path -->
<script src="../../config-general/assets/plugins/jquery/jquery.min.js"></script>
<script src="../../config-general/assets/plugins/popper/popper.js"></script>
<script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js"></script>
<script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
<!-- bootstrap -->
<script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- data tables -->
<script src="../../config-general/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.js"></script>
<script src="../../config-general/assets/js/pages/table/table_data.js"></script>
<!-- Common js-->
<script src="../../config-general/assets/js/app.js"></script>
<script src="../../config-general/assets/js/layout.js"></script>
<script src="../../config-general/assets/js/theme-color.js"></script>
<!-- notifications -->
<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js"></script>
<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js"></script>
<!-- Material -->
<script src="../../config-general/assets/plugins/material/material.min.js"></script>
<!-- end js include path -->
</body>

</html>