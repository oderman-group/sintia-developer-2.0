<?php
include("session.php");
$idPaginaInterna = 'DC0093';
include("../compartido/historial-acciones-guardar.php");
include("verificar-carga.php");
include("../compartido/head.php");
?>
</head>
<div class="card card-topline-purple" id="idElemento">
    <div class="card-head">
        <header><?= $frases[374][$datosUsuarioActual['uss_idioma']]; ?></header>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-6">
                <?php
                if( CargaAcademica::validarPermisoPeriodosDiferentes($datosCargaActual, $periodoConsultaActual) ) {
                ?>
                    <div class="btn-group" id="agregarNuevo">
                        <a href="unidades-agregar.php?carga=<?= base64_encode($cargaConsultaActual); ?>&periodo=<?= base64_encode($periodoConsultaActual); ?>" id="addRow" class="btn deepPink-bgcolor">
                            Agregar nuevo<i class="fa fa-plus"></i>
                        </a>
                    </div>
                <?php }?>
            </div>
        </div>
        <div class="table-scrollable">
            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle" id="example4">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= $frases[49][$datosUsuarioActual['uss_idioma']]; ?></th>
                        <th><?= $frases[187][$datosUsuarioActual['uss_idioma']]; ?></th>
                        <th><?= $frases[54][$datosUsuarioActual['uss_idioma']]; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_unidades 
                        WHERE uni_id_carga='" . $cargaConsultaActual . "' AND uni_periodo='" . $periodoConsultaActual . "' AND uni_eliminado!=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                        $contReg = 1;
                        while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

                            $consultaClases = mysqli_query($conexion, "SELECT cls_id FROM ".BD_ACADEMICA.".academico_clases 
                            WHERE cls_id_carga='" . $cargaConsultaActual . "' AND cls_periodo='" . $periodoConsultaActual . "' AND cls_unidad='" . $resultado['uni_id'] . "' AND cls_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                            $numClases=mysqli_num_rows($consultaClases);
                    ?>
                        <tr id="reg<?= $resultado['id_nuevo']; ?>">
                            <td><?= $contReg; ?></td>
                            <td><?= $resultado['uni_id']; ?></td>
                            <td><?= $resultado['uni_nombre']; ?></td>
                            <td>
                                <?php
                                    $arrayEnviar = array("tipo" => 1, "descripcionTipo" => "Para ocultar fila del registro.");
                                    $arrayDatos = json_encode($arrayEnviar);
                                    $objetoEnviar = htmlentities($arrayDatos);
                                ?>
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-info dropdown-toggle center no-margin" type="button" data-toggle="dropdown" aria-expanded="false"> Acciones
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-left" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <li><a href="unidades-editar.php?idR=<?= base64_encode($resultado['id_nuevo']); ?>">Editar</a></li>
                                        <?php if($numClases<1){?>
                                            <li><a href="#" title="<?= $objetoEnviar; ?>" id="<?= $resultado['id_nuevo']; ?>" name="unidades-eliminar.php?idR=<?= base64_encode($resultado['id_nuevo']); ?>&carga=<?= base64_encode($cargaConsultaActual); ?>&periodo=<?= base64_encode($periodoConsultaActual); ?>" onClick="deseaEliminar(this)">Eliminar</a></li>
                                        <?php }?>
                                    </ul>
                                </div>
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

<?php include("../compartido/guardar-historial-acciones.php");?>