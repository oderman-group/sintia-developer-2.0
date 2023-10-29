<?php
	$usrEstud="";
	if(!empty($_GET["usrEstud"])){ $usrEstud=base64_decode($_GET["usrEstud"]);}
?>
<div class="page-content">
                    <div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class=" pull-left">
                                <div class="page-title"><?=$frases[242][$datosUsuarioActual['uss_idioma']];?></div>
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
												<p><b>P.A:</b> <?=$frases[284][$datosUsuarioActual['uss_idioma']];?></p>
										</div>
									</div>
									
									<?php
									if($datosUsuarioActual[3]!=4){
									?>

									<div class="panel">
											<header class="panel-heading panel-heading-yellow"><?=strtoupper($frases[283][$datosUsuarioActual['uss_idioma']]);?></header>

											<div class="panel-body">
												<ul class="list-group list-group-unbordered">
													<li class="list-group-item">
														<b><?=strtoupper($frases[61][$datosUsuarioActual['uss_idioma']]);?></b> 
														<div class="profile-desc-item pull-right"><?=strtoupper($datosEstudianteActual[3]." ".$datosEstudianteActual[4]." ".$datosEstudianteActual[5]);?></div>
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
                                            <header><?=$frases[242][$datosUsuarioActual['uss_idioma']];?></header>
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
														<th><?=$frases[116][$datosUsuarioActual['uss_idioma']];?></th>
														<th style="text-align:center;">P.A</th>
														<th style="text-align:center;"><?=$frases[118][$datosUsuarioActual['uss_idioma']];?></th>
														<th style="text-align:center;"><?=$frases[285][$datosUsuarioActual['uss_idioma']];?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													$contReg = 1; 
													$cCargas = mysqli_query($conexion, "SELECT * FROM academico_cargas 
													WHERE car_curso='".$datosEstudianteActual[6]."' AND car_grupo='".$datosEstudianteActual[7]."'");
													while($rCargas = mysqli_fetch_array($cCargas, MYSQLI_BOTH)){
														$cDatos = mysqli_query($conexion, "SELECT mat_id, mat_nombre, gra_codigo, gra_nombre, uss_id, uss_nombre FROM academico_materias, academico_grados, usuarios WHERE mat_id='".$rCargas[4]."' AND gra_id='".$rCargas[2]."' AND uss_id='".$rCargas[1]."'");
														$rDatos = mysqli_fetch_array($cDatos, MYSQLI_BOTH);
														
														//DEFINITIVAS
														$carga = $rCargas[0];
														$periodo = $rCargas[5];
														$estudiante = $datosEstudianteActual['mat_id'];
														include("../definitivas.php");
														if($definitiva<$config[5] and $definitiva!="") $colorNota = $config[6]; elseif($definitiva>=$config[5]) $colorNota = $config[7]; else {$colorNota = 'black'; $definitiva='';}
													?>
                                                    
													<tr>
                                                        <td style="text-align:center;"><?=$contReg;?></td>
														<td style="text-align:center;"><?=$rCargas[0];?></td>
														<td><?=$rDatos[1];?></td>
														<td style="text-align:center;"><?=$rCargas[5];?></td>
														
														<?php if($config['conf_sin_nota_numerica']!=1){?>
														<td style="text-align:center;">
															<a href="calificaciones.php?carga=<?=base64_encode($rCargas[0]);?>&periodo=<?=base64_encode($rCargas[5]);?>&usrEstud=<?=base64_encode($usrEstud);?>" style="color:<?=$colorNota;?>; text-decoration:underline;"><?=$definitiva;?></a>
														</td>
														<?php }else{?>
														<td style="text-align:center;">
															<a href="calificaciones.php?carga=<?=base64_encode($rCargas[0]);?>&periodo=<?=base64_encode($rCargas[5]);?>&usrEstud=<?=base64_encode($usrEstud);?>" style="text-decoration:underline;"><?=$frases[39][$datosUsuarioActual['uss_idioma']];?></a>
														</td>
														<?php }?>
														
														<td style="text-align:center;">
															<div class="btn-group">
																	  <button type="button" class="btn btn-danger"><?=$frases[88][$datosUsuarioActual[8]];?></button>
																	  <button type="button" class="btn btn-danger dropdown-toggle m-r-20" data-toggle="dropdown">
																		  <i class="fa fa-angle-down"></i>
																	  </button>
																	  <ul class="dropdown-menu" role="menu">
																		  <li><a href="cronograma-actividades.php?carga=<?=base64_encode($rCargas[0]);?>&periodo=<?=base64_encode($rCargas[5]);?>&usrEstud=<?=base64_encode($usrEstud);?>"><?=$frases[111][$datosUsuarioActual['uss_idioma']];?></a></li>
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