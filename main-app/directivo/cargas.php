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
require_once("../class/Estudiantes.php");
$jQueryTable = '';
if($config['conf_id_institucion'] != ICOLVEN && $config['conf_id_institucion'] != DEVELOPER && $config['conf_id_institucion'] != DEVELOPER_PROD) {
	$jQueryTable = 'id="example1"';
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
								$curso = '';
								if(!empty($_GET["curso"])){ $curso = base64_decode($_GET['curso']); $filtro .= " AND car_curso='".$curso."'";}
								if(!empty($_GET["grupo"])){$filtro .= " AND car_grupo='".base64_decode($_GET["grupo"])."'";}
								if(!empty($_GET["docente"])){$filtro .= " AND car_docente='".base64_decode($_GET["docente"])."'";}
								if(!empty($_GET["asignatura"])){$filtro .= " AND car_materia='".base64_decode($_GET["asignatura"])."'";}

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
														<?php if (Modulos::validarPermisoEdicion()) { ?>
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#nuevaCargModal" class="btn deepPink-bgcolor">
														   <?=$frases[231][$datosUsuarioActual['uss_idioma']];?> <i class="fa fa-plus"></i>
                                                        </a>
                                                        <?php
                                                        $idModal = "nuevaCargModal";
                                                        $contenido = "../directivo/cargas-agregar-modal.php";
                                                        include("../compartido/contenido-modal.php");
                                                        } ?>
													</div>
												</div>
											</div>
											
                                        <div>
                                    		<table <?php echo $jQueryTable;?> class="display" style="width:100%;">
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
														INNER JOIN academico_grados ON gra_id=car_curso {$filtroMT}
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

														//Para calcular el porcentaje de actividades en las cargas
														$cargaSP = $resultado[0];
														$periodoSP = $resultado['car_periodo'];
														include("../suma-porcentajes.php");

														$marcaMediaTecnica = '';
														$filtroDocentesParaListarEstudiantes = " AND mat_grado='".$resultado['car_curso']."' AND mat_grupo='".$resultado['car_grupo']."'";
														if($resultado['gra_tipo'] == GRADO_INDIVIDUAL) {
															$cantidadEstudiantes = Estudiantes::contarEstudiantesParaDocentesMT($resultado);
															$marcaMediaTecnica = '<i class="fa fa-bookmark" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Media técnica"></i> ';
														} else {
															$cantidadEstudiantes = Estudiantes::contarEstudiantesParaDocentes($filtroDocentesParaListarEstudiantes);
														}

														$infoTooltipCargas = "
														<b>COD:</b> 
														{$resultado['car_id']}<br>
														<b>Director de grupo:</b> 
														{$opcionSINO[$resultado['car_director_grupo']]}<br>
														<b>I.H:</b> 
														{$resultado['car_ih']}<br>
														<b>Puede editar en otros periodos?:</b> 
														{$opcionSINO[$resultado['car_permiso2']]}<br>
														<b>Indicadores automáticos?:</b> 
														{$opcionSINO[$resultado['car_indicador_automatico']]}<br>
														<b>Max. Indicadores:</b> 
														{$resultado['car_maximos_indicadores']}<br>
														<b>Max. Calificaciones:</b> 
														{$resultado['car_maximas_calificaciones']}<br>
														<b>Nro. Estudiantes:</b> 
														{$cantidadEstudiantes}
														";
													?>
													<tr>
                          								<td><?=$contReg;?></td>
														<td><a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="Información adicional" data-content="<?=$infoTooltipCargas;?>" data-html="true" data-placement="top" style="border-bottom: 1px dotted #000;"><?=$resultado['car_id'];?></a></td>
														<td><?=strtoupper($resultado['uss_nombre']." ".$resultado['uss_nombre2']." ".$resultado['uss_apellido1']." ".$resultado['uss_apellido2']);?></td>
														<td><?=$marcaMediaTecnica ."[".$resultado['gra_id']."] ".strtoupper($resultado['gra_nombre']." ".$resultado['gru_nombre']);?></td>
														<td><?="[".$resultado['mat_id']."] ".strtoupper($resultado['mat_nombre'])." (".$resultado['mat_valor']."%)";?></td>
														<td><?=$resultado['car_ih'];?></td>
														<td><?=$resultado['car_periodo'];?></td>
                                        				<td><a href="../compartido/reporte-notas.php?carga=<?=base64_encode($resultado[0]);?>&per=<?=base64_encode($resultado['car_periodo']);?>&grado=<?=base64_encode($resultado["car_curso"]);?>&grupo=<?=base64_encode($resultado["car_grupo"]);?>" target="_blank" style="text-decoration:underline; color:#00F;" title="Calificaciones"><?=$spcd[0];?>%&nbsp;&nbsp;-&nbsp;&nbsp;<?=$spcr[0];?>%</a></td>

														
														<td>
															<div class="btn-group">
																  <button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual[8]];?></button>
																  <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																	  <i class="fa fa-angle-down"></i>
																  </button>
																  <ul class="dropdown-menu" role="menu" style="z-index: 9000;">
																		<?php if(Modulos::validarPermisoEdicion()){?>
																			<li><a href="cargas-editar.php?idR=<?=base64_encode($resultado['car_id']);?>"><?=$frases[165][$datosUsuarioActual[8]];?></a></li>
																			<?php if($config['conf_permiso_eliminar_cargas'] == 'SI'){?>
																				<li>
																				    <a href="javascript:void(0);" title="Eliminar" onClick="sweetConfirmacion('Alerta!','Deseas eliminar esta accion?','question','cargas-eliminar.php?id=<?=base64_encode($resultado[0]);?>')"><?=$frases[174][$datosUsuarioActual[8]];?></a>
																				</li>
																			<?php }?>
																	  		<li>
																			    <a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','Esta acción te permitirá entrar como docente y ver todos los detalles de esta carga. Deseas continuar?','question','auto-login.php?user=<?=base64_encode($resultado['car_docente']);?>&tipe=<?=base64_encode(2)?>&carga=<?=base64_encode($resultado['car_id']);?>&periodo=<?=base64_encode($resultado['car_periodo']);?>')">Ver como docente</a>
																			<?php }?>
																	  <li><a href="cargas-horarios.php?id=<?=base64_encode($resultado[0]);?>" title="Ingresar horarios">Ingresar Horarios</a></li>
																	  <li><a href="periodos-resumen.php?carga=<?=base64_encode($resultado[0]);?>" title="Resumen Periodos"><?=$frases[84][$datosUsuarioActual[8]];?></a></li>
																	  <li><a href="cargas-indicadores.php?carga=<?=base64_encode($resultado['car_id']);?>&docente=<?=base64_encode($resultado['car_docente']);?>">Indicadores</a></li>
																	  <li><a href="../compartido/planilla-docentes.php?carga=<?=base64_encode($resultado['car_id']);?>" target="_blank">Ver Planilla</a></li>
																	  <li><a href="../compartido/planilla-docentes-notas.php?carga=<?=base64_encode($resultado['car_id']);?>" target="_blank">Ver Planilla con notas</a></li>
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
	<script>
		$(function () {
			$('[data-toggle="popover"]').popover();
		});

		$('.popover-dismiss').popover({trigger: 'focus'});
	</script>
</body>

</html>