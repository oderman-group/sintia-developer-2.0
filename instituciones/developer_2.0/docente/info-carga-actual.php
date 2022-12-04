										<div class="panel">
											<header class="panel-heading panel-heading-yellow"><?=$frases[207][$datosUsuarioActual[8]];?></header>

											<div class="panel-body">
												<p><?=$frases[208][$datosUsuarioActual[8]];?></p>
												<ul class="list-group list-group-unbordered">
													<li class="list-group-item">
														<b><?=strtoupper($frases[49][$datosUsuarioActual[8]]);?></b> 
														<div class="profile-desc-item pull-right"><?=$datosCargaActual['car_id'];?></div>
													</li>
													<li class="list-group-item">
														<b><?=strtoupper($frases[116][$datosUsuarioActual[8]]);?></b> 
														<div class="profile-desc-item pull-right"><?=strtoupper($datosCargaActual['mat_nombre']);?></div>
													</li>
													<li class="list-group-item">
														<b><?=strtoupper($frases[26][$datosUsuarioActual[8]]);?></b> 
														<div class="profile-desc-item pull-right"><?=strtoupper($datosCargaActual['gra_nombre']." ".$datosCargaActual['gru_nombre']);?></div>
													</li>
													<li class="list-group-item">
														<b><?=strtoupper($frases[27][$datosUsuarioActual[8]]);?></b> 
														<div class="profile-desc-item pull-right"><?=$periodoConsultaActual;?></div>
													</li>
													<li class="list-group-item">
														<b>I.H</b> 
														<div class="profile-desc-item pull-right"><?=$datosCargaActual['car_ih'];?></div>
													</li>
													<li class="list-group-item">
														<b>D.GRUPO</b> 
														<div class="profile-desc-item pull-right"><?=$dgArray[$datosCargaActual['car_director_grupo']];?></div>
													</li>
													<!--
													<li class="list-group-item">
														<b>% <?=strtoupper($frases[63][$datosUsuarioActual[8]]);?></b> 
														<div class="profile-desc-item pull-right"><?=$configCargasArray[$datosCargaActual['car_valor_indicador']];?></div>
													</li>
													<li class="list-group-item">
														<b>% <?=strtoupper($frases[6][$datosUsuarioActual[8]]);?></b> 
														<div class="profile-desc-item pull-right"><?=$configCargasArray[$datosCargaActual['car_configuracion']];?></div>
													</li>
													<li class="list-group-item">
														<b>MAX. <?=strtoupper($frases[63][$datosUsuarioActual[8]]);?></b> 
														<div class="profile-desc-item pull-right"><?=$datosCargaActual['car_maximos_indicadores'];?></div>
													</li>
													<li class="list-group-item">
														<b>MAX. <?=strtoupper($frases[6][$datosUsuarioActual[8]]);?></b> 
														<div class="profile-desc-item pull-right"><?=$datosCargaActual['car_maximas_calificaciones'];?></div>
													</li>
													<li class="list-group-item">
														<b>G. INFORME AUTOMÁTICO</b> 
														<div class="profile-desc-item pull-right"><?=$datosCargaActual['car_fecha_generar_informe_auto'];?></div>
													</li>
													-->
												</ul>
												
												

											</div>
											<p align="center"><a href="cargas-configurar.php" class="btn yellow"><?=$frases[14][$datosUsuarioActual[8]];?></a></p>
										</div>
