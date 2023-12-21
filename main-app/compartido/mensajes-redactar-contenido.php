<div class="inbox">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-topline-gray">
                                <div class="card-body no-padding height-9">
									<div class="row">
			                            <div class="col-md-3">
				                                <div class="inbox-sidebar">
				                                    <a href="mensajes-redactar.php" data-title="Compose" class="btn red compose-btn btn-block">
				                                        <i class="fa fa-edit"></i> Redactar </a>
				                                    <ul class="inbox-nav inbox-divider">
				                                        <li class="active"><a href="mensajes.php"><iclass="fa fa-inbox"></i> Recibidos</a></li>
				                                        <li><a href="mensajes.php?opt=<?=base64_encode(2)?>"><i class="fa fa-envelope"></i> Enviados</a></li>
				                                    </ul>
				                                </div>
				                            </div>
			                            <div class="col-md-9">
			                                <div class="inbox-body">
		                                    <div class="inbox-body no-pad">
		                                        <div class="mail-list">
		                                            <div class="compose-mail">
		                                                <form method="post" action="#../compartido/mensajes-enviar.php">
															<label>Para:</label>
		                                                    <div class="form-group">
																<select id="select_usuario" class="form-control select2-multiple" multiple name="para[]" required>
																	<?php
																		if(!empty($_GET['para'])){
																			$filtro=" AND uss_id='".base64_decode($_GET['para'])."'";
																			$lista=UsuariosPadre::obtenerTodosLosDatosDeUsuarios($filtro);
																			while($dato=mysqli_fetch_array($lista, MYSQLI_BOTH)){
																				$nombre=UsuariosPadre::nombreCompletoDelUsuario($dato)." - ".$dato["pes_nombre"];
																	?>
																		<option value="<?=$dato["uss_id"];?>" selected><?=$nombre;?></option>
																	<?php
																			}
																		}
																	?>
																</select>
		                                                    </div>
															<script>
																$(document).ready(function() {
																	$('#select_usuario').select2({
																	placeholder: 'Seleccione el usuario...',
																	theme: "bootstrap",
																	multiple: true,
																		ajax: {
																			type: 'GET',
																			url: '../compartido/ajax-listar-usuarios.php',
																			processResults: function(data) {
																				data = JSON.parse(data);
																				return {
																					results: $.map(data, function(item) {
																						return {
																							id: item.value,
																							text: item.label
																						}
																					})
																				};
																			}
																		}
																	});
																});
															</script>
															<label>Asunto:</label>
		                                                    <div class="form-group">
		                                                        <input type="text" tabindex="1" class="form-control" id="asunto" name="asunto" value="<?php if(isset($_GET["asunto"])){ echo base64_decode($_GET["asunto"]);}?>" required>
		                                                    </div>
															<?php $nombreEmisor=UsuariosPadre::nombreCompletoDelUsuario($datosUsuarioActual); ?>
		                                                    <div class="form-group">
																<textarea cols="80" id="editor1" name="contenido" class="form-control" rows="8" placeholder="Escribe tu mensaje" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;" required>
																	<br>
																	<br>
																	<br>
																	--- --- ---
																	<p>    Cordialmente, </p>
																	<small><b><?=$nombreEmisor;?></b></small>
																</textarea>
		                                                    </div>
															
		                                                    <div class="btn-group margin-top-20 ">
				                                                <button type="button" onclick="enviarMensajes(<?=$_SESSION['bd']?>,<?=$_SESSION['idInstitucion']?>,<?=$_SESSION['id']?>,'<?=$nombreEmisor?>')" class="btn btn-primary btn-sm margin-right-10"><i class="fa fa-check"></i> Enviar</button>
				                                                <button type="reset" class="btn btn-sm btn-default margin-right-10"><i class="fa fa-times"></i> Cancelar</button>
				                                            </div>
		                                                </form>
		                                            </div>
		                                        </div>
		                                    </div>
		                                </div>
			                            </div>
			                        </div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
				<script>
					socket.on("envio_correo_<?=$_SESSION['id']?>_<?=$_SESSION['idInstitucion']?>",async (data) => {
						if (data["ema_id"] != null || data["ema_id"] !== '' || data["ema_id"] !== undefined) {
							$.toast({
								heading: 'Notificación',  
								text: 'Mensaje enviado correctamente.',
								position: 'bottom-right',
								showHideTransition: 'slide',
								loaderBg:'#26c281', 
								icon: 'success', 
								hideAfter: 5000, 
								stack: 6
							})
							location.href='mensajes.php?opt=Mg==';
						}else{
							$.toast({
								heading: 'Notificación',  
								text: 'Mensaje no enviado, intente nuevamente.',
								position: 'bottom-right',
								showHideTransition: 'slide',
								loaderBg:'#ff6849',
								icon: 'warning',
								hideAfter: 5000, 
								stack: 6
							})
						}
					});
				</script>