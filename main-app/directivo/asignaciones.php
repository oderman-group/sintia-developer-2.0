<?php
include("session.php");
$idPaginaInterna = 'DT0318';
include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");
require_once(ROOT_PATH."/main-app/class/Asignaciones.php");
require_once(ROOT_PATH."/main-app/class/Asignaturas.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

$idE = '';
if (!empty($_GET['idE'])) {
    $idE = base64_decode($_GET['idE']);;
}
?>
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
                                <div class="page-title">Asignaciones</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                        	<?php include("../../config-general/mensajes-informativos.php"); ?>
							<?php include("../compartido/publicidad-lateral.php");?>
							<?php include("includes/barra-superior-asignaciones.php"); ?>
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header>Asignaciones</header>
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
														<?php if(Modulos::validarPermisoEdicion() &&  Modulos::validarSubRol(['DT0319']) ) {?>
															<a href="asignaciones-agregar.php?idE=<?=base64_encode($idE)?>" id="addRow" class="btn deepPink-bgcolor">
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
														<th>Tipo</th>
														<th>Evaluado</th>
														<th>Evaluador</th>
														<th>Estado</th>
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0321', 'DT0323'])){?>
															<th><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></th>
														<?php }?>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													include("includes/consulta-paginacion-asignaciones.php");
													$filtroLimite = 'LIMIT '.$inicio.','.$registros;
													$consulta = Asignaciones::listarAsignaciones($conexion, $config, $idE, $filtro, $filtroLimite);
													$contReg = 1;
													if(!empty($consulta)){
														while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
															switch ($resultado['epag_tipo']) {
																case CURSO:
																	require_once(ROOT_PATH."/main-app/class/Grados.php");
																	$datosEvaluado = Grados::obtenerGrado($resultado['epag_id_evaluado']);
																	$nombreEvaluado = $datosEvaluado['gra_nombre'];
																break;

																case AREA:
																	$consultaEvaluado = mysqli_query($conexion, "SELECT ar_nombre FROM ".BD_ACADEMICA.".academico_areas
																	WHERE ar_id='".$resultado['epag_id_evaluado']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
																	$datosEvaluado = mysqli_fetch_array($consultaEvaluado, MYSQLI_BOTH);
																	$nombreEvaluado = $datosEvaluado['ar_nombre'];
																break;

																case MATERIA:
																	require_once(ROOT_PATH . "/main-app/class/Asignaturas.php");
																	$datosEvaluado = Asignaturas::consultarDatosAsignatura($conexion, $config, $resultado['epag_id_evaluado']);
																	$nombreEvaluado = $datosEvaluado['mat_nombre'];
																break;

																default:
																	if($resultado['epag_tipo'] == DIRECTIVO || $resultado['epag_tipo'] == DOCENTE) {
																		$datosEvaluado = UsuariosPadre::sesionUsuario($resultado['epag_id_evaluado']);
																		$nombreEvaluado = UsuariosPadre::nombreCompletoDelUsuario($datosEvaluado);
																	}
																break;
															}

															$arrayEnviar = array("tipo"=>1, "descripcionTipo"=>"Para ocultar fila del registro.");
															$arrayDatos = json_encode($arrayEnviar);
															$objetoEnviar = htmlentities($arrayDatos);
													?>
													<tr id="reg<?=$resultado['epag_id'];?>">
                                                        <td><?=$contReg;?></td>
														<td><?=$resultado['epag_tipo'];?></td>
														<td><?=strtoupper($nombreEvaluado);?></td>
														<td><?=UsuariosPadre::nombreCompletoDelUsuario($resultado);?></td>
														<td><?=$resultado['epag_estado'];?></td>
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0321', 'DT0323'])){?>
															<td>
																<div class="btn-group">
																	<button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></button>
																	<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																		<i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu" role="menu" style="z-index: 10000;">
																	<?php if(Modulos::validarSubRol(['DT0321']) ){?>
																		<li><a href="asignaciones-editar.php?id=<?=base64_encode($resultado['epag_id']);?>"><?=$frases[165][$datosUsuarioActual['uss_idioma']];?></a></li>
																	<?php }?>
																	<?php if(Modulos::validarSubRol(['DT0323']) && $resultado['epag_estado'] == PENDIENTE){?>
                                                                    	<li><a href="javascript:void(0);" title="<?=$objetoEnviar;?>" id="<?=$resultado['epag_id'];?>" name="asignaciones-eliminar.php?id=<?=base64_encode($resultado['epag_id']);?>" onClick="deseaEliminar(this)"><?=$frases[174][$datosUsuarioActual['uss_idioma']];?></a></li>
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