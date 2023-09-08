<div class="page-content">
                    <div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class=" pull-left">
                                <div class="page-title"><?=$frases[245][$datosUsuarioActual['uss_idioma']];?></div>
                            </div>
                            <ol class="breadcrumb page-breadcrumb pull-right">
								<?php if($datosUsuarioActual[3]==4){?>
                                	<li><a class="parent-item" href="cronograma.php"><?=$frases[111][$datosUsuarioActual['uss_idioma']];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                	<li class="active"><?=$frases[245][$datosUsuarioActual['uss_idioma']];?></li>
								<?php }?>
								
								<?php if($datosUsuarioActual[3]==3){?>
                                	<li><a class="parent-item" href="notas-actuales.php?usrEstud=<?=base64_encode($_GET["usrEstud"]);?>">Defintivas actuales</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                	<li class="active"><?=$frases[111][$datosUsuarioActual['uss_idioma']];?></li>
								<?php }?>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-md-12">
							
							
                             <div class="card-box">
                                 <div class="card-head">
                                     <header><?=$frases[245][$datosUsuarioActual['uss_idioma']];?></header>
                                 </div>
								 
								 
                                 <div class="card-body">
                                 	<div class="panel-body">
                                       <div id="calendar" class="has-toolbar"> </div>
                                    </div>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>