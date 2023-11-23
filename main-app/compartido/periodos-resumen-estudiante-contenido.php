<?php
if(($datosUsuarioActual['uss_tipo']==3 or $datosUsuarioActual['uss_tipo']==4) and $config['conf_sin_nota_numerica']==1){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=218";</script>';
	exit();
}
require_once(ROOT_PATH."/main-app/class/Boletin.php");
?>
<?php require_once("../class/servicios/MediaTecnicaServicios.php"); ?>
<div class="page-content">
                    <div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class=" pull-left">
                                <div class="page-title"><?=$frases[84][$datosUsuarioActual['uss_idioma']];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                
								<div class="col-md-4 col-lg-3">
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple"><?=$frases[119][$datosUsuarioActual['uss_idioma']];?> </header>
                                        <div class="panel-body">
												<p>
													<b><?=$frases[117][$datosUsuarioActual['uss_idioma']];?>:</b> <?=$frases[120][$datosUsuarioActual['uss_idioma']];?>
												</p>
											
												<p>
													<b><?=$frases[118][$datosUsuarioActual['uss_idioma']];?>:</b> <?=$frases[121][$datosUsuarioActual['uss_idioma']];?>
												</p>
										</div>
									</div>
									
									<?php
									if($datosUsuarioActual['uss_tipo']!=4){
									?>

									<div class="panel">
											<header class="panel-heading panel-heading-yellow"><?=strtoupper($frases[283][$datosUsuarioActual['uss_idioma']]);?></header>

											<div class="panel-body">
												<ul class="list-group list-group-unbordered">
													<li class="list-group-item">
														<b><?=strtoupper($frases[61][$datosUsuarioActual['uss_idioma']]);?></b> 
														<div class="profile-desc-item pull-right"><?=Estudiantes::NombreCompletoDelEstudiante($datosEstudianteActual);?></div>
													</li>
													
												</ul>

											</div>
										</div>
									<?php }?>

									<?php include("../compartido/publicidad-lateral.php");?>
									
								</div>
									
								<div class="col-md-8 col-lg-9">
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header><?=$frases[84][$datosUsuarioActual['uss_idioma']];?></header>
                                            <div class="tools">
                                                <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
			                                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
			                                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                            </div>
                                        </div>
                                        <div class="card-body ">
                                        <div class="table-responsive">
                                            <table class="table table-striped custom-table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
														<th style="text-align:center;"><?=$frases[49][$datosUsuarioActual['uss_idioma']];?></th>
														<th style="text-align:center;"><?=$frases[116][$datosUsuarioActual['uss_idioma']];?></th>
														<?php
															$p = 1;
															while($p<=$datosEstudianteActual['gra_periodos']){
																$consultaPeriodosCursos=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados_periodos
																WHERE gvp_grado='".$datosEstudianteActual['mat_grado']."' AND gvp_periodo='".$p."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
																");
																$periodosCursos = mysqli_fetch_array($consultaPeriodosCursos, MYSQLI_BOTH);
																$numPeriodosCursos=mysqli_num_rows($consultaPeriodosCursos);
																$porcentaje=25;
																if($numPeriodosCursos>0){
																	$porcentaje=$periodosCursos['gvp_valor'];
																}
																echo '<th style="text-align:center;">'.$p.'P<br>('.$porcentaje.'%)</th>';
																$p++;
															}
														?> 
														<th style="text-align:center;"><?=$frases[117][$datosUsuarioActual['uss_idioma']];?></th>
														<?php if($datosUsuarioActual['uss_tipo']!=3 and $datosUsuarioActual['uss_tipo']!=4){?>
															<th style="text-align:center;"><?=$frases[118][$datosUsuarioActual['uss_idioma']];?></th>
														<?php }?>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													$contReg = 1;
													$parametros = ['matcur_id_matricula' => $datosEstudianteActual["mat_id"]];
													$listaCursosMediaTecnica = MediaTecnicaServicios::listar($parametros);
													$filtroOr='';
													if ($listaCursosMediaTecnica != null) { 
														foreach ($listaCursosMediaTecnica as $dato) {
															$filtroOr=$filtroOr.' OR (car_curso='.$dato["matcur_id_curso"].' AND car_grupo='.$dato["matcur_id_grupo"].')';
														}
													}
													$cCargas = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas WHERE (car_curso='".$datosEstudianteActual['mat_grado']."' AND car_grupo='".$datosEstudianteActual['mat_grupo']."') AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} ".$filtroOr);
													while($rCargas = mysqli_fetch_array($cCargas, MYSQLI_BOTH)){
														$cDatos = mysqli_query($conexion, "SELECT mat_id, mat_nombre, gra_codigo, gra_nombre, uss_id, uss_nombre FROM ".BD_ACADEMICA.".academico_materias am, ".BD_ACADEMICA.".academico_grados gra, ".BD_GENERAL.".usuarios uss WHERE am.mat_id='".$rCargas['car_materia']."' AND gra_id='".$rCargas['car_curso']."' AND uss_id='".$rCargas['car_docente']."' AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]} AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]} AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}");
														$rDatos = mysqli_fetch_array($cDatos, MYSQLI_BOTH);
													?>
                                                    
													<tr>
                                                        <td style="text-align:center;"><?=$contReg;?></td>
														<td style="text-align:center;"><?=$rCargas['car_id'];?></td>
														<td><?=$rDatos[1];?></td>

														<?php
														 $definitiva = 0;
														 $sumatoria = 0;
														 $decimal = 0;
														 $sumaPorcentaje = 0;
														 $n = 0;
														 for($i=1; $i<=$datosEstudianteActual['gra_periodos']; $i++){
															
															$consultaPeriodosCursos=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados_periodos
															WHERE gvp_grado='".$datosEstudianteActual['mat_grado']."' AND gvp_periodo='".$p."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
															");
															$periodosCursos = mysqli_fetch_array($consultaPeriodosCursos, MYSQLI_BOTH);
															$numPeriodosCursos=mysqli_num_rows($consultaPeriodosCursos);
															$porcentaje=25;
															if($numPeriodosCursos>0){
																$porcentaje=$periodosCursos['gvp_valor'];
															}
															 $decimal = $porcentaje/100;
															 
															//LAS CALIFICACIONES
															$notasConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='".$datosEstudianteActual['mat_id']."' AND bol_carga='".$rCargas['car_id']."' AND bol_periodo='".$i."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
															$notasResultado = mysqli_fetch_array($notasConsulta, MYSQLI_BOTH);
															$numN = mysqli_num_rows($notasConsulta);
															if($numN){
																$n++;
																$definitiva += $notasResultado['bol_nota']*$decimal;
																$sumaPorcentaje += $decimal;
															}
															if(!empty($notasResultado['bol_nota']) && $notasResultado['bol_nota']<$config[5])$color = $config[6]; elseif(!empty($notasResultado['bol_nota']) && $notasResultado['bol_nota']>=$config[5]) $color = $config[7];
															if(!empty($notasResultado['bol_tipo']) && $notasResultado['bol_tipo']==2) $tipo = '<span style="color:red; font-size:9px;">'.$frases[123][$datosUsuarioActual['uss_idioma']].'</span>'; elseif(!empty($notasResultado['bol_tipo']) && $notasResultado['bol_tipo']==1) $tipo = '<span style="color:blue; font-size:9px;">'.$frases[122][$datosUsuarioActual['uss_idioma']].'</span>'; else $tipo='';
															$usrEstud="";
															if(!empty($_GET["usrEstud"])){ $usrEstud=base64_decode($_GET["usrEstud"]);}

															$notaFinal="";
															if(!empty($notasResultado['bol_nota'])) {
																$notaFinal=$notasResultado['bol_nota'];
																if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
																	$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notasResultado['bol_nota']);
																	$notaFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
																}
															}

														?>
															<td style="text-align:center;">
																<a href="calificaciones.php?carga=<?=base64_encode($rCargas['car_id']);?>&periodo=<?=base64_encode($i);?>&usrEstud=<?=base64_encode($usrEstud);?>" style="color:<?=$color;?>; text-decoration:underline;"><?=$notaFinal."<br>".$tipo;?></a>
															</td>
														<?php		
														 }
														 	
															if(!empty($sumaPorcentaje)){
																$definitiva = ($definitiva / $sumaPorcentaje);
															}
															$consultaN = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_cod_estudiante=".$datosEstudianteActual['mat_id']." AND niv_id_asg='".$rCargas['car_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
															
															$numN = mysqli_num_rows($consultaN);
															$rN = mysqli_fetch_array($consultaN, MYSQLI_BOTH);
															if($numN==0){
																if($n>0)
																	$definitiva = round(($definitiva), $config['conf_decimales_notas']);
																	$tN = '<span style="color:blue; font-size:9px;">'.$frases[122][$datosUsuarioActual['uss_idioma']].'</span>';
															}else{
																$definitiva = $rN['niv_definitiva'];
																$tN = '<span style="color:red; font-size:9px;">'.$frases[124][$datosUsuarioActual['uss_idioma']].'</span>';
															}
														 if($definitiva<$config[5])$color = $config[6]; elseif($definitiva>=$config[5]) $color = $config[7];
														 
														 //CALCULAR NOTA MINIMA EN EL ULTIMO PERIODO PARA APROBAR LA MATERIA
														 //PREGUNTAMOS SI ESTAMOS EN EL PERIODO PENULTIMO O ULTIMO
														 if($config[2]==$datosEstudianteActual['gra_periodos']){
															 $notaMinima = ($config[5]-$definitiva);
															 $periodosCursos2 = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados_periodos
															 WHERE gvp_grado='".$datosEstudianteActual['mat_grado']."' AND gvp_periodo='".$datosEstudianteActual['gra_periodos']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
															 "), MYSQLI_BOTH);
															 $decimal2 = $periodosCursos2['gvp_valor']/100;
															
															if(!empty($decimal2)){ 
																$notaMinima = round(($notaMinima / $decimal2), $config['conf_decimales_notas']);
															}

															if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
																$estiloNotaRecuperacion = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaMinima);
																$notaMinima= !empty($estiloNotaRecuperacion['notip_nombre']) ? $estiloNotaRecuperacion['notip_nombre'] : "";
															}
															 
															 if($notaMinima<=0){
																$notaMinima = "-";
																$colorFaltante = "green";
															 }else{
																if($notaMinima<=$config[4]) $colorFaltante = "blue"; else $colorFaltante = "red"; 
															 }
														 }else{
															$notaMinima = "-";
															$colorFaltante = "black";
														}
														?>

														<td style="text-align:center; color:<?=$colorFaltante;?>; font-weight:bold;"><?=$notaMinima;?></td>
														
														<?php if($datosUsuarioActual['uss_tipo']!=3 and $datosUsuarioActual['uss_tipo']!=4){?>
															<td style="text-align:center; color:<?=$color;?>;">
																<?=$definitiva."<br>".$tN;?>
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
								
							
                            </div>
                        </div>
                    </div>
                </div>