<?php require_once("../class/servicios/CargaServicios.php"); ?>
<?php require_once("../class/servicios/MediaTecnicaServicios.php"); ?>
<?php require_once("../class/servicios/GradoServicios.php"); ?>
<div class="panel">
										<header class="panel-heading panel-heading-purple"><?=$frases[73][$datosUsuarioActual['uss_idioma']];?> </header>
										<div class="panel-body">
											<?php
											$cCargas = mysqli_query($conexion, "SELECT * FROM academico_cargas 
											INNER JOIN academico_materias ON mat_id=car_materia
											WHERE car_curso='".$datosEstudianteActual[6]."' AND car_grupo='".$datosEstudianteActual[7]."'");
											$nCargas = mysqli_num_rows($cCargas);
											while($rCargas = mysqli_fetch_array($cCargas, MYSQLI_BOTH)){
												//Verificar si el estudiante está matriculado en cursos de extensión o complementarios
												if($rCargas['car_curso_extension']==1){
													$cursoExt = mysqli_num_rows(mysqli_query($conexion, "SELECT * FROM academico_cargas_estudiantes WHERE carpest_carga='".$rCargas['car_id']."' AND carpest_estudiante='".$datosEstudianteActual['mat_id']."' AND carpest_estado=1"));
													if($cursoExt==0){continue;}
												}
												
												if($rCargas['car_id']==$cargaConsultaActual) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';
											?>
												<p><a href="<?=$_SERVER['PHP_SELF'];?>?carga=<?=$rCargas['car_id'];?>&periodo=<?=$periodoConsultaActual;?>" <?=$estiloResaltado;?>><?=strtoupper($rCargas['mat_nombre']);?></a></p>
											<?php }?>
										</div>
                                    </div>
									<?php if (array_key_exists(10, $arregloModulos)) { 
										$parametros = ['matcur_id_matricula' => $datosEstudianteActual["mat_id"]];
										$listaCursosMediaTecnica = MediaTecnicaServicios::listar($parametros);
										foreach ($listaCursosMediaTecnica as $dato) {
											$cursoMediaTecnica = GradoServicios::consultarCurso($dato["matcur_id_curso"]);

										?>
										
										<div class="panel">
										<header class="panel-heading panel-heading-purple"><?=$cursoMediaTecnica['gra_nombre'];?> </header>
											<div class="panel-body">
												<?php $parametros = [
													'matcur_id_matricula' => $datosEstudianteActual["mat_id"],
													'matcur_id_curso'     => $dato["matcur_id_curso"]
													];
												$listacargaMediaTecnica = MediaTecnicaServicios::listarMaterias($parametros);
												if ($listacargaMediaTecnica != null) { 
													foreach ($listacargaMediaTecnica as $cargaMediaTecnica) {
													 if($cargaMediaTecnica['car_id']==$cargaConsultaActual) $estiloResaltado = 'style="color: orange;"'; else $estiloResaltado = '';?>
													<p><a href="<?=$_SERVER['PHP_SELF'];?>?carga=<?=$cargaMediaTecnica['car_id'];?>&periodo=<?=$periodoConsultaActual;?>" <?=$estiloResaltado;?>><?=strtoupper($cargaMediaTecnica['mat_nombre']);?></a></p>
													<?php }?>													
												<?php } else {?>
													<p> El curso <?=$cursoMediaTecnica["gra_nombre"]?>  no tiene carga academica.</p>
												<?php }?>
											
											</div>
										</div>
										<?php }?>
                                    
									<?php } ?>