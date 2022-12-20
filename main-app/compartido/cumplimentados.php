<?php
include("../modelo/conexion.php");
?>

											<?php
											$cumpleU = mysqli_query($conexion, "SELECT uss_nombre, YEAR(uss_fecha_nacimiento) AS agno, uss_foto, uss_id, uss_mostrar_edad FROM usuarios 
											WHERE MONTH(uss_fecha_nacimiento)='".date("m")."' AND DAY(uss_fecha_nacimiento)='".date("d")."'");
											while($cumple = mysqli_fetch_array($cumpleU, MYSQLI_BOTH)){
												$edad = date("Y") - $cumple['agno'];
											?>
											<div class="user-panel">
												<div class="pull-left image">
													<img src="../files/fotos/<?=$cumple['uss_foto'];?>" class="img-circle user-img-circle" alt="User Image" />
												</div>
												<div class="pull-left info">
													<p>
														<a href="#">
															<?=strtoupper($cumple['uss_nombre']);?>
															<?php if($cumple['uss_mostrar_edad']==1){echo "(".$edad.")";}?>
														</a><br>
														<a href="mensajes-redactar.php?para=<?=$cumple['uss_id'];?>&asunto=TE DESEO UN FELIZ CUMPLEAÑOS" style="font-size: 12px; color: crimson;"><i class="fa fa-envelope-o"></i> Felicitar <?php //echo $frases[236][$datosUsuarioActual[8]];?></a>
													</p>
												</div>
											</div>	
											<?php }?>
											<p>&nbsp;</p>
										