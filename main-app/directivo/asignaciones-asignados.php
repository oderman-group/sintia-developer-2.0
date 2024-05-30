<?php
include("session.php");
$idPaginaInterna = 'DT0329';
include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");
require_once(ROOT_PATH."/main-app/class/Asignaciones.php");
require_once(ROOT_PATH."/main-app/class/Asignaturas.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

$idE = '';
if (!empty($_GET['id'])) {
    $idE = base64_decode($_GET['id']);;
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
                                <div class="page-title">Usuarios Asignados</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                        	<?php include("../../config-general/mensajes-informativos.php"); ?>
							<?php include("../compartido/publicidad-lateral.php");?>
							<?php include("includes/barra-superior-asignaciones-asignados.php"); ?>
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header>Usuarios Asignados</header>
                                            <div class="tools">
                                                <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
			                                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
			                                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
											
                                        <div>
                                    		<table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
														<th>Evaluador</th>
														<th>Estado</th>
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0330'])){?>
															<th><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></th>
														<?php }?>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													include("includes/consulta-paginacion-asignaciones-asignados.php");
													$filtroLimite = 'LIMIT '.$inicio.','.$registros;
													$consulta = Asignaciones::listarAsignaciones($conexion, $config, $idE, $filtro, $filtroLimite);
													$contReg = 1;
													if(!empty($consulta)){
														while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){

															$arrayEnviar = array("tipo"=>1, "descripcionTipo"=>"Para ocultar fila del registro.");
															$arrayDatos = json_encode($arrayEnviar);
															$objetoEnviar = htmlentities($arrayDatos);
													?>
													<tr id="reg<?=$resultado['epag_id'];?>">
                                                        <td><?=$contReg;?></td>
														<td><?=UsuariosPadre::nombreCompletoDelUsuario($resultado)?></td>
														<td><?=$resultado['epag_estado']?></td>
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0330']) && $resultado['epag_estado'] == PENDIENTE){?>
															<td>
																<div class="btn-group">
																	<button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></button>
																	<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																		<i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu" role="menu" style="z-index: 10000;">
																	<?php if(Modulos::validarSubRol(['DT0330'])){?>
                                                                    	<li><a href="javascript:void(0);" title="<?=$objetoEnviar;?>" id="<?=$resultado['epag_id'];?>" name="asignaciones-asignados-eliminar.php?id=<?=base64_encode($resultado['epag_id']);?>" onClick="deseaEliminar(this)"><?=$frases[174][$datosUsuarioActual['uss_idioma']];?></a></li>
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