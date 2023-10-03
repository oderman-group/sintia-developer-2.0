<link rel="stylesheet" href="../../librerias/croppie/croppie.css">
<script src="../../librerias/croppie/croppie.js"></script>
						<div class="col-sm-3">
							<div class="panel">
								<header class="panel-heading panel-heading-blue">Indicaciones</header>
								<div class="panel-body">
									<p><b>1.</b> Ajuste el recorte a la parte de la foto que deseas y haz click en el botón <b>RECORTAR Y FINALIZAR</b></p>
									<p><b>2.</b> Los formatos permitidos para recortar la fotos son <b>JPG Y JPEG</b>.</p>
								</div>
							</div>
							<?php include("../compartido/publicidad-lateral.php");?>
						</div>	

						<div class="col-sm-9">
							
							<div style="background-color: tan; color:black; padding: 10px;">
								<h4 style="font-weight: bold;">RECORTA TU FOTO</h4>
								Al parecer esta foto no es cuadrada. Tiene un ancho de <b><?=base64_decode($_GET["ancho"]);?></b> y un alto de <b><?=base64_decode($_GET["alto"]);?></b><br>
								Puedes recortarla con la herramienta de recorte de SINTIA en este momento.<br>
								<mark>El formato de esta foto es: <b><?=strtoupper(base64_decode($_GET["ext"]));?></b></mark>
							</div>
							
                            <div class="card card-box">
                                <div class="card-body " id="bar-parent6">
									<div id="croppie-editor" class="d-none">
										<div id="croppie-field"></div>
										<div class="mx-0 text-center">
											<button class="btn btn-sm btn-light border border-dark rounded-0" id="rotate-left" type="button">Rotar a la izquierda</button>
											<button class="btn btn-sm btn-light border border-dark rounded-0" id="rotate-right" type="button">Rotar a la derecha</button>
											<button class="btn btn-sm btn-primary rounded-0" id="upload-btn" type="button">RECORTAR Y FINALIZAR</button>
										</div>
									</div>
                                </div>
                            </div>
                        </div>
						<?php
							$destinos = validarUsuarioActual($datosUsuarioActual);
							$url = $destinos.'perfil.php';
						?>
						<script>
							var $croppie = new Croppie($('#croppie-field')[0], {
								enableExif: true,
								enableResize:false,
								enableZoom:true,
								boundary: { width: 800, height: 800 },
								viewport: {
									height: 600,
									width: 600
								},
								enableOrientation: true
							})
							$(document).ready(function(){
								var img_name;

								function cargarImagenPreexistente(src) {
									img_name = '<?=$datosUsuarioActual['uss_foto'];?>';
									$croppie.bind({
										url: src
									});
									$('#croppie-editor').removeClass('d-none');
								}

								cargarImagenPreexistente('../files/fotos/<?=$datosUsuarioActual['uss_foto'];?>');

								$('#rotate-left').click(function(){
									$croppie.rotate(90);
								})

								$('#rotate-right').click(function(){
									$croppie.rotate(-90);

								})

								$('#upload-btn').click(function(){
									$croppie.result({
										type:'base64',
										format: 'png'
									}).then((imgBase64)=>{
									$.ajax({
										url:'../compartido/do-crop.php',
										method:'POST',
										data: { 'img' : imgBase64, 'fname' : img_name },
										dataType: 'json',
										error: err => {
											console.error(err)
										},
										success: function(response){
											if(response.status == 'success'){
                    							window.location.href = '<?=$url?>';
											}else{
												console.error(response)
											}
										}
									})
									})
								})
							})
						</script>