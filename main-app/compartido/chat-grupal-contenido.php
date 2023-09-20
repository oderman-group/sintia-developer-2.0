<?php
require_once("../class/Estudiantes.php");
?>
					<div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class="pull-left">
                                <div class="page-title"><?=$datosConsultaBD['foro_nombre'];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>

					<div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                
								<div class="col-md-4 col-lg-3">
									<?php 
									//DOCENTES
									if($datosUsuarioActual[3]==2){
										include("info-carga-actual.php");
									}
									//ESTUDIANTES
									if($datosUsuarioActual[3]==4){
										include("filtro-cargas.php");
									}
									?>
									
									<div class="panel">
											<header class="panel-heading panel-heading-yellow">Participantes</header>

											<div class="panel-body">
												<p>&nbsp;</p>
												<ul class="list-group list-group-unbordered">
													<?php
													$filtroAdicional= "AND mat_grado='".$datosCargaActual[2]."' AND mat_grupo='".$datosCargaActual[3]."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
													$consulta =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"");
													$contReg = 1;
													while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
														$nombreCompleto =Estudiantes::NombreCompletoDelEstudiante($resultado);
													?>
													<li class="list-group-item">
														<a href="chat-grupal.php?usuario=<?=$resultado['mat_id_usuario'];?>"><?=$nombreCompleto?></a> 
														<div class="profile-desc-item pull-right">&nbsp;</div>
													</li>
													<?php }?>
												</ul>
												
												<p><a href="chat-grupal.php?carga=">VER TODOS</a></p>

											</div>
										</div>
									
									
									
								</div>
								
								
								<div class="col-md-4 col-lg-6">
									
									<div class="card card-box">
										<div class="card-head">
											<header><?=$datosConsultaBD['foro_nombre'];?></header>
										</div>
										
										<div class="card-body " id="bar-parent1">
											<?=$datosConsultaBD['foro_nombre'];?>
										</div>
									</div>
									
									<div class="card card-box">
										
										<div class="card-body " id="bar-parent1">
										<form class="form-horizontal" action="../compartido/guardar.php" method="post">
											<input type="hidden" name="id" value="13">
											<input type="hidden" name="carga" value="<?=$datosCargaActual[0];?>">
											
											<div class="form-group row">
												<div class="col-sm-12">
													<textarea name="mensaje" class="form-control" rows="3" placeholder="Escribe aquí..." style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;" required></textarea>
												</div>
											</div>
											
											<div class="form-group">
												<div class="offset-md-3 col-md-9">
													<button type="submit" class="btn btn-info">Enviar</button>
													<button type="reset" class="btn btn-default"><?=$frases[171][$datosUsuarioActual[8]];?></button>
												</div>
											</div>
										</form>
											
										</div>
									</div>
									
									

											<?php
											$filtro = '';
											if($_GET["usuario"]){$filtro .= " AND chatg_emisor='".$_GET["usuario"]."'";}
											$consulta = mysqli_query($conexion, "SELECT * FROM academico_chat_grupal
											INNER JOIN usuarios ON uss_id=chatg_emisor
											WHERE chatg_carga='".$datosCargaActual[0]."'
											$filtro
											ORDER BY chatg_id DESC
											");
											$contReg = 1;
											while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
											?>
												<div id="PUB<?=$resultado['com_id'];?>" class="row">
													<div class="col-sm-12">
														<div class="panel">
															
															<div class="card-head">
																
																	<?php if($_SESSION["id"]==$resultado['chatg_emisor']){?>
																	<button id ="panel-<?=$resultado['chatg_id'];?>" 
																	   class = "mdl-button mdl-js-button mdl-button--icon pull-right" 
																	   data-upgraded = ",MaterialButton">
																	   <i class = "material-icons">more_vert</i>
																	</button>
																	<ul class = "mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
																	   data-mdl-for="panel-<?=$resultado['chatg_id'];?>">
																	   <li class = "mdl-menu__item"><a href="../compartido/guardar.php?get=18&idR=<?=$resultado['chatg_id'];?>" onClick="if(!sweetConfirmacion('Alerta!','Deseas eliminar este mensaje?')){return false;}"><i class="fa fa-trash"></i><?=$frases[174][$datosUsuarioActual[8]];?></a></li>
																	</ul>
																	<?php }?>
															</div>
															
															<div class="user-panel">
																	<div class="pull-left image">
																		<img src="../files/fotos/<?=$resultado['uss_foto'];?>" class="img-circle user-img-circle" alt="User Image" height="50" width="50" />
																	</div>
																	<div class="pull-left info">
																		<p><a href="<?=$_SERVER['PHP_SELF'];?>?usuario=<?=$resultado['uss_id'];?>"><?=$resultado['uss_nombre'];?></a><br><span style="font-size: 11px; color: #000;"><?=$resultado['chatg_fecha'];?></span></p>
																	</div>
															</div>

															<div class="panel-body">
																<p><?=$resultado['chatg_mensaje'];?></p>	
															</div>

															<div class="card-body">
																
															</div>
															
														</div>
														
														
														
													</div>
												</div>

											<?php
												$contReg ++;
											}
											?>
                                </div>
								
								
								<div class="col-md-4 col-lg-3">
									
									<?php include("../compartido/publicidad-lateral.php");?>
									
                                </div>
								
							
                            </div>
                        </div>
                    </div>