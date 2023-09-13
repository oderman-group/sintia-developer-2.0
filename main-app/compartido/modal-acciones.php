

<div class="modal fade bd-example-modal-sm" id="modalMsjMarketplace" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="z-index:10000;">
	   <div class="modal-dialog modal-sm"  style="max-width: 600px!important;">
		  <div class="modal-content">
			 <div class="modal-body">
				 <div class="row">
				 <div class="col-sm-12">

                            <div class="card card-box">
                                <div class="card-head">
                                    <header>Contactar con el vendedor</header>
                                </div>
                                <div class="card-body" id="bar-parent6">
                                    <form class="form-horizontal" action="../compartido/guardar.php" method="post">
										<input type="hidden" name="id" value="18">
										<input type="hidden" id="destinoMarketplace" name="destinoMarketplace">
										<input type="hidden" id="asuntoMarketplace" name="asuntoMarketplace">
										
										<div class="form-group row">
											<div class="col-sm-12">
												<textarea name="contenido" class="form-control" rows="3" placeholder="Preguntale algo al vendedor sobre esta publicación..." style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;" required></textarea>
											</div>
										</div>

										<div class="form-group">
											<div class="offset-md-3 col-md-9">
												<button type="submit" class="btn btn-info">Enviar ahora</button>
												<button data-dismiss="modal" class="btn btn-danger"><?=$frases[171][$datosUsuarioActual[8]];?></button>
											</div>
										</div>
									</form>
                                </div>
                            </div>
					 
                        </div>
				 </div>
				 
			 </div>
			 <div class="modal-footer"></div>
		  </div>
	   </div>
	</div>






























