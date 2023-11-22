<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0104';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}?>
	<!-- data tables -->
    <link href="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
</head>
<!-- END HEAD -->
<?php include("../compartido/body.php");?>
    <div class="page-wrapper">
        <?php include("../compartido/encabezado.php");?>
		
        <?php include("../compartido/panel-color.php");?>
        <!-- start page container -->
        <div class="page-container">
 			<?php include("../compartido/menu.php");?>
			<!-- start page content -->
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class=" pull-left">
                                <div class="page-title"><?=$frases[95][$datosUsuarioActual['uss_idioma']];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                        <?php include("../../config-general/mensajes-informativos.php"); ?>
								
									<?php
										include("includes/barra-superior-movimientos-financieros.php");

										$consultaEstadisticas=mysqli_query($conexion, "SELECT
										(SELECT sum(fcu_valor) FROM ".BD_FINANCIERA.".finanzas_cuentas WHERE fcu_tipo=1 AND fcu_anulado='0' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}),
										(SELECT sum(fcu_valor) FROM ".BD_FINANCIERA.".finanzas_cuentas WHERE fcu_tipo=2 AND fcu_anulado='0' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}),
										(SELECT sum(fcu_valor) FROM ".BD_FINANCIERA.".finanzas_cuentas WHERE fcu_tipo=3 AND fcu_anulado='0' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}),
										(SELECT sum(fcu_valor) FROM ".BD_FINANCIERA.".finanzas_cuentas WHERE fcu_tipo=4 AND fcu_anulado='0' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]})");
										$estadisticasCuentas = mysqli_fetch_array($consultaEstadisticas, MYSQLI_BOTH);

										if($estadisticasCuentas[2]>0){
											$porcentajeIngreso = round(($estadisticasCuentas[0]/$estadisticasCuentas[2])*100,2);
										}	

										if($estadisticasCuentas[3]>0){
											$porcentajeEgreso = round(($estadisticasCuentas[1]/$estadisticasCuentas[3])*100,2);
										}
										if(empty($estadisticasCuentas[0])){ $estadisticasCuentas[0]=0; }

										?>

									<?php include("../compartido/publicidad-lateral.php");?>
								
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header><?=$frases[95][$datosUsuarioActual['uss_idioma']];?></header>
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
														<?php if(Modulos::validarPermisoEdicion() &&  Modulos::validarSubRol(['DT0106']) ) {?>
															<a href="movimientos-agregar.php" id="addRow" class="btn deepPink-bgcolor">
																Agregar nuevo <i class="fa fa-plus"></i>
															</a>
														<?php }?>
													</div>
												</div>
											</div>
											
                                        <div class="table-scrollable">
                                    		<table class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
														<th><?=$frases[49][$datosUsuarioActual['uss_idioma']];?></th>
														<th>Fecha</th>
														<th>Detalle</th>
														<th>Valor</th>
														<th>Tipo</th>
														<th>Usuario</th>
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0128', 'DT0089'])){?>
															<th><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></th>
														<?php }?>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													include("includes/consulta-paginacion-movimientos.php");
													
													try{
														$consulta = mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".finanzas_cuentas
														INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=fcu_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
														WHERE fcu_id=fcu_id AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} $filtro
														ORDER BY fcu_id
														LIMIT $inicio,$registros");
													} catch (Exception $e) {
														include("../compartido/error-catch-to-report.php");
													}
													 $contReg = 1;
													$estadosCuentas = array("","Ingreso","Egreso","Cobro (CPC)","Deuda (CPP)");
													 while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
														 $bgColor = '';
														 if($resultado['fcu_anulado']==1) $bgColor = '#ff572238';
													 ?>
													<tr style="background-color:<?=$bgColor;?>;">
                                                        <td><?=$contReg;?></td>
														<td><?=$resultado['fcu_id'];?></td>
														<td>
															<a href="<?=$_SERVER['PHP_SELF'];?>?usuario=<?=base64_encode($usuario)?>&tipo=<?=base64_encode($tipo);?>&fecha=<?=base64_encode($resultado['fcu_fecha']);?>" style="text-decoration: underline;"><?=$resultado['fcu_fecha'];?></a>
														</td>
														<td><?=$resultado['fcu_detalle'];?></td>
														<td>$<?php if(!empty($resultado['fcu_valor']) && is_numeric($resultado['fcu_valor'])) echo number_format($resultado['fcu_valor'],0,",",".");?></td>
														<td>
															<a href="<?=$_SERVER['PHP_SELF'];?>?usuario=<?=base64_encode($usuario);?>&tipo=<?=base64_encode($resultado['fcu_tipo']);?>&fecha=<?= base64_encode($fecha); ?>" style="text-decoration: underline;"><?=$estadosCuentas[$resultado['fcu_tipo']];?></a>
														</td>
														<td>
															<a href="<?=$_SERVER['PHP_SELF'];?>?usuario=<?=base64_encode($resultado['uss_id']);?>&tipo=<?=base64_encode($tipo);?>&fecha=<?= base64_encode($fecha); ?>" style="text-decoration: underline;"><?=UsuariosPadre::nombreCompletoDelUsuario($resultado);?></a>
														</td>

														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0128', 'DT0089'])){?>
															<td>
																<div class="btn-group">
																	<button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></button>
																	<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																		<i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu" role="menu">
																		<?php if( Modulos::validarSubRol(['DT0128']) ){?>
																			<li><a href="movimientos-editar.php?idU=<?=base64_encode($resultado['fcu_id']);?>"><?=$frases[165][$datosUsuarioActual['uss_idioma']];?></a></li>
																		<?php }?>
																		<?php if($resultado['fcu_anulado']!=1 && Modulos::validarSubRol(['DT0089'])){?>
																			<li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','¿Deseas anular esta transacción?','question','movimientos-anular.php?idR=<?=base64_encode($resultado['fcu_id']);?>&id=<?=base64_encode($resultado['uss_id']);?>')">Anular</a></li>
																		<?php } ?>
																	</ul>
																</div>
															</td>
														<?php }?>
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
                      				<?php include("enlaces-paginacion.php");?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page content -->
             <?php // include("../compartido/panel-configuracion.php");?>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php");?>
    </div>
    <!-- start js include path -->
    <script src="../../config-general/assets/plugins/jquery/jquery.min.js" ></script>
    <script src="../../config-general/assets/plugins/popper/popper.js" ></script>
    <script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- bootstrap -->
    <script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
    <script src="../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" ></script>
	<!-- data tables -->
    <script src="../../config-general/assets/plugins/datatables/jquery.dataTables.min.js" ></script>
 	<script src="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.js" ></script>
    <script src="../../config-general/assets/js/pages/table/table_data.js" ></script>
    <!-- Common js-->
	<script src="../../config-general/assets/js/app.js" ></script>
    <script src="../../config-general/assets/js/layout.js" ></script>
	<script src="../../config-general/assets/js/theme-color.js" ></script>
	<!-- notifications -->
	<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>
	<!-- Material -->
	<script src="../../config-general/assets/plugins/material/material.min.js"></script>
    <!-- end js include path -->
</body>

</html>