					<div class="row">
                        <div class="col-sm-9">
                            <div class="card card-box">
                                <div class="card-head">
                                    <header>Registrar mi negocio</header>
                                </div>
                                <div class="card-body " id="bar-parent6">
                                    <form class="form-horizontal" action="../compartido/guardar.php" method="post" enctype="multipart/form-data">
										<input type="hidden" name="id" value="16">
                                        
										<div class="form-group row">
                                            <label class="col-sm-3 control-label">Nombre de tu negocio (*)</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="nombre" class="form-control" style="font-weight: bold;" required value="EL NEGOCIO DE <?=$datosUsuarioActual['uss_nombre'];?>" autofocus>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-3 control-label">Email de contacto (*)</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="email" class="form-control" required value="<?=$datosUsuarioActual['uss_email'];?>">
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-3 control-label">Teléfono de contacto (*)</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="telefono" class="form-control" required value="<?=$datosUsuarioActual['uss_celular'];?>">
                                            </div>
                                        </div>
										

										
										<div class="form-group row">
												<label class="col-sm-3 control-label">Sector de tu negocio (*)</label>
												<div class="col-sm-9">
													<select id="multiple" class="form-control select2-multiple" multiple name="sector[]" required aucomplete="off">
													<?php
													$infoConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosMarketPlace.".servicios_categorias");
													while($infoDatos = mysqli_fetch_array($infoConsulta, MYSQLI_BOTH)){
													?>	
													  <option value="<?=$infoDatos[0];?>"><?=strtoupper($infoDatos['svcat_nombre']);?></option>
													<?php }?>	
													</select>
													<span style="color: navy;">Seleccione al menos un sector.</span>
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