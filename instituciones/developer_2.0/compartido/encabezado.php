<?php
if(isset($_GET["idNotify"]) and is_numeric($_GET["idNotify"])){
	mysql_query("UPDATE general_alertas SET alr_vista=1 WHERE alr_id='".$_GET["idNotify"]."' AND alr_vista=0",$conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
}
switch($_SESSION["inst"]){
	case "mobiliar_icolven": $institucionNombre = 'ICOLVEN'; break;
	case "mobiliar_coalst": $institucionNombre = 'COALST'; break;
	case "mobiliar_eduardoortega": $institucionNombre = 'E.O. NIQUÍA'; break;
	case "mobiliar_cemped": $institucionNombre = 'CEMPED'; break;
	case "mobiliar_maxtrummer": $institucionNombre = 'MAXTRUMMER'; break;
	case "mobiliar_eoa_lacamila": $institucionNombre = 'E.O. LA CAMILA'; break;
	case "mobiliar_eoa_altosoriente": $institucionNombre = 'E.O. A. ORIENTE'; break;
	case "mobiliar_eoa_paris": $institucionNombre = 'E.O. PARIS'; break;
	case "mobiliar_eoa_pinar": $institucionNombre = 'E.O. PINAR'; break;
	case "mobiliar_eoa_srita": $institucionNombre = 'E.O. S. RITA'; break;
	case "mobiliar_ict": $institucionNombre = 'IC&amp;T'; break;		
    case "mobiliar_sintiademo": $institucionNombre = 'DEMO'; break;
    case "mobiliar_ellenkey": $institucionNombre = 'ELLEN KEY'; break;	
    case "mobiliar_jose_antonio": $institucionNombre = 'JOSE ANTONIO'; break;
    case "mobiliar_redencion": $institucionNombre = 'REDENCIÓN'; break;
    case "mobiliar_innovadores": $institucionNombre = 'L.I.G. INNOVADORES'; break;
    case "mobiliar_dev": $institucionNombre = 'DEVELOPER'; break;
		
	default:
		$institucionNombre = 'DESC.';
	break;	
}
?>


<!-- start header -->
        <div class="page-header navbar navbar-fixed-top">

            <?php include("../compartido/barra-developer.php");?>
			
            <div class="page-header-inner">
                <!-- logo start -->
                <div class="page-logo">
                    <a href="index.php">
                    <span class="logo-default" style="font-weight: bold; font-size: 12px;"><?=$institucionNombre;?></span> </a>
                </div>
                <!-- logo end -->
				<ul class="nav navbar-nav navbar-left in">
					<li><a href="#" class="menu-toggler sidebar-toggler"><i class="icon-menu"></i></a></li>
				</ul>
				
				<?php //include("mega-menu.php");?>
				
                 <form class="search-form-opened" action="#" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="<?=$frases[8][$datosUsuarioActual[8]];?>..." name="query">
                        <span class="input-group-btn">
                          <a href="javascript:;" class="btn submit">
                             <i class="icon-magnifier"></i>
                           </a>
                        </span>
												
                    </div>
					 
					 
                </form>
				
                <!-- start mobile menu -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                    <span></span>
                </a>
               <!-- end mobile menu -->
                <!-- start header menu -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
						
						
                    	<li><a href="javascript:;" class="fullscreen-btn"><i class="fa fa-arrows-alt"></i></a></li>
						
                    	<!-- start language menu -->
                        <li class="dropdown language-switch" data-step="3" data-intro="<b>Idiomas:</b> Aquí puedes cambiar el idioma de la plataforma." data-position='bottom' data-scrollTo='tooltip'>
							<?php
							switch($datosUsuarioActual[8]){
								case 1:
									$idiomaImg = 'es.png';
									$idiomaNombre = 'Español';
								break;
									
								case 2:
									$idiomaImg = 'gb.png';
									$idiomaNombre = 'English';
								break;
							}
							?>
                            <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <img src="../../../config-general/assets/img/flags/<?=$idiomaImg;?>" class="position-left" alt=""> <?=$idiomaNombre;?> <span class="fa fa-angle-down"></span>
                            </a>
							
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="../compartido/guardar.php?get=1&idioma=2" class="english"><img src="../../../config-general/assets/img/flags/gb.png" alt=""> English</a>
                                </li>
                                <li>
                                    <a href="../compartido/guardar.php?get=1&idioma=1" class="espana"><img src="../../../config-general/assets/img/flags/es.png" alt=""> Español</a>
                                </li>
                            </ul>
                        </li>
                        <!-- end language menu -->
                        
						<!-- start notification dropdown -->
                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar" data-step="4" data-intro="<b>Notificaciones:</b> Aquí recibirás notificaciones importantes relacionadas contigo." data-position='bottom' data-scrollTo='tooltip'>
                            <!--<span id="notificaciones"></span>-->
                        </li>
                        <!-- end notification dropdown -->
						
                        <!-- start message dropdown -->
 						<li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar" data-step="5" data-intro="<b>Mensajes:</b> Aquí verás los mensajes directos que te envíen otros usuarios de la plataforma." data-position='bottom' data-scrollTo='tooltip'>
                            <!--<span id="mensajes"></span>-->
                        </li>
                        <!-- end message dropdown -->
 						<!-- start manage user dropdown -->
 						<li class="dropdown dropdown-user" data-step="6" data-intro="<b>Cuenta personal:</b> Aquí puedes acceder a tu perfil a cambiar tus datos personales, y en la opción salir podrás cerrar tu sesión con seguirdad cuando hayas terminado de trabajar con la plataforma." data-position='bottom' data-scrollTo='tooltip'>
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <img alt="" class="img-circle " src="../files/fotos/<?=$datosUsuarioActual['uss_foto'];?>"/>
                                <span class="username username-hide-on-mobile"> <?=$datosUsuarioActual['uss_nombre'];?> </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li><a href="perfil.php"><i class="icon-user"></i> Perfil </a></li>
                                <li><a href="#cambiar-clave.php"><i class="icon-user"></i> Cambiar contraseña </a></li>
								
                                <li class="divider"> </li>
								<li><a href="https://plataformasintia.com/faq-uso.php" target="_blank"><i class="icon-question"></i> Ayuda </a></li>
                                <li><a href="../controlador/salir.php" onClick="localStorage.clear();"><i class="icon-logout"></i> Salir </a></li>
                            </ul>
                        </li>
						
						<?php
                        /*
						$arrayEnviarE = array("idUsuario"=>$_SESSION["id"], "nombreUsuario"=>$datosUsuariosActual['uss_nombre'], "fotoUsuario"=>$datosUsuariosActual["uss_foto"]);
						$arrayDatosE = json_encode($arrayEnviarE);
						$objetoEnviarE = htmlentities($arrayDatosE);
                        */
						?>
						
                        <!-- end manage user dropdown --
                        <li class="dropdown dropdown-quick-sidebar-toggler">
                             <a id="headerSettingButton" class="mdl-button mdl-js-button mdl-button--icon pull-right" data-upgraded=",MaterialButton">
	                           <i class="fa fa-weixin" id="<?=$objetoEnviarE;?>" onclick="conectarme(this)"></i>
	                        </a>
                        </li>-->
                    </ul>

					
                </div>

            </div>
        </div>
        <!-- end header -->