<?php 
							//MENÚ DOCENTES
							if($datosUsuarioActual['uss_tipo']==TIPO_DOCENTE){
								if(!empty($_SESSION["infoCargaActual"])) {
									$datosCargaActual = $_SESSION["infoCargaActual"]['datosCargaActual'];
								}
							?>
							
							<li class="nav-item" data-step="13" data-intro="<b>Cargas académicas:</b> Aquí encontrarás las cargas académicas que los directivos te han asignado para trabajar. Debes seleccionar una carga primero, antes de empezar a llenar cualquier información como calificaciones, actividades, foros, etc." data-position='right' data-scrollTo='tooltip'>
	                            <a href="cargas.php" class="nav-link nav-toggle"> <i class="material-icons">class</i>
	                                <span class="title"><?=$frases[12][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>
							
							
							<?php 
							if((!empty($_COOKIE["carga"]) && !empty($_COOKIE["periodo"])) || (!empty($_GET["carga"]) && !empty($_GET["periodo"]))){
								$arrayItemsAcademico = [
									"DC0034","DC0080", "DC0035", "DC0011", "DC0079", "DC0039", "DC0022", "DC0043", "DC0046", "DC0012", "DC0037", "DC0018", "DC0015", "DC0021", "DC0020", "DC0007", "DC0029", "DC0025", "DC0070", "DC0072", "DC0071", "DC0019", "DC0028", "DC0077"
								]
							?>
							<li <?php agregarClass(MENU_PADRE, $arrayItemsAcademico) ?>>
	                            <a href="#" class="nav-link nav-toggle"> <i class="material-icons">assignment_ind</i>
	                                <span class="title"><?=$frases[88][$datosUsuarioActual['uss_idioma']]?></span> <span class="arrow"></span>
	                            </a>
	                            <ul class="sub-menu" <?php agregarClass(SUB_MENU, $arrayItemsAcademico)?>>
									
									<?php if(isset($datosCargaActual) && ($datosCargaActual['car_indicador_automatico']==0 or $datosCargaActual['car_indicador_automatico']==null)){?>
	                                	<li <?php agregarClass(MENU,["DC0034", "DC0019", "DC0028", "DC0077"]) ?>><a href="indicadores.php" class="nav-link "> <span class="title"><?=$frases[63][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php }?>
									
									<li <?php agregarClass(MENU,["DC0035", "DC0021", "DC0020", "DC0029", "DC0039", "DC0007"]) ?>><a href="calificaciones.php" class="nav-link "> <span class="title"><?=$frases[6][$datosUsuarioActual['uss_idioma']];?></span></a></li>

									<?php if(!empty($arregloModulos) && array_key_exists(11, $arregloModulos)){?>
										<li <?php agregarClass(MENU,["DC0046", "DC0025", "DC0070", "DC0072", "DC0071"]) ?>><a href="clases.php" class="nav-link "> <span class="title"><?=$frases[7][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php }?>

									<?php if(!empty($arregloModulos) && array_key_exists(15, $arregloModulos)){?>
										<li <?php agregarClass(MENU,["DC0012", "DC0015"]) ?>><a href="cronograma-calendario.php" class="nav-link "> <span class="title"><?=$frases[111][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php }?>
									
									<li <?php agregarClass(MENU,["DC0022"]) ?>><a href="importar-info.php" class="nav-link "> <span class="title"><?=$frases[167][$datosUsuarioActual['uss_idioma']];?></span></a> </li>

									<?php if(!empty($arregloModulos) && array_key_exists(14, $arregloModulos)){?>
										<li <?php agregarClass(MENU,["DC0018"]) ?>><a href="actividades.php" class="nav-link "> <span class="title"><?=$frases[112][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php }?>
									
									<?php if(!empty($arregloModulos) && array_key_exists(12, $arregloModulos)){?>
										<li <?php agregarClass(MENU,["DC0043"]) ?>><a href="evaluaciones.php" class="nav-link "> <span class="title"><?=$frases[114][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php }?>

									<?php if(!empty($arregloModulos) && array_key_exists(13, $arregloModulos)){?>
										<li <?php agregarClass(MENU,["DC0037"]) ?>><a href="foros.php" class="nav-link "> <span class="title"><?=$frases[113][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php }?>

	                            </ul>
	                        </li>
							<?php }?>
							
							<?php if(!empty($arregloModulos) && array_key_exists(3, $arregloModulos)){?>
								<li class="nav-item">
									<a href="#" class="nav-link nav-toggle"> <i class="fa fa-gavel"></i>
										<span class="title"><?=$frases[90][$datosUsuarioActual['uss_idioma']];?></span> <span class="arrow"></span>
									</a>
									<ul class="sub-menu">
										<li class="nav-item"><a href="reportes-crear.php" class="nav-link"> <span class="title"><?=$frases[96][$datosUsuarioActual['uss_idioma']];?></span></a></li>
										<li class="nav-item"><a href="reportes-lista.php" class="nav-link"> <span class="title"><?=$frases[97][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									</ul>
								</li>
							<?php }?>
							
							<?php if(isset($datosCargaActual) && $datosCargaActual['car_director_grupo']==1){?>
							<li class="nav-item">
	                            <a href="comportamiento.php" class="nav-link nav-toggle"> <i class="fa fa-pencil-square-o"></i>
	                                <span class="title"><?=$frases[234][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>
							<li class="nav-item">
	                            <a href="aspectos.php" class="nav-link nav-toggle"> <i class="fa fa-pencil-square-o"></i>
	                                <span class="title">Aspectos</span> 
	                            </a>
	                        </li>
							<?php }?>
							
							
							<?php if(isset($datosCargaActual) && !empty($datosCargaActual['car_id'])){?>
							<li class="nav-item">
	                            <a href="estudiantes.php" class="nav-link nav-toggle"> <i class="fa fa-group"></i>
	                                <span class="title">Mis <?=$frases[55][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>
	                        <?php }?>

	                        <li class="nav-item">
	                            <a href="estudiantes-todos.php" class="nav-link nav-toggle"> <i class="fa fa-group"></i>
	                                <span class="title">Todos los estudiantes</span> 
	                            </a>
	                        </li>
							
							<li class="nav-item">
	                            <a href="cargas-carpetas.php" class="nav-link nav-toggle"> <i class="fa fa-folder"></i>
	                                <span class="title"><?=$frases[216][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>
							
							<li class="nav-item active" data-step="11" data-intro="<b><?=$frases[175][$datosUsuarioActual['uss_idioma']];?>:</b> Encuentra los mejores productos y servicios complementarios." data-position='left'>
	                            <a href="marketplace.php" class="nav-link nav-toggle"> <i class="fa fa-shopping-cart"></i>
	                                <span class="title">Marketplace</span> 
	                            </a>
	                        </li>
							
							
							<li class="nav-item">
	                            <a href="https://www.youtube.com/playlist?list=PL119_PkDEyLohcyXRnqHd36SqvLeKb5hF" target="_blank" class="nav-link nav-toggle"> <i class="fa fa-youtube"></i>
	                                <span class="title">TUTORIALES DE AYUDA</span> 
	                            </a>
	                        </li>
							
							
							
							
							<?php }?>