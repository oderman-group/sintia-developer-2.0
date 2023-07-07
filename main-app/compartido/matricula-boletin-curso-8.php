<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once("../class/Estudiantes.php");
require_once("../class/Usuarios.php");
require_once("../class/UsuariosPadre.php");

$year=$agnoBD;
if(isset($_GET["year"])){
$year=$_GET["year"];
}
$BD=$_SESSION["inst"]."_".$year;

$modulo = 1;
if ($_GET["periodo"] == "") {
    $periodoActual = 1;
} else {
    $periodoActual = $_GET["periodo"];
}

if ($periodoActual == 1) $periodoActuales = "Primero";
if ($periodoActual == 2) $periodoActuales = "Segundo";
if ($periodoActual == 3) $periodoActuales = "Tercero";
if ($periodoActual == 4) $periodoActuales = "Final";
//CONSULTA ESTUDIANTES MATRICULADOS
$filtro = '';
if (!empty($_GET["id"])) {
    $filtro .= " AND mat_id='" . $_GET["id"] . "'";
}
if (!empty($_REQUEST["curso"])) {
    $filtro .= " AND mat_grado='" . $_REQUEST["curso"] . "'";
}
if(!empty($_REQUEST["grupo"])){$filtro .= " AND mat_grupo='".$_REQUEST["grupo"]."'";}

