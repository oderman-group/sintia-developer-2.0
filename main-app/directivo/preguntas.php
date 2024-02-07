<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0289';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");
require_once(ROOT_PATH."/main-app/class/PreguntaGeneral.php");

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
                                <div class="page-title"><?=$frases[139][$datosUsuarioActual['uss_idioma']];?></div>
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
                                            <header><?=$frases[139][$datosUsuarioActual['uss_idioma']];?></header>
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
														<?php if(Modulos::validarPermisoEdicion() &&  Modulos::validarSubRol(['DT0289']) ) {?>
															<a href="pregunta-agregar.php" id="addRow" class="btn deepPink-bgcolor">
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
														<th><?=$frases[139][$datosUsuarioActual['uss_idioma']];?></th>
														<th><?=$frases[294][$datosUsuarioActual['uss_idioma']];?> <?=$frases[139][$datosUsuarioActual['uss_idioma']];?></th>
														<th>Visible</th>
														<th>Obligatoria</th>
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0289', 'DT0293'])){?>
															<th><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></th>
														<?php }?>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													 $parametros = [
														'evag_year'=>$config['conf_agno']
													];
													$consulta = PreguntaGeneral::listar($parametros);
													$contReg = 1;
												if(!empty($consulta)){
													while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){

														$descripcion = !empty($resultado['pregg_descripcion']) ? $resultado['pregg_descripcion'] : "";
														$tipo_pregunta = !empty($resultado['pregg_tipo_pregunta']) ? $resultado['pregg_tipo_pregunta'] : "";
														$visible = !empty($resultado['pregg_visible']) ? $resultado['pregg_visible'] : "";
														$obligatoria = !empty($resultado['pregg_obligatoria']) ? $resultado['pregg_obligatoria'] : "";
														$arrayEnviar = array("tipo"=>1, "descripcionTipo"=>"Para ocultar fila del registro.");
														$arrayDatos = json_encode($arrayEnviar);
														$objetoEnviar = htmlentities($arrayDatos);
													?>
													<tr id="reg<?=$resultado['pregg_id'];?>">
                                                        <td><?=$contReg;?></td>
														<td><?=$descripcion;?></td>
														<td>
														<?php 
															if($tipo_pregunta === TEXT){?>
 															    	<button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="<?=$frases[421][$datosUsuarioActual['uss_idioma']];?>"><i class="fa fa-inbox"></i></button>
																<?php }elseif($tipo_pregunta === MULTIPLE){?>
																	<button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="<?=$frases[422][$datosUsuarioActual['uss_idioma']];?>"><i class="fa fa-tasks"></i></button>
															<?php  }else if($tipo_pregunta === SINGLE){?>
																	<button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="<?=$frases[423][$datosUsuarioActual['uss_idioma']];?>"><i class="fa fa-check"></i></button>
														<?php }?>
														</td>
														<td>
														<?php 
															if($visible == 1){?>
 														   		<button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta visible"><i class="fa fa-eye"></i></button>
															<?php }else{?>
																<button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="No esta visible"><i class="fa fa-eye-slash"></i></button>
														<?php }?>
														</td>
														<td>
														<?php 
															if($obligatoria == 1){?>
 																<button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Es requerido"><i class="fa fa-lock"></i></button>
															<?php }else{?>
																<button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="No es requerido"><i class="fa fa-unlock" aria-hidden="true"></i></button>
														<?php }?>
														</td>
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0289', 'DT0293'])){?>
															<td>
																<div class="btn-group">
																	<button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></button>
																	<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																		<i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu" role="menu" style="z-index: 10000;">
																	<?php if( Modulos::validarSubRol(['DT0289']) ){?>
																		<li><a href="pregunta-editar.php?id=<?=base64_encode($resultado['pregg_id']);?>"><?=$frases[165][$datosUsuarioActual['uss_idioma']];?></a></li>
																	<?php }?>
																	<?php if( Modulos::validarSubRol(['DT0316']) ){?>
																		<li><a href="preguntas-respuestas.php?id=<?=base64_encode($resultado['pregg_id']);?>">Relacionar Respuestas</a></li>
																	<?php }?>
																	<?php if( Modulos::validarSubRol(['DT0293']) ){?>
                                                                    	<li><a href="javascript:void(0);" title="<?=$objetoEnviar;?>" id="<?=$resultado['pregg_id'];?>" name="pregunta-eliminar.php?id=<?=base64_encode($resultado['pregg_id']);?>" onClick="deseaEliminar(this)"><?=$frases[174][$datosUsuarioActual['uss_idioma']];?></a></li>
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