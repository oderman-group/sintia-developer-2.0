<div class="panel" style="position: sticky; top:0;">
						        <header class="panel-heading panel-heading-red">Proceso de matrícula</header>

						        <div class="panel-body">
						            <p>&nbsp;</p>
						            <ul class="list-group list-group-unbordered">
						                
						                    <li class="list-group-item">
						                        <a href="matricula.php">1. Actualizar datos.</a>
												<?php if($datosEstudianteActual["mat_actualizar_datos"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

                                            <li class="list-group-item">
						                        <a href="pago-matricula.php">2. Pago matrícula.</a>
												<?php if($datosEstudianteActual["mat_pago_matricula"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

                                            <li class="list-group-item">
						                        <a href="contrato.php">3. Contrato.</a>
												<?php if($datosEstudianteActual["mat_contrato"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>


											<li class="list-group-item">
						                        <a href="pagare.php">4. Pagaré.</a>
												<?php if($datosEstudianteActual["mat_pagare"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

											<li class="list-group-item">
						                        <a href="comp-academico.php">5. Compromiso académico.</a>
												<?php if($datosEstudianteActual["mat_compromiso_academico"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

											<li class="list-group-item">
						                        <a href="comp-convivencia.php">6. Compromiso de convivencia.</a>
												<?php if($datosEstudianteActual["mat_compromiso_convivencia"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

											<li class="list-group-item">
						                        <a href="manual.php">7. Manual de convivencia.</a>
												<?php if($datosEstudianteActual["mat_manual"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

											<li class="list-group-item">
						                        <a href="contrato14.php">8. Contrato mayores de 14 años.</a>
												<?php if($datosEstudianteActual["mat_mayores14"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>

											<li class="list-group-item">
						                        <a href="adjuntar-firma.php">9. Firma matrícula.</a>
												<?php if($datosEstudianteActual["mat_hoja_firma"] == 1){?><i class="fa fa-check-square"></i><?php }?>
						                        <div class="profile-desc-item pull-right">&nbsp;</div>
						                    </li>
						                
						            </ul>

						        </div>
						    </div>