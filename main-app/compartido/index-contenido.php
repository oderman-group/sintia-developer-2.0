<div class="page-content">
                    <div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class=" pull-left">
                                <div class="page-title"><?=$frases[100][$datosUsuarioActual['uss_idioma']];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
	
	
                   <!-- start widget --
					<div class="state-overview">
							<div class="row">
						        <div class="col-xl-3 col-md-6 col-12">
						          <div class="info-box bg-b-green">
						            <span class="info-box-icon push-bottom"><i class="material-icons">group</i></span>
						            <div class="info-box-content">
						              <span class="info-box-text">Total Estudiantes</span>
						              <span class="info-box-number">1.250</span>
						              <div class="progress">
						                <div class="progress-bar" style="width: 45%"></div>
						              </div>
						              <span class="progress-description">
						                    45% de incremento en 28 días
						                  </span>
						            </div>
						            <!-- /.info-box-content --
						          </div>
						          <!-- /.info-box --
						        </div>
						        <!-- /.col --
						        <div class="col-xl-3 col-md-6 col-12">
						          <div class="info-box bg-b-yellow">
						            <span class="info-box-icon push-bottom"><i class="material-icons">person</i></span>
						            <div class="info-box-content">
						              <span class="info-box-text">Nuevos Estudiantes</span>
						              <span class="info-box-number">155</span>
						              <div class="progress">
						                <div class="progress-bar" style="width: 40%"></div>
						              </div>
						              <span class="progress-description">
						                    40% de incremento en 28 días
						                  </span>
						            </div>
						            <!-- /.info-box-content --
						          </div>
						          <!-- /.info-box --
						        </div>
						        <!-- /.col --
						        <div class="col-xl-3 col-md-6 col-12">
						          <div class="info-box bg-b-blue">
						            <span class="info-box-icon push-bottom"><i class="material-icons">school</i></span>
						            <div class="info-box-content">
						              <span class="info-box-text">Cursos nuevos</span>
						              <span class="info-box-number">52</span>
						              <div class="progress">
						                <div class="progress-bar" style="width: 85%"></div>
						              </div>
						              <span class="progress-description">
						                    85% de incremento en 28 días
						                  </span>
						            </div>
						            <!-- /.info-box-content --
						          </div>
						          <!-- /.info-box --
						        </div>
						        <!-- /.col --
						        <div class="col-xl-3 col-md-6 col-12">
						          <div class="info-box bg-b-pink">
						            <span class="info-box-icon push-bottom"><i class="material-icons">monetization_on</i></span>
						            <div class="info-box-content">
						              <span class="info-box-text">Ingresos</span>
						              <span class="info-box-number">130.921.000</span><span>$</span>
						              <div class="progress">
						                <div class="progress-bar" style="width: 50%"></div>
						              </div>
						              <span class="progress-description">
						                    50% de incremento en 28 días
						                  </span>
						            </div>
						            <!-- /.info-box-content --
						          </div>
						          <!-- /.info-box --
						        </div>
						        <!-- /.col --
						      </div>
						</div>
					<!-- end widget -->
                     
			        <div class="row">
						
						<div class="col-sm-4">				
							
							<?php include("../compartido/encuestas.php");?>
							
							<!--
							<div class="panel" data-hint="Envíanos tu sugerencia para la plataforma. Ayudanos a mejorar.">
								<header class="panel-heading panel-heading-red">Tu opinión o sugerencia sobre la plataforma SINTIA es muy importante</header>
                                <div class="panel-body">
									<p style="text-align: justify;"><mark>Esta opción NO es para comunicarse con la Institución. Los mensajes aquí enviados llegarán solamente a los proveedores de la plataforma SINTIA.</mark></p>
									<?php if(isset($_GET["msg"]) and $_GET["msg"]==1){?>
										<p class="text-success">
											<i class="fa fa-thumbs-up"></i>
											Muchas gracias por enviar tu opinión! La tendremos en cuenta para seguir mejorando.
										</p>
									<?php }else{?>
									<form class="form-horizontal" action="../compartido/guardar.php" method="post">
										<input type="hidden" name="id" value="10">
										<input type="hidden" name="usuario" value="<?=$datosUsuarioActual['uss_nombre'];?>">
										<input type="hidden" name="tipoUsuario" value="<?=$datosUsuarioActual['uss_tipo'];?>">
										
										<input type="hidden" id="institucionSug" value="<?=$_SESSION["inst"];?>">
										<input type="hidden" id="usuarioSug" value="<?=$datosUsuarioActual['uss_id'];?>">
										<input type="hidden" id="usuarioNombreSug" value="<?=$datosUsuarioActual['uss_nombre'];?>">
										<input type="hidden" id="tipoUsuarioSug" value="<?=$datosUsuarioActual['uss_tipo'];?>">
										
										<div class="form-group row">
											<div class="col-sm-12">
												<textarea id="contenidoSugerencia" name="contenido" class="form-control" rows="3" placeholder="¿Cuál es tu opinión o sugerencia sobre la plataforma SINTIA?" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;" required></textarea>
											</div>
										</div>

										<div class="form-group">
											<div class="offset-md-3 col-md-9">
												<button type="button" onClick="insertarFirebase()" class="btn btn-info">Enviar ahora</button>
												<button type="reset" class="btn btn-default"><?=$frases[171][$datosUsuarioActual[8]];?></button>
											</div>
										</div>
									</form>
									<?php }?>
								</div>
							</div>
							-->

							<div class="panel">
								
							    <header class="panel-heading panel-heading-purple" align="center"><?=$frases[258][$datosUsuarioActual['uss_idioma']];?> (5)</header>
								<div class="col-sm-12">
									<ul class="feed-blog">
									<?php	
										$ultimasPaginas = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".seguridad_historial_acciones 
										LEFT JOIN ".$baseDatosServicios.".paginas_publicidad ON pagp_id=hil_titulo
										WHERE 
										hil_id IN (SELECT MAX(hil_id) FROM ".$baseDatosServicios.".seguridad_historial_acciones GROUP BY hil_titulo, hil_usuario, hil_institucion)
										AND hil_usuario= ".$datosUsuarioActual[0]." AND hil_institucion =".$config['conf_id_institucion']."
										ORDER BY hil_id DESC LIMIT 5");										 
										while($consultaReciente = mysqli_fetch_array($ultimasPaginas)){						                       
										?>
										
										<li class="diactive-feed">
											<div class="feed-user-img">
												<img src="<?=$fotoPerfilUsr;?>" class="img-radius "
													alt="User-Profile-Image">
											</div>
											<h6>
												<span class="label label-sm label-success">
												<a href="<?=$consultaReciente['pagp_ruta'];?>" style="color:#FFF;"><?php echo $consultaReciente["pagp_pagina"]; ?></a>
												</span> 
												</span>&nbsp;</span>
												<small class="text-muted"><?=$consultaReciente['hil_fecha'];?></small>
											</h6>
										</li>

										<?php }?>
									</ul>	
								</div>
							</div>

							<div class="panel">
								
							    <header class="panel-heading panel-heading-blue" align="center"><?=$frases[259][$datosUsuarioActual['uss_idioma']];?> (5)</header>
								<div class="col-sm-12">
								<?php	
                                    $paginasMasVisitadasConsulta = mysqli_query($conexion, "SELECT count(*) as visitas, pagp_pagina, pagp_ruta FROM ".$baseDatosServicios.".seguridad_historial_acciones
									INNER JOIN ".$baseDatosServicios.".paginas_publicidad ON pagp_id=hil_titulo
									WHERE hil_usuario = ".$datosUsuarioActual[0]." AND hil_institucion = ".$config['conf_id_institucion']."
									GROUP BY hil_titulo
									ORDER BY count(*) DESC
									LIMIT 5");										 
                                    while($paginasMasVisitadasDatos = mysqli_fetch_array($paginasMasVisitadasConsulta)){						                       
                                    ?>
										<li><a href="<?=$paginasMasVisitadasDatos['pagp_ruta'];?>" style="text-decoration: underline;"><?php echo $paginasMasVisitadasDatos["pagp_pagina"]." (".$paginasMasVisitadasDatos["visitas"].")"; ?></a></li>
									<?php }?>
								</div>
							</div>
							
							<?php 
							if($datosUsuarioActual['uss_tipo'] == 1 || $datosUsuarioActual['uss_tipo'] == 5) {
								include("../compartido/peso.php");
							}
							?>

								
							<?php include("../compartido/modulo-frases-lateral.php");?>
							
						</div>	
						
						<!-- Activity feed start -->
						<div class="col-sm-8" data-hint="Este es tu asistente personal de actividades. Él te ayudará a decidir por donde empezar a hacer las tareas.">
							<?php if($datosUsuarioActual[3]==2 or $datosUsuarioActual[3]==5 || $datosUsuarioActual[3]==1){?>
								<?php include("../compartido/progreso-docentes.php");?>
							<?php }?>

						<!--
							<div class="card-box">
								<div class="card-head">
									<header>Contenido variado</header>
								</div>
								<div class="card-body">

									<ul class="feed-blog" id="listarDatos"></ul>
									
									
									<ul class="feed-blog">
										
										<li class="diactive-feed">
											<div class="feed-user-img">
												<img src="../../config-general/assets/img/std/std2.jpg" class="img-radius "
													alt="User-Profile-Image">
											</div>
											<h6>
												<span class="label label-sm label-success">Tarea </span> Lorem</span>
												<small class="text-muted">Hace 5 horas</small>
											</h6>
										</li>
										
									</ul>
									
								</div>
							</div>
							-->

						</div>	
						<!-- Activity feed end -->
					</div>
					

                </div>