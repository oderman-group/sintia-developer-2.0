<div class="panel" style="position: sticky; top:0;">
						        <header class="panel-heading panel-heading-red"><?=$frases[330][$datosUsuarioActual[8]];?></header>

						        <div class="panel-body">
						            <p>&nbsp;</p>
						            <ul class="list-group list-group-unbordered">
						                
						                    <li class="list-group-item">
						                        <a href="matricula.php">1. <?=$frases[331][$datosUsuarioActual[8]];?></a>
												<?php if($datosEstudianteActual["mat_actualizar_datos"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

                                            <li class="list-group-item">
						                        <a href="pago-matricula.php">2. <?=$frases[332][$datosUsuarioActual[8]];?></a>
												<?php if($datosEstudianteActual["mat_pago_matricula"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

                                            <li class="list-group-item">
						                        <a href="contrato.php">3. <?=$frases[333][$datosUsuarioActual[8]];?></a>
												<?php if($datosEstudianteActual["mat_contrato"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>


											<li class="list-group-item">
						                        <a href="pagare.php">4. <?=$frases[334][$datosUsuarioActual[8]];?></a>
												<?php if($datosEstudianteActual["mat_pagare"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

											<li class="list-group-item">
						                        <a href="comp-academico.php">5. <?=$frases[335][$datosUsuarioActual[8]];?></a>
												<?php if($datosEstudianteActual["mat_compromiso_academico"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

											<li class="list-group-item">
						                        <a href="comp-convivencia.php">6. <?=$frases[336][$datosUsuarioActual[8]];?></a>
												<?php if($datosEstudianteActual["mat_compromiso_convivencia"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

											<li class="list-group-item">
						                        <a href="manual.php">7. <?=$frases[337][$datosUsuarioActual[8]];?></a>
												<?php if($datosEstudianteActual["mat_manual"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

											<li class="list-group-item">
						                        <a href="contrato14.php">8. <?=$frases[338][$datosUsuarioActual[8]];?></a>
												<?php if($datosEstudianteActual["mat_mayores14"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

											<li class="list-group-item">
						                        <a href="adjuntar-firma.php">9. <?=$frases[339][$datosUsuarioActual[8]];?></a>
												<?php if($datosEstudianteActual["mat_hoja_firma"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>
						                
						            </ul>

						        </div>
						    </div>