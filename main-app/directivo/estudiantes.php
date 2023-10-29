<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0001';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php
require_once("../class/Estudiantes.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
$jQueryTable = '';
if($config['conf_id_institucion'] != ICOLVEN && $config['conf_id_institucion'] != DEVELOPER && $config['conf_id_institucion'] != DEVELOPER_PROD) {
	$jQueryTable = 'id="example1"';
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
									if($config['conf_id_institucion'] == ICOLVEN){
										if(isset($_GET['msgsion'])){
											$aler='alert-danger';
											$mensajeSion='Por favor, verifique todos los datos del estudiante y llene los campos vacios.';
											if($_GET['msgsion']!=''){
												$aler='alert-success';
												$mensajeSion=base64_decode($_GET['msgsion']);
												if(base64_decode($_GET['stadsion'])!=true){
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
														<?php if(Modulos::validarPermisoEdicion()){?>
															<a href="estudiantes-agregar.php" id="addRow" class="btn deepPink-bgcolor">
																Agregar nuevo <i class="fa fa-plus"></i>
															</a>
														<?php }?>
													</div>
												</div>
											</div>
											
                                        <div>
											
                                    		<table <?php echo $jQueryTable;?> class="display" style="width:100%;">
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
													<?php
													include("includes/consulta-paginacion-estudiantes.php");
													$filtroLimite = 'LIMIT '.$inicio.','.$registros;
													$consulta = Estudiantes::listarEstudiantes(0, $filtro, $filtroLimite);
													$contReg = 1;

													while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){

														try{
															$consultaAcudientes = mysqli_query($conexion, "SELECT * FROM usuarios 
															WHERE uss_id='".$resultado["mat_acudiente"]."'");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														$acudiente = mysqli_fetch_array($consultaAcudientes, MYSQLI_BOTH);

														$bgColor = $resultado['uss_bloqueado'] == 1 ? '#ff572238' : '';

														$color = $resultado["mat_inclusion"] == 1 ? 'blue' : '';

														$nombreAcudiente = '';
														if (isset($acudiente[0])) {
															$nombreAcudiente = UsuariosPadre::nombreCompletoDelUsuario($acudiente); 
															$idAcudiente = $acudiente['uss_id'];
														}

														$miArray = [
															'id_estudiante'    => $resultado['mat_id'], 
															'estado_matricula' => $resultado['mat_estado_matricula'],
															'bloqueado' 	   => $resultado['uss_bloqueado'],
															'id_usuario'       => $resultado['uss_id'],
														];
														$dataParaJavascript = json_encode($miArray);

														$fotoEstudiante = $usuariosClase->verificarFoto($resultado['mat_foto']);

														$infoTooltipEstudiante = "
														<p>
															<img src='{$fotoEstudiante}' class='img-thumbnail' width='120px;' height='120px;'>
														</p>
														<b>Fecha de matrícula:</b><br>
														{$resultado['mat_fecha']}<br>
														<b>Teléfono:</b><br>
														{$resultado['mat_telefono']}<br>
														<b>Documento:</b><br>
														{$resultado['mat_documento']}<br>
														<b>Email:</b><br>
														{$resultado['mat_email']}<br>
														<b>Fecha de nacimiento:</b><br>
														{$resultado['mat_fecha_nacimiento']}
														";
													?>
													<tr id="EST<?=$resultado['mat_id'];?>" style="background-color:<?=$bgColor;?>;">
														<td>
															<?php if($resultado["mat_compromiso"]==1){?>
																<a href="javascript:void(0);" title="Activar para la matricula"
																 onClick="sweetConfirmacion('Alerta!','Deseas ejecutar esta accion?','question','estudiantes-activar.php?id=<?=base64_encode($resultado["mat_id"]);?>')"
																 ><img src="../files/iconos/agt_action_success.png" height="20" width="20"></a>
															<?php }else{?>
																<a href="javascript:void(0);" title="Bloquear para la matricula" 
																onClick="sweetConfirmacion('Alerta!','Deseas ejecutar esta accion?','question','estudiantes-bloquear.php?id=<?=base64_encode($resultado["mat_id"]);?>')"
																><img src="../files/iconos/msn_blocked.png" height="20" width="20"></a>
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
														
														<td style="color:<?=$color;?>;"><a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="<?=Estudiantes::NombreCompletoDelEstudiante($resultado);?>" data-content="<?=$infoTooltipEstudiante;?>" data-html="true" data-placement="top" style="border-bottom: 1px dotted #000;"><?=$nombre;?></a></td>
														<td><?=strtoupper($resultado['gra_nombre']." ".$resultado['gru_nombre']);?></td>
														<td><?=$resultado['uss_usuario'];?></td>
														<td><a href="usuarios-editar.php?id=<?=base64_encode($idAcudiente);?>" style="text-decoration:underline;" target="_blank"><?=$nombreAcudiente;?></a>
														<?php if(!empty($acudiente['uss_id']) && !empty($nombreAcudiente)){?>
															<br><a href="mensajes-redactar.php?para=<?=base64_encode($acudiente[0]);?>" style="text-decoration:underline; color:blue;">Enviar mensaje</a>
														<?php }?>
														</td>

														<td>
															<div class="btn-group">
																<button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual[8]];?></button>
																<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																	<i class="fa fa-angle-down"></i>
																</button>
																<ul class="dropdown-menu" role="menu" style="z-index: 10000;">
																	<?php if(Modulos::validarPermisoEdicion()){?>
																		<li><a href="estudiantes-editar.php?id=<?=base64_encode($resultado['mat_id']);?>"><?=$frases[165][$datosUsuarioActual[8]];?> matrícula</a></li>
																		
																		<?php if($config['conf_id_institucion'] == ICOLVEN){ ?>
																			<li><a href="javascript:void(0);" 
																			onClick="sweetConfirmacion('Alerta!','Esta seguro que desea transferir este estudiante a SION?','question','estudiantes-crear-sion.php?id=<?=base64_encode($resultado['mat_id']);?>')"
																			>Transferir a SION</a></li>
																		<?php } ?>
																		
																		<?php if(!empty($resultado['uss_usuario'])) {?>
																			<li><a 
																			href="javascript:void(0);"
																			onclick='cambiarBloqueo(<?=$dataParaJavascript;?>)'
																			>Bloquear/Desbloquear</a></li>
																		<?php }?>

																		<?php if(!empty($resultado['uss_id'])) {?>
																			<li><a href="usuarios-editar.php?id=<?=base64_encode($resultado['uss_id']);?>"><?=$frases[165][$datosUsuarioActual[8]];?> usuario</a></li>
																		<?php }?>

																		
																		<?php if(!empty($resultado['gra_nombre'])){ ?>
																		<li><a href="javascript:void(0);"  data-toggle="modal" data-target="#cambiarGrupoModal<?=$resultado['mat_id'];?>" >Cambiar de grupo</a></li>
																		<?php } ?>
																		<?php 
																		$retirarRestaurar='Retirar';
																		if($resultado['mat_estado_matricula']==3){
																				$retirarRestaurar='Restaurar';
																		}
																		?>
																		<li><a href="javascript:void(0);"  data-toggle="modal" data-target="#retirarModal<?=$resultado['mat_id'];?>"><?=$retirarRestaurar?></a></li>
																		<li><a href="javascript:void(0);"
																		onClick="sweetConfirmacion('Alerta!','Esta seguro que desea reservar el cupo para este estudiante?','question','estudiantes-reservar-cupo.php?idEstudiante=<?=base64_encode($resultado['mat_id']);?>')" 
																		>Reservar cupo</a></li>
																		 <li><a href="javascript:void(0);" 
																		onClick="sweetConfirmacion('Alerta!','Esta seguro de ejecutar esta acción?','question','estudiantes-eliminar.php?idE=<?=base64_encode($resultado["mat_id"]);?>&idU=<?=base64_encode($resultado["mat_id_usuario"]);?>')"
																		>Eliminar</a></li>
																		
																		<li><a href="javascript:void(0);"  
																		onClick="sweetConfirmacion('Alerta!','Está seguro de ejecutar esta acción?','question','estudiantes-crear-usuario-estudiante.php?id=<?=base64_encode($resultado["mat_id"]);?>')"
																		>Generar usuario</a></li>

																		<?php if(!empty($resultado['uss_usuario'])) {?>
																			<li><a href="auto-login.php?user=<?=base64_encode($resultado['mat_id_usuario']);?>&tipe=<?=base64_encode(4)?>">Autologin</a></li>
																		<?php }?>

																	<?php }?>

																	<li><a href="aspectos-estudiantiles.php?idR=<?=base64_encode($resultado['mat_id_usuario']);?>">Ficha estudiantil</a></li>
																	<li><a href="../compartido/matricula-boletin-curso-<?=$resultado['gra_formato_boletin'];?>.php?id=<?=base64_encode($resultado["mat_id"]);?>&periodo=<?=base64_encode($config[2]);?>" target="_blank">Boletín</a></li>
																	<li><a href="../compartido/matricula-libro.php?id=<?=base64_encode($resultado["mat_id"]);?>&periodo=<?=base64_encode($config[2]);?>" target="_blank">Libro Final</a></li>
																	<li><a href="../compartido/matriculas-formato3.php?ref=<?=base64_encode($resultado["mat_matricula"]);?>" target="_blank">Hoja de matrícula</a></li>
																	<li><a href="../compartido/informe-parcial.php?estudiante=<?=base64_encode($resultado["mat_id"]);?>" target="_blank">Informe parcial</a></li>
																	
																	<?php if($config['conf_id_institucion'] == ICOLVEN){ ?>	
																		<li><a href="http://sion.icolven.edu.co/Services/ServiceIcolven.svc/GenerarEstadoCuenta/<?=$resultado['mat_codigo_tesoreria'];?>/<?=date('Y');?>" target="_blank">SION - Estado de cuenta</a></li>
																	<?php }?>

																	<?php if(!empty($resultado['uss_usuario'])) {?>
																		<li><a href="finanzas-cuentas.php?id=<?=base64_encode($resultado["mat_id_usuario"]);?>" target="_blank">Estado de cuenta</a></li>
																		<li><a href="reportes-lista.php?est=<?=base64_encode($resultado["mat_id_usuario"]);?>&filtros=<?=base64_encode(1);?>" target="_blank">Disciplina</a></li>
																	<?php }?>
																</ul>
															</div>
														</td>
                                                    </tr>
													<?php 
														  $_GET["id"]=base64_encode($resultado['mat_id']); 
													      if(!empty($resultado['gra_nombre'])){
															$idModal="cambiarGrupoModal".$resultado['mat_id'];															
															$contenido="../directivo/estudiantes-cambiar-grupo-modal.php"; 
															include("../compartido/contenido-modal.php");
														  }
														 
															$idModal="retirarModal".$resultado['mat_id'];															
															$contenido="../directivo/estudiantes-retirar-modal.php"; 
															include("../compartido/contenido-modal.php");
													     
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
		
		
		<?php $idModal="ModalSintia1"; $_GET["id"]=base64_encode(2803); $contenido="../compartido/noticias-agregar-modal.php"; include("../compartido/contenido-modal.php");?>
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