					<div class="row">
                        <div class="col-sm-9">
                            <div class="card card-box">
                                <div class="card-head">
                                    <header><?=$frases[96][$datosUsuarioActual[8]];?></header>
                                </div>
                                <div class="card-body " id="bar-parent6">
                                    <form class="form-horizontal" action="../compartido/guardar.php" method="post" enctype="multipart/form-data">
										<input type="hidden" name="id" value="12">

										<div class="form-group row">
                                            <label class="col-sm-2 control-label"><?=$frases[51][$datosUsuarioActual[8]];?></label>
                                            <div class="col-sm-4">  
                                                <input type="date" class="form-control" name="fecha" required value="<?=date("Y-m-d");?>">
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label"><?=$frases[55][$datosUsuarioActual[8]];?></label>
                                            <div class="col-sm-10">
                                                <?php
												$datosConsulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas 
												INNER JOIN usuarios ON uss_id=mat_id_usuario
												WHERE (mat_estado_matricula=1 OR mat_estado_matricula=2) AND mat_eliminado=0 ORDER BY mat_primer_apellido
												");
												?>
                                                <select class="form-control  select2" name="estudiante" required>
                                                    <option value="">Seleccione una opción</option>
													<?php
													while($datos = mysqli_fetch_array($datosConsulta, MYSQLI_BOTH)){
													?>
                                                    	<option value="<?=$datos['uss_id'];?>"><?="[".$datos['uss_id']."] ".$datos['uss_nombre']?></option>
													<?php }?>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label"><?=$frases[248][$datosUsuarioActual[8]];?></label>
                                            <div class="col-sm-10">
                                                <select id="multiple" name="faltas[]" class="form-control select2-multiple" multiple>
												<?php
												$datosConsulta = mysqli_query($conexion, "SELECT * FROM disciplina_faltas 
												INNER JOIN disciplina_categorias ON dcat_id=dfal_id_categoria
												");
												while($datos = mysqli_fetch_array($datosConsulta, MYSQLI_BOTH)){
												?>	
                                                  <option value="<?=$datos['dfal_id'];?>"><?=$datos['dfal_codigo'].". ".$datos['dfal_nombre'];?></option>	
												<?php }?>	
                                                </select>
                                            </div>
                                        </div>
										
										<?php if($datosUsuarioActual[3]==5){?>
											<div class="form-group row">
												<label class="col-sm-2 control-label"><?=$frases[75][$datosUsuarioActual[8]];?></label>
												<div class="col-sm-10">
													<?php
													$datosConsulta = mysqli_query($conexion, "SELECT * FROM usuarios
													WHERE (uss_tipo=2 OR uss_tipo=5)
													ORDER BY uss_tipo, uss_nombre
													");
													?>
													<select class="form-control  select2" name="usuario" required>
														<option value="">Seleccione una opción</option>
														<?php
														while($datos = mysqli_fetch_array($datosConsulta, MYSQLI_BOTH)){
														?>
															<option value="<?=$datos['uss_id'];?>"><?=$datos['uss_nombre']?></option>
														<?php }?>
													</select>
												</div>
											</div>
										<?php }else{?>
											<input type="hidden" name="usuario" value="<?=$_SESSION["id"];?>">
										<?php }?>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label"><?=$frases[50][$datosUsuarioActual[8]];?></label>
                                            <div class="col-sm-10">  
                                                <textarea name="contenido" class="form-control" rows="5" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;"></textarea>
                                            </div>
                                        </div>
										
										<input type="submit" class="btn btn-primary" value="Guardar cambios">&nbsp;
										
										<a href="#" name="noticias.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>

                                    </form>
                                </div>
                            </div>
                        </div>
						
                        <div class="col-sm-3">
                            <?php include("../compartido/publicidad-lateral.php");?>
                        </div>
						
                    </div>