<!-- start sidebar menu -->
 			<div class="sidebar-container" >
 				<div class="sidemenu-container navbar-collapse collapse fixed-menu">
	                <div id="remove-scroll">
				
						<?php
						//Mostrar a los directivos si tiene deuda
						if($config['conf_deuda']==1 and $datosUsuarioActual['uss_tipo']==TIPO_DIRECTIVO){
						?>
							<div class="mt-4 p-1" style="background-color: yellow;">
								<p>
									<h4>¡Saldo pendiente!</h4>
                                    <b>NRO. FACTURA:</b> <?=$config['conf_numero_factura'];?><br />
									<b>CONCEPTO:</b> <?=$config['conf_concepto'];?><br />
                                    <b>VALOR NETO:</b> $<?=number_format($config['conf_valor'],0,",",".");?> COP.
                                </p>
						
								<p><a href="https://plataformasintia.com/files-general/qr_sintia_abonos.pdf" class="btn btn-danger" target="_blank">ABONAR CON QR BANCOLOMBIA</a></p>
							</div>
						<?php }?>
						
	                    <ul class="sidemenu  page-header-fixed <?=$datosUsuarioActual['uss_tipo_menu'];?>" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px" data-step="1" data-intro="<b>Menú principal:</b> Aquí encontrarás todas las opciones para el uso de la plataforma." data-position='left'>
	                        <li class="sidebar-toggler-wrapper hide">
	                            <div class="sidebar-toggler">
	                                <span></span>
	                            </div>
	                        </li>
	                        <?php
							$fotoPerfilUsr = $usuariosClase->verificarFoto($datosUsuarioActual['uss_foto']);
							?>
							<li class="sidebar-user-panel">
	                            <div class="user-panel">
	                                <div class="pull-left image">
	                                    <img src="<?=$fotoPerfilUsr;?>" class="img-circle user-img-circle" alt="User Image" />
	                                </div>
	                                <div class="pull-left info">
	                                    <p> <?=UsuariosPadre::nombreCompletoDelUsuario($datosUsuarioActual);?></p>
	                                </div>
	                            </div>
	                        </li>
							
							<?php
								if($datosUsuarioActual['uss_tipo']!=TIPO_DOCENTE){
							?>
							<li class="nav-item">
							<a href="como-empezar.php" class="nav-link nav-toggle">
	                                <i class="material-icons">toc</i>
	                                <span class="title"><?=$frases[255][$datosUsuarioActual['uss_idioma']];?></span>
                                	<span class="selected"></span>
	                            </a>
	                        </li>

							<?php }?>
							
							<li class="nav-item start">
							<a href="javascript:void(0);" onclick="javascript:introJs().start();" class="nav-link nav-toggle">
	                                <i class="fa fa-life-ring"></i>
	                                <span class="title">Tour SINTIA</span>
                                	<span class="selected"></span>
	                            </a>
	                        </li>

							<?php include_once("menu-metodos.php")?>
							<li <?php agregarClass(MENU,["DT0004"]) ?>>
							<a href="index.php" class="nav-link nav-toggle">
	                                <i class="material-icons">dashboard</i>
	                                <span class="title"><?=$frases[100][$datosUsuarioActual['uss_idioma']];?></span>
                                	<span class="selected"></span>
	                            </a>
	                        </li>
							
							<li class="nav-item" data-step="8" data-intro="<b><?=$frases[69][$datosUsuarioActual['uss_idioma']];?>:</b> Aquí podrás ver y publicar noticias. También verás cumpleaños y otra información de interés." data-position='left'>
	                            <a href="noticias.php" class="nav-link nav-toggle">
	                                <i class="material-icons">view_comfy</i>
	                                <span class="title"><?=$frases[69][$datosUsuarioActual['uss_idioma']];?></span>
	                            </a>
	                        </li>
							
							
							
							<?php 
								//MENÚ DIRECTIVOS
								if($datosUsuarioActual['uss_tipo']==TIPO_DIRECTIVO || $datosUsuarioActual['uss_tipo']==TIPO_DEV){							
								
								//MÓDULO ACADÉMICO
								if(!empty($arregloModulos) && array_key_exists(1, $arregloModulos)){
									if(Modulos::validarSubRol(["DT0102","DT0001","DT0062","DT0017","DT0020","DT0032","DT0121","DT0195"])){
							?>
							<li <?php agregarClass(MENU_PADRE,["DT0001","DT0062","DT0017","DT0020","DT0032","DT0121","DT0195","DT0196","DT0197"]) ?>>
	                            <a href="#" class="nav-link nav-toggle"> <i class="material-icons">assignment_ind</i>
	                                <span class="title"><?=$frases[88][$datosUsuarioActual['uss_idioma']];?></span> <span class="arrow"></span>
	                            </a>
	                            <ul class="sub-menu" <?php agregarClass(SUB_MENU,["DT0001","DT0062","DT0017","DT0020","DT0032","DT0121","DT0195","DT0196","DT0197"]) ?> >
									
									<?php 
										if(Modulos::validarSubRol(['DT0001'])){
									?>
	                                	<li <?php agregarClass(MENU,["DT0001"]) ?>><a href="estudiantes.php" class="nav-link "> <span class="title"><?=$frases[209][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php
										}
									
										if(Modulos::validarSubRol(['DT0062'])){
									?>
										<li <?php agregarClass(MENU,["DT0062"]) ?>><a href="cursos.php" class="nav-link "> <span class="title"><?=$frases[5][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php
										}
									
										if(Modulos::validarSubRol(['DT0195'])){
									?>
										<li <?php agregarClass(MENU,["DT0195","DT0196","DT0197"]) ?>><a href="grupos.php" class="nav-link "> <span class="title"><?=$frases[254][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php
										}
									
										if(Modulos::validarSubRol(['DT0017'])){
									?>
										<li <?php agregarClass(MENU,["DT0017"]) ?>><a href="areas.php" class="nav-link "> <span class="title"><?=$frases[93][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php
										}
									
										if(Modulos::validarSubRol(['DT0020'])){
									?>
										<li <?php agregarClass(MENU,["DT0020"]) ?>><a href="asignaturas.php" class="nav-link "> <span class="title"><?=$frases[73][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php
										}
									
										if(Modulos::validarSubRol(['DT0032'])){
									?>
										<li <?php agregarClass(MENU,["DT0032"]) ?>><a href="cargas.php" class="nav-link "> <span class="title"><?=$frases[12][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php
										}
										
										if(!empty($arregloModulos) && array_key_exists(9, $arregloModulos)){
											if(Modulos::validarSubRol(['DT0121'])){
									?>
										<li <?php agregarClass(MENU,["DT0121"]) ?>><a href="reservar-cupo.php" class="nav-link "> <span class="title">Reserva de cupos</span></a></li>
									<?php }}?>
									
	                            </ul>
	                        </li>
							<?php }}?>

							<?php 
							//MÓDULO INSCRIPCIONES Y ADMISIONES
							if(!empty($arregloModulos) && array_key_exists(8, $arregloModulos)){
								if(Modulos::validarSubRol(["DT0102"])){
							?>
								<li <?php agregarClass(MENU_PADRE,["DT0102", "DT0014"]) ?>>
									<a href="#" class="nav-link nav-toggle"> <i class="fa fa-address-book"></i>
										<span class="title">Inscripciones</span> <span class="arrow"></span>
									</a>
									<ul class="sub-menu" <?php agregarClass(SUB_MENU,["DT0102", "DT0014"]) ?>>
										<?php
											if(Modulos::validarSubRol(["DT0102"])){
										?>
											<li <?php agregarClass(MENU,["DT0102"]) ?>><a href="inscripciones.php" class="nav-link "> <span class="title">Listado de inscripciones</span></a></li>
										<?php }?>

										<?php
											if(Modulos::validarSubRol(["DT0014"])){
										?>
											<li <?php agregarClass(MENU,["DT0014"]) ?>><a href="configuracion-admisiones.php" class="nav-link "> <span class="title">Configuración</span></a></li>
										<?php }?>
									</ul>
								</li>
							<?php }}?>
							
							<?php 
							//MÓDULO FINANCIERO
							if(!empty($arregloModulos) && array_key_exists(2, $arregloModulos)){
								if(Modulos::validarSubRol(["DT0104"])){
							?>
								<li <?php agregarClass(MENU_PADRE,["DT0104", "DT0106", "DT0128", "DT0105"]) ?>>
									<a href="#" class="nav-link nav-toggle"> <i class="fa fa-money"></i>
										<span class="title"><?=$frases[89][$datosUsuarioActual['uss_idioma']];?></span> <span class="arrow"></span>
									</a>
									<ul class="sub-menu" <?php agregarClass(SUB_MENU,["DT0104", "DT0106", "DT0128", "DT0105", "DT0258", "DT0259", "DT0261"]) ?>>
										<?php
											if(Modulos::validarSubRol(["DT0104", "DT0258"])){
										?>
											<li <?php agregarClass(MENU,["DT0104", "DT0106", "DT0128", "DT0105"]) ?>><a href="movimientos.php" class="nav-link "> <span class="title"><?=$frases[95][$datosUsuarioActual['uss_idioma']];?></span></a></li>
											<li <?php agregarClass(MENU,["DT0258", "DT0259", "DT0261"]) ?>><a href="items.php" class="nav-link "> <span class="title">Items</span></a></li>
										<?php }?>
									</ul>
								</li>
							<?php }}?>
							
							<?php 
							//MÓDULO DISCIPLINARIO
							if(!empty($arregloModulos) && array_key_exists(3, $arregloModulos)){
								if(Modulos::validarSubRol(["DT0119","DT0117","DT0069","DT0066"])){
							?>
								<li class="nav-item">
									<a href="#" class="nav-link nav-toggle"> <i class="fa fa-gavel"></i>
										<span class="title"><?=$frases[90][$datosUsuarioActual['uss_idioma']];?></span> <span class="arrow"></span>
									</a>
									<ul class="sub-menu">
										<?php
											if(Modulos::validarPermisoEdicion()){
												if(Modulos::validarSubRol(["DT0119"])){
										?>
											<li class="nav-item"><a href="reportes-crear.php" class="nav-link"> <span class="title"><?=$frases[96][$datosUsuarioActual['uss_idioma']];?></span></a></li>
										<?php
												}
											}

											if(Modulos::validarSubRol(["DT0117"])){
										?>
											<li class="nav-item"><a href="reportes-lista.php" class="nav-link"> <span class="title"><?=$frases[97][$datosUsuarioActual['uss_idioma']];?></span></a></li>
										<?php
											}

											if(Modulos::validarSubRol(["DT0069"])){
										?>
											<li class="nav-item"><a href="disciplina-categorias.php" class="nav-link"> <span class="title">Categorías</span></a></li>
										<?php
											}
											
											if(Modulos::validarSubRol(["DT0066"])){
										?>
											<li class="nav-item"><a href="disciplina-faltas.php" class="nav-link"> <span class="title">Faltas</span></a></li>
										<?php
											}
										?>
									</ul>
								</li>
							<?php }}?>
							
							<?php 
							//MÓDULO ADMINISTRTIVO
							if(!empty($arregloModulos) && array_key_exists(4, $arregloModulos)){
								if(Modulos::validarSubRol(["DT0126","DT0122","DT0011"])){
							?>
							<li <?php agregarClass(MENU_PADRE,["DT0011","DT0122","DT0124","DT0126","DT0204","DT0205"]) ?>>
	                            <a href="#" class="nav-link nav-toggle"> <i class="fa fa-tachometer"></i>
	                                <span class="title"><?=$frases[87][$datosUsuarioActual['uss_idioma']];?></span> <span class="arrow"></span>
	                            </a>
	                            <ul class="sub-menu" <?php agregarClass(SUB_MENU,["DT0011","DT0122","DT0124","DT0126","DT0204","DT0205"])?>>
									<?php
										if(Modulos::validarSubRol(["DT0126"])){
									?>
										<li <?php agregarClass(MENU,["DT0126","DT0124"]) ?>><a href="usuarios.php" class="nav-link "> <span class="title"><?=$frases[75][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php
										}
										
										if(Modulos::validarSubRol(["DT0122"])){
									?>
										<li <?php agregarClass(MENU,["DT0122"]) ?>><a href="solicitudes.php" class="nav-link "> <span class="title">Solicitud desbloqueo</span></a></li>
									<?php
										}
										
										if(Modulos::validarSubRol(["DT0011"])){
									?>
										<li <?php agregarClass(MENU,["DT0011"]) ?>><a href="galeria.php" class="nav-link "> <span class="title"><?=$frases[223][$datosUsuarioActual['uss_idioma']];?></span></a></li>
									<?php
										}
										
										if( array_key_exists(16, $arregloModulos) && Modulos::validarSubRol(["DT0204"])){
									?>
										<li <?php agregarClass(MENU,["DT0204","DT0205"]) ?>><a href="sub-roles.php" class="nav-link"> <span class="title">Sub Roles</span></a></li>
									<?php
										}
									?>
	                            </ul>
	                        </li>
							<?php }}?>

							<?php 
							//MÓDULO CUESTIONARIO EVALUATIVO
							if(!empty($arregloModulos) && array_key_exists(18, $arregloModulos)){?>
							<li class="nav-item">
	                            <a href="#" class="nav-link nav-toggle"> <i class="fa fa-question"></i>
	                                <span class="title">Cuestionarios</span> <span class="arrow"></span>
	                            </a>
	                            <ul class="sub-menu">
	                                <li class="nav-item"><a href="#" class="nav-link "> <span class="title">Ver cuestionarios</span></a></li>
									<li class="nav-item"><a href="#" class="nav-link "> <span class="title">Preguntas</span></a></li>

	                            </ul>
	                        </li>
							<?php }?>
							
							<?php 
							//MÓDULO MERCADEO
							if(!empty($arregloModulos) && array_key_exists(6, $arregloModulos)){?>
							<li class="nav-item">
	                            <a href="#" class="nav-link nav-toggle"> <i class="fa fa-phone"></i>
	                                <span class="title"><?=$frases[210][$datosUsuarioActual['uss_idioma']];?></span> <span class="arrow"></span>
	                            </a>
	                            <ul class="sub-menu">
	                                <li class="nav-item"><a href="#" class="nav-link "> <span class="title"><?=$frases[75][$datosUsuarioActual['uss_idioma']];?></span></a></li>

	                            </ul>
	                        </li>
							<?php }?>
							
							<?php
								if(Modulos::validarSubRol(["DT0057","DT0060"])){
							?>
							<li class="nav-item">
	                            <a href="#" class="nav-link nav-toggle"> <i class="fa fa-cogs"></i></i>
	                                <span class="title">Configuraci&oacute;n </span> <span class="arrow"></span>
	                            </a>
	                            <ul class="sub-menu">
									<?php
										if(Modulos::validarSubRol(["DT0057"])){
									?>
										<li><a href="configuracion-sistema.php">del Sistema</a></li>
									<?php
										}
										
										if(Modulos::validarSubRol(["DT0060"])){
									?>
										<li><a href="configuracion-institucion.php">de la Instituci&oacute;n</a></li>
									<?php
										}
									?>
	                            </ul>
	                        </li>
							<?php }?>
							
							<?php
								if(Modulos::validarSubRol(["DT0099"])){
							?>
							<li class="nav-item">
	                            <a href="informes-todos.php" class="nav-link nav-toggle"> <i class="fa fa-file-text"></i>
	                                <span class="title">Informes</span> 
	                            </a>
	                        </li>
							<?php }?>

							<?php
								if($datosUsuarioActual['uss_permiso1'] == CODE_DEV_MODULE_PERMISSION && $datosUsuarioActual['uss_tipo'] == TIPO_DEV && ($_SESSION["idInstitucion"] == DEVELOPER_PROD || $_SESSION["idInstitucion"] == DEVELOPER) ){
							?>
								<li  <?php agregarClass(MENU_PADRE,["DV0038","DV0039"]) ?> >
									<a href="#" class="nav-link nav-toggle"> <i class="fa fa-database"></i>
										<span class="title">DEV-ADMIN</span> <span class="arrow"></span>
									</a>
									<ul  class="sub-menu" <?php agregarClass(SUB_MENU,["DV0038","DV0039"])?>>
										<li class="nav-item"><a href="dev-scripts.php" class="nav-link"> <span class="title">scripts SQL</span></a></li>
										<li class="nav-item"><a href="dev-crear-nueva-bd.php" class="nav-link"> <span class="title">Crear nueva BD</span></a></li>
										<li class="nav-item"><a href="dev-errores-sistema.php" class="nav-link"> <span class="title">Log de errores</span></a></li>
										<li class="nav-item"><a href="dev-console.php" class="nav-link"> <span class="title">Console</span></a></li>
										<li class="nav-item"><a href="dev-historial-acciones.php" class="nav-link"> <span class="title">Historial de acciones</span></a></li>
										<li class="nav-item"><a href="dev-instituciones.php" class="nav-link"> <span class="title">Instituciones</span></a></li>
										<li  <?php agregarClass(MENU,["DV0038","DV0039"]) ?>><a href="dev-solicitudes-cancelacion.php" class="nav-link"> <span class="title">Solicitudes de cancelacion</span></a></li>
										<li class="nav-item"><a href="dev-modulos.php" class="nav-link"> <span class="title">Módulos</span></a></li>
										<li class="nav-item"><a href="dev-paginas.php" class="nav-link"> <span class="title">Páginas</span></a></li>
										<li class="nav-item"><a href="configuracion-opciones-generales.php" class="nav-link"> <span class="title">Opciones generales</span></a></li>
										<li class="nav-item"><a href="#" class="nav-link"> <span class="title">Claves restauradas</span></a></li>
										<li class="nav-item"><a href="dev-contratos.php" class="nav-link"> <span class="title">Contratos</span></a></li>
										<li class="nav-item"><a href="dev-terminos.php" class="nav-link"> <span class="title">T&C</span></a></li>
										<li class="nav-item"><a href="dev-datos-contacto.php" class="nav-link"> <span class="title">Datos de contacto</span></a></li>
									</ul>
								</li>
								
								<li class="nav-item">
									<a href="#" class="nav-link nav-toggle"> <i class="fa fa-shopping-cart"></i>
										<span class="title">ADMIN-MPS</span> <span class="arrow"></span>
									</a>
									<ul  class="sub-menu">
										<li class="nav-item"><a href="mps-categorias-productos.php" class="nav-link"> <span class="title">Categorias productos</span></a></li>
										<li class="nav-item"><a href="mps-categorias-servicios.php" class="nav-link"> <span class="title">Categorias servicios</span></a></li>
										<li class="nav-item"><a href="mps-productos.php" class="nav-link"> <span class="title">Productos</span></a></li>
										<li class="nav-item"><a href="mps-empresas.php" class="nav-link"> <span class="title">Empresas</span></a></li>
									</ul>
								</li>
							<?php }?>
							
							<?php }?>
							
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
							
							<?php 
							//MENÚ ACUDIENTES
							if($datosUsuarioActual['uss_tipo'] == TIPO_ACUDIENTE){?>
							
							<li class="nav-item" data-step="10" data-intro="<b><?=$frases[71][$datosUsuarioActual['uss_idioma']];?>:</b> Aquí verás tus acudidos y toda su información." data-position='left'>
	                            <a href="estudiantes.php" class="nav-link nav-toggle"> <i class="fa fa-group"></i>
	                                <span class="title"><?=$frases[71][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>
							
							<li class="nav-item" data-step="12" data-intro="<b><?=$frases[104][$datosUsuarioActual['uss_idioma']];?>:</b> Aquí verás toda la información relacionada con tu estado de cuenta financiero." data-position='left'>
	                            <a href="estado-de-cuenta.php" class="nav-link nav-toggle"> <i class="material-icons">attach_money</i>
	                                <span class="title"><?=$frases[104][$datosUsuarioActual['uss_idioma']];?></span> 
	                            </a>
	                        </li>

							<li class="nav-item active" data-step="11" data-intro="<b><?=$frases[175][$datosUsuarioActual['uss_idioma']];?>:</b> Encuentra los mejores productos y servicios complementarios." data-position='left'>
	                            <a href="marketplace.php" class="nav-link nav-toggle bg-warning text-dark"> <i class="fa fa-shopping-cart text-dark"></i>
	                                <span class="title">Marketplace</span> 
	                            </a>
	                        </li>
							
							<?php }?>
							
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

	                    </ul>
	                </div>
                </div>
            </div>
            <!-- end sidebar menu --> 