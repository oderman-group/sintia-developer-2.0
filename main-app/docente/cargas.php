<?php
include("session.php");
$idPaginaInterna = 'DC0033';
include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");
require_once("../class/UsuariosPadre.php");
require_once("../class/Estudiantes.php");
require_once("../class/Sysjobs.php");

try{
	$config = Plataforma::sesionConfiguracion();
	$_SESSION["configuracion"] = $config;
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
?>
</head>
<style>
	.alert-warning-select {
    color: #4f3e0d;
    background-color: #f5c426;
    border-color: #ffeeba;
}
</style>
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
                   
                   
                     <!-- start course list -->
                     <div class="row">
						 
						 <div class="col-sm-12">
						 <?php include("../../config-general/mensajes-informativos.php"); ?>

							 <?php
							 $cCargas = mysqli_query($conexion, "SELECT * FROM academico_cargas 
							 INNER JOIN academico_materias ON mat_id=car_materia
							 INNER JOIN academico_grados ON gra_id=car_curso
							 INNER JOIN academico_grupos ON gru_id=car_grupo
							 WHERE car_docente='".$_SESSION["id"]."'
							 ORDER BY car_posicion_docente, car_curso, car_grupo, mat_nombre
							 ");
							  $cargasCont = 1;
							 $nCargas = mysqli_num_rows($cCargas);
							 $mensajeCargas = new Cargas;
							 $mensajeCargas->verificarNumCargas($nCargas);
							 if ($nCargas > 0) {
							 ?>
								
							 <p>
								 	<a href="../compartido/planilla-docentes.php?docente=<?=base64_encode($_SESSION["id"]);?>" target="_blank" style="text-decoration: underline;">Imprimir todas mis planillas</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									 <a href="../compartido/planilla-docentes-notas.php?docente=<?=base64_encode($_SESSION["id"]);?>" target="_blank" style="text-decoration: underline;">Imprimir planillas con resumen de notas</a>
									 &nbsp;&nbsp;|&nbsp;&nbsp;
									 <a href="cargas-general.php" style="text-decoration: underline;">Ir a vista general</a>
							 </p>
							 <?php }?>
							 <div class="row">
								 
								 
								 
									<?php
									while($rCargas = mysqli_fetch_array($cCargas, MYSQLI_BOTH)){
									    $ultimoAcceso = 'Nunca';
										$fondoCargaActual = '#FFF';

										if(!empty($rCargas['car_ultimo_acceso_docente'])){$ultimoAcceso = $rCargas['car_ultimo_acceso_docente'];}
										if(!empty($_COOKIE["carga"]) && $rCargas[0]==$_COOKIE["carga"]){$fondoCargaActual = 'cornsilk';}
										
										$cargaSP = $rCargas["car_id"];
										$periodoSP = $rCargas["car_periodo"];
										include("../suma-porcentajes.php");
										
										if($rCargas["car_periodo"]>$rCargas["gra_periodos"]){
											$mensajeI = "<span style='color:blue;'>Terminado</span>";
										  }else{
											  if($spcr[0]<96){
													$mensajeI = $spcr[0];
											  }elseif($rCargas["car_permiso1"]==0){
												$mensajeI = 'Sin permiso para generar';
											  }else{
												$parametros = array(
													"carga" =>$rCargas["car_id"],
													"periodo" =>$rCargas["car_periodo"],
													"grado" => $rCargas["car_curso"],
													"grupo"=>$rCargas["car_grupo"]
												);
												
												$parametrosBuscar = array(
													"tipo" =>JOBS_TIPO_GENERAR_INFORMES,
													"responsable" => $_SESSION['id'],
													"parametros" => json_encode($parametros),
													"agno"=>$config['conf_agno']
												);
												$buscarJobs=SysJobs::consultar($parametrosBuscar);
												$jobsEncontrado = mysqli_fetch_array($buscarJobs, MYSQLI_BOTH);

												$btnGenerarInforme='
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn red">Generar Informe</button>
                                                                    <button type="button" class="btn red dropdown-toggle m-r-20" data-toggle="dropdown">
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu" role="menu">
                                                                        <li><a title="Se generará el informe de forma inmediata" href="../compartido/generar-informe.php?carga='.base64_encode($rCargas["car_id"]).'&periodo='.base64_encode($rCargas["car_periodo"]).'&grado='.base64_encode($rCargas["car_curso"]).'&grupo='.base64_encode($rCargas["car_grupo"]).'">Forma tradicional</a></li>
                                                                        <li><a title="Se programara la generación de informe y se te notificará cuando esté listo" id="'.$rCargas["car_id"].'" href="javascript:void(0);" name="../compartido/job-generar-informe.php?carga='.base64_encode($rCargas["car_id"]).'&periodo='.base64_encode($rCargas["car_periodo"]).'&grado='.base64_encode($rCargas["car_curso"]).'&grupo='.base64_encode($rCargas["car_grupo"]).'" onclick="mensajeGenerarInforme(this)">Forma nueva</a></li>
                                                                    </ul>
                                                                </div>
															';
												
												if(empty($jobsEncontrado)){
													$mensajeI = $btnGenerarInforme;
													if($config['conf_porcentaje_completo_generar_informe']==1){
														$consultaListaEstudantesSinNotas =Estudiantes::listarEstudiantesNotasFaltantes($rCargas["car_id"],$rCargas["car_periodo"]);
														$numSinNotas=mysqli_num_rows($consultaListaEstudantesSinNotas);
														if($numSinNotas>0){
															$mensajeI = '<div class="alert alert-danger" role="alert" style="margin-right: 20px;">
																			<a target="_blank" href="calificaciones-faltantes.php?carga='.base64_encode($rCargas["car_id"]).'&periodo='.base64_encode($rCargas["car_periodo"]).'&get='.base64_encode(100).'">El informe no se puede generar, coloque las notas a todos los estudiantes para generar el informe.</a>
																		</div>';
														}
													}
												}else{
													$intento = intval($jobsEncontrado["job_intentos"]);
													switch($jobsEncontrado["job_estado"]){
														case JOBS_ESTADO_ERROR:														
															$mensajeI = $btnGenerarInforme
																	.'<div class="alert alert-danger" role="alert" style="margin-right: 20px;">'.$jobsEncontrado["job_mensaje"].'</div>';
																	break;
														case JOBS_ESTADO_PENDIENTE && $intento==0:
															$mensajeI ='<div class="alert alert-success" role="alert" style="margin-right: 20px;">'.$jobsEncontrado["job_mensaje"].'</div>';
															break;
														case JOBS_ESTADO_PENDIENTE && $intento>0 &&  $fondoCargaActual=="#FFF":
															$mensajeI ='<div class="alert alert-warning" role="alert" style="margin-right: 20px;">'.$jobsEncontrado["job_mensaje"].'</div>';
															break;
														case JOBS_ESTADO_PENDIENTE && $intento>0 :
															$mensajeI ='<div class="alert alert-warning-select" role="alert" style="margin-right: 20px;">'.$jobsEncontrado["job_mensaje"].'</div>';
															break;
														
													}
												}
												
											  }	
										}
										
										$induccionEntrar = '';
										$induccionSabanas = '';
										if($cargasCont == 1){
											$induccionEntrar = 'data-hint="Haciendo click sobre el nombre o sobre la imagen puedes entrar a administrar esta carga académica."';
											$induccionSabanas = 'data-hint="Puedes ver las sábanas de cada uno de los periodos pasados."';
										}
									?>
						 <div class="col-lg-3 col-md-6 col-12 col-sm-6"> 
							<div class="blogThumb" style="background-color:<?=$fondoCargaActual;?>;">
								<div class="thumb-center">
									<a href="guardar.php?carga=<?=base64_encode($rCargas['car_id']);?>&periodo=<?=base64_encode($rCargas['car_periodo']);?>&get=<?=base64_encode(100);?>" title="Entrar">
										<img class="img-responsive" alt="user" src="../../config-general/assets/img/course/course1.jpg">
									</a>	
								</div>
	                        	<div class="course-box">
	                        	<h5 <?=$induccionEntrar;?>><a href="guardar.php?carga=<?=base64_encode($rCargas['car_id']);?>&periodo=<?=base64_encode($rCargas['car_periodo']);?>&get=<?=base64_encode(100);?>" title="Entrar" style="text-decoration: underline;"><?="[".$rCargas['car_id']."] ".strtoupper($rCargas['mat_nombre']);?></a></h5>
		                            
									<p>
										<span> <b><?=$frases[164][$datosUsuarioActual[8]];?>:</b> <?=strtoupper($rCargas['gra_nombre']." ".$rCargas['gru_nombre']);?></span>
									</p>
									
									
									<p align="center" <?=$induccionSabanas;?>>
                                      	<?php for($i=1; $i<$rCargas["car_periodo"]; $i++){?><a href="../compartido/informes-generales-sabanas.php?curso=<?=base64_encode($rCargas["car_curso"]);?>&grupo=<?=base64_encode($rCargas["car_grupo"]);?>&per=<?=base64_encode($i);?>" target="_blank" style="text-decoration:underline; color:#00F;" title="Sabanas"><?=$i;?></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php }?>
                                    </p>
									
		                            
									
									<div class="text">
										<span class="m-r-10" style="font-size: 10px;"><b>Notas:</b> <?=$spcd[0];?>% / <?=$spcr[0];?>% | <b>Periodo:</b> <?=$rCargas['car_periodo'];?> | <b>Posición:</b> <?=$rCargas['car_posicion_docente'];?></span> 

		                            	<?php if($rCargas['car_director_grupo']==1){?><br><a class="course-likes m-l-10" style="color: slateblue;"><i class="fa fa-user-circle-o"></i> Director de grupo</a><?php }?>
		                            </div>
									
									<span id="mensajeI<?=$rCargas['car_id']?>"><?=$mensajeI;?></span>
									
	                        	</div>
	                        </div>	
                    	</div>
						 <?php 
								$cargasCont ++;
							}
						 ?>
						
							 </div>
						</div>		 
	                    
			        </div>
					<script>
						function mensajeGenerarInforme(datos){
							var id = datos.id;
    						var url = datos.name;
    						var contenedorMensaje = document.getElementById('mensajeI'+id);
							var nuevoContenido = '<div class="alert alert-success" role="alert" style="margin-right: 20px;">La petición de generación de informe se envió correctamente.</div>';

							axios.get(url).then(function(response) {
									contenedorMensaje.innerHTML = nuevoContenido;

									$.toast({
										heading: 'Acción realizada',
										text: 'La petición de generación de informe se envió correctamente.',
										position: 'botom-left',
										loaderBg: '#26c281',
										icon: 'success',
										hideAfter: 5000,
										stack: 6
									});

							}).catch(function(error) {
								// handle error
								console.error(error);
								window.location.href = url;
							});
						}
					</script>
					
					<div class="row">
						 
						 <div class="col-sm-12">
						 	<?php include("../compartido/progreso-docentes.php");?>
						 </div>
						
					</div>
					
			        <!-- End course list -->
			        
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
    <script src="../../config-general/assets/plugins/sparkline/jquery.sparkline.js" ></script>
	<script src="../../config-general/assets/js/pages/sparkline/sparkline-data.js" ></script>
    <!-- Common js-->
	<script src="../../config-general/assets/js/app.js" ></script>
    <script src="../../config-general/assets/js/layout.js" ></script>
    <script src="../../config-general/assets/js/theme-color.js" ></script>
	<!-- notifications -->
	<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>
    <!-- material -->
    <script src="../../config-general/assets/plugins/material/material.min.js"></script>
    <!-- chart js -->
    <script src="../../config-general/assets/plugins/chart-js/Chart.bundle.js" ></script>
    <script src="../../config-general/assets/plugins/chart-js/utils.js" ></script>
    <script src="../../config-general/assets/js/pages/chart/chartjs/home-data.js" ></script>
    <!-- summernote -->
    <script src="../../config-general/assets/plugins/summernote/summernote.js" ></script>
    <script src="../../config-general/assets/js/pages/summernote/summernote-data.js" ></script>
    <!-- end js include path -->
  </body>

</html>