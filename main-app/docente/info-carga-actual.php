<?php if( !CargaAcademica::validarPermisoPeriodosDiferentes($datosCargaActual, $periodoConsultaActual) ) {?>
	<p style="color: tomato;"> Podrás consultar la información de otros periodos diferentes al actual, pero no se podrán hacer modificaciones. </p>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var miEnlace = document.getElementById("configurarCargaHref");

			// Agrega un manejador de eventos para prevenir la acción predeterminada del enlace
			miEnlace.addEventListener("click", function(event) {
			event.preventDefault();
			Swal.fire("No es posible hacer ediciones en periodos anteriores."); 
			});
		});
	</script>
<?php }?>

										<div class="panel">
											<header class="panel-heading panel-heading-yellow"><?=$frases[207][$datosUsuarioActual['uss_idioma']];?></header>

											<div class="panel-body">
												<p><?=$frases[208][$datosUsuarioActual['uss_idioma']];?></p>
												<ul class="list-group list-group-unbordered">
													<li class="list-group-item">
														<b><?=strtoupper($frases[49][$datosUsuarioActual['uss_idioma']]);?></b> 
														<div class="profile-desc-item pull-right"><?=$datosCargaActual['car_id'];?></div>
													</li>
													<li class="list-group-item">
														<b><?=strtoupper($frases[116][$datosUsuarioActual['uss_idioma']]);?></b> 
														<div class="profile-desc-item pull-right"><?=strtoupper($datosCargaActual['mat_nombre']);?></div>
													</li>
													<li class="list-group-item">
														<b><?=strtoupper($frases[26][$datosUsuarioActual['uss_idioma']]);?></b> 
														<div class="profile-desc-item pull-right"><?=strtoupper($datosCargaActual['gra_nombre']." ".$datosCargaActual['gru_nombre']);?></div>
													</li>
													<li class="list-group-item">
														<b><?=strtoupper($frases[27][$datosUsuarioActual['uss_idioma']]);?></b> 
														<div class="profile-desc-item pull-right"><?=$periodoConsultaActual;?></div>
													</li>
													<li class="list-group-item">
														<b>I.H</b> 
														<div class="profile-desc-item pull-right"><?=$datosCargaActual['car_ih'];?></div>
													</li>
													<li class="list-group-item">
														<b>D.GRUPO</b> 
														<div class="profile-desc-item pull-right"><?=$dgArray[$datosCargaActual['car_director_grupo']];?></div>
													</li>
													<!--
													<li class="list-group-item">
														<b>% <?=strtoupper($frases[63][$datosUsuarioActual['uss_idioma']]);?></b> 
														<div class="profile-desc-item pull-right"><?=$configCargasArray[$datosCargaActual['car_valor_indicador']];?></div>
													</li>
													<li class="list-group-item">
														<b>% <?=strtoupper($frases[6][$datosUsuarioActual['uss_idioma']]);?></b> 
														<div class="profile-desc-item pull-right"><?=$configCargasArray[$datosCargaActual['car_configuracion']];?></div>
													</li>
													<li class="list-group-item">
														<b>MAX. <?=strtoupper($frases[63][$datosUsuarioActual['uss_idioma']]);?></b> 
														<div class="profile-desc-item pull-right"><?=$datosCargaActual['car_maximos_indicadores'];?></div>
													</li>
													<li class="list-group-item">
														<b>MAX. <?=strtoupper($frases[6][$datosUsuarioActual['uss_idioma']]);?></b> 
														<div class="profile-desc-item pull-right"><?=$datosCargaActual['car_maximas_calificaciones'];?></div>
													</li>
													<li class="list-group-item">
														<b>G. INFORME AUTOMÁTICO</b> 
														<div class="profile-desc-item pull-right"><?=$datosCargaActual['car_fecha_generar_informe_auto'];?></div>
													</li>
													-->
												</ul>
												
												

											</div>
											<p align="center"><a href="cargas-configurar.php" class="btn yellow" id="configurarCargaHref"><?=$frases[14][$datosUsuarioActual['uss_idioma']];?></a></p>
										</div>
