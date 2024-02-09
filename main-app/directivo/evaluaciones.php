<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0281';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");
require_once(ROOT_PATH."/main-app/class/EvaluacionGeneral.php");

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
                                <div class="page-title"><?=$frases[114][$datosUsuarioActual['uss_idioma']];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                        <?php include("../../config-general/mensajes-informativos.php"); ?>
									<?php include("../compartido/publicidad-lateral.php");?>
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header><?=$frases[114][$datosUsuarioActual['uss_idioma']];?></header>
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
														<?php if(Modulos::validarPermisoEdicion() &&  Modulos::validarSubRol(['DT0283']) ) {?>
															<a href="evaluacion-agregar.php" id="addRow" class="btn deepPink-bgcolor">
															<?=$frases[231][$datosUsuarioActual['uss_idioma']];?> <i class="fa fa-plus"></i>
															</a>
														<?php }?>
													</div>
												</div>
											</div>
											
                                        <div>
                                    		<table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
														<th><?=$frases[51][$datosUsuarioActual['uss_idioma']];?></th>
														<th><?=$frases[187][$datosUsuarioActual['uss_idioma']];?></th>
														<th>Visible</th>
														<th><?=$frases[139][$datosUsuarioActual['uss_idioma']];?></th>
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0283', 'DT0287'])){?>
															<th><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></th>
														<?php }?>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													 $parametros = [
														'evag_year'=>$config['conf_agno'],
														'arreglo'=>false
													];
													$consulta = EvaluacionGeneral::listar($parametros);
													$contReg = 1;
												if(!empty($consulta)){
													while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){

														$fechaBD = new DateTime($resultado['evag_fecha']);
														$fecha = $fechaBD->format('d/m/Y');
														$nombre = !empty($resultado['evag_nombre']) ? $resultado['evag_nombre'] : "";
														$visible = !empty($resultado['evag_visible']) ? $resultado['evag_visible'] : "";
														$preguntas = !empty($resultado['preguntas']) ? $resultado['preguntas'] :  0;
														$arrayEnviar = array("tipo"=>1, "descripcionTipo"=>"Para ocultar fila del registro.");
														$arrayDatos = json_encode($arrayEnviar);
														$objetoEnviar = htmlentities($arrayDatos);
													?>
													<tr id="reg<?=$resultado['evag_id'];?>">
                                                        <td><?=$contReg;?></td>
														<td><?=$fecha?></td>
														<td><?=$nombre;?></td>
														<td>
															<?php 
																if($visible==1){?>
 															      <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta visible"><i class="fa fa-eye"></i></button>
																<?php }else{?>
																  <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="No esta visible"><i class="fa fa-eye-slash"></i></button>
															<?php }?>
														</td>
														<td><?=$preguntas;?></td>
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0283', 'DT0287'])){?>
															<td>
																<div class="btn-group">
																	<button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></button>
																	<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																		<i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu" role="menu" style="z-index: 10000;">
																		<?php if( Modulos::validarSubRol(['DT0283']) ){?>
																			<li><a href="evaluacion-editar.php?id=<?=base64_encode($resultado['evag_id']);?>"><?=$frases[165][$datosUsuarioActual['uss_idioma']];?></a></li>
																		<?php }?>
																		<?php if( Modulos::validarSubRol(['DT0314']) ){?>
																			<li><a href="evaluaciones-preguntas.php?id=<?=base64_encode($resultado['evag_id']);?>">Relacionar Preguntas</a></li>
																		<?php }?>
																		<?php if( Modulos::validarSubRol(['DT0318']) ){?>
																			<li><a href="asignaciones.php?idE=<?=base64_encode($resultado['evag_id']);?>">Asignaciones</a></li>
																		<?php }?>
																		<?php if( Modulos::validarSubRol(['DT0287']) && $preguntas==0){?>
                                                                            <li><a href="javascript:void(0);" title="<?=$objetoEnviar;?>" id="<?=$resultado['evag_id'];?>" name="evaluacion-eliminar.php?id=<?=base64_encode($resultado['evag_id']);?>" onClick="deseaEliminar(this)"><?=$frases[174][$datosUsuarioActual['uss_idioma']];?></a></li>
																		<?php } ?>
																	</ul>
																</div>
															</td>
														<?php }?>
                                                    </tr>
													<?php 
															$contReg++;
														}
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