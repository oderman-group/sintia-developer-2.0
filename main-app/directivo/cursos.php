<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0062';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");

Utilidades::validarParametros($_GET);

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
                                <div class="page-title"><?=$frases[5][$datosUsuarioActual['uss_idioma']];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
								
								
								<div class="col-md-12">
								
									<?php include("../../config-general/mensajes-informativos.php"); ?>
									
									<?php include("includes/barra-superior-cursos.php"); ?>
								

                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header><?=$frases[5][$datosUsuarioActual['uss_idioma']];?></header>
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
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0065'])){?>
															<a href="javascript:void(0);"  data-toggle="modal" data-target="#nuevoCursoModal"  class="btn deepPink-bgcolor">
																Agregar nuevo <i class="fa fa-plus"></i>
															</a>
														<?php 
													$idModal="nuevoCursoModal";															
													$contenido="../directivo/cursos-agregar-modal.php"; 
													include("../compartido/contenido-modal.php");
													}?>
													</div>
												</div>
											</div>
											
                                        <div class="table-scrollable">
                                    		<table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
														<th><?=$frases[49][$datosUsuarioActual['uss_idioma']];?></th>
														<th><?=$frases[5][$datosUsuarioActual['uss_idioma']];?></th>
														<th>Formato boletín</th>
														<th>Matrícula</th>
														<th>Pensión</th>														
														<th>#P</th>
														<?php if(array_key_exists(10,$arregloModulos) ){?>
															<th><?=$frases[53][$datosUsuarioActual['uss_idioma']];?></th>
														<?php }?>
														<th><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php	
													$tipo=NULL;

													if (!empty($_GET['tipo'])) {
														$tipo = base64_decode($_GET['tipo']);
													}

													$consulta = Grados::listarGrados(1,$tipo);
													$contReg = 1;
													while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
													?>
													<tr>
                                                        <td><?=$contReg;?></td>
														<td><?=$resultado['gra_id'];?></td>
														<td><?=$resultado['gra_nombre'];?></td>
														<td><?=$resultado['gra_formato_boletin'];?></td>
														<td>$<?=number_format($resultado['gra_valor_matricula']);?></td>
														<td>$<?=number_format($resultado['gra_valor_pension']);?></td>														
														<td><?=$resultado['gra_periodos'];?></td>
														<?php if(array_key_exists(10,$arregloModulos) ){?>
															<td><?=strtoupper($resultado['gra_tipo']);?></td>
														<?php }?>
														<td>
															<div class="btn-group">
																  <button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></button>
																  <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																	  <i class="fa fa-angle-down"></i>
																  </button>
																  <ul class="dropdown-menu" role="menu">
																	<?php if(Modulos::validarPermisoEdicion()){?>
																		<?php if(Modulos::validarSubRol(['DT0064'])){?>
																		<li><a href="cursos-editar.php?id=<?=base64_encode($resultado['gra_id']);?>"><?=$frases[165][$datosUsuarioActual['uss_idioma']];?></a></li>
																		<?php } if(Modulos::validarSubRol(['DT0158'])){?>
																		<li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','¿Deseas eliminar este curso?','question','cursos-eliminar.php?id=<?=base64_encode($resultado['gra_id']);?>')">Eliminar</a></li>
																	<?php }}?>
																	<?php if(Modulos::validarSubRol(['DT0224'])){?>
																	<li><a href="../compartido/matricula-boletin-curso-<?=$resultado['gra_formato_boletin'];?>.php?curso=<?=base64_encode($resultado['gra_id']);?>&periodo=<?=base64_encode($config[2]);?>" title="Imprimir boletin por curso" target="_blank">Boletin por curso</a></li>
                                                        			<?php }?>
																	<?php if(Modulos::validarSubRol(['DT0250'])){?>
																	<li><a href="../compartido/indicadores-perdidos-curso.php?curso=<?=base64_encode($resultado['gra_id']);?>&periodo=<?=base64_encode($config[2]);?>" title="Imprimir boletin por curso" target="_blank">Indicadores perdidos</a></li>
                                                        			<?php }?>
																	<?php if(Modulos::validarSubRol(['DT0227'])){?>
																	<li><a href="../compartido/matricula-libro-curso-<?=$config['conf_libro_final']?>.php?curso=<?=base64_encode($resultado['gra_id']);?>" title="Imprimir Libro por curso" target="_blank">Libro por curso</a></li>
                                                        			<?php }?>
																	<?php if(Modulos::validarSubRol(['DT0251'])){?>
																	<li><a href="../compartido/matriculas-formato3-curso.php?curso=<?=base64_encode($resultado['gra_id']);?>" title="Hoja de matrícula por curso" target="_blank">Hojas de matrícula</a></li>
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