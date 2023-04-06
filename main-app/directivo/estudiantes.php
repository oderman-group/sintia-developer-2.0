<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0001';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php
include("../class/Estudiantes.php");


$filtro = '';
if (isset($_GET["curso"]) AND is_numeric($_GET["curso"])) {
	$filtro .= " AND mat_grado='".$_GET["curso"]."'";
	$fcurso = $_GET["curso"];
}
if(isset($_GET["estadoM"]) AND is_numeric($_GET["estadoM"])){
	$filtro .= " AND mat_estado_matricula='".$_GET["estadoM"]."'";
}
?>
	<!-- data tables -->
    <link href="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
</head>
<!-- END HEAD -->
<?php include("../compartido/body.php"); //6 consultas para optmizar: Enuar ?>
    <div class="page-wrapper">
        <?php include("../compartido/encabezado.php"); //1 por otimizar, parece estar repetida ?>
		
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
                                <div class="page-title"><?=$frases[209][$datosUsuarioActual['uss_idioma']];?></div>
								<?php include("../compartido/texto-manual-ayuda.php"); //1 por otimizar, parece estar repetida ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
								
								<div class="col-md-12">
								<?php include("../../config-general/mensajes-informativos.php"); ?>
								<span id="respuestaCambiarEstado"></span>

								<?php include("includes/barra-superior-matriculas.php"); ?>

									<?php
									if($config['conf_id_institucion']==1){
										if(isset($_GET['msgsion'])){
											$aler='alert-danger';
											$mensajeSion='Por favor, verifique todos los datos del estudiante y llene los campos vacios.';
											if($_GET['msgsion']!=''){
												$aler='alert-success';
												$mensajeSion=$_GET['msgsion'];
												if($_GET['stadsion']!=true){
													$aler='alert-danger';
												}
											}
									?>
										<div class="alert alert-block <?=$aler;?>">
											<button type="button" class="close" data-dismiss="alert">×</button>
											<h4 class="alert-heading">SION!</h4>
											<p><?=$mensajeSion;?></p>
										</div>
									<?php 
										}
									}
									if(isset($_GET['msgsintia'])){
										$aler='alert-success';
										if($_GET['stadsintia']!=true){
										$aler='alert-danger';
										}
									?>
									<div class="alert alert-block <?=$aler;?>">
										<button type="button" class="close" data-dismiss="alert">×</button>
										<h4 class="alert-heading">SINTIA!</h4>
										<p><?=$_GET['msgsintia'];?></p>
									</div>
									<?php }?>
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header><?=$frases[209][$datosUsuarioActual['uss_idioma']];?></header>
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
														<a href="estudiantes-agregar.php" id="addRow" class="btn deepPink-bgcolor">
															Agregar nuevo <i class="fa fa-plus"></i>
														</a>
													</div>
												</div>
											</div>
											
                                        <div >
                                    		<table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
														<th><?=$frases[246][$datosUsuarioActual[8]];?></th>
														<th><?=$frases[241][$datosUsuarioActual[8]];?></th>
														<th><?=$frases[61][$datosUsuarioActual[8]];?></th>
														<th><?=$frases[26][$datosUsuarioActual[8]];?></th>
														<th>Usuario</th>
														<th>Acudiente</th>
														<th><?=$frases[54][$datosUsuarioActual[8]];?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<script type="text/javascript">
														const estudiantesPorEstados = {};

														function cambiarEstadoMatricula(data) {
															let idHref = 'estadoMatricula'+data.id_estudiante;
															let href   = document.getElementById(idHref);
															
															if (!estudiantesPorEstados.hasOwnProperty(data.id_estudiante)) {
																estudiantesPorEstados[data.id_estudiante] = data.estado_matricula;
															}

															if(estudiantesPorEstados[data.id_estudiante] == 1) {
																href.innerHTML = `<span class="text-warning">No Matriculado</span>`;
																estudiantesPorEstados[data.id_estudiante] = 4;
															} else {
																href.innerHTML = `<span class="text-success">Matriculado</span>`;
																estudiantesPorEstados[data.id_estudiante] = 1;
															}

															let datos = "nuevoEstado="+estudiantesPorEstados[data.id_estudiante]+
																		"&idEstudiante="+data.id_estudiante;

															$.ajax({
																type: "POST",
																url: "ajax-cambiar-estado-matricula.php",
																data: datos,
																success: function(data){
																	$('#respuestaCambiarEstado').empty().hide().html(data).show(1);
																}

															});
														}
													</script>
													<?php
													include("consulta-paginacion-estudiantes.php");
													$filtroLimite = 'LIMIT '.$inicio.','.$registros;
													$consulta = Estudiantes::listarEstudiantes(0, $filtro, $filtroLimite);
													$contReg = 1;

													while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){

														$consultaAcudientes = mysqli_query($conexion, "SELECT * FROM usuarios 
														WHERE uss_id='".$resultado["mat_acudiente"]."'");
														$acudiente = mysqli_fetch_array($consultaAcudientes, MYSQLI_BOTH);

														$bgColor = $resultado['uss_bloqueado'] == 1 ? '#ff572238' : '';

														$color = $resultado["mat_inclusion"] == 1 ? 'blue' : '';

														$nombreAcudiente = '';
														if (isset($acudiente[0])) {
															$nombreAcudiente = strtoupper($acudiente[4].' '.$acudiente["uss_nombre2"].' '.$acudiente["uss_apellido1"].' '.$acudiente["uss_apellido2"]); 
															$idAcudiente = $acudiente['uss_id'];
														}

														$miArray = [
															'id_estudiante'    => $resultado['mat_id'], 
															'estado_matricula' => $resultado['mat_estado_matricula']
														];
														$dataParaJavascript = json_encode($miArray);
													?>
													<tr style="background-color:<?=$bgColor;?>;">
														<td>
															<?php if($resultado["mat_compromiso"]==1){?>
																<a href="estudiantes-activar.php?id=<?=$resultado["mat_id"];?>" title="Activar para la matricula" onClick="if(!confirm('Esta seguro de ejecutar esta acción?')){return false;}"><img src="../files/iconos/agt_action_success.png" height="20" width="20"></a>
															<?php }else{?>
																<a href="estudiantes-bloquear.php?id=<?=$resultado["mat_id"];?>" title="Bloquear para la matricula" onClick="if(!confirm('Esta seguro de ejecutar esta acción?')){return false;}"><img src="../files/iconos/msn_blocked.png" height="20" width="20"></a>
															<?php }?>
															<?=$resultado["mat_id"];?>
														</td>
                                        				
														<td>
															<a style="cursor: pointer;" id="estadoMatricula<?=$resultado['mat_id'];?>" 
															onclick='cambiarEstadoMatricula(<?=$dataParaJavascript;?>)'
															>
																<span class="<?=$estadosEtiquetasMatriculas[$resultado['mat_estado_matricula']];?>">
																	<?=$estadosMatriculasEstudiantes[$resultado['mat_estado_matricula']];?>
																</span>
															</a>
														</td>
														<td><?=$resultado['mat_documento'];?></td>
														<?php $nombre = Estudiantes::NombreCompletoDelEstudiante($resultado);?>
														
														<td style="color:<?=$color;?>;"><?=$nombre;?></td>
														<td><?=strtoupper($resultado['gra_nombre']." ".$resultado['gru_nombre']);?></td>
														<td><?=$resultado['uss_usuario'];?></td>
														<td><a href="usuarios-editar.php?id=<?=$idAcudiente;?>" style="text-decoration:underline;" target="_blank"><?=$nombreAcudiente;?></a>
														<?php if(!empty($acudiente['uss_id']) && !empty($nombreAcudiente)){?>
															<br><a href="mensajes-redactar.php?destino=<?=$acudiente[0];?>" style="text-decoration:underline; color:blue;">Enviar mensaje</a>
														<?php }?>
														</td>

														<td>
															<div class="btn-group">
																<button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual[8]];?></button>
																<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																	<i class="fa fa-angle-down"></i>
																</button>
																<ul class="dropdown-menu" role="menu" style="z-index: 10000;">
																	<li><a href="estudiantes-editar.php?id=<?=$resultado['mat_id'];?>"><?=$frases[165][$datosUsuarioActual[8]];?></a></li>
																	<?php if($config['conf_id_institucion']==1){ ?>
																		<li><a href="estudiantes-crear-sion.php?id=<?=$resultado["mat_id"];?>" onClick="if(!confirm('Esta seguro que desea transferir este estudiante a SION?')){return false;}">Transferir a SION</a></li>
																	<?php } ?>

																	
																	<li><a href="guardar.php?get=17&idR=<?=$resultado['mat_id_usuario'];?>&lock=<?=$resultado['uss_bloqueado'];?>">Bloquear/Desbloquear</a></li>
																	<li><a href="aspectos-estudiantiles.php?idR=<?=$resultado['mat_id_usuario'];?>">Ficha estudiantil</a></li>
																	<li><a href="estudiantes-cambiar-grupo.php?id=<?=$resultado["mat_id"];?>" target="_blank">Cambiar de grupo</a></li>
																	<?php 
																	$retirarRestaurar='Retirar';
																	  if($resultado['mat_estado_matricula']==3){
																		    $retirarRestaurar='Restaurar';
																	}
																	?>
																	<li><a href="estudiantes-retirar.php?id=<?=$resultado["mat_id"];?>" target="_blank"><?=$retirarRestaurar?></a></li>
																	<li><a href="../compartido/matricula-boletin-curso-<?=$resultado['gra_formato_boletin'];?>.php?id=<?=$resultado["mat_id"];?>&periodo=<?=$config[2];?>" target="_blank">Boletín</a></li>
																	<li><a href="../compartido/matricula-libro.php?id=<?=$resultado["mat_id"];?>&periodo=<?=$config[2];?>" target="_blank">Libro Final</a></li>
																	<li><a href="estudiantes-reservar-cupo.php?idEstudiante=<?=$resultado["mat_id"];?>" onClick="if(!confirm('Esta seguro que desea reservar el cupo para este estudiante?')){return false;}">Reservar cupo</a></li>
																	<li><a href="../compartido/matriculas-formato3.php?ref=<?=$resultado["mat_matricula"];?>" target="_blank">Hoja de matrícula</a></li>
																	<li><a href="../compartido/informe-parcial.php?estudiante=<?=$resultado["mat_id"];?>" target="_blank">Informe parcial</a></li>
																	<?php if($config['conf_id_institucion']==1){ ?>	
																		<li><a href="http://sion.icolven.edu.co/Services/ServiceIcolven.svc/GenerarEstadoCuenta/<?=$resultado['mat_codigo_tesoreria'];?>/<?=date('Y');?>" target="_blank">SION - Estado de cuenta</a></li>
																	<?php }?>
																	<li><a href="finanzas-cuentas.php?id=<?=$resultado["mat_id_usuario"];?>" target="_blank">Estado de cuenta</a></li>
																	<li><a href="reportes-lista.php?est=<?=$resultado["mat_id_usuario"];?>" target="_blank">Disciplina</a></li>
																	<li><a href="estudiantes-eliminar.php?idE=<?=$resultado["mat_id"];?>&idU=<?=$resultado["mat_id_usuario"];?>" target="_blank" onClick="if(!confirm('Esta seguro de ejecutar esta acción?')){return false;}">Eliminar</a></li>
																	<li><a href="estudiantes-crear-usuario-estudiante.php?id=<?=$resultado["mat_id"];?>" target="_blank" onClick="if(!confirm('Esta seguro de ejecutar esta acción?')){return false;}">Generar usuario</a></li>
																	<li><a href="auto-login.php?user=<?=$resultado['mat_id_usuario'];?>&tipe=4">Autologin</a></li>
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