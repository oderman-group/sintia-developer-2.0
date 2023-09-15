<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0032';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php
$Plataforma = new Plataforma;
if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
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
                                <div class="page-title"><?=$frases[12][$datosUsuarioActual['uss_idioma']];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
								
								
								
								<?php 
								$filtro = '';
								if(!empty($_GET["curso"])){$filtro .= " AND car_curso='".$_GET["curso"]."'";}
								if(!empty($_GET["grupo"])){$filtro .= " AND car_grupo='".$_GET["grupo"]."'";}
								if(!empty($_GET["docente"])){$filtro .= " AND car_docente='".$_GET["docente"]."'";}
								if(!empty($_GET["asignatura"])){$filtro .= " AND car_materia='".$_GET["asignatura"]."'";}

								//include("includes/cargas-filtros.php");
								?>
								
								<div class="col-md-12">
								<?php
									include("../../config-general/mensajes-informativos.php");
									include("includes/barra-superior-cargas.php");
								?>

                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header><?=$frases[12][$datosUsuarioActual['uss_idioma']];?></header>
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
														<?php if(Modulos::validarPermisoEdicion()){?>
															<a href="cargas-agregar.php" id="addRow" class="btn deepPink-bgcolor">
																Agregar nuevo <i class="fa fa-plus"></i>
															</a>
														<?php }?>
													</div>
												</div>
											</div>
											
                                        <div class="table-scrollable">
                                    		<table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
														<th><?=$frases[49][$datosUsuarioActual[8]];?></th>
														<th>Docente</th>
														<th>Curso</th>
														<th>Asignatura</th>
														<th>I.H</th>
														<th>Periodo Actual</th>
                                        				<th style="text-align:center;">NOTAS<br>Declaradas - Registradas</th>
														<th><?=$frases[54][$datosUsuarioActual[8]];?></th>
														</tr>
													</thead>
													<tbody>
													<?php
													include("includes/consulta-paginacion-cargas.php");	
													try{										       
														$busqueda=mysqli_query($conexion,"SELECT * FROM academico_cargas
														LEFT JOIN academico_grados ON gra_id=car_curso
														LEFT JOIN academico_grupos ON gru_id=car_grupo
														LEFT JOIN academico_materias ON mat_id=car_materia
														LEFT JOIN usuarios ON uss_id=car_docente
														WHERE car_id=car_id $filtro
														ORDER BY car_id
														LIMIT $inicio,$registros;");
													} catch (Exception $e) {
														include("../compartido/error-catch-to-report.php");
													}
    												$contReg = 1;
													 while ($resultado = mysqli_fetch_array($busqueda, MYSQLI_BOTH)){
																										
														$estadosMatriculas = array("","Matriculado","Asistente","Cancelado","No Matriculado");
														try{
															$consultaCargaAcademica=mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_id='".$resultado[0]."'");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														$cargaAcademica = mysqli_fetch_array($consultaCargaAcademica, MYSQLI_BOTH);
														$cargaSP = $resultado[0];
														$periodoSP = $resultado['car_periodo'];
														include("../suma-porcentajes.php");
														?>
													<tr>
                          								<td><?=$contReg;?></td>
														<td><?=$resultado['car_id'];?></td>
														<td><?=strtoupper($resultado['uss_nombre']." ".$resultado['uss_nombre2']." ".$resultado['uss_apellido1']." ".$resultado['uss_apellido2']);?></td>
														<td><?="[".$resultado['gra_id']."] ".strtoupper($resultado['gra_nombre']." ".$resultado['gru_nombre']);?></td>
														<td><?="[".$resultado['mat_id']."] ".strtoupper($resultado['mat_nombre'])." (".$resultado['mat_valor']."%)";?></td>
														<td><?=$resultado['car_ih'];?></td>
														<td><?=$resultado['car_periodo'];?></td>
                                        				<td><a href="../compartido/reporte-notas.php?carga=<?=$resultado[0];?>&per=<?=$resultado['car_periodo'];?>&grado=<?=$resultado["car_curso"];?>&grupo=<?=$resultado["car_grupo"];?>" target="_blank" style="text-decoration:underline; color:#00F;" title="Calificaciones"><?=$spcd[0];?>%&nbsp;&nbsp;-&nbsp;&nbsp;<?=$spcr[0];?>%</a></td>

														
														<td>
															<div class="btn-group">
																  <button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual[8]];?></button>
																  <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																	  <i class="fa fa-angle-down"></i>
																  </button>
																  <ul class="dropdown-menu" role="menu">
																		<?php if(Modulos::validarPermisoEdicion()){?>
																			<li><a href="cargas-editar.php?idR=<?=$resultado['car_id'];?>"><?=$frases[165][$datosUsuarioActual[8]];?></a></li>
																			<?php if($config['conf_permiso_eliminar_cargas'] == 'SI'){?>
																				<li><a href="cargas-eliminar.php?id=<?=$resultado[0];?>" title="Eliminar" onClick="if(!confirm('Desea ejecutar esta accion?')){return false;}"><?=$frases[174][$datosUsuarioActual[8]];?></a></li>
																			<?php }?>
																	  		<li><a href="auto-login.php?user=<?=$resultado['car_docente'];?>&tipe=2&carga=<?=base64_encode($resultado['car_id']);?>&periodo=<?=base64_encode($resultado['car_periodo']);?>" onClick="if(!confirm('Esta acción te permitirá entrar como docente y ver todos los detalles de esta carga. Deseas continuar?')){return false;}">Ver como docente</a></li>
																		<?php }?>
																	  <li><a href="cargas-horarios.php?id=<?=$resultado[0];?>" title="Ingresar horarios">Ingresar Horarios</a></li>
																	  <li><a href="periodos-resumen.php?carga=<?=$resultado[0];?>" title="Resumen Periodos"><?=$frases[84][$datosUsuarioActual[8]];?></a></li>
																	  <li><a href="cargas-indicadores.php?carga=<?=$resultado['car_id'];?>&docente=<?=$resultado['car_docente'];?>">Indicadores</a></li>
																	  <li><a href="../compartido/planilla-docentes.php?carga=<?=base64_encode($resultado['car_id']);?>" target="_blank">Ver Planilla</a></li>
																	  <li><a href="../compartido/planilla-docentes-notas.php?carga=<?=$resultado['car_id'];?>" target="_blank">Ver Planilla con notas</a></li>
																  </ul>
															  </div>
														</td>
                            </tr>
													  <?php $contReg++;} ?>
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