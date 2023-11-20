									<h4 align="center"><?=strtoupper($frases[205][$datosUsuarioActual[8]]);?></h4>
									<p style="color: darkblue;"><?=$frases[206][$datosUsuarioActual[8]];?></p>
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple"><?=$frases[106][$datosUsuarioActual['uss_idioma']];?> </header>
                                        <div class="panel-body">
											<?php
											for($i=1; $i<=$datosCargaActual['gra_periodos']; $i++){
												$consultaPeriodosCursos=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados_periodos
												WHERE gvp_grado='".$datosCargaActual['car_curso']."' AND gvp_periodo='".$i."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
												");
												$periodosCursos = mysqli_fetch_array($consultaPeriodosCursos, MYSQLI_BOTH);
												$numPeriodosCursos=mysqli_num_rows($consultaPeriodosCursos);
												$porcentaje=25;
												if($numPeriodosCursos>0){
													$porcentaje=$periodosCursos['gvp_valor'];
												}

												if($i==$datosCargaActual['car_periodo']) $msjPeriodoActual = '- ACTUAL'; else $msjPeriodoActual = '';
												if($i==$periodoConsultaActual) $estiloResaltadoP = 'style="color: orange;"'; else $estiloResaltadoP = '';
											?>
												<p>
													<a href="<?=$_SERVER['PHP_SELF'];?>?carga=<?=base64_encode($cargaConsultaActual);?>&periodo=<?=base64_encode($i);?>&get=<?=base64_encode(100);?>" <?=$estiloResaltadoP;?>><?=strtoupper($frases[27][$datosUsuarioActual['uss_idioma']]);?> <?=$i;?> (<?=$porcentaje;?>%) <?=$msjPeriodoActual;?></a>
											
												</p>
											<?php }?>
										
										</div>
									</div>
								
							
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple"><?=$frases[73][$datosUsuarioActual['uss_idioma']];?> </header>
										<div class="panel-body">
											<?php
											$cCargas = mysqli_query($conexion, "SELECT * FROM academico_cargas 
											INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
											INNER JOIN academico_grados ON gra_id=car_curso
											INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
											WHERE car_docente='".$_SESSION["id"]."'
											ORDER BY car_posicion_docente, car_curso, car_grupo, am.mat_nombre
											");
											$nCargas = mysqli_num_rows($cCargas);
											while($rCargas = mysqli_fetch_array($cCargas, MYSQLI_BOTH)){
												if($rCargas['car_id']==$cargaConsultaActual) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
												if($rCargas['car_director_grupo']==1) {$estiloDG = 'style="font-weight: bold;"'; $msjDG = ' - D.G';} else {$estiloDG = ''; $msjDG = '';}
											?>
												<p><a href="<?=$_SERVER['PHP_SELF'];?>?carga=<?=base64_encode($rCargas['car_id']);?>&periodo=<?=base64_encode($periodoConsultaActual);?>&get=<?=base64_encode(100);?>" <?=$estiloResaltado;?>><span <?=$estiloDG;?>><?=$rCargas['car_posicion_docente'];?>. <?=strtoupper($rCargas['mat_nombre']);?> (<?=strtoupper($rCargas['gra_nombre']." ".$rCargas['gru_nombre']);?>) <?=$msjDG;?></span></a></p>
											<?php }?>
										</div>
                                    </div>