<?php if($config['conf_id_institucion']==1){ ?>
												<hr>
												<hr>
												<h2><b>Proceso de matrícula</b></h2>
												
												<p>
													<b>Listo:</b> Sígnifica que el estudiante ya hizo este paso o que no tiene que pasar por el.<br>
													<b>Pendiente:</b> Sígnifica que el estudiante está pendiente de realizar este paso.<br>
												</p>	
													
												<div class="form-group row">
													<label class="col-sm-2 control-label">Puede iniciar el proceso?</label>
													<div class="col-sm-2">
														<select <?=$disabledPermiso;?> class="form-control  select2" name="iniciarProceso">
															<option value="">Seleccione una opción</option>
															<option value="1"<?php if ($datosEstudianteActual['mat_iniciar_proceso'] == 1){echo "selected";}?>>SI</option>
															<option value="0"<?php if ($datosEstudianteActual['mat_iniciar_proceso'] == '0'){echo "selected";}?>>NO</option>
														</select>
													</div>
												</div>
													
												<div class="form-group row">
													<label class="col-sm-2 control-label">1. Actualizar datos</label>
													<div class="col-sm-2">
														<select <?=$disabledPermiso;?> class="form-control  select2" name="actualizarDatos">
															<option value="">Seleccione una opción</option>
															<option value="1"<?php if ($datosEstudianteActual['mat_actualizar_datos'] == 1){echo "selected";}?>>Listo</option>
															<option value="0"<?php if ($datosEstudianteActual['mat_actualizar_datos'] == '0'){echo "selected";}?>>Pendiente</option>
														</select>
														<?php if($datosEstudianteActual["mat_actualizar_datos"] == 1){?><i class="icon-ok"></i><?php }?>
													</div>
												</div>
													
												<div class="form-group row">
													<label class="col-sm-2 control-label">2. Pago de matrícula</label>
													<div class="col-sm-2">
														<select <?=$disabledPermiso;?> class="form-control  select2" name="pagoMatricula">
															<option value="">Seleccione una opción</option>
															<option value="1"<?php if ($datosEstudianteActual['mat_pago_matricula'] == 1){echo "selected";}?>>Listo</option>
															<option value="0"<?php if ($datosEstudianteActual['mat_pago_matricula'] == '0'){echo "selected";}?>>Pendiente</option>
														</select>
														<?php if($datosEstudianteActual["mat_pago_matricula"] == 1){?><i class="icon-ok"></i><?php }?>   

														<?php if($datosEstudianteActual["mat_pago_matricula"] == 1 and $datosEstudianteActual["mat_soporte_pago"] != ""){?>  
														<a href="https://plataformasintia.com/main-app/v2.0/files/comprobantes/<?=$datosEstudianteActual["mat_soporte_pago"];?>" target="_blank"><?=$datosEstudianteActual["mat_soporte_pago"];?></a>   
														<?php }?> 
													</div>
												</div>
													
												<div class="form-group row">
													<label class="col-sm-2 control-label">3. Contrato</label>
													<div class="col-sm-2">
														<select <?=$disabledPermiso;?> class="form-control  select2" name="contrato">
															<option value="">Seleccione una opción</option>
															<option value="1"<?php if ($datosEstudianteActual['mat_contrato'] == 1){echo "selected";}?>>Listo</option>
															<option value="0"<?php if ($datosEstudianteActual['mat_contrato'] == '0'){echo "selected";}?>>Pendiente</option>
														</select>
														<?php if($datosEstudianteActual["mat_contrato"] == 1){?><i class="icon-ok"></i><?php }?>
													</div>
												</div>
													
												<div class="form-group row">
													<label class="col-sm-2 control-label">4. Pagaré</label>
													<div class="col-sm-2">
														<select <?=$disabledPermiso;?> class="form-control  select2" name="pagare">
															<option value="">Seleccione una opción</option>
															<option value="1"<?php if ($datosEstudianteActual['mat_pagare'] == 1){echo "selected";}?>>Listo</option>
															<option value="0"<?php if ($datosEstudianteActual['mat_pagare'] == '0'){echo "selected";}?>>Pendiente</option>
														</select>  

														<?php if($datosEstudianteActual["mat_contrato"] == 1){?><i class="icon-ok"></i><?php }?>           
													</div>
												</div>
													
												<div class="form-group row">
													<label class="col-sm-2 control-label">5. Compromiso académico</label>
													<div class="col-sm-2">
														<select <?=$disabledPermiso;?> class="form-control  select2" name="compromisoA">
															<option value="">Seleccione una opción</option>
															<option value="1"<?php if ($datosEstudianteActual['mat_compromiso_academico'] == 1){echo "selected";}?>>Listo</option>
															<option value="0"<?php if ($datosEstudianteActual['mat_compromiso_academico'] == '0'){echo "selected";}?>>Pendiente</option>
														</select>  

														<?php if($datosEstudianteActual["mat_compromiso_academico"] == 1){?><i class="icon-ok"></i><?php }?>          
													</div>
												</div>
													
												<div class="form-group row">
													<label class="col-sm-2 control-label">6. Compromiso de convivencia</label>
													<div class="col-sm-2">
														<select <?=$disabledPermiso;?> class="form-control  select2" name="compromisoC">
															<option value="">Seleccione una opción</option>
															<option value="1"<?php if ($datosEstudianteActual['mat_compromiso_convivencia'] == 1){echo "selected";}?>>Listo</option>
															<option value="0"<?php if ($datosEstudianteActual['mat_compromiso_convivencia'] == '0'){echo "selected";}?>>Pendiente</option>
														</select> 
													</div>
													
													<div class="col-sm-2">
														<select <?=$disabledPermiso;?> class="form-control  select2" name="compromisoOpcion">
															<option value="">Seleccione una opción</option>
															<option value="1"<?php if ($datosEstudianteActual['mat_compromiso_convivencia_opcion'] == 1){echo "selected";}?>>Nuevos</option>
															<option value="2"<?php if ($datosEstudianteActual['mat_compromiso_convivencia_opcion'] == 2){echo "selected";}?>>Antiguos</option>
															<option value="3"<?php if ($datosEstudianteActual['mat_compromiso_convivencia_opcion'] == 3){echo "selected";}?>>Matrícula condicional</option>
														</select>  
													</div>

													<?php if($datosEstudianteActual["mat_compromiso_convivencia"] == 1){?><i class="icon-ok"></i><?php }?>
												</div>
													
												<div class="form-group row">
													<label class="col-sm-2 control-label">7. Manual de convivencia</label>
													<div class="col-sm-2">
														<select <?=$disabledPermiso;?> class="form-control  select2" name="manual">
															<option value="">Seleccione una opción</option>
															<option value="1"<?php if ($datosEstudianteActual['mat_manual'] == 1){echo "selected";}?>>Listo</option>
															<option value="0"<?php if ($datosEstudianteActual['mat_manual'] == '0'){echo "selected";}?>>Pendiente</option>
														</select>   

														<?php if($datosEstudianteActual["mat_manual"] == 1){?><i class="icon-ok"></i><?php }?>                
													</div>
												</div>
													
												<div class="form-group row">
													<label class="col-sm-2 control-label">8. Contrato mayores de 14 años</label>
													<div class="col-sm-2">
														<select <?=$disabledPermiso;?> class="form-control  select2" name="contrato14">
															<option value="">Seleccione una opción</option>
															<option value="1"<?php if ($datosEstudianteActual['mat_mayores14'] == 1){echo "selected";}?>>Listo</option>
															<option value="0"<?php if ($datosEstudianteActual['mat_mayores14'] == '0'){echo "selected";}?>>Pendiente</option>
														</select> 

														<?php if($datosEstudianteActual["mat_mayores14"] == 1){?><i class="icon-ok"></i><?php }?>                       
													</div>
												</div>
													
												<div class="form-group row">
													<label class="col-sm-2 control-label">9. Firma hoja matrícula</label>
													<div class="col-sm-2">
														<select <?=$disabledPermiso;?> class="form-control  select2" name="firmaHoja">
															<option value="">Seleccione una opción</option>
															<option value="1"<?php if ($datosEstudianteActual['mat_hoja_firma'] == 1){echo "selected";}?>>Listo</option>
															<option value="0"<?php if ($datosEstudianteActual['mat_hoja_firma'] == '0'){echo "selected";}?>>Pendiente</option>
														</select> 

														<?php if($datosEstudianteActual["mat_hoja_firma"] == 1){?><i class="icon-ok"></i><?php }?>   

														<?php if($datosEstudianteActual["mat_hoja_firma"] == 1 and $datosEstudianteActual["mat_firma_adjunta"] != ""){?>  
														<a href="https://plataformasintia.com/main-app/v2.0/files/comprobantes/<?=$datosEstudianteActual["mat_firma_adjunta"];?>" target="_blank"><?=$datosEstudianteActual["mat_firma_adjunta"];?></a>   
														<?php }?>                       
													</div>
												</div>
											<?php } ?>