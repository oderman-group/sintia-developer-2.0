<?php
$datosConsultaBD = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM academico_cronograma WHERE cro_id='".$_GET["idR"]."'"), MYSQLI_BOTH);
?>
<div class="page-content">
                    <div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class=" pull-left">
                                <div class="page-title"><?=$datosConsultaBD['cro_tema'];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <?php if($datosUsuarioActual[3]==4){?>
									<li><a class="parent-item" href="cronograma-calendario.php"><?=$frases[111][$datosUsuarioActual[8]];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
								<?php }?>
								
								<?php if($datosUsuarioActual[3]==3){?>
									<li><a class="parent-item" href="notas-actuales.php?usrEstud=<?=$_GET["usrEstud"];?>">Defintivas actuales</a>&nbsp;<i class="fa fa-angle-right"></i></li>
									<li><a class="parent-item" href="cronograma-actividades.php?carga=<?=$_GET["carga"];?>&periodo=<?=$_GET["periodo"];?>&usrEstud=<?=$_GET["usrEstud"];?>"><?=$frases[111][$datosUsuarioActual[8]];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
								<?php }?>
								
                                <li class="active"><?=$datosConsultaBD['cro_tema'];?></li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
						
						<div class="col-sm-3">
							
						<?php include("../compartido/publicidad-lateral.php");?>	

                        </div>
						
                        <div class="col-sm-9">


								<div class="panel">
									<header class="panel-heading panel-heading-purple"><?=$frases[119][$datosUsuarioActual[8]];?> </header>
                                	<div class="panel-body">

      


											<div class="form-group row">
												<label class="col-sm-2 control-label">Descripción</label>
												<div class="col-sm-10">
													<?=$datosConsultaBD['cro_tema'];?>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Recursos</label>
												<div class="col-sm-10">
													<?=$datosConsultaBD['cro_recursos'];?>
												</div>
											</div>
											
											<div class="form-group row">
													<label class="col-sm-2 control-label">Fecha</label>
													<div class="col-sm-4">
														<?=$datosConsultaBD['cro_fecha'];?>
													</div>
											</div>

										<?php if($datosUsuarioActual[3]==4){?>
										<a href="cronograma-calendario.php" class="btn btn-secondary"><i class="fa fa-long-arrow-left"></i>Regresar</a>
										<?php }?>
										
										<?php if($datosUsuarioActual[3]==3){?>
										<a href="cronograma-actividades.php?carga=<?=$_GET["carga"];?>&periodo=<?=$_GET["periodo"];?>&usrEstud=<?=$_GET["usrEstud"];?>" class="btn btn-secondary"><i class="fa fa-long-arrow-left"></i>Regresar</a>
										<?php }?>
                                </div>
                            </div>
                        </div>
						
                    </div>

                </div>