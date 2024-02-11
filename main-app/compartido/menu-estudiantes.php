<?php 
							//MENÚ ESTUDIANTES
							if($datosUsuarioActual['uss_tipo'] == TIPO_ESTUDIANTE){?>

	                        
							
							<li class="nav-item">
	                            <a href="cargas.php" class="nav-link nav-toggle"> <i class="material-icons">class</i>
	                                <span class="title"><?=$frases[73][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>


							<?php if((!empty($_COOKIE["cargaE"]) && !empty($_COOKIE["periodoE"])) || (!empty($_GET["carga"]) && !empty($_GET["periodo"]))){?>
							<li class="nav-item">
	                            <a href="#" class="nav-link nav-toggle"> <i class="material-icons">assignment_ind</i>
	                                <span class="title"><?=$frases[88][$datosUsuarioActual['uss_idioma']];?></span> <span class="arrow"></span>
	                            </a>
	                            <ul class="sub-menu">

	                            	<?php 
	                            	//Temporal para que el estudiante no vea notas ni nada de eso.
	                            	if($config['conf_mostrar_calificaciones_estudiantes']!=1){}else{?>

	                                <li class="nav-item"><a href="indicadores.php" class="nav-link "> <span class="title"><?=$frases[63][$datosUsuarioActual['uss_idioma']];?></span></a></li>

									
									<li class="nav-item"><a href="calificaciones.php" class="nav-link "> <span class="title"><?=$frases[6][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									
									<?php if($config['conf_sin_nota_numerica']==1){}else{?>
									
									<li class="nav-item"><a href="periodos-resumen.php" class="nav-link "> <span class="title"><?=$frases[84][$datosUsuarioActual['uss_idioma']];?></span></a> </li>
									
									<?php }?>

									<?php }?>
									
									<?php if(!empty($arregloModulos) && array_key_exists(11, $arregloModulos)){?>
										<li class="nav-item"><a href="ausencias.php" class="nav-link "> <span class="title"><?=$frases[7][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php }?>
									
									<?php if(!empty($arregloModulos) && array_key_exists(15, $arregloModulos)){?>
										<li class="nav-item"><a href="cronograma-calendario.php" class="nav-link "> <span class="title"><?=$frases[111][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php }?>
									
									<?php if(!empty($arregloModulos) && array_key_exists(14, $arregloModulos)){?>
										<li class="nav-item"><a href="actividades.php" class="nav-link "> <span class="title"><?=$frases[112][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php }?>
									
									<?php if(!empty($arregloModulos) && array_key_exists(13, $arregloModulos)){?>
										<li class="nav-item"><a href="foros.php" class="nav-link "> <span class="title"><?=$frases[113][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php }?>
									
									<?php if(!empty($arregloModulos) && array_key_exists(12, $arregloModulos)){?>
										<li class="nav-item"><a href="evaluaciones.php" class="nav-link "> <span class="title"><?=$frases[114][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php }?>
									
	                            </ul>
	                        </li>
							<?php }?>
							
							<li class="nav-item">
	                            <a href="cargas-carpetas.php" class="nav-link nav-toggle"> <i class="fa fa-folder"></i>
	                                <span class="title"><?=$frases[216][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>

							
							<li class="nav-item">
	                            <a href="matricula.php" class="nav-link nav-toggle"> <i class="fa fa-pencil-square-o"></i>
	                                <span class="title"><?=$frases[60][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>
							
							<li class="nav-item">
	                            <a href="estado-de-cuenta.php" class="nav-link nav-toggle"> <i class="material-icons">attach_money</i>
	                                <span class="title"><?=$frases[104][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>
							
							<li class="nav-item">
	                            <a href="reportes-disciplinarios.php" class="nav-link nav-toggle"> <i class="material-icons">backspace</i>
	                                <span class="title"><?=$frases[105][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>

							<?php if($config['conf_ver_observador']==1){?>

							<li class="nav-item">
	                            <a href="aspectos.php" class="nav-link nav-toggle"> <i class="material-icons">backspace</i>
	                                <span class="title"><?=$frases[264][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>

							<?php }?>
							
							<li class="nav-item">
	                            <a href="estudiantes.php" class="nav-link nav-toggle"> <i class="material-icons">group</i>
	                                <span class="title"><?=$frases[74][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>

							<li class="nav-item active" data-step="11" data-intro="<b><?=$frases[175][$datosUsuarioActual['uss_idioma']];?>:</b> Encuentra los mejores productos y servicios complementarios." data-position='left'>
	                            <a href="marketplace.php" class="nav-link nav-toggle bg-warning text-dark"> <i class="fa fa-shopping-cart text-dark"></i>
	                                <span class="title">Marketplace</span> 
	                            </a>
	                        </li>

							
							
							<?php }?>