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
							
							
							<div class="panel" data-hint="Envíanos tu sugerencia para la plataforma. Ayudanos a mejorar.">
								<header class="panel-heading panel-heading-red">Tu opinión o sugerencia sobre la plataforma SINTIA es muy importante</header>
                                <div class="panel-body">
									<p style="text-align: justify;"><mark>Esta opción NO es para comunicarse con la Institución. Los mensajes aquí enviados llegarán solamente a los proveedores de la plataforma SINTIA.</mark></p>
									<?php if($_GET["msg"]==1){?>
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
							
							
							<?php include("../compartido/modulo-frases-lateral.php");?>
							

						</div>	
						
						<!-- Activity feed start -->
						<div class="col-sm-8" data-hint="Este es tu asistente personal de actividades. Él te ayudará a decidir por donde empezar a hacer las tareas.">
							<div class="card-box">
								<div class="card-head">
									<header>Listado de opiniones y sugerencias</header>
								</div>
								<div class="card-body">

									<!--<ul class="feed-blog" id="listarDatos"></ul>-->
									
									<!--
									<ul class="feed-blog">
										<?php
										$consultaReciente = mysql_query("SELECT * FROM social_noticias
										INNER JOIN usuarios ON uss_id=not_usuario
										WHERE (not_estado=1 or (not_estado=0 and not_usuario='".$_SESSION["id"]."')) 
										AND (not_para LIKE '%".$datosUsuarioActual[3]."%' OR not_usuario='".$_SESSION["id"]."')
										
										ORDER BY not_id DESC
										LIMIT 0,3
										",$conexion);
										while($resultadoReciente = mysql_fetch_array($consultaReciente)){
											$fotoUsr = $usuariosClase->verificarFoto($resultadoReciente['uss_foto']);
										?>
										<li class="active-feed">
											<div class="feed-user-img">
												<img src="<?php echo $fotoUsr;?>" class="img-radius" alt="User-Profile-Image">
											</div>
											<h6>
												<a href="noticias.php#PUB<?php echo $resultadoReciente['not_id'];?>">
												<span class="label label-sm label-danger">Publicación</span>
												<b><?php echo $resultadoReciente['uss_nombre'];?></b> ha publicado <b><?php echo $resultadoReciente['not_titulo'];?></b>
												<small class="text-muted"><?php echo $resultadoReciente['not_fecha'];?></small>
												</a>
											</h6>
											<p class="m-b-15 m-t-15">
												<?php echo substr($resultadoReciente['not_descripcion'],0,100);?>
											</p>
												
										</li>
										<?php }?>
										<li class="diactive-feed">
											<div class="feed-user-img">
												<img src="../../../config-general/assets/img/std/std2.jpg" class="img-radius "
													alt="User-Profile-Image">
											</div>
											<h6>
												<span class="label label-sm label-success">Tarea </span> Te han dejado una tarea que tiene plazo de entrega hasta pasado mañana: <span class="green-color"> 
												Please add new student details.</span>
												<small class="text-muted">Hace 5 horas</small>
											</h6>
										</li>
										
										<li class="diactive-feed">
											<div class="feed-user-img">
												<img src="../../../config-general/assets/img/std/std3.jpg" class="img-radius "
													alt="User-Profile-Image">
											</div>
											<h6>
												<span class="label label-sm label-primary">Evaluación</span> Hay una evaluación de matemáticas para dentro de una semana. Estudia. <span class="text-c-green">Please add new student details.</span> <small class="text-muted">Hace 6 horas</small>
											</h6>
										</li>
										
									</ul>
									-->
								</div>
							</div>
						</div>	
						<!-- Activity feed end -->
					</div>
					

                </div>