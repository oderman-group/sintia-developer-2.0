<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once("../class/Estudiantes.php");
require_once("../class/Usuarios.php");
require_once("../class/UsuariosPadre.php");

$year=$_SESSION["bd"];
if(isset($_GET["year"])){
$year=base64_decode($_GET["year"]);
}
$BD=$_SESSION["inst"]."_".$year;

$modulo = 1;
if (empty($_GET["periodo"])) {
    $periodoActual = 1;
} else {
    $periodoActual = base64_decode($_GET["periodo"]);
}

if ($periodoActual == 1) $periodoActuales = "Primero";
if ($periodoActual == 2) $periodoActuales = "Segundo";
if ($periodoActual == 3) $periodoActuales = "Tercero";
if ($periodoActual == 4) $periodoActuales = "Final";
//CONSULTA ESTUDIANTES MATRICULADOS
$filtro = '';
if (!empty($_GET["id"])) {
    $filtro .= " AND mat_id='" . base64_decode($_GET["id"]) . "'";
}
if (!empty($_REQUEST["curso"])) {
    $filtro .= " AND mat_grado='" . base64_decode($_REQUEST["curso"]) . "'";
}
if(!empty($_REQUEST["grupo"])){$filtro .= " AND mat_grupo='".base64_decode($_REQUEST["grupo"])."'";}

