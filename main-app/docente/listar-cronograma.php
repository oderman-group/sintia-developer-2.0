<?php include("session.php");?>
<?php $idPaginaInterna = 'DC0012';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("verificar-carga.php");?>
<?php include("../compartido/head.php");?>
<!-- full calendar -->
<link href="../../config-general/assets/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
</head>

<div class="row">
                                
    <div class="col-md-8">
        <div class="card card-topline-purple">
            <div class="card-head">
                <header><?=$frases[111][$datosUsuarioActual['uss_idioma']];?></header>
                <div class="tools">
                    <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                </div>
            </div>
            <div class="card-body">
                
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sm-12">
                        
                <?php
                if( CargaAcademica::validarPermisoPeriodosDiferentes($datosCargaActual, $periodoConsultaActual) )
                {
                ?>
                
                        <div class="btn-group">
                            <a href="cronograma-agregar.php?carga=<?=base64_encode($cargaConsultaActual);?>&periodo=<?=base64_encode($periodoConsultaActual);?>" id="addRow" class="btn deepPink-bgcolor">
                                Agregar nuevo <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        
                        
                <?php
                }
                ?>
                        <a href="cronograma-calendario.php" class="btn btn-danger"><i class="fa fa-calendar"></i> VER EN CALENDARIO</a>
                
                    </div>
                </div>
                
            <div class="dataTables_scrollHeadInner">
                <table class="display dataTable no-footer" style="width:100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?=$frases[49][$datosUsuarioActual[8]];?></th>
                            <th><?=$frases[50][$datosUsuarioActual[8]];?></th>
                            <th><?=$frases[51][$datosUsuarioActual[8]];?></th>
                            <th><?=$frases[244][$datosUsuarioActual[8]];?></th>
                            <th><?=$frases[54][$datosUsuarioActual[8]];?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cronograma 
                            WHERE cro_id_carga='".$cargaConsultaActual."' AND cro_periodo='".$periodoConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                        $contReg=1; 
                        while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
                            ?>
                        <tr id="reg<?=$resultado['cro_id'];?>">
                            <td><?=$contReg;?></td>
                            <td><?=$resultado['cro_id'];?></td>
                            <td><?=$resultado['cro_tema'];?></td>
                            <td><?=$resultado['cro_fecha'];?></td>
                            <td><?=$resultado['cro_recursos'];?></td>
                            <td>
                                <?php if($periodoConsultaActual==$datosCargaActual['car_periodo'] or $datosCargaActual['car_permiso2']==1){?>
                                
                                <?php
                                    $arrayEnviar = array("tipo"=>1, "descripcionTipo"=>"Para ocultar fila del registro.");
                                    $arrayDatos = json_encode($arrayEnviar);
                                    $objetoEnviar = htmlentities($arrayDatos);
                                    ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary">Acciones</button>
                                        <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
                                            <i class="fa fa-angle-down"></i>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="cronograma-editar.php?idR=<?=base64_encode($resultado['cro_id']);?>&carga=<?=base64_encode($cargaConsultaActual);?>&periodo=<?=base64_encode($periodoConsultaActual);?>">Editar</a></li>
                                            
                                            <li><a href="#" title="<?=$objetoEnviar;?>" id="<?=$resultado['cro_id'];?>" name="cronograma-eliminar.php?idR=<?=base64_encode($resultado['cro_id']);?>&carga=<?=base64_encode($cargaConsultaActual);?>&periodo=<?=base64_encode($periodoConsultaActual);?>" onClick="deseaEliminar(this)">Eliminar</a></li>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php 
                                $contReg++;
                            }
                            ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
    <p>
							<?php
											if( CargaAcademica::validarPermisoPeriodosDiferentes($datosCargaActual, $periodoConsultaActual) )
											{
											?>
											
													<div class="btn-group">
														<a href="cronograma-agregar.php?carga=<?=base64_encode($cargaConsultaActual);?>&periodo=<?=base64_encode($periodoConsultaActual);?>" id="addRow" class="btn deepPink-bgcolor">
															Agregar nuevo <i class="fa fa-plus"></i>
														</a>
													</div>
													
													
											<?php
											}
											?>
							</p>
							
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

<?php include("../compartido/guardar-historial-acciones.php");?>

