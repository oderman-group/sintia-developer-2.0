<?php
include("session.php");
$idPaginaInterna = 'DC0034';
include("../compartido/historial-acciones-guardar.php");
include("verificar-carga.php");
include("../compartido/head.php");

$consultaSumaIndicadores=mysqli_query($conexion, "SELECT
(SELECT sum(ipc_valor) FROM ".BD_ACADEMICA.".academico_indicadores_carga 
WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}),
(SELECT sum(ipc_valor) FROM ".BD_ACADEMICA.".academico_indicadores_carga 
WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}),
(SELECT count(*) FROM ".BD_ACADEMICA.".academico_indicadores_carga 
WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]})");
$sumaIndicadores = mysqli_fetch_array($consultaSumaIndicadores, MYSQLI_BOTH);
$porcentajePermitido = 100 - $sumaIndicadores[0];
$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);
?>
</head>

<div class="card card-topline-purple" id="idElemento">
    <div class="card-head">
        <header><?=$frases[63][$datosUsuarioActual['uss_idioma']];?></header>
        <div class="tools">
            <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
        </div>
    </div>
    <div class="card-body">
            <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <?php 
                            if(
                                (
                                    ( $datosCargaActual['car_valor_indicador'] == CONFIG_AUTOMATICO_INDICADOR 
                                    && $sumaIndicadores[2] < $datosCargaActual['car_maximos_indicadores'] 
                                    ) 
                                    ||
                                    ( $datosCargaActual['car_valor_indicador'] == CONFIG_MANUAL_INDICADOR 
                                    && $sumaIndicadores[2] < $datosCargaActual['car_maximos_indicadores'] 
                                    && $porcentajeRestante > 0 )
                                ) 
                                && CargaAcademica::validarPermisoPeriodosDiferentes($datosCargaActual, $periodoConsultaActual)
                            )
                            {
                            ?>
                            <div class="btn-group" id="agregarNuevo">
                                <a href="indicadores-agregar.php?carga=<?=base64_encode($cargaConsultaActual);?>&periodo=<?=base64_encode($periodoConsultaActual);?>" id="addRow" class="btn deepPink-bgcolor">
                                    Agregar nuevo<i class="fa fa-plus"></i>
                                </a>
                            </div>
                            <?php }?>
                            
                            <?php if($datosCargaActual['car_valor_indicador']==1 and $porcentajeRestante<=0){?>
                                <p style="color: tomato;"> Has alcanzado el 100% de valor para los indicadores. </p>
                            <?php }?>
                            
                            <?php if($datosCargaActual['car_maximos_indicadores']<=$sumaIndicadores[2]){?>
                                <p style="color: tomato;"> Has alcanzado el número máximo de indicadores permitidos. </p>
                            <?php }?>

                            <div class="btn-group" id="agregarNuevo">
                                <a href="../compartido/indicadores-perdidos-curso.php?curso=<?=base64_encode($datosCargaActual['car_curso']);?>&periodo=<?=base64_encode($periodoConsultaActual);?>" class="btn btn-secondary" target="_blank">
                                    Ver indicadores perdidos<i class="fa fa-file-text-o"></i>
                                </a>
                            </div>

                        </div>
                        
                    </div>	
        <div class="table-scrollable">
            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle" id="example4">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?=$frases[49][$datosUsuarioActual[8]];?></th>
                    <th><?=$frases[50][$datosUsuarioActual[8]];?></th>
                    <th><?=$frases[52][$datosUsuarioActual[8]];?></th>
                    
                    <?php if($datosCargaActual['car_saberes_indicador']==1){?>
                        <th>Tipo evaluación</th>
                    <?php }?>
                    
                    <th><?=$frases[54][$datosUsuarioActual[8]];?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $saberes = array("","Saber saber (55%)","Saber hacer (35%)","Saber ser (10%)");
                    $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga ipc
                    INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=ipc.ipc_indicador AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$_SESSION["bd"]}
                    WHERE ipc.ipc_carga='".$cargaConsultaActual."' AND ipc.ipc_periodo='".$periodoConsultaActual."' AND ipc.institucion={$config['conf_id_institucion']} AND ipc.year={$_SESSION["bd"]}");
                    $contReg = 1; 
                    $porcentajeActual = 0;
                    while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
                        $porcentajeActual +=$resultado['ipc_valor'];
                    ?>
                <tr id="reg<?=$resultado['ipc_id'];?>">
                    <td><?=$contReg;?></td>
                    <td><?=$resultado['ipc_id'];?></td>
                    <td><?=$resultado['ind_nombre'];?></td>
                    <td><?=$resultado['ipc_valor'];?>%</td>
                    
                    <?php if($datosCargaActual['car_saberes_indicador']==1){?>
                        <th><?=$saberes[$resultado['ipc_evaluacion']];?></th>
                    <?php }?>
                    
                    <td>
                        
                            <?php
                            $arrayEnviar = array("tipo"=>1, "descripcionTipo"=>"Para ocultar fila del registro.");
                            $arrayDatos = json_encode($arrayEnviar);
                            $objetoEnviar = htmlentities($arrayDatos);
                            ?>
                        <div class="btn-group">
                            <button class="btn btn-xs btn-info dropdown-toggle center no-margin" type="button" data-toggle="dropdown" aria-expanded="false"> Acciones
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-left" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">
                                
                                    <?php if($resultado['ipc_creado']==1 and ($periodoConsultaActual==$datosCargaActual['car_periodo'] or $datosCargaActual['car_permiso2']==1)){?>
                                        <li><a href="indicadores-editar.php?idR=<?=base64_encode($resultado['ipc_id']);?>">Editar</a></li>
                                
                                <li><a href="#" title="<?=$objetoEnviar;?>" id="<?=$resultado['ipc_id'];?>" name="indicadores-eliminar.php?idR=<?=base64_encode($resultado['ipc_id']);?>&idIndicador=<?=base64_encode($resultado['ipc_indicador']);?>&carga=<?=base64_encode($cargaConsultaActual);?>&periodo=<?=base64_encode($periodoConsultaActual);?>" onClick="deseaEliminar(this)">Eliminar</a></li>
                                    <?php } ?>
                                    
                                    <?php if($periodoConsultaActual<$datosCargaActual['car_periodo']){?>
                                    <li><a href="indicadores-recuperar.php?idR=<?=base64_encode($resultado['ipc_indicador']);?>">Recuperar</a></li>
                                    <?php } ?>
                            </ul>
                        </div>
                        
                    </td>
                </tr>
                
                <?php 
                        $contReg++;
                    }
                    ?>
            </tbody>
            <tfoot>
                <tr style="font-weight:bold;">
                    <td colspan="3"><?=strtoupper($frases[107][$datosUsuarioActual['uss_idioma']]);?></td>
                    <td><?=$porcentajeActual;?>%</td>
                    <td></td>
                    </tr>
            </tfoot>   
        </table>
        </div>
    </div>
</div>

<?php include("../compartido/guardar-historial-acciones.php");?>