<div class="col-md-4 col-lg-3">
									<div class="panel">
										<header class="panel-heading panel-heading-red">MENÚ <?=strtoupper($frases[12][$datosUsuarioActual['uss_idioma']]);?></header>
										<div class="panel-body">
                                        	
											<p><a href="cargas-transferir.php">Transferir cargas</a></p>
											<p><a href="cargas-estilo-notas.php">Estilo de notas</a></p>
											<p><a href="cargas-indicadores-obligatorios.php">Indicadores obligatorios</a></p>
										</div>
                                	</div>
									
									<?php
									try{
										$consultaEstadisticaCarga=mysqli_query($conexion, "SELECT (SELECT count(car_id) FROM academico_cargas)");
									} catch (Exception $e) {
										include("../compartido/error-catch-to-report.php");
									}
										$estadisticasCargas = mysqli_fetch_array($consultaEstadisticaCarga, MYSQLI_BOTH);
										?>
									
									<h4 align="center"><?=strtoupper($frases[205][$datosUsuarioActual[8]]);?></h4>
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple"><?=$frases[5][$datosUsuarioActual['uss_idioma']];?> </header>
										<div class="panel-body">
											<?php
											try{
												$cursos = mysqli_query($conexion, "SELECT * FROM academico_grados
												WHERE gra_estado=1
												ORDER BY gra_vocal
												");
											} catch (Exception $e) {
												include("../compartido/error-catch-to-report.php");
											}
											while($curso = mysqli_fetch_array($cursos, MYSQLI_BOTH)){
												try{
													$consultaEstudianteGrado=mysqli_query($conexion, "SELECT count(car_id) FROM academico_cargas WHERE car_curso='".$curso['gra_id']."'");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
												$estudiantesPorGrado = mysqli_fetch_array($consultaEstudianteGrado, MYSQLI_BOTH);
												$porcentajePorGrado = 0;
												if(!empty($estadisticasCargas[0])){
													$porcentajePorGrado = round(($estudiantesPorGrado[0]/$estadisticasCargas[0])*100,2);
												}
												if($curso['gra_id']==$_GET["curso"]) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
											?>
											
												<div class="work-monitor work-progress">
															<div class="states">
																<div class="info">
																	<div class="desc pull-left"><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$curso['gra_id'];?>&grupo=<?=$_GET["grupo"];?>&docente=<?=$_GET["docente"];?>&asignatura=<?=$_GET["asignatura"];?>" <?=$estiloResaltado;?>><?=strtoupper($curso['gra_nombre']);?>: <b><?=$estudiantesPorGrado[0];?></b></a></div>
																	<div class="percent pull-right"><?=$porcentajePorGrado;?>%</div>
																</div>

																<div class="progress progress-xs">
																	<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?=$porcentajePorGrado;?>%">
																		<span class="sr-only">90% </span>
																	</div>
																</div>
															</div>
														</div>
											<?php }?>
											<p align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$_GET["grupo"];?>">VER TODOS</a></p>
										</div>
                                    </div>
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple">Grupos </header>
										<div class="panel-body">
											<?php
											try{
												$grupos = mysqli_query($conexion, "SELECT * FROM academico_grupos");
											} catch (Exception $e) {
												include("../compartido/error-catch-to-report.php");
											}
											while($grupo = mysqli_fetch_array($grupos, MYSQLI_BOTH)){
												if($grupo['gru_id']==$_GET["grupo"]) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
											?>
												<p><a href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$grupo['gru_id'];?>&curso=<?=$_GET["curso"];?>&docente=<?=$_GET["docente"];?>&asignatura=<?=$_GET["asignatura"];?>" <?=$estiloResaltado;?>><?=strtoupper($grupo['gru_nombre']);?></a></p>
											<?php }?>
											<p align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$_GET['curso'];?>">VER TODOS</a></p>
										</div>
                                    </div>
									
									
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple"><?=$frases[28][$datosUsuarioActual['uss_idioma']];?> </header>
										<div class="panel-body">
											<?php
											try{
												$docentes = mysqli_query($conexion, "SELECT * FROM usuarios
												WHERE uss_tipo=2 AND uss_bloqueado=0
												ORDER BY uss_nombre
												");
											} catch (Exception $e) {
												include("../compartido/error-catch-to-report.php");
											}
											while($docente = mysqli_fetch_array($docentes, MYSQLI_BOTH)){
												try{
													$consultaCargaDocente=mysqli_query($conexion, "SELECT count(car_id) FROM academico_cargas WHERE car_docente='".$docente['uss_id']."'");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
												$cargasPorDocente = mysqli_fetch_array($consultaCargaDocente, MYSQLI_BOTH);
												$porcentajePorGrado = 0;
												if(!empty($estadisticasCargas[0])){
													$porcentajePorGrado = round(($cargasPorDocente[0]/$estadisticasCargas[0])*100,2);
												}
												if($docente['uss_id']==$_GET["docente"]) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
											?>
											
												<div class="work-monitor work-progress">
															<div class="states">
																<div class="info">
																	<div class="desc pull-left"><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$_GET['curso'];?>&grupo=<?=$_GET["grupo"];?>&docente=<?=$docente['uss_id'];?>&asignatura=<?=$_GET["asignatura"];?>" <?=$estiloResaltado;?>><?=UsuariosPadre::nombreCompletoDelUsuario($docente);?>: <b><?=$cargasPorDocente[0];?></b></a></div>
																	<div class="percent pull-right"><?=$porcentajePorGrado;?>%</div>
																</div>

																<div class="progress progress-xs">
																	<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?=$porcentajePorGrado;?>%">
																		<span class="sr-only">90% </span>
																	</div>
																</div>
															</div>
														</div>
											<?php }?>
											<p align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$_GET["grupo"];?>">VER TODOS</a></p>
										</div>
                                    </div>
									
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple"><?=$frases[73][$datosUsuarioActual['uss_idioma']];?> </header>
										<div class="panel-body">
											<?php
											try{
												$docentes = mysqli_query($conexion, "SELECT * FROM academico_materias
												ORDER BY mat_nombre
												");
											} catch (Exception $e) {
												include("../compartido/error-catch-to-report.php");
											}
											while($docente = mysqli_fetch_array($docentes, MYSQLI_BOTH)){
												try{
													$consultaCargaDocente=mysqli_query($conexion, "SELECT count(car_id) FROM academico_cargas WHERE car_materia='".$docente['mat_id']."'");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
												$cargasPorDocente = mysqli_fetch_array($consultaCargaDocente, MYSQLI_BOTH);
												$porcentajePorGrado = 0;
												if(!empty($estadisticasCargas[0])){
													$porcentajePorGrado = round(($cargasPorDocente[0]/$estadisticasCargas[0])*100,2);
												}
												if($docente['mat_id']==$_GET["asignatura"]) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
											?>
											
												<div class="work-monitor work-progress">
															<div class="states">
																<div class="info">
																	<div class="desc pull-left"><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$_GET['curso'];?>&grupo=<?=$_GET["grupo"];?>&docente=<?=$_GET["docente"];?>&asignatura=<?=$docente['mat_id'];?>" <?=$estiloResaltado;?>><?=strtoupper($docente['mat_nombre']);?>: <b><?=$cargasPorDocente[0];?></b></a></div>
																	<div class="percent pull-right"><?=$porcentajePorGrado;?>%</div>
																</div>

																<div class="progress progress-xs">
																	<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?=$porcentajePorGrado;?>%">
																		<span class="sr-only">90% </span>
																	</div>
																</div>
															</div>
														</div>
											<?php }?>
											<p align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$_GET["grupo"];?>">VER TODOS</a></p>
										</div>
                                    </div>
									
									<div class="panel">
										<header class="panel-heading panel-heading-purple">Cantidades </header>
										<div class="panel-body">
											<?php
											for($i=10; $i<=100; $i=$i+10){
												if($i==$_GET["cantidad"]) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
											?>
												<p><a href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$_GET['grupo'];?>&curso=<?=$_GET["curso"];?>&cantidad=<?=$i;?>&docente=<?=$_GET["docente"];?>&asignatura=<?=$_GET["asignatura"];?>" <?=$estiloResaltado;?>><?=$i." cargas";?></a></p>
											<?php }?>
											<p align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$_GET['curso'];?>&grupo=<?=$_GET["grupo"];?>">VER TODOS</a></p>
										</div>
                                    </div>
									
									
									
									<?php include("../compartido/publicidad-lateral.php");?>
								</div>