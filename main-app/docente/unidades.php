<?php
include("session.php");
$idPaginaInterna = 'DC0093';
include("../compartido/historial-acciones-guardar.php");
include("verificar-carga.php");
include("../compartido/head.php");
?>
<!-- data tables -->
<link href="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
</head>
<!-- END HEAD -->
<?php include("../compartido/body.php"); ?>
<div class="page-wrapper">
	<?php
		include("../compartido/encabezado.php");
		include("../compartido/panel-color.php");
	?>
	<!-- start page container -->
	<div class="page-container">
		<?php include("../compartido/menu.php"); ?>
		<!-- start page content -->
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="page-bar">
					<div class="page-title-breadcrumb">
						<div class=" pull-left">
							<div class="page-title"><?= $frases[374][$datosUsuarioActual['uss_idioma']]; ?></div>
							<?php include("../compartido/texto-manual-ayuda.php"); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php include("../../config-general/mensajes-informativos.php"); ?>
						<div class="row">
							<div class="col-md-4 col-lg-3">
								<?php if ($periodoConsultaActual != $datosCargaActual['car_periodo'] and $datosCargaActual['car_permiso2'] != 1) { ?>
									<p style="color: tomato;"> Podrás consultar la información de otros periodos diferentes al actual, pero no se podrán hacer modificaciones. </p>
								<?php 
									} 
									include("info-carga-actual.php");
									include("filtros-cargas.php");
									include("../compartido/publicidad-lateral.php");
								?>
							</div>

							<div class="col-md-8 col-lg-9">
								<div class="card card-topline-purple" id="idElemento">
									<div class="card-head">
										<header><?= $frases[374][$datosUsuarioActual['uss_idioma']]; ?></header>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6 col-sm-6 col-6">
												<div class="btn-group" id="agregarNuevo">
													<a href="unidades-agregar.php?carga=<?= base64_encode($cargaConsultaActual); ?>&periodo=<?= base64_encode($periodoConsultaActual); ?>" id="addRow" class="btn deepPink-bgcolor">
														Agregar nuevo<i class="fa fa-plus"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle" id="example4">
												<thead>
													<tr>
														<th>#</th>
														<th><?= $frases[49][$datosUsuarioActual[8]]; ?></th>
														<th><?= $frases[187][$datosUsuarioActual[8]]; ?></th>
														<th><?= $frases[54][$datosUsuarioActual[8]]; ?></th>
													</tr>
												</thead>
												<tbody>
													<?php
														$consulta = mysqli_query($conexion, "SELECT * FROM academico_unidades 
														WHERE uni_id_carga='" . $cargaConsultaActual . "' AND uni_periodo='" . $periodoConsultaActual . "' AND uni_eliminado!=1");
														$contReg = 1;
														while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

															$consultaClases = mysqli_query($conexion, "SELECT cls_id FROM academico_clases 
															WHERE cls_id_carga='" . $cargaConsultaActual . "' AND cls_periodo='" . $periodoConsultaActual . "' AND cls_unidad='" . $resultado['uni_id'] . "'");
															$numClases=mysqli_num_rows($consultaClases);
													?>
														<tr id="reg<?= $resultado['uni_id']; ?>">
															<td><?= $contReg; ?></td>
															<td><?= $resultado['uni_id']; ?></td>
															<td><?= $resultado['uni_nombre']; ?></td>
															<td>
																<?php
																	$arrayEnviar = array("tipo" => 1, "descripcionTipo" => "Para ocultar fila del registro.");
																	$arrayDatos = json_encode($arrayEnviar);
																	$objetoEnviar = htmlentities($arrayDatos);
																?>
																<div class="btn-group">
																	<button class="btn btn-xs btn-info dropdown-toggle center no-margin" type="button" data-toggle="dropdown" aria-expanded="false"> Acciones
																		<i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu pull-left" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">
																		<li><a href="unidades-editar.php?idR=<?= base64_encode($resultado['uni_id']); ?>">Editar</a></li>
																		<?php if($numClases<1){?>
																			<li><a href="#" title="<?= $objetoEnviar; ?>" id="<?= $resultado['uni_id']; ?>" name="unidades-eliminar.php?idR=<?= base64_encode($resultado['uni_id']); ?>&carga=<?= base64_encode($cargaConsultaActual); ?>&periodo=<?= base64_encode($periodoConsultaActual); ?>" onClick="deseaEliminar(this)">Eliminar</a></li>
																		<?php }?>
																	</ul>
																</div>
															</td>
														</tr>
													<?php
															$contReg++;
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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