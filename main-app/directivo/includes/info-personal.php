<fieldset>
											
										<?php if(!empty($datosEstudianteActual['mat_foto'])) {?>
										<div class="form-group row">
                                            <div class="col-sm-4" style="margin: 0 auto 10px">
												<div class="item">
													<img src="../files/fotos/<?=$datosEstudianteActual['mat_foto'];?>" width="150" height="150" />
												</div>
                                            </div>
                                        </div>
										<?php }?>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label"><?=$frases[219][$datosUsuarioActual[8]];?></label>
                                            <div class="col-sm-4">
                                                <input type="file" name="fotoMat" class="form-control" <?=$disabledPermiso;?>>
                                                <span style="color: #6017dc;">La foto debe estar en formato JPG o PNG.</span>
                                            </div>
                                        </div>
										<hr>

											<div class="form-group row">
												<label class="col-sm-2 control-label">Código del Sistema</label>
												<div class="col-sm-2">
													<input type="text" name="matricula" class="form-control" readonly autocomplete="off" value="<?=$datosEstudianteActual[1];?>"  <?=$disabledPermiso;?>>
												</div>
												
												<label class="col-sm-2 control-label">Fecha de Matr&iacute;cula</label>
												<div class="col-sm-2">
													<input type="text" name="fechaMatricula" class="form-control" autocomplete="off" value="<?=$datosEstudianteActual[2];?>" disabled>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Número de matrícula</label>
												<div class="col-sm-4">
													<input type="text" name="NumMatricula" class="form-control" autocomplete="off" value="<?=$datosEstudianteActual["mat_numero_matricula"];?>" <?=$disabledPermiso;?>>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-sm-2 control-label">Tipo de documento</label>
												<div class="col-sm-2">
													<?php
													try{
														$opcionesConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales
														WHERE ogen_grupo=1
														");
													} catch (Exception $e) {
														include("../compartido/error-catch-to-report.php");
													}
													?>
													<select class="form-control  select2" name="tipoD" <?=$disabledPermiso;?>>
														<option value="">Seleccione una opción</option>
														<?php while($o = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
															if($o[0]==$datosEstudianteActual[11])
															echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
														else
															echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
												
												<label class="col-sm-2 control-label">Número de documento <span style="color: red;">(*)</span></label>
												<div class="col-sm-2">
													<input type="text" name="nDoc" id="nDoc" required class="form-control" autocomplete="off" onChange="validarEstudiante(this)" value="<?=$datosEstudianteActual[12];?>" <?=$disabledPermiso;?>>
												</div>
											</div>	
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Lugar de expedición</label>
												<div class="col-sm-4">
													<select class="form-control  select2" name="lugarD" <?=$disabledPermiso;?>>
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
														<option value="<?=$opg['ciu_id'];?>" <?php if($opg['ciu_id']==$datosEstudianteActual["mat_lugar_expedicion"]){echo "selected";}?>><?=$opg['ciu_nombre'].", ".$opg['dep_nombre'];?></option>
														<?php }?>
													</select>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Folio</label>
												<div class="col-sm-2">
													<input type="text" name="folio" class="form-control" autocomplete="off" value="<?=$datosEstudianteActual[34];?>" <?=$disabledPermiso;?>>
												</div>
												
												<label class="col-sm-2 control-label">Codigo Tesoreria</label>
												<div class="col-sm-2">
													<input type="text" name="codTesoreria" class="form-control" autocomplete="off" value="<?=$datosEstudianteActual[35];?>" <?=$disabledPermiso;?>>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Primer apellido <span style="color: red;">(*)</span></label>
												<div class="col-sm-2">
													<input type="text" name="apellido1" id="apellido1" required class="form-control" autocomplete="off" value="<?=$datosEstudianteActual[3];?>" <?=$disabledPermiso;?>>
												</div>
												
												<label class="col-sm-2 control-label">Segundo apellido</label>
												<div class="col-sm-2">
													<input type="text" name="apellido2" id="apellido2" class="form-control" autocomplete="off" value="<?=$datosEstudianteActual[4];?>" <?=$disabledPermiso;?>>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Primer Nombre <span style="color: red;">(*)</span></label>
												<div class="col-sm-2">
													<input type="text" name="nombres" id="nombres" required class="form-control" autocomplete="off" value="<?=$datosEstudianteActual[5];?>" <?=$disabledPermiso;?>>
												</div>

												<label class="col-sm-2 control-label">Otro Nombre</label>
												<div class="col-sm-2">
													<input type="text" name="nombre2" class="form-control" autocomplete="off" value="<?=$datosEstudianteActual['mat_nombre2'];?>" <?=$disabledPermiso;?>>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Email</label>
												<div class="col-sm-6">
													<input type="text" name="email" class="form-control" autocomplete="off" value="<?=$datosEstudianteActual['mat_email'];?>" <?=$disabledPermiso;?>>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Fecha de nacimiento</label>
												<div class="col-sm-4">
													<div class="input-group date form_date" data-date-format="dd MM yyyy" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
													<input class="form-control" size="16" type="text" value="<?=$datosEstudianteActual['mat_fecha_nacimiento'];?>" <?=$disabledPermiso;?>>
													<span class="input-group-addon"><span class="fa fa-calendar"></span>
													</div>
												</div>
												<input type="hidden" id="dtp_input1" name="fNac">
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Lugar de Nacimiento</label>
												<div class="col-sm-4">
													<select class="form-control  select2" name="lNac" <?=$disabledPermiso;?>>
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
														<option value="<?=$opg['ciu_id'];?>" <?php if($opg['ciu_id']==$datosEstudianteActual['mat_lugar_nacimiento']){echo "selected";}?>><?=$opg['ciu_nombre'].", ".$opg['dep_nombre'];?></option>
														<?php }?>
													</select>
												</div>
												
												<?php 
													$lugarPro="";
													if(!is_numeric($datosEstudianteActual['mat_lugar_nacimiento'])){
														$lugarPro=$datosEstudianteActual['mat_lugar_nacimiento'];
													}
												?>
												<label class="col-sm-2 control-label">Ciudad de Procedencia</label>
												<div class="col-sm-4" >
													<input type="text" name="ciudadPro" class="form-control" autocomplete="off" value="<?=$lugarPro;?>" <?=$disabledPermiso;?>>
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Genero</label>
												<?php
												try{
													$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=4");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
												?>
												<div class="col-sm-4">
													<select class="form-control  select2" name="genero" <?=$disabledPermiso;?>>
														<option value="">Seleccione una opción</option>
														<?php while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
															if($o[0]==$datosEstudianteActual[8])
																echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
															else
																echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Grupo Sanguineo</label>
												<div class="col-sm-2">
													<input type="text" name="tipoSangre" class="form-control" autocomplete="off" value="<?=$datosEstudianteActual["mat_tipo_sangre"];?>" <?=$disabledPermiso;?>>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">EPS</label>
												<div class="col-sm-2">
													<input type="text" name="eps" class="form-control" autocomplete="off" value="<?=$datosEstudianteActual["mat_eps"];?>" <?=$disabledPermiso;?>>
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Estudiante de Inclusión</label>
												<div class="col-sm-2">
													<select class="form-control  select2" name="inclusion" <?=$disabledPermiso;?>>
														<option value="">Seleccione una opción</option>
														<option value="1"<?php if ($datosEstudianteActual[37]==1){echo "selected";}?>>Si</option>
														<option value="0"<?php if ($datosEstudianteActual[37]==0){echo "selected";}?>>No</option>
													</select>
												</div>
												
												<label class="col-sm-2 control-label">Religi&oacute;n</label>
												<?php
												try{
													$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=2");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
												?>
												<div class="col-sm-2">
													<select class="form-control  select2" name="religion" <?=$disabledPermiso;?>>
														<option value="">Seleccione una opción</option>
														<?php while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
															if($o[0]==$datosEstudianteActual[14])
																echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
															else
																echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Extranjero?</label>
												<div class="col-sm-2">
													<select class="form-control  select2" name="extran"  onChange="mostrar(this)" <?=$disabledPermiso;?>>
														<option value="">Seleccione una opción</option>
														<option value="1"<?php if ($datosEstudianteActual[39]==1){echo "selected";}?>>Si</option>
														<option value="0"<?php if ($datosEstudianteActual[39]==0){echo "selected";}?>>No</option>
													</select>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Direcci&oacute;n</label>
												<div class="col-sm-4">
													<input type="text" name="direccion" class="form-control" autocomplete="off" value="<?=$datosEstudianteActual[15];?>" <?=$disabledPermiso;?>>
												</div>
												<div class="col-sm-4">
													<input type="text" name="barrio" class="form-control" placeholder="Barrio" autocomplete="off" value="<?=$datosEstudianteActual[16];?>" <?=$disabledPermiso;?>>
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Ciudad de residencia</label>
												<div class="col-sm-4">
													<select class="form-control  select2" name="ciudadR" <?=$disabledPermiso;?>>
														<option value="">Seleccione una opción</option>
														<?php
														try{
															$opcionesG = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".localidad_ciudades
															INNER JOIN ".$baseDatosServicios.".localidad_departamentos ON dep_id=ciu_departamento 
															ORDER BY ciu_nombre ");
														} catch (Exception $e) {
															include("../compartido/error-catch-to-report.php");
														}
														while($opg = mysqli_fetch_array($opcionesG, MYSQLI_BOTH)){
														$selected='';
														$opg['ciu_codigo'] = trim($opg['ciu_codigo']);
														if($opg['ciu_codigo']==$datosEstudianteActual['mat_ciudad_residencia']){
															$selected='selected';
														}

														?>
														<option value="<?=$opg['ciu_codigo'];?>" <?=$selected;?>><?=$opg['ciu_nombre'].", ".$opg['dep_nombre'];?></option>
														<?php }?>
													</select>
												</div>
											</div>
												
											<div class="form-group row">
												<label class="col-sm-2 control-label">Estrato</label>
												<?php
												try{
													$op = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=3");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
												?>
												<div class="col-sm-2">
													<select class="form-control  select2" name="estrato" <?=$disabledPermiso;?>>
														<option value="">Seleccione una opción</option>
														<?php while($o = mysqli_fetch_array($op, MYSQLI_BOTH)){
															if($o[0]==$datosEstudianteActual[19])
																echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
															else
																echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
														}?>
													</select>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Contactos</label>
												<div class="col-sm-2">
													<input type="text" name="telefono" class="form-control" placeholder="Telefono" autocomplete="off" value="<?=$datosEstudianteActual[17];?>" <?=$disabledPermiso;?>>
												</div>
												<div class="col-sm-2">
													<input type="text" name="celular" class="form-control" placeholder="celular" autocomplete="off" value="<?=$datosEstudianteActual[18];?>" <?=$disabledPermiso;?>>
												</div>
												<div class="col-sm-2">
													<input type="text" name="celular2" class="form-control" placeholder="celular #2" autocomplete="off" value="<?=$datosEstudianteActual['mat_celular2'];?>" <?=$disabledPermiso;?>>
												</div>
											</div>	

											
											<?php include("includes/pasos-matricula.php");?>

										</fieldset>