$matriculadosPorCurso = Estudiantes::estudiantesMatriculados($filtro, $BD);
$numMatriculados = mysqli_num_rows($matriculadosPorCurso);
$idDirector="";
while ($matriculadosDatos = mysqli_fetch_array($matriculadosPorCurso, MYSQLI_BOTH)) {

    $gradoActual = $matriculadosDatos['mat_grado'];
    $grupoActual = $matriculadosDatos['mat_grupo'];

    //contadores
    $contador_periodos = 0;
    $contador_indicadores = 0;
    $materiasPerdidas = 0;
    if ($matriculadosDatos[0] == "") { ?>
        <script type="text/javascript">
            window.close();
        </script>
    <?php
        exit();
    }
    $contp = 1;
    $puestoCurso = 0;
    $puestos = mysqli_query($conexion, "SELECT mat_id, bol_estudiante, bol_carga, mat_nombres, mat_grado, bol_periodo, avg(bol_nota) as prom FROM $BD.academico_matriculas
INNER JOIN $BD.academico_boletin ON bol_estudiante=mat_id AND bol_periodo='" . $_GET["periodo"] . "'
WHERE  mat_grado='" . $matriculadosDatos['mat_grado'] . "' AND mat_grupo='" . $matriculadosDatos['mat_grupo'] . "' GROUP BY mat_id ORDER BY prom DESC");
    while ($puesto = mysqli_fetch_array($puestos, MYSQLI_BOTH)) {
        if ($puesto['bol_estudiante'] == $matriculadosDatos['mat_id']) {
            $puestoCurso = $contp;
        }
        $contp++;
    }
    //======================= DATOS DEL ESTUDIANTE MATRICULADO =========================
    $usr =Estudiantes::obtenerDatosEstudiantesParaBoletin($matriculadosDatos[0],$BD);
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
                        <td width="3%" colspan="2"><a href="<?= $_SERVER['PHP_SELF']; ?>?id=<?= $datosUsr[0]; ?>&periodo=<?= $j ?>" style="color:#000; text-decoration:none;">Periodo <?= $j ?></a></td>
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
            $conCargas = mysqli_query($conexion, "SELECT * FROM $BD.academico_cargas
	INNER JOIN $BD.academico_materias ON mat_id=car_materia
	WHERE car_curso='" . $datosUsr['mat_grado'] . "' AND car_grupo='" . $datosUsr['mat_grupo'] . "'");
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

                    $consultaDatosAusencias=mysqli_query($conexion, "SELECT sum(aus_ausencias) as sumAus FROM $BD.academico_ausencias
                    INNER JOIN $BD.academico_cargas ON car_curso='".$datosUsr['gra_id']."' AND car_materia='".$datosCargas['mat_id']."'
                    INNER JOIN $BD.academico_clases ON cls_id=aus_id_clase AND cls_id_carga=car_id AND cls_periodo='".$j."'
                    WHERE aus_id_estudiante='".$datosUsr['mat_id']."'");
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

                            $consultaDatosBoletin=mysqli_query($conexion, "SELECT * FROM $BD.academico_boletin 
                            LEFT JOIN $BD.academico_notas_tipos ON notip_categoria='" . $config["conf_notas_categoria"] . "' AND bol_nota>=notip_desde AND bol_nota<=notip_hasta
                            WHERE bol_carga='" . $datosCargas['car_id'] . "' AND bol_estudiante='" . $datosUsr['mat_id'] . "' AND bol_periodo='" . $j . "'");
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
                        ?>
                            <td align="center"><?= $notaBoletin; ?></td>
                            <td align="center"><img src="../files/iconos/<?= $datosBoletin['notip_imagen']; ?>" width="15" height="15"></td>
                        <?php
                        }
                        $promedioMateria = round($promedioMateria / ($j - 1), 1);
                        $promedioMateriaFinal = $promedioMateria;
                        $consultaNivelacion=mysqli_query($conexion, "SELECT * FROM $BD.academico_nivelaciones WHERE niv_id_asg='" . $datosCargas['car_id'] . "' AND niv_cod_estudiante='" . $datosUsr['mat_id'] . "'");
                        $nivelacion = mysqli_fetch_array($consultaNivelacion, MYSQLI_BOTH);

                        // SI PERDIÓ LA MATERIA A FIN DE AÑO
                        if ($promedioMateria < $config["conf_nota_minima_aprobar"]) {
                            if ($nivelacion['niv_definitiva'] >= $config["conf_nota_minima_aprobar"]) {
                                $promedioMateriaFinal = $nivelacion['niv_definitiva'];
                            } else {
                                $materiasPerdidas++;
                            }
                        }

                        $consultaPromediosMateriaEstiloNota=mysqli_query($conexion, "SELECT * FROM $BD.academico_notas_tipos 
                        WHERE notip_categoria='" . $config["conf_notas_categoria"] . "' AND '" . $promedioMateriaFinal . "'>=notip_desde AND '" . $promedioMateriaFinal . "'<=notip_hasta");
                        $promediosMateriaEstiloNota = mysqli_fetch_array($consultaPromediosMateriaEstiloNota, MYSQLI_BOTH);

                            if($promedioMateriaFinal == '0'){$promedioMateriaFinal='0.0';}
                            if($promedioMateriaFinal == 1){$promedioMateriaFinal='1.0';}
                            if($promedioMateriaFinal == 2){$promedioMateriaFinal='2.0';}
                            if($promedioMateriaFinal == 3){$promedioMateriaFinal='3.0';}
                            if($promedioMateriaFinal == 4){$promedioMateriaFinal='4.0';}
                            if($promedioMateriaFinal == 5){$promedioMateriaFinal='5.0';}

                        ?>
                        <td align="center"><?= $promedioMateriaFinal; ?></td>
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
                        $consultaPromedioPeriodoTodos=mysqli_query($conexion, "SELECT ROUND(AVG(bol_nota), 1) as promedio FROM $BD.academico_boletin 
                        WHERE bol_estudiante='" . $datosUsr['mat_id'] . "' AND bol_periodo='" . $j . "'");
                        $promediosPeriodos = mysqli_fetch_array($consultaPromedioPeriodoTodos, MYSQLI_BOTH);

                        $consultaSumaAusencias=mysqli_query($conexion, "SELECT sum(aus_ausencias) FROM $BD.academico_clases 
                        INNER JOIN $BD.academico_ausencias ON aus_id_clase=cls_id AND aus_id_estudiante='" . $datosUsr['mat_id'] . "'
                        WHERE cls_periodo='" . $j . "'");
                        $sumaAusencias = mysqli_fetch_array($consultaSumaAusencias, MYSQLI_BOTH);

                        $consultaPromedioEstiloNota=mysqli_query($conexion, "SELECT * FROM $BD.academico_notas_tipos 
                        WHERE notip_categoria='" . $config["conf_notas_categoria"] . "' AND '" . $promediosPeriodos['promedio'] . "'>=notip_desde AND '" . $promediosPeriodos['promedio'] . "'<=notip_hasta");
                        $promediosEstiloNota = mysqli_fetch_array($consultaPromedioEstiloNota, MYSQLI_BOTH);
                    ?>

                        <td><?= $promediosPeriodos['promedio']; ?></td>
                        <td><img src="../files/iconos/<?= $promediosEstiloNota['notip_imagen']; ?>" width="15" height="15"></td>
                    <?php 
                        $promedioFinal +=$promediosPeriodos['promedio'];
                    } 

                        $promedioFinal = round($promedioFinal/$periodoActual,2);
                        $consultaPromedioFinalEstiloNota=mysqli_query($conexion, "SELECT * FROM $BD.academico_notas_tipos 
                        WHERE notip_categoria='".$config["conf_notas_categoria"]."' AND '".$promedioFinal."'>=notip_desde AND '".$promedioFinal."'<=notip_hasta");
                        $promedioFinalEstiloNota = mysqli_fetch_array($consultaPromedioFinalEstiloNota, MYSQLI_BOTH);
                    ?>
                    <td><?=$promedioFinal;?></td>
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
                $cndisiplina = mysqli_query($conexion, "SELECT * FROM disiplina_nota 
                WHERE dn_cod_estudiante='".$datosUsr[0]."' AND dn_periodo<='".$_GET["periodo"]."'");
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
            $conCargasDos = mysqli_query($conexion, "SELECT * FROM academico_cargas
	        INNER JOIN academico_materias ON mat_id=car_materia
	        WHERE car_curso='" . $gradoActual . "' AND car_grupo='" . $grupoActual . "'");
            while ($datosCargasDos = mysqli_fetch_array($conCargasDos, MYSQLI_BOTH)) {

                
            ?>
                <tbody>
                    <tr style="color:#000;">
                        <td><?= $datosCargasDos['mat_nombre']; ?><br><span style="color:#C1C1C1;"><?= $datosCargasDos['uss_nombre']; ?></span></td>
                        <td>
                        
                            <?php
                            //INDICADORES
                            $indicadores = mysqli_query($conexion, "SELECT * FROM $BD.academico_indicadores_carga 
		                    INNER JOIN $BD.academico_indicadores ON ind_id=ipc_indicador
		                    WHERE ipc_carga='" . $datosCargasDos['car_id'] . "' AND ipc_periodo='" . $_GET["periodo"] . "'");
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