<div class="page-content">
                    <div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class=" pull-left">
                                <div class="page-title"><?=$frases[216][$datosUsuarioActual['uss_idioma']];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                
								<?php
								if(is_numeric($_GET["carpeta"])){	
									$idFolderActual = $_GET["carpeta"];
									$var = 1;
									$i=0;
									$vectorDatos = array();
									while($var==1){
										$carpetaActual = mysql_fetch_array(mysql_query("SELECT fold_id, fold_padre FROM general_folders WHERE fold_id='".$idFolderActual."' AND fold_estado=1",$conexion));
										$vectorDatos[$i] = $carpetaActual['fold_id'];
										if($carpetaActual['fold_padre']!="" and $carpetaActual['fold_padre']!='0'){
											$idFolderActual = $carpetaActual['fold_padre'];
										}else{$var = 2;}
										$i++;
									}
									?>
									
									<li><a class="parent-item" href="cargas-carpetas.php"><?=$frases[216][$datosUsuarioActual[8]];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
									<?php
									$cont = count($vectorDatos);
									$cont = $cont - 1;
									while($cont>=0){
										$carpetaActual = mysql_fetch_array(mysql_query("SELECT * FROM general_folders WHERE fold_id='".$vectorDatos[$cont]."' AND fold_estado=1",$conexion));
										if($cont>0){
									?>
											<li><a class="parent-item" href="cargas-carpetas.php?carpeta=<?=$carpetaActual['fold_id'];?>"><?=$carpetaActual['fold_nombre'];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
									<?php
										}else{
									?>
											<li class="active"><?=$carpetaActual['fold_nombre'];?></li>
									<?php
										}
										$cont--;
									}
								}
								?>
                                
                            </ol>
                        </div>
                    </div>
                   
                     <!-- chart start -->
                    <div class="row">
                    	<div class="col-sm-3">
							<div class="panel">
										<header class="panel-heading panel-heading-blue"><?=$frases[8][$datosUsuarioActual['uss_idioma']];?></header>
                                        <div class="panel-body">
											<form action="<?=$_SERVER['PHP_SELF'];?>" method="get">
	
											<div class="form-group row">
												<div class="col-sm-8">
													<input type="text" name="busqueda" class="form-control" value="<?=$_GET["busqueda"];?>" placeholder="Búsqueda...">
												</div>
												<div class="col-sm-4">
													<input type="submit" class="btn btn-primary" value="<?=$frases[8][$datosUsuarioActual[8]];?>">
												</div>
											</div>
											</form>
											<?php if(isset($_GET["busqueda"])){?><div align="center"><a href="<?=$_SERVER['PHP_SELF'];?>"><?=$frases[230][$datosUsuarioActual[8]];?></a></div><?php }?>
										</div>
									</div>
							
							<?php 
							//DOCENTES
							if($datosUsuarioActual[3]==2){?>
								<?php include("info-carga-actual.php");?>

								<?php include("filtros-cargas.php");?>
							<?php }?>
                        </div>
						
                        <div class="col-sm-9">
							
							
							<?php if(is_numeric($_GET["carpeta"])){?>
								<a href="javascript:history.go(-1);" class="btn btn-secondary"><i class="fa fa-long-arrow-left"></i><?=$frases[184][$datosUsuarioActual[8]];?></a>
							<?php }?>
							
							<a href="cargas-carpetas-agregar.php?carga=<?=$cargaConsultaActual;?>&periodo=<?=$periodoConsultaActual;?>&carpeta=<?=$_GET['carpeta'];?>" class="btn btn-pink"><i class="fa fa-plus-circle"></i><?=$frases[231][$datosUsuarioActual[8]];?></a>
							<p>&nbsp;</p>
                       	 	<!-- start widget -->
							<div class="state-overview">
									
									<h3 style="color: black;"><i class="fa fa-folder"></i> <?=strtoupper($frases[232][$datosUsuarioActual[8]]);?></h3>
									<div class="row">
										<?php
										$filtro = '';
										if(is_numeric($_GET["carpeta"])){$filtro .= " AND fold_padre='".$_GET["carpeta"]."'";}
										if($_GET["busqueda"]!=""){$filtro .= " AND (fold_nombre LIKE '%".$_GET["busqueda"]."%' OR fold_keywords LIKE '%".$_GET["busqueda"]."%')";}
										$carpetas = mysql_query("SELECT * FROM general_folders 
										WHERE fold_id_recurso_principal='".$cargaConsultaActual."' AND fold_propietario='".$_SESSION["id"]."' AND fold_activo=1 AND fold_categoria=2 AND fold_estado=1 $filtro
										ORDER BY fold_tipo, fold_nombre
										",$conexion);
										while($carpeta = mysql_fetch_array($carpetas)){
											$compartidoNum = mysql_num_rows(mysql_query("SELECT * FROM general_folders_usuarios_compartir WHERE fxuc_folder='".$carpeta['fold_id']."'",$conexion));
											
											$numRecursos = mysql_num_rows(mysql_query("SELECT * FROM general_folders WHERE fold_padre='".$carpeta['fold_id']."' AND fold_estado=1",$conexion));
											if(!is_numeric($_GET["carpeta"]) and $carpeta['fold_padre']!="" and $carpeta['fold_padre']!="0" and $_GET["busqueda"]=="") continue;
										?>
										
										<?php if($carpeta['fold_tipo']==1){?>
										<div class="col-xl-3 col-md-6 col-12" title="<?=$carpeta['fold_nombre'];?>">
										  <div class="info-box bg-b-green">
											<span class="info-box-icon push-bottom"><i class="fa fa-folder"></i></span>
											<div class="info-box-content">
											  <span class="info-box-text"><a href="cargas-carpetas.php?carga=<?=$cargaConsultaActual;?>&periodo=<?=$periodoConsultaActual;?>&carpeta=<?=$carpeta['fold_id'];?>" style="color: white;"><?=$carpeta['fold_nombre'];?></a></span>
											
											  <span class="info-box-number"><?=$numRecursos;?></span>
											  <div class="progress">
												<div class="progress-bar" style="width: 15%"></div>
											  </div>
												
											 <p align="right">
												 <?php if($compartidoNum>0){?>
												 	<i class="fa fa-share-alt pull-left" style="color: black;" title="Compartido con <?=$compartidoNum;?> usuarios"></i>
												 <?php }?>
												 
												 <a href="cargas-carpetas-editar.php?idR=<?=$carpeta['fold_id'];?>&carga=<?=$cargaConsultaActual;?>&periodo=<?=$periodoConsultaActual;?>" style="color: black;"><i class="fa fa-edit"></i></a>
												 
												 <a href="#" name="../compartido/guardar.php?get=9&idR=<?=$carpeta['fold_id'];?>" onClick="deseaEliminar(this)" style="color: black;"><i class="fa fa-trash-o"></i></a>
											</p>	
												
											</div>
											<!-- /.info-box-content -->
										  </div>
										  <!-- /.info-box -->
										</div>
										
										<?php }else{?>
										<div class="col-xl-3 col-md-6 col-12" title="<?=$carpeta['fold_nombre'];?>">
										  <div class="info-box">
											<span class="info-box-icon push-bottom"><i class="fa fa-file-text-o"></i></span>
											<div class="info-box-content">
											  <span class="info-box-text"><a href="../files/archivos/<?=$carpeta['fold_nombre'];?>" style="font-size: 12px;" target="_blank"><?=$carpeta['fold_nombre'];?></a></span>
											
											
												<span class="info-box-number">&nbsp;</span>
												<div class="progress">
													<div class="progress-bar" style="width: 0%"></div>
												</div>
												
												<p align="right">
													<a href="cargas-carpetas-editar.php?idR=<?=$carpeta['fold_id'];?>&carga=<?=$cargaConsultaActual;?>&periodo=<?=$periodoConsultaActual;?>" style="color: black;"><i class="fa fa-edit"></i></a>
													
													<a href="#" name="../compartido/guardar.php?get=9&idR=<?=$carpeta['fold_id'];?>" onClick="deseaEliminar(this)" style="color: black;"><i class="fa fa-trash-o"></i></a>
												</p>
												
											</div>
											<!-- /.info-box-content -->
										  </div>
										  <!-- /.info-box -->
										</div>
										<?php }?>
										
										
										<?php }?>
										

									  </div>
								
								
									<h3 style="color: black;"><i class="fa fa-share-alt-square"></i> <?=strtoupper($frases[233][$datosUsuarioActual[8]]);?></h3>
									<div class="row">
										
										<?php
										$carpetasCompartidas = mysql_query("SELECT * FROM general_folders
										INNER JOIN general_folders_usuarios_compartir ON (fxuc_folder=fold_id OR fxuc_folder=fold_padre) AND fxuc_usuario='".$_SESSION["id"]."'
										WHERE fold_activo=1 AND fold_categoria=2 AND fold_estado=1 $filtro
										ORDER BY fold_tipo, fold_nombre
										",$conexion);
										while($carpetaCompartida = mysql_fetch_array($carpetasCompartidas)){
											$numRecursosCompartido = mysql_num_rows(mysql_query("SELECT * FROM general_folders WHERE fold_padre='".$carpetaCompartida['fold_id']."' AND fold_estado=1",$conexion));
											if(!is_numeric($_GET["carpeta"]) and $carpetaCompartida['fold_padre']!="" and $carpetaCompartida['fold_padre']!="0" and $_GET["busqueda"]=="" and $carpetaCompartida['fxuc_folder']!=$carpetaCompartida['fold_id']) continue;
										?>
										
										<?php if($carpetaCompartida['fold_tipo']==1){?>
										<div class="col-xl-3 col-md-6 col-12" title="<?=$carpetaCompartida['fold_nombre'];?>">
										  <div class="info-box bg-b-blue">
											<span class="info-box-icon push-bottom"><i class="fa fa-folder"></i></span>
											<div class="info-box-content">
											  <span class="info-box-text"><a href="cargas-carpetas.php?carga=<?=$cargaConsultaActual;?>&periodo=<?=$periodoConsultaActual;?>&carpeta=<?=$carpetaCompartida['fold_id'];?>" style="color: white;"><?=$carpetaCompartida['fold_nombre'];?></a></span>
											
											  <span class="info-box-number"><?=$numRecursosCompartido;?></span>
											  <div class="progress">
												<div class="progress-bar" style="width: 15%"></div>
											  </div>
												
											<!--	
											 <p align="right">
												 <a href="cargas-carpetas-editar.php?idR=<?=$carpetaCompartida['fold_id'];?>&carga=<?=$cargaConsultaActual;?>&periodo=<?=$periodoConsultaActual;?>" style="color: black;"><i class="fa fa-edit"></i></a>
												 
												 <a href="#" name="../compartido/guardar.php?get=9&idR=<?=$carpetaCompartida['fold_id'];?>" onClick="deseaEliminar(this)" style="color: black;"><i class="fa fa-trash-o"></i></a>
											</p>
											-->
												
											</div>
											<!-- /.info-box-content -->
										  </div>
										  <!-- /.info-box -->
										</div>
										
										<?php }else{?>
										<div class="col-xl-3 col-md-6 col-12" title="<?=$carpetaCompartida['fold_nombre'];?>">
										  <div class="info-box">
											<span class="info-box-icon push-bottom"><i class="fa fa-file-text-o"></i></span>
											<div class="info-box-content">
											  <span class="info-box-text"><a href="../files/archivos/<?=$carpetaCompartida['fold_nombre'];?>" style="font-size: 12px;" target="_blank"><?=$carpetaCompartida['fold_nombre'];?></a></span>
											
											
												<span class="info-box-number">&nbsp;</span>
												<div class="progress">
													<div class="progress-bar" style="width: 0%"></div>
												</div>
												
												<!--
												<p align="right">
													<a href="cargas-carpetas-editar.php?idR=<?=$carpetaCompartida['fold_id'];?>&carga=<?=$cargaConsultaActual;?>&periodo=<?=$periodoConsultaActual;?>" style="color: black;"><i class="fa fa-edit"></i></a>
													
													<a href="#" name="../compartido/guardar.php?get=9&idR=<?=$carpetaCompartida['fold_id'];?>" onClick="deseaEliminar(this)" style="color: black;"><i class="fa fa-trash-o"></i></a>
												</p>
												-->
												
											</div>
											<!-- /.info-box-content -->
										  </div>
										  <!-- /.info-box -->
										</div>
										<?php }?>
										
										
										<?php }?>
										

									  </div>
								
								
								
								</div>
							<!-- end widget -->
                        </div>
						
                    </div>
                     <!-- Chart end -->

                </div>