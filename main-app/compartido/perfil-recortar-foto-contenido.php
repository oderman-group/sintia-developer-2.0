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

									<div id="crop_wrapper">
									  <img src="../files/fotos/<?=$datosUsuarioActual['uss_foto'];?>">
									  <div id="crop_div"></div>
									</div>

									<p>&nbsp;</p>
									<div style="margin-left:150px;">
										<form method="post" action="../compartido/do-crop.php" onsubmit="return crop();">
										  <input type="hidden" name="tipoUsuario" value="<?=$datosUsuarioActual['uss_tipo'];?>">
											
										  <input type="hidden" value="" id="top" name="top">
										  <input type="hidden" value="" id="left" name="left">
										  <input type="hidden" value="" id="right" name="right">
										  <input type="hidden" value="" id="bottom" name="bottom">
										  <input type="submit" name="crop_image" value="RECORTAR Y FINALIZAR" style="width:200px; height:50px; background:#036; color:#FFF;">
										</form>
									</div>
									
									
                                </div>
                            </div>
                        </div>