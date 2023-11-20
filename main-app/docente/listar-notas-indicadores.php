<?php
include("session.php");
$idPaginaInterna = 'DC0079';
include("../compartido/historial-acciones-guardar.php");
include("verificar-carga.php");
include("../compartido/head.php");
require_once("../class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");
?>
</head>

<div class="card card-topline-purple">

<div class="card-head">

    <header><?= $frases[252][$datosUsuarioActual['uss_idioma']]; ?></header>

    <div class="tools">

        <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>

        <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>

        <a class="t-close btn-color fa fa-times" href="javascript:;"></a>

    </div>

</div>

<div class="card-body">



    <div class="table-responsive">



        <table class="table table-striped custom-table table-hover">

            <thead>

                <tr>

                    <th style="width: 50px;">#</th>

                    <th style="width: 400px;"><?= $frases[61][$datosUsuarioActual[8]]; ?></th>

                    <?php

                    $cA = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga ipc
                    INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=ipc.ipc_indicador AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$_SESSION["bd"]}
                    WHERE ipc.ipc_carga='" . $cargaConsultaActual . "' AND ipc.ipc_periodo='" . $periodoConsultaActual . "' AND ipc.institucion={$config['conf_id_institucion']} AND ipc.year={$_SESSION["bd"]}");

                    while ($rA = mysqli_fetch_array($cA, MYSQLI_BOTH)) {

                        echo '<th style="text-align:center; font-size:11px; width:100px;"><a href="indicadores-editar.php?idR=' . base64_encode($rA['ipc_id']) . '">' . $rA['ind_nombre'] . '<br>

                    ' . $rA['ind_id'] . '<br>

                    (' . $rA['ipc_valor'] . '%)</a>

                    </th>';
                    }

                    ?>

                    <th style="text-align:center; width:60px;">%</th>

                    <th style="text-align:center; width:60px;"><?= $frases[118][$datosUsuarioActual[8]]; ?></th>

                </tr>

            </thead>

            <tbody>

                <?php

                $contReg = 1;

                $consulta = Estudiantes::escogerConsultaParaListarEstudiantesParaDocentes($datosCargaActual);

                while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

                    //DEFINITIVAS

                    $carga = $cargaConsultaActual;

                    $periodo = $periodoConsultaActual;

                    $estudiante = $resultado['mat_id'];

                    include("../definitivas.php");



                    $colorEstudiante = '#000;';

                    if ($resultado['mat_inclusion'] == 1) {
                        $colorEstudiante = 'blue;';
                    }

                ?>



                    <tr>

                        <td style="text-align:center;" style="width: 100px;"><?= $contReg; ?></td>

                        <td style="color: <?= $colorEstudiante; ?>">

                            <img src="../files/fotos/<?= $resultado['uss_foto']; ?>" width="50">

                            <?= Estudiantes::NombreCompletoDelEstudiante($resultado); ?>

                        </td>



                        <?php

                        $cA = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga ipc
                        INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=ipc.ipc_indicador AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$_SESSION["bd"]}
                        WHERE ipc.ipc_carga='" . $cargaConsultaActual . "' AND ipc.ipc_periodo='" . $periodoConsultaActual . "' AND ipc.institucion={$config['conf_id_institucion']} AND ipc.year={$_SESSION["bd"]}");

                        while ($rA = mysqli_fetch_array($cA, MYSQLI_BOTH)) {

                            //LAS CALIFICACIONES
                            $consultaSumaNotas=mysqli_query($conexion, "SELECT SUM(cal_nota * (act_valor/100)) FROM ".BD_ACADEMICA.".academico_calificaciones aac
                            INNER JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id=aac.cal_id_actividad AND aa.act_id_tipo='" . $rA['ipc_indicador'] . "' AND aa.act_periodo='" . $periodoConsultaActual . "' AND aa.act_id_carga='" . $cargaConsultaActual . "' AND aa.act_estado=1 AND aa.institucion={$config['conf_id_institucion']} AND aa.year={$_SESSION["bd"]}
                            WHERE aac.cal_id_estudiante='" . $resultado['mat_id']."' AND aac.institucion={$config['conf_id_institucion']} AND aac.year={$_SESSION["bd"]}");
                            $sumaNotas = mysqli_fetch_array($consultaSumaNotas, MYSQLI_BOTH);

                            $notasResultado = round($sumaNotas[0] / ($rA['ipc_valor'] / 100), $config['conf_decimales_notas']);

                            if($notasResultado<$config[5] and $notasResultado!="") $colorNota = $config[6]; elseif($notasResultado>=$config[5]) $colorNota = $config[7]; else $colorNota = "black";
        
                            $notasResultadoFinal=$notasResultado;
                            $atributosA='style="text-decoration:underline; color:'.$colorNota.';"';
                            if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                                $atributosA='tabindex="0" role="button" data-toggle="popover" data-trigger="hover" title="Nota Cuantitativa: '.$notasResultado.'" data-content="<b>Nota Cuantitativa:</b><br>'.$notasResultado.'" data-html="true" data-placement="top" style="border-bottom: 1px dotted #000; color:'.$colorNota.';"';
        
                                $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notasResultado);
                                $notasResultadoFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
                            }

                        ?>

                            <td style="width: 100px; text-align:center;">
                                <a href="calificaciones-estudiante.php?usrEstud=<?= base64_encode($resultado['mat_id_usuario']); ?>&periodo=<?= base64_encode($periodoConsultaActual); ?>&carga=<?= base64_encode($cargaConsultaActual); ?>&indicador=<?= base64_encode($rA['ipc_indicador']); ?>" <?=$atributosA;?>><?= $notasResultadoFinal; ?></a>
                            </td>

                        <?php

                        }

                        if ($definitiva < $config[5] and $definitiva != "") $colorDef = $config[6];
                        elseif ($definitiva >= $config[5]) $colorDef = $config[7];
                        else $colorDef = "black";

                        $definitivaFinal=$definitiva;
                        $atributosA='style="text-decoration:underline; color:'.$colorDef.';"';
                        if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                            $atributosA='tabindex="0" role="button" data-toggle="popover" data-trigger="hover" title="Nota Cuantitativa: '.$definitiva.'" data-content="<b>Nota Cuantitativa:</b><br>'.$definitiva.'" data-html="true" data-placement="top" style="border-bottom: 1px dotted #000; color:'.$colorDef.';"';
    
                            $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $definitiva);
                            $definitivaFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
                        }

                        ?>



                        <td style="text-align:center;"><?= $porcentajeActual; ?></td>

                        <td style="color:<?= $colorDef; ?>; text-align:center; font-weight:bold;"><a href="calificaciones-estudiante.php?usrEstud=<?= base64_encode($resultado['mat_id_usuario']); ?>&periodo=<?= base64_encode($periodoConsultaActual); ?>&carga=<?= base64_encode($cargaConsultaActual); ?>" <?=$atributosA;?>><?= $definitivaFinal; ?></a></td>

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