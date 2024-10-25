<!-- start sidebar menu -->
 			<div class="sidebar-container" >
 				<div class="sidemenu-container navbar-collapse collapse fixed-menu">
	                <div id="remove-scroll">
				
						<?php
						//Mostrar a los directivos si tiene deuda
						if ($config['conf_deuda'] == 1 && $datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO) {
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
						
	                    <ul class="sidemenu  page-header-fixed <?=$datosUsuarioActual['uss_tipo_menu'];?>" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px" data-step="1" data-intro="<b>Menú principal:</b> Aquí encontrarás las opciones principales para el uso de la plataforma. Algunas estarán activas y otras inactivas, dependiendo los módulos que haya contratado su institución." data-position='left'>
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
								if ($datosUsuarioActual['uss_tipo'] != TIPO_DOCENTE) {
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

							<?php include_once("menu-metodos.php");?>
							<li <?php agregarClass(MENU,["DT0004"]) ?>>
							<a href="index.php" class="nav-link nav-toggle">
	                                <i class="material-icons">dashboard</i>
	                                <span class="title"><?=$frases[100][$datosUsuarioActual['uss_idioma']];?></span>
                                	<span class="selected"></span>
	                            </a>
	                        </li>
							
							<li class="nav-item">
	                            <a href="noticias.php" class="nav-link nav-toggle">
	                                <i class="material-icons">view_comfy</i>
	                                <span class="title"><?=$frases[69][$datosUsuarioActual['uss_idioma']];?></span>
	                            </a>
	                        </li>
							

							<?php include_once("menu-directivos.php");?>

							<?php include_once("menu-docentes.php");?>
							
							<?php include_once("menu-acudientes.php");?>
							
							<?php include_once("menu-estudiantes.php");?>

							<li class="nav-item">
	                            <a href="<?=REDIRECT_ROUTE."/releases.php";?>" target="_blank" class="nav-link nav-toggle">
	                                <i class="fa fa-external-link-square"></i>
	                                <span class="title">Lanzamientos</span>
	                            </a>
	                        </li>

	                    </ul>
	                </div>
                </div>
            </div>
            <!-- end sidebar menu --> 