$matriculadosPorCurso = Estudiantes::estudiantesMatriculados($filtro, $BD,$year);
$numMatriculados = mysqli_num_rows($matriculadosPorCurso);
$idDirector="";
while ($matriculadosDatos = mysqli_fetch_array($matriculadosPorCurso, MYSQLI_BOTH)) {

    $gradoActual = $matriculadosDatos['mat_grado'];
    $grupoActual = $matriculadosDatos['mat_grupo'];

    //contadores
    $contador_periodos = 0;
    $contador_indicadores = 0;
    $materiasPerdidas = 0;
    if ($matriculadosDatos['mat_id'] == "") { ?>
        <script type="text/javascript">
            window.close();
        </script>
    <?php
        exit();
    }
    $contp = 1;
    $puestoCurso = 0;
    $puestos = mysqli_query($conexion, "SELECT mat_id, bol_estudiante, bol_carga, mat_nombres, mat_grado, bol_periodo, avg(bol_nota) as prom FROM ".BD_ACADEMICA.".academico_matriculas mat
    INNER JOIN ".BD_ACADEMICA.".academico_boletin bol ON bol_estudiante=mat_id AND bol_periodo='" . $periodoActual . "' AND bol.institucion={$config['conf_id_institucion']} AND bol.year={$year}
    WHERE  mat_grado='" . $matriculadosDatos['mat_grado'] . "' AND mat_grupo='" . $matriculadosDatos['mat_grupo'] . "' AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$year} GROUP BY mat_id ORDER BY prom DESC");
    while ($puesto = mysqli_fetch_array($puestos, MYSQLI_BOTH)) {
        if ($puesto['bol_estudiante'] == $matriculadosDatos['mat_id']) {
            $puestoCurso = $contp;
        }
        $contp++;
    }
    //======================= DATOS DEL ESTUDIANTE MATRICULADO =========================
    $usr =Estudiantes::obtenerDatosEstudiantesParaBoletin($matriculadosDatos['mat_id'],$BD,$year);
    $datosUsr = mysqli_fetch_array($usr, MYSQLI_BOTH);
    $nombre = Estudiantes::NombreCompletoDelEstudiante($datosUsr);
    ?>
    <!doctype html>
    <html class="no-js" lang="en">

    <head>
        <title>Boletín Ellen Key</title>
        <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
        <style>
            #saltoPagina {
                PAGE-BREAK-AFTER: always;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    </head>

    <body style="font-family:Arial; font-size:9px;">

        <div style="margin-bottom: 10px;">  

            <div align="center">
                <?php
                    if($config['conf_id_institucion']==16){
                ?>
                    <img src="../files/images/logo/encabezadoellen.png" width="95%">
                <?php }else{?>
                    <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" height="150" width="200"><br>
                <?php }?>
            </div>

            <div>
                <table width="100%" cellspacing="5" cellpadding="5" border="1" rules="all" style="font-size: 14px;">
                    <tr>
                        <td>Estudiante: <b><?=$nombre?></b></td>
                        <td>Grado: <b><?= $datosUsr["gra_nombre"] . " " . $datosUsr["gru_nombre"]; ?></b></td>
                        <td>Periodo/Año: <b><?= $periodoActual . " / " . $year . ""; ?></b></td>
                    </tr>
                </table>
            </div>
        </div>

        <table width="100%" rules="all" border="1">
            <thead>
                <tr>
                    <td style="text-align: center; font-weight: bold; background-color: #00adefad; font-size: 13px;">INFORME PERIÓDICO DEL PROCESO DE DESARROLLO EDUCATIVO</td>
                </tr>
            </thead>
        </table>

        <table width="100%" rules="all" border="1">
            <thead>

                <tr style="font-weight:bold; text-align:center;">
                    <td width="20%" rowspan="2">ASIGNATURAS</td>
                    <td width="2%" rowspan="2">I.H.</td>

                    <?php for ($j = 1; $j <= $periodoActual; $j++) { ?>
                        <td width="3%" colspan="2"><a href="<?= $_SERVER['PHP_SELF']; ?>?id=<?= $datosUsr['mat_id']; ?>&periodo=<?= $j ?>" style="color:#000; text-decoration:none;">Periodo <?= $j ?></a></td>
                    <?php } ?>
                    <td width="3%" colspan="3">Final</td>
                </tr>

                <tr style="font-weight:bold; text-align:center;">
                    <?php for ($j = 1; $j <= $periodoActual; $j++) { ?>
                        <td width="3%">Nota</td>
                        <td width="3%">Nivel</td>
                    <?php } ?>
                    <td width="3%">Nota</td>
                    <td width="3%">Nivel</td>
                    <td width="3%">Hab</td>
                </tr>

            </thead>

            <?php
            $contador = 1;
            $ausPer1Total=0;
            $ausPer2Total=0;
            $ausPer3Total=0;
            $ausPer4Total=0;
            $sumAusenciasTotal=0;
            $conCargas = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas car
            INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}
            WHERE car_curso='" . $datosUsr['mat_grado'] . "' AND car_grupo='" . $datosUsr['mat_grupo'] . "' AND car.institucion={$config['conf_id_institucion']} AND car.year={$year}");
            while ($datosCargas = mysqli_fetch_array($conCargas, MYSQLI_BOTH)) {
                //DIRECTOR DE GRUPO
                if($datosCargas["car_director_grupo"]==1){
                    $idDirector=$datosCargas["car_docente"];
                }

                if ($contador % 2 == 1) {
                    $fondoFila = '#EAEAEA';
                } else {
                    $fondoFila = '#FFF';
                }
                $sumAusencias=0;
                $j=1;
                $ausPer1=0;
                $ausPer2=0;
                $ausPer3=0;
                $ausPer4=0;
                while($j<=$periodoActual){

                    $consultaDatosAusencias=mysqli_query($conexion, "SELECT sum(aus_ausencias) as sumAus FROM ".BD_ACADEMICA.".academico_ausencias aus
                    INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car_curso='".$datosUsr['gra_id']."' AND car_materia='".$datosCargas['mat_id']."' AND car.institucion={$config['conf_id_institucion']} AND car.year={$year}
                    INNER JOIN ".BD_ACADEMICA.".academico_clases cls ON cls.cls_id=aus.aus_id_clase AND cls.cls_id_carga=car_id AND cls.cls_periodo='".$j."' AND cls.institucion={$config['conf_id_institucion']} AND cls.year={$year}
                    WHERE aus.aus_id_estudiante='".$datosUsr['mat_id']."' AND aus.institucion={$config['conf_id_institucion']} AND aus.year={$year}");
                    $datosAusencias = mysqli_fetch_array($consultaDatosAusencias, MYSQLI_BOTH);

                    if($datosAusencias['sumAus']>0){
                        switch($j){
                            case 1:
                                $ausPer1+=$datosAusencias['sumAus'];
                                break;
                            case 2:
                                $ausPer2+=$datosAusencias['sumAus'];
                                break;
                            case 3:
                                $ausPer3+=$datosAusencias['sumAus'];
                                break;
                            case 4:
                                $ausPer4+=$datosAusencias['sumAus'];
                                break;
                        }
                        $sumAusencias+=$datosAusencias['sumAus'];
                    }
                    $j++;
                }
            ?>
                <tbody>
                    <tr style="background:<?= $fondoFila; ?>">
                        <td><?= $datosCargas['mat_nombre']; ?></td>
                        <td align="center"><?= $datosCargas['car_ih']; ?></td>
                        <?php
                        $promedioMateria = 0;
                        for ($j = 1; $j <= $periodoActual; $j++) {

                            $consultaDatosBoletin=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_boletin bol 
                            LEFT JOIN ".BD_ACADEMICA.".academico_notas_tipos ntp ON ntp.notip_categoria='" . $config["conf_notas_categoria"] . "' AND bol_nota>=ntp.notip_desde AND bol_nota<=ntp.notip_hasta AND ntp.institucion={$config['conf_id_institucion']} AND ntp.year={$year}
                            WHERE bol_carga='" . $datosCargas['car_id'] . "' AND bol_estudiante='" . $datosUsr['mat_id'] . "' AND bol_periodo='" . $j . "' AND bol.institucion={$config['conf_id_institucion']} AND bol.year={$year}");
                            $datosBoletin = mysqli_fetch_array($consultaDatosBoletin, MYSQLI_BOTH);


                            $promedioMateria += $datosBoletin['bol_nota'];
                            $notaBoletin=0;
                            if (!empty($datosBoletin['bol_nota'])) {
                                $notaBoletin = round($datosBoletin['bol_nota'], 1);
                            }

                            if($notaBoletin == '0'){$notaBoletin='0.0';}
                            if($notaBoletin == 1){$notaBoletin='1.0';}
                            if($notaBoletin == 2){$notaBoletin='2.0';}
                            if($notaBoletin == 3){$notaBoletin='3.0';}
                            if($notaBoletin == 4){$notaBoletin='4.0';}
                            if($notaBoletin == 5){$notaBoletin='5.0';}

                            $notaBoletinFinal=$notaBoletin;
                            if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                                $notaBoletinFinal= !empty($datosBoletin['notip_nombre']) ? $datosBoletin['notip_nombre'] : "";
                            }
                        ?>
                            <td align="center"><?= $notaBoletinFinal; ?></td>
                            <td align="center"><img src="../files/iconos/<?= $datosBoletin['notip_imagen']; ?>" width="15" height="15"></td>
                        <?php
                        }
                        $promedioMateria = round($promedioMateria / ($j - 1), 1);
                        $promedioMateriaFinal = $promedioMateria;
                        $consultaNivelacion=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_id_asg='" . $datosCargas['car_id'] . "' AND niv_cod_estudiante='" . $datosUsr['mat_id'] . "' AND institucion={$config['conf_id_institucion']} AND year={$year}");
                        $nivelacion = mysqli_fetch_array($consultaNivelacion, MYSQLI_BOTH);

                        // SI PERDIÓ LA MATERIA A FIN DE AÑO
                        if ($promedioMateria < $config["conf_nota_minima_aprobar"]) {
                            if ($nivelacion['niv_definitiva'] >= $config["conf_nota_minima_aprobar"]) {
                                $promedioMateriaFinal = $nivelacion['niv_definitiva'];
                            } else {
                                $materiasPerdidas++;
                            }
                        }

                        $consultaPromediosMateriaEstiloNota=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos 
                        WHERE notip_categoria='" . $config["conf_notas_categoria"] . "' AND '" . $promedioMateriaFinal . "'>=notip_desde AND '" . $promedioMateriaFinal . "'<=notip_hasta AND institucion={$config['conf_id_institucion']} AND year={$year}");
                        $promediosMateriaEstiloNota = mysqli_fetch_array($consultaPromediosMateriaEstiloNota, MYSQLI_BOTH);

                            if($promedioMateriaFinal == '0'){$promedioMateriaFinal='0.0';}
                            if($promedioMateriaFinal == 1){$promedioMateriaFinal='1.0';}
                            if($promedioMateriaFinal == 2){$promedioMateriaFinal='2.0';}
                            if($promedioMateriaFinal == 3){$promedioMateriaFinal='3.0';}
                            if($promedioMateriaFinal == 4){$promedioMateriaFinal='4.0';}
                            if($promedioMateriaFinal == 5){$promedioMateriaFinal='5.0';}

                            $promedioMateriaTotal=$promedioMateriaFinal;
                            if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                              $promedioMateriaTotal= !empty($promediosMateriaEstiloNota['notip_nombre']) ? $promediosMateriaEstiloNota['notip_nombre'] : "";
                            }

                        ?>
                        <td align="center"><?= $promedioMateriaTotal; ?></td>
                        <td align="center"><img src="../files/iconos/<?= $promediosMateriaEstiloNota['notip_imagen']; ?>" width="15" height="15"></td>
                        <td align="center">&nbsp;</td>
                    </tr>
                </tbody>
            <?php
                $contador++;                
                $ausPer1Total+=$ausPer1;
                $ausPer2Total+=$ausPer2;
                $ausPer3Total+=$ausPer3;
                $ausPer4Total+=$ausPer4;
                $sumAusenciasTotal+=$sumAusencias;
            }
            ?>
            <tfoot>
                <tr style="font-weight:bold; text-align:center;">
                    <td style="text-align:left;">PROMEDIO GENERAL</td>
                    <td>&nbsp;</td>

                    <?php
                    $promedioFinal = 0;
                    for ($j = 1; $j <= $periodoActual; $j++) {
                        $consultaPromedioPeriodoTodos=mysqli_query($conexion, "SELECT ROUND(AVG(bol_nota), 1) as promedio FROM ".BD_ACADEMICA.".academico_boletin 
                        WHERE bol_estudiante='" . $datosUsr['mat_id'] . "' AND bol_periodo='" . $j . "' AND institucion={$config['conf_id_institucion']} AND year={$year}");
                        $promediosPeriodos = mysqli_fetch_array($consultaPromedioPeriodoTodos, MYSQLI_BOTH);

                        $consultaSumaAusencias=mysqli_query($conexion, "SELECT sum(aus_ausencias) FROM ".BD_ACADEMICA.".academico_clases cls 
                        INNER JOIN ".BD_ACADEMICA.".academico_ausencias aus ON aus.aus_id_clase=cls.cls_id AND aus.aus_id_estudiante='" . $datosUsr['mat_id'] . "' AND aus.institucion={$config['conf_id_institucion']} AND aus.year={$year}
                        WHERE cls.cls_periodo='" . $j . "' AND cls.institucion={$config['conf_id_institucion']} AND cls.year={$year}");
                        $sumaAusencias = mysqli_fetch_array($consultaSumaAusencias, MYSQLI_BOTH);

                        $consultaPromedioEstiloNota=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos 
                        WHERE notip_categoria='" . $config["conf_notas_categoria"] . "' AND '" . $promediosPeriodos['promedio'] . "'>=notip_desde AND '" . $promediosPeriodos['promedio'] . "'<=notip_hasta AND institucion={$config['conf_id_institucion']} AND year={$year}");
                        $promediosEstiloNota = mysqli_fetch_array($consultaPromedioEstiloNota, MYSQLI_BOTH);

                        $promediosPeriodosTotal=$promediosPeriodos['promedio'];
                        if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                          $promediosPeriodosTotal= !empty($promediosEstiloNota['notip_nombre']) ? $promediosEstiloNota['notip_nombre'] : "";
                        }
                    ?>

                        <td><?= $promediosPeriodosTotal; ?></td>
                        <td><img src="../files/iconos/<?= $promediosEstiloNota['notip_imagen']; ?>" width="15" height="15"></td>
                    <?php 
                        $promedioFinal +=$promediosPeriodos['promedio'];
                    } 

                        $promedioFinal = round($promedioFinal/$periodoActual,2);
                        $consultaPromedioFinalEstiloNota=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos 
                        WHERE notip_categoria='".$config["conf_notas_categoria"]."' AND '".$promedioFinal."'>=notip_desde AND '".$promedioFinal."'<=notip_hasta AND institucion={$config['conf_id_institucion']} AND year={$year}");
                        $promedioFinalEstiloNota = mysqli_fetch_array($consultaPromedioFinalEstiloNota, MYSQLI_BOTH);

                        $promedioFinalTotal=$promedioFinal;
                        if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                          $promedioFinalTotal= !empty($promedioFinalEstiloNota['notip_nombre']) ? $promedioFinalEstiloNota['notip_nombre'] : "";
                        }
                    ?>
                    <td><?=$promedioFinalTotal;?></td>
                    <td><img src="../files/iconos/<?= $promedioFinalEstiloNota['notip_imagen']; ?>" width="15" height="15"></td>
                    <td>-</td>
                </tr>

                <tr style="font-weight:bold; text-align:center;">
                    <td style="text-align:left;">AUSENCIAS</td>
                    <td>&nbsp;</td>
                    <?php
                    for ($j = 1; $j <= $periodoActual; $j++) {
                        switch($j){
                            case 1:
                                echo '<td>&nbsp;</td>
                                      <td>'.$ausPer1Total.' Aus.</td>';
                                break;
                            case 2:
                                echo '<td>&nbsp;</td>
                                      <td>'.$ausPer2Total.' Aus.</td>';
                                break;
                            case 3:
                                echo '<td>&nbsp;</td>
                                      <td>'.$ausPer3Total.' Aus.</td>';
                                break;
                            case 4:
                                echo '<td>&nbsp;</td>
                                      <td>'.$ausPer4Total.' Aus.</td>';
                                break;
                        }
                    }
                    ?>
                    <td>&nbsp;</td>
                    <td><?=$sumAusenciasTotal?> Aus.</td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>

        <table width="100%" rules="all" border="1" style=" font-size: 13px;">
            <thead>
                <tr>
                    <td style="text-align: center; font-weight: bold; background-color: #00adefad;" colspan="2">OBSERVACIONES DE CONVIVENCIA</td>
                </tr>

                <tr style="font-weight:bold; text-align:center;">
                    <td>PERIODO</td>
                    <td>OBSERVACIONES</td>
                </tr>

                <?php 
                $cndisiplina = mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disiplina_nota 
                WHERE dn_cod_estudiante='".$datosUsr['mat_id']."' AND dn_periodo<='".$periodoActual."' AND institucion={$config['conf_id_institucion']} AND year={$year}");
                while($rndisiplina=mysqli_fetch_array($cndisiplina, MYSQLI_BOTH)){
                ?>

                    <tr>
                        <td style="text-align: center;"><?=$rndisiplina["dn_periodo"];?></td>
                        <td><?=$rndisiplina["dn_observacion"];?></td>
                    </tr>

                <?php }?>

            </thead>
        </table>

        <p>&nbsp;</p>

        <table width="100%" cellspacing="2" cellpadding="2" rules="all" border="1">
            <?php
                $directorGrupo = Usuarios::obtenerDatosUsuario($idDirector);
                $nombreDirectorGrupo = UsuariosPadre::nombreCompletoDelUsuario($directorGrupo);
            ?>
            <thead>
                <tr>
                    <td style="width: 40%;">Dir. Curso: <?=$nombreDirectorGrupo?></td>
                    <td style="width: 15%;"><img src="../files/iconos/sup.png" width="10"> SUP 4.7 – 5.0 </td>
                    <td style="width: 15%;"><img src="../files/iconos/alto.png" width="10"> ALT 4.0 – 4.6 </td>
                    <td style="width: 15%;"><img src="../files/iconos/bas.png" width="10"> BAS 3.0 – 3.9 </td>
                    <td style="width: 15%;"><img src="../files/iconos/bajo.png" width="10"> BAJ 1.0 – 2.9</td>
                </tr>

                <tr style="height: 70px;">
                    <td style="text-align: center;">
                    <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
                    Firmas y Sellos Autorizados
                    </td>
                    <td colspan="4">

                        “El hogar es la primera escuela del niño. Practíquese en la casa la temperancia en todas las cosas, y apóyese al maestro que está tratando de brindar a sus hijos una verdadera educación” CDD (EGW)
                    </td>
                </tr>


            </thead>
        </table>

        <p>&nbsp;</p>
        



       <!-- <div id="saltoPagina"></div>-->

        <table width="100%" cellspacing="5" cellpadding="5" rules="all" border="1">
            <thead>
                <tr style="font-weight:bold; text-align:center; background-color: #00adefad;">
                    <td width="30%">Asignatura</td>
                    <td width="70%">Indicadores de desempeño</td>
                </tr>
            </thead>

            <?php
            $conCargasDos = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas car
	        INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}
	        INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=car_docente AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$year}
	        WHERE car_curso='" . $gradoActual . "' AND car_grupo='" . $grupoActual . "' AND car.institucion={$config['conf_id_institucion']} AND car.year={$year}");
            while ($datosCargasDos = mysqli_fetch_array($conCargasDos, MYSQLI_BOTH)) {

                
            ?>
                <tbody>
                    <tr style="color:#000;">
                        <td><?= $datosCargasDos['mat_nombre']; ?><br><span style="color:#C1C1C1;"><?= UsuariosPadre::nombreCompletoDelUsuario($datosCargasDos); ?></span></td>
                        <td>
                        
                            <?php
                            //INDICADORES
                            $indicadores = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga aic 
		                    INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=aic.ipc_indicador AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$year}
		                    WHERE aic.ipc_carga='" . $datosCargasDos['car_id'] . "' AND aic.ipc_periodo='" . $periodoActual . "' AND aic.institucion={$config['conf_id_institucion']} AND aic.year={$year}");
                            while ($indicador = mysqli_fetch_array($indicadores, MYSQLI_BOTH)) {
                            ?>
                   
                        <?= $indicador['ind_nombre']; ?><br>
                    
                <?php
                            }
                ?>
                        
                </td>
                </tr>
                </tbody>
            <?php
            }
            ?>
        </table>

        <p>&nbsp;</p>

        <table width="100%" cellspacing="3" cellpadding="3" rules="all" border="1">
            <thead>
                <tr>
                    <td style="text-align: center; font-weight: bold; font-size: medium;">CARRERA 13 # 15N -45 B. KENNEDY       TELEFONO 6406120</td>
                </tr>
            </thead>
        </table>

        <div id="saltoPagina"></div>
    <?php
} // FIN DE TODOS LOS MATRICULADOS
    ?>


    <script type="application/javascript">
        print();
    </script>


    </body>

    </html>