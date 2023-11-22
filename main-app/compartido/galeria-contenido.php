<div class="row">
                        <div class="col-md-12">
									
                       		<div class="row">
											<?php
											$datosConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_GENERAL.".usuarios uss
											INNER JOIN ".$baseDatosServicios.".general_perfiles ON pes_id=uss_tipo
											WHERE uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
											ORDER BY uss_nombre
											LIMIT 0,100
											");
											while($datos = mysqli_fetch_array($datosConsulta, MYSQLI_BOTH)){
												$fileFoto = "../files/fotos/".$datos['uss_foto'];
												if(empty($datos['uss_foto']) or !file_exists($fileFoto) or empty($datos['uss_nombre'])) continue;
												
											?>	
					                        <div class="col-md-3">
				                                <div class="card card-box">
				                                    <div class="card-body no-padding ">
				                                    	<div class="doctor-profile">
				                                            <img src="../files/fotos/<?=$datos['uss_foto'];?>" class="doctor-pic" alt=""> 
					                                        <div class="profile-usertitle">
					                                            <div class="doctor-name"><?=$datos['pes_nombre'];?></div>
					                                            <div class="name-center"><?=substr(UsuariosPadre::nombreCompletoDelUsuario($datos),0,60);?></div>
					                                        </div>
				                                        </div>
				                                    </div>
				                                </div>
					                        </div>
											<?php }?>
                    	   </div>
								
                        </div>
                    </div>