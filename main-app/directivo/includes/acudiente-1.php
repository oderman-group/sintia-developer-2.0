											<?php 
											if(!empty($datosEstudianteActual["mat_acudiente"])){
												$acudiente = UsuariosPadre::sesionUsuario($datosEstudianteActual["mat_acudiente"]);
											}
											?>
                                            
											<h2><b>ACUDIENTE 1</b></h2>

											<div class="form-group row">
												<label class="col-sm-2 control-label">Tipo de documento</label>
												<div class="col-sm-3">
													<?php
													try{
														$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=1");
													} catch (Exception $e) {
														include("../compartido/error-catch-to-report.php");
													}
													?>
													<select class="form-control" name="tipoDAcudiente" <?=$disabledPermiso;?>>
														<?php while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
															if($o[0]==$acudiente["uss_tipo_documento"])
															echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
														else
															echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
												
												<label class="col-sm-2 control-label">Documento <span style="color: red;">(*)</span></label>
												<div class="col-sm-3">
													<input type="text" name="documentoA" required class="form-control" autocomplete="off" value="<?php if(isset($acudiente['uss_usuario'])){ echo $acudiente['uss_usuario'];}?>" <?=$disabledPermiso;?>>
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Lugar de expedición</label>
												<div class="col-sm-3">
													<select class="form-control" name="lugardA" <?=$disabledPermiso;?>>
														<option value="">Seleccione una opción</option>
														<?php
														try{
															$opcionesG = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".localidad_ciudades
															INNER JOIN ".$baseDatosServicios.".localidad_departamentos ON dep_id=ciu_departamento 
															ORDER BY ciu_nombre
															");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														while($opg = mysqli_fetch_array($opcionesG, MYSQLI_BOTH)){
														?>
														<option value="<?=$opg['ciu_id'];?>" <?php if(isset($acudiente["uss_lugar_expedicion"])&&$opg['ciu_id']==$acudiente["uss_lugar_expedicion"]){echo "selected";}?>><?=$opg['ciu_nombre'].", ".$opg['dep_nombre'];?></option>
														<?php }?>
													</select>
												</div>	

												<label class="col-sm-2 control-label">Ocupaci&oacute;n</label>
												<div class="col-sm-3">
													<input type="text" name="ocupacionA" class="form-control" autocomplete="off" value="<?php if(isset($acudiente["uss_ocupacion"])){ echo $acudiente["uss_ocupacion"];}?>" <?=$disabledPermiso;?>>
												</div>
											</div>

											<div class="form-group row">												
												<label class="col-sm-2 control-label">Primer Apellido</label>
												<div class="col-sm-3">
													<input type="text" name="apellido1A" class="form-control" autocomplete="off" value="<?php if(isset($acudiente["uss_apellido1"])){ echo $acudiente["uss_apellido1"];}?>" <?=$disabledPermiso;?>>
												</div>
																							
												<label class="col-sm-2 control-label">Segundo Apellido</label>
												<div class="col-sm-3">
													<input type="text" name="apellido2A" class="form-control" autocomplete="off" value="<?php if(isset($acudiente["uss_apellido2"])){ echo $acudiente["uss_apellido2"];}?>" <?=$disabledPermiso;?>>
												</div>
											</div>

											<div class="form-group row">												
												<label class="col-sm-2 control-label">Nombre <span style="color: red;">(*)</span></label>
												<div class="col-sm-3">
													<input type="text" name="nombreA" required class="form-control" autocomplete="off" value="<?php if(isset($acudiente["uss_nombre"])){ echo $acudiente["uss_nombre"];}?>" <?=$disabledPermiso;?>>
												</div>
																								
												<label class="col-sm-2 control-label">Otro Nombre</label>
												<div class="col-sm-3">
													<input type="text" name="nombre2A" class="form-control" autocomplete="off" value="<?php if(isset($acudiente["uss_nombre2"])){ echo $acudiente["uss_nombre2"];}?>" <?=$disabledPermiso;?>>
												</div>
											</div>	
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Genero</label>
												<div class="col-sm-3">
													<?php
													try{
														$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=4");
													} catch (Exception $e) {
														include("../compartido/error-catch-to-report.php");
													}
													?>
													<select class="form-control" name="generoA" <?=$disabledPermiso;?>>
														<option value="">Seleccione una opción</option>
														<?php while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
															if($o[0]==$acudiente[16])
																echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
															else
																echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
											</div>