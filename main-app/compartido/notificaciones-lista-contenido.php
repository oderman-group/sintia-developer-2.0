								<?php
								mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".general_alertas SET alr_vista=1 WHERE alr_usuario='".$_SESSION["id"]."' AND alr_vista=0 AND alr_year='" . $_SESSION["bd"] . "'");
								
								?>
								<div class="col-md-9">
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header><?=$frases[218][$datosUsuarioActual['uss_idioma']];?></header>
                                            <div class="tools">
                                                <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
			                                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
			                                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
											
											<a href="#" name="../compartido/notificaciones-eliminar-todas.php" onClick="deseaEliminar(this)" class="btn btn-warning"><i class="fa fa-trash"></i> Eliminar todas las notificaciones</a>
											
                                        <div class="table-scrollable">
                                    		<table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
														<th><?=$frases[49][$datosUsuarioActual['uss_idioma']];?></th>
														<th><?=$frases[218][$datosUsuarioActual['uss_idioma']];?></th>
														<th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													 $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_alertas 
													 WHERE alr_usuario='".$_SESSION["id"]."' AND alr_year='" . $_SESSION["bd"] . "'
													 ORDER BY alr_id DESC
													 ");
													$contReg=1; 
													while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
														$dias = mysqli_fetch_array(mysqli_query($conexion, "SELECT DATEDIFF('".$resultado['alr_fecha_envio']."','".date("Y-m-d")."')"), MYSQLI_BOTH);
														$dias = ($dias[0] * -1);
														switch($dias){
															case ($dias==0): $msjFecha = 'Hoy'; break;
															case ($dias>0 and $dias<30): $msjFecha = 'Hace '.$dias.' dias'; break;
															case ($dias>30 and $dias<60): $msjFecha = 'Hace más de 1 mes'; break;
															case ($dias>60 and $dias<365): $msjFecha = 'Hace varios meses'; break;
															case ($dias>365): $msjFecha = 'Hace más de 1 año'; break;
														}
													 ?>
													<tr>
                                                        <td><?=$contReg;?></td>
														<td><?=$resultado['alr_id'];?></td>
														<td>
															<a href="<?=$resultado['alr_url_acceso'];?>">
																<span style="font-weight: bold;"><?=$resultado['alr_nombre'];?></span><br>
																<span style="font-size: 12px;"><?=$resultado['alr_descripcion'];?></span><br>
																<span style="font-size: 10px; color: slategrey;"><?="<b>".$msjFecha."</b> - ".$resultado['alr_fecha_envio'];?></span>
															</a>	
														</td>

														<td>
															<a href="#" name="../compartido/notificaciones-eliminar.php?idR=<?=$resultado['alr_id'];?>" onClick="deseaEliminar(this)"><i class="fa fa-trash-o"></i></a>
														</td>
                                                    </tr>
													<?php 
														 $contReg++;
													  }
													  ?>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>