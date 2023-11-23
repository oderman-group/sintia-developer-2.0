<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0020';?>
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
                                <div class="page-title"><?=$frases[73][$datosUsuarioActual['uss_idioma']];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
								
								<div class="col-md-12">
                                <?php include("../../config-general/mensajes-informativos.php"); ?>
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header><?=$frases[73][$datosUsuarioActual['uss_idioma']];?></header>
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
														<?php if (Modulos::validarPermisoEdicion()) { ?>
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#nuevaAsigModal" class="btn deepPink-bgcolor">
														   <?=$frases[231][$datosUsuarioActual['uss_idioma']];?> <i class="fa fa-plus"></i>
                                                        </a>
                                                        <?php
                                                        $idModal = "nuevaAsigModal";
                                                        $contenido = "../directivo/asignaturas-agregar-modal.php";
                                                        include("../compartido/contenido-modal.php");
                                                        } ?>
													</div>
												</div>
											</div>
											
                                        <div class="table-scrollable">
                                    		<table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
														<th><?=$frases[49][$datosUsuarioActual['uss_idioma']];?></th>
														<th><?=$frases[73][$datosUsuarioActual['uss_idioma']];?></th>
														<?php if($config['conf_agregar_porcentaje_asignaturas']=='SI'){ ?>
															<th>Valor(%)</th>
														<?php }?>	
														<th><?=$frases[93][$datosUsuarioActual['uss_idioma']];?></th>
														<th>Cargas</th>
														<?php if(Modulos::validarPermisoEdicion()){?>
															<th><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></th>
														<?php }?>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													 $filtro = '';
													 if(isset($_GET["area"]) and is_numeric(base64_decode($_GET["area"]))){$filtro .= " AND am.mat_area='".base64_decode($_GET["area"])."'";}
													try{
														$consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_materias am
														INNER JOIN ".BD_ACADEMICA.".academico_areas ar ON ar.ar_id=am.mat_area AND ar.institucion={$config['conf_id_institucion']} AND ar.year={$_SESSION["bd"]}
														WHERE am.mat_id=am.mat_id AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]} $filtro");
													} catch (Exception $e) {
														include("../compartido/error-catch-to-report.php");
													}
													 $contReg = 1;
													 while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
														try{
															$consultaNumeros=mysqli_query($conexion, "SELECT COUNT(car_id) FROM ".BD_ACADEMICA.".academico_cargas WHERE car_materia='".$resultado['mat_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														 $numeros = mysqli_fetch_array($consultaNumeros, MYSQLI_BOTH);
													 ?>
													<tr>
                                                        <td><?=$contReg;?></td>
														<td><?=$resultado['mat_id'];?></td>
														<td><?=$resultado['mat_nombre'];?></td>
														<?php if($config['conf_agregar_porcentaje_asignaturas']=='SI'){ ?>
															<td><?=$resultado['mat_valor'];?></td>
														<?php }?>	
														<td><?=$resultado['ar_nombre'];?></td>
														<td><a href="cargas.php?asignatura=<?=base64_encode($resultado['mat_id']);?>" style="text-decoration: underline;"><?=$numeros[0];?></a></td>
														
														<?php if(Modulos::validarPermisoEdicion()){?>
															<td>
																<div class="btn-group">
																	<button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></button>
																	<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																		<i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu" role="menu">
																		<li><a href="asignaturas-editar.php?id=<?=base64_encode($resultado['mat_id']);?>"><?=$frases[165][$datosUsuarioActual['uss_idioma']];?></a></li>
																		<?php if($numeros[0]==0){?><li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','Deseas eliminar este registro?','question','asignaturas-eliminar.php?id=<?=base64_encode($resultado['mat_id']);?>')">Eliminar</a></li><?php } ?>
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
                                </div>
								
								<div class="col-md-4 col-lg-3">
									<?php include("../compartido/publicidad-lateral.php");?>
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