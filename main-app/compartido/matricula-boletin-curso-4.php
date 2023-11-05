<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once("../class/Estudiantes.php");
    

$year=$agnoBD;
if(isset($_GET["year"])){
$year=base64_decode($_GET["year"]);
}
$BD=$_SESSION["inst"]."_".$year;

$modulo = 1;
if(empty($_GET["periodo"])){
	$periodoActual = 1;
}else{
	$periodoActual = base64_decode($_GET["periodo"]);
}

//$periodoActual=2;

if ($periodoActual == 1) $periodoActuales = "Primero";

if ($periodoActual == 2) $periodoActuales = "Segundo";

if ($periodoActual == 3) $periodoActuales = "Tercero";

if ($periodoActual == 4) $periodoActuales = "Final";

?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>

<?php
$filtro = "";
if (!empty($_GET["id"])) {

    $filtro .= " AND mat_id='" . base64_decode($_GET["id"]) . "'";
}

if (!empty($_REQUEST["curso"])) {

    $filtro .= " AND mat_grado='" . base64_decode($_REQUEST["curso"]) . "'";
}

if (!empty($_REQUEST["grupo"])) {

    $filtro .= " AND mat_grupo='" . base64_decode($_REQUEST["grupo"]) . "'";
}

$matriculadosPorCurso = Estudiantes::estudiantesMatriculados($filtro, $BD);
while ($matriculadosDatos = mysqli_fetch_array($matriculadosPorCurso, MYSQLI_BOTH)) {

    //contador materias

    $cont_periodos = 0;

    $contador_indicadores = 0;

    $materiasPerdidas = 0;

    //======================= DATOS DEL ESTUDIANTE MATRICULADO =========================
    $usr =Estudiantes::obtenerDatosEstudiantesParaBoletin($matriculadosDatos[0],$BD);
    $num_usr = mysqli_num_rows($usr);

    $datosUsr = mysqli_fetch_array($usr, MYSQLI_BOTH);
    $nombre = Estudiantes::NombreCompletoDelEstudiante($datosUsr);

    if ($num_usr == 0) {

?>

        <script type="text/javascript">
            window.close();
        </script>

    <?php

        exit();
    }



    $contador_periodos = 0;

    ?>

    <!doctype html>

    <!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->

    <!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->

    <!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->

    <!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->

    <!--[if gt IE 8]><!-->

    <html class="no-js" lang="en">

    <!--<![endif]-->



    <head>

        <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">

        <title>Boletín</title>

        <style>
            #saltoPagina {

                PAGE-BREAK-AFTER: always;

            }
        </style>

    </head>



    <body style="font-family:Arial;">

        <?php

        //CONSULTA QUE ME TRAE LAS areas DEL ESTUDIANTE

        $consulta_mat_area_est = mysqli_query($conexion, "SELECT ar_id, car_ih FROM $BD.academico_cargas ac

		INNER JOIN $BD.academico_materias am ON am.mat_id=ac.car_materia

		INNER JOIN $BD.academico_areas ar ON ar.ar_id= am.mat_area

		WHERE  car_curso=" . $datosUsr["mat_grado"] . " AND car_grupo=" . $datosUsr["mat_grupo"] . " GROUP BY ar.ar_id ORDER BY ar.ar_posicion ASC;");

        //$numero_periodos=$config["conf_periodos_maximos"];

        $numero_periodos = $config["conf_periodo"];

        ?>

        <div align="center" style="margin-bottom:20px;">
    <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" height="150" width="200"><br>
    <!-- <?=$informacion_inst["info_nombre"]?><br>
    BOLETÍN DE CALIFICACIONES<br> -->

        </div>





        <table width="100%" cellspacing="5" cellpadding="5" border="0" rules="none">

            <tr>

                <td>Documento:<br> <?= number_format($datosUsr["mat_documento"], 0, ",", "."); ?></td>

                <td>Nombre:<br> <?=$nombre?></td>

                <td>Grado:<br> <?= $datosUsr["gra_nombre"] . " " . $datosUsr["gru_nombre"]; ?></td>

                <td>&nbsp;</td>

            </tr>



            <tr>

                <td>Jornada:<br> Mañana</td>

                <td>Sede:<br> <?= $informacion_inst["info_nombre"] ?></td>

                <td>Periodo:<br> <b><?= $periodoActuales . " (" . $year . ")"; ?></b></td>

                <td>Fecha Impresión:<br> <?= date("d/m/Y H:i:s"); ?></td>

            </tr>

        </table>

        <p>&nbsp;</p>



        <table width="100%" id="tblBoletin" cellspacing="0" cellpadding="0" rules="all" border="1" align="left">

            <tr style="font-weight:bold; background-color:#75c3ff; border-color:#000; height:40px; color:#000; font-size:12px;">

                <td width="2%" align="center">NO</td>

                <td width="20%" align="center">AREAS/ ASIGNATURAS</td>

                <td width="2%" align="center">I.H</td>

                <td width="2%" align="center">NOTA</td>

                <td width="2%" align="center">DEF.</td>

            </tr>



            <!-- Aca ira un while con los indiracores, dentro de los cuales debera ir otro while con las notas de los indicadores-->

            <?php

            $contador = 1;

            while ($fila = mysqli_fetch_array($consulta_mat_area_est, MYSQLI_BOTH)) {

                $sumaValorMaterias = 0;

                if ($periodoActual == 1) {

                    $condicion = "1";

                    $condicion2 = "1";
                }

                if ($periodoActual == 2) {

                    $condicion = "1,2";

                    $condicion2 = "2";
                }

                if ($periodoActual == 3) {

                    $condicion = "1,2,3";

                    $condicion2 = "3";
                }

                if ($periodoActual == 4) {

                    $condicion = "1,2,3,4";

                    $condicion2 = "4";
                }



                //CONSULTA QUE ME TRAE EL NOMBRE Y EL PROMEDIO DEL AREA

                $consulta_notdef_area = mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre, ar_id FROM $BD.academico_materias am

				INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area

				INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id

				INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id

				WHERE bol_estudiante='" . $matriculadosDatos[0] . "' and a.ar_id=" . $fila["ar_id"] . " and bol_periodo in (" . $condicion . ")

				GROUP BY ar_id;");

                //CONSULTA QUE ME TRAE LA DEFINITIVA POR MATERIA Y NOMBRE DE LA MATERIA

                $consulta_a_mat = mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma, ar_nombre, mat_nombre, mat_id, car_id, mat_valor FROM $BD.academico_materias am

				INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area

				INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id

				INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id

				WHERE bol_estudiante='" . $matriculadosDatos[0] . "' and a.ar_id=" . $fila["ar_id"] . " and bol_periodo in (" . $condicion . ")

				GROUP BY mat_id

				ORDER BY mat_id;");

                //CONSULTA QUE ME TRAE LAS DEFINITIVAS POR PERIODO

                $consulta_a_mat_per = mysqli_query($conexion, "SELECT bol_nota,bol_periodo,ar_nombre,mat_nombre,mat_id, mat_valor FROM $BD.academico_materias am

				INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area

				INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id

				INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id

				WHERE bol_estudiante='" . $matriculadosDatos[0] . "' and a.ar_id=" . $fila["ar_id"] . " and bol_periodo in (" . $condicion . ")

				ORDER BY mat_id,bol_periodo

				;");





                //CONSULTA QUE ME TRAE LOS INDICADORES DE CADA MATERIA

                $consulta_a_mat_indicadores = mysqli_query($conexion, "SELECT mat_nombre,mat_area,mat_id,ind_nombre,ipc_periodo,
                ROUND(SUM(cal_nota*(act_valor/100)) / SUM(act_valor/100),2) as nota, ind_id FROM $BD.academico_materias am

				INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area

				INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id

				INNER JOIN $BD.academico_indicadores_carga aic ON aic.ipc_carga=ac.car_id

				INNER JOIN $BD.academico_indicadores ai ON aic.ipc_indicador=ai.ind_id

				INNER JOIN $BD.academico_actividades aa ON aa.act_id_tipo=aic.ipc_indicador AND act_id_carga=car_id AND act_estado=1 AND act_registrada=1

				INNER JOIN $BD.academico_calificaciones aac ON aac.cal_id_actividad=aa.act_id

				WHERE car_curso=" . $datosUsr["mat_grado"] . "  and car_grupo=" . $datosUsr["mat_grupo"] . " and mat_area=" . $fila["ar_id"] . " AND ipc_periodo in (" . $condicion . ") AND cal_id_estudiante='" . $matriculadosDatos[0] . "' and act_periodo=" . $condicion2 . "

				group by act_id_tipo, act_id_carga

				order by mat_id,ipc_periodo,ind_id;");



                $numIndicadores = mysqli_num_rows($consulta_a_mat_indicadores);



                $resultado_not_area = mysqli_fetch_array($consulta_notdef_area, MYSQLI_BOTH);

                $numfilas_not_area = mysqli_num_rows($consulta_notdef_area);

                if ($numfilas_not_area > 0) {

                    $consultaSumaValorMateria=mysqli_query($conexion, "SELECT SUM(mat_valor) FROM $BD.academico_materias
                    INNER JOIN $BD.academico_cargas ON car_materia=mat_id AND car_curso='".$matriculadosDatos['mat_grado']."' AND car_grupo='".$matriculadosDatos['mat_grupo']."'
                    WHERE mat_area='".$resultado_not_area["ar_id"]."'");
                    $sumaValorMaterias = mysqli_fetch_array($consultaSumaValorMateria, MYSQLI_BOTH); 

            ?>

                    <tr style="background-color: #f9dcca" style="font-size:12px;">

                        <td colspan="2" style="font-size:12px; height:25px; font-weight:bold;"><?php echo $resultado_not_area["ar_nombre"]." (".$sumaValorMaterias[0]."%)"; ?></td>

                        <td align="center" style="font-weight:bold; font-size:12px;"></td>

                        <td>&nbsp;</td>

                        <td>&nbsp;</td>

                    </tr>

                    <?php

                    $sumaNotasPorArea = 0;

                    while ($fila2 = mysqli_fetch_array($consulta_a_mat, MYSQLI_BOTH)) {

                        $contador_periodos = 0;

                        mysqli_data_seek($consulta_a_mat_per, 0);

                        $consultaDatosBoletin=mysqli_query($conexion, "SELECT * FROM $BD.academico_boletin 
                        INNER JOIN $BD.academico_notas_tipos ON notip_categoria='".$config["conf_notas_categoria"]."' AND bol_nota>=notip_desde AND bol_nota<=notip_hasta
                        WHERE bol_carga='".$fila2["car_id"]."' AND bol_estudiante='".$matriculadosDatos['mat_id']."' AND bol_periodo='".$_GET['periodo']."'");
                        $datosBoletin = mysqli_fetch_array($consultaDatosBoletin, MYSQLI_BOTH);


                        $consultaNotaFinal=mysqli_query($conexion, "SELECT ROUND(AVG(bol_nota),2) AS def FROM academico_boletin 
                        WHERE bol_estudiante='".$matriculadosDatos['mat_id']."' AND bol_carga='".$fila2["car_id"]."'");
                        $notaFinal = mysqli_fetch_array($consultaNotaFinal, MYSQLI_BOTH);


                        //Calculo
                        if(!empty($datosBoletin['bol_nota'])) $sumaNotasPorArea += $datosBoletin['bol_nota'] * ($fila2["mat_valor"] / 100);




                    ?>

                        <tr bgcolor="#EAEAEA" style="font-size:12px;">

                            <td align="center"><?= $contador; ?></td>

                            <td style="font-size:12px; height:35px; font-weight:bold;background:#EAEAEA;"><?php echo "[".$fila2["mat_id"]."] ".$fila2["mat_nombre"]." (".$fila2["mat_valor"]."%)"; ?></td>

                            <td align="center" style="font-weight:bold; font-size:12px;background:#EAEAEA;"><?php echo $fila["car_ih"]; ?></td>

                            <td align="center" style="font-weight:bold; font-size:14px;background:#EAEAEA;"><?=$datosBoletin['bol_nota'];?></td>

                            <td align="center" style="font-weight:bold; font-size:15px;background:#EAEAEA;"><?=$notaFinal['def'];?></td>

                        </tr>

                        <?php

                        if ($numIndicadores > 0) {

                            mysqli_data_seek($consulta_a_mat_indicadores, 0);

                            $contador_indicadores = 0;

                            while ($fila4 = mysqli_fetch_array($consulta_a_mat_indicadores, MYSQLI_BOTH)) {

                                if ($fila4["mat_id"] == $fila2["mat_id"]) {

                                    $consultaRecuperacionIndicador=mysqli_query($conexion, "SELECT * FROM $BD.academico_indicadores_recuperacion 
                                    WHERE rind_estudiante='".$matriculadosDatos[0]."' AND rind_carga='".$fila2["car_id"]."' AND rind_periodo='".$_GET["periodo"]."' AND rind_indicador='".$fila4["ind_id"]."'");
                                    $recuperacionIndicador = mysqli_fetch_array($consultaRecuperacionIndicador, MYSQLI_BOTH);

                                    

                                    $contador_indicadores++;
                                    $leyendaRI = '';
                                    if($recuperacionIndicador['rind_nota']>$fila4["nota"]){
                                        $nota_indicador = round($recuperacionIndicador['rind_nota'], 1);
                                        $leyendaRI = '<br><span style="color:navy; font-size:9px;">Recuperdo.</span>';
                                    }else{
                                        $nota_indicador = round($fila4["nota"], 1);
                                    }

                                    

                                    if ($nota_indicador == 1)    $nota_indicador = "1.0";

                                    if ($nota_indicador == 2)    $nota_indicador = "2.0";

                                    if ($nota_indicador == 3)    $nota_indicador = "3.0";

                                    if ($nota_indicador == 4)    $nota_indicador = "4.0";

                                    if ($nota_indicador == 5)    $nota_indicador = "5.0";

                        ?>

                                    <tr bgcolor="#FFF" style="font-size:12px;">

                                        <td align="center">&nbsp;</td>

                                        <td style="font-size:12px; height:15px;"><?php echo $contador_indicadores . "." . $fila4["ind_nombre"]; ?></td>

                                        <td>&nbsp;</td>

                                        <td align="center" style="font-weight:bold; font-size:12px;"><?= $nota_indicador." ".$leyendaRI; ?></td>

                                        <td>&nbsp;</td>

                                    </tr>

                        <?php

                                } //fin if

                            }
                        }

                        ?>





                        <!-- observaciones de la asignatura-->

                        <?php

                        $consultaObservacion=mysqli_query($conexion, "SELECT * FROM $BD.academico_boletin

						WHERE bol_carga='" . $fila2["car_id"] . "' AND bol_periodo='" . $_GET["periodo"] . "' AND bol_estudiante='" . $matriculadosDatos[0] . "'");
                        $observacion = mysqli_fetch_array($consultaObservacion, MYSQLI_BOTH);

                        if ($observacion['bol_observaciones_boletin'] != "") {

                        ?>

                            <tr>

                                <td colspan="5">

                                    <h5 align="center">Observaciones</h5>

                                    <p style="margin-left: 5px; font-size: 11px; margin-top: -10px; margin-bottom: 5px; font-style: italic;">

                                        <?= $observacion['bol_observaciones_boletin']; ?>

                                    </p>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php

                        $contador++;
                    } //while fin materias

                    ?>

            <?php }
            } //while fin areas

            ?>

        </table>



        <p>&nbsp;</p>

        <?php

        $cndisiplina = mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disiplina_nota WHERE dn_cod_estudiante='" . $matriculadosDatos[0] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND dn_periodo in(" . $condicion . ");");

        if (@mysqli_num_rows($cndisiplina) > 0) {

        ?>

            <table width="100%" cellspacing="0" cellpadding="0" rules="all" border="1" align="center">

                <tr style="font-weight:bold; background:#4c9858; border-color:#036; height:40px; font-size:12px; text-align:center">

                    <td colspan="3">OBSERVACIONES DE CONVIVENCIA</td>

                </tr>

                <tr style="font-weight:bold; background:#e0e0153b; height:25px; color:#000; font-size:12px; text-align:center">

                    <td width="8%">Periodo</td>

                    <!--<td width="8%">Nota</td>-->

                    <td>Observaciones</td>

                </tr>

                <?php

                while ($rndisiplina = mysqli_fetch_array($cndisiplina, MYSQLI_BOTH)) {

                    $consultaDesempenoND=mysqli_query($conexion, "SELECT * FROM $BD.academico_notas_tipos WHERE notip_categoria='" . $config[22] . "' AND '" . $rndisiplina["dn_nota"] . "'>=notip_desde AND '" . $rndisiplina["dn_nota"] . "'<=notip_hasta");
                    $desempenoND = mysqli_fetch_array($consultaDesempenoND, MYSQLI_BOTH);

                ?>

                    <tr align="center" style="font-weight:bold; font-size:12px; height:20px;">

                        <td><?= $rndisiplina["dn_periodo"] ?></td>

                        <!--<td><?= $desempenoND[1] ?></td>-->

                        <td align="left"><?= $rndisiplina["dn_observacion"] ?></td>

                    </tr>

                <?php } ?>

            </table>

        <?php } ?>

        <!--<hr align="center" width="100%">-->

        <div align="center">

            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="text-align:center; font-size:12px;">

                <tr>

                    <td style="font-weight:bold;" align="left"><?php if ($num_observaciones > 0) { ?>

                            COMPORTAMIENTO:

                        <?php } ?>

                        <b><u>

                                <!-- <?= strtoupper($r_diciplina[3]); ?> -->

                            </u></b><br>

                        <?php

                        ?></td>

                </tr>

            </table>

            <?php

            //print_r($vectorT);

            ?>

        </div>

        <!--

<div>

<table width="100%" cellspacing="0" cellpadding="0"  border="0" style="text-align:center; font-size:12px;">

  <tr>

    <td style="font-weight:bold;" align="left">

    OBSERVACIONES:_____________________________________________________________________________________________________________<br><br>

    ____________________________________________________________________________________________________________________________<br><br>

    ____________________________________________________________________________________________________________________________<br>

    </td>

  </tr>

</table>



</div>

-->



        <p>&nbsp;</p>

        <!--<div align="center"><img src="../files/firmas/fondo1.jpg" height="160"></div>-->

        

<table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">

	<tr>

		<td align="center"><img src="../files/firmas/firmaCampillo.jpeg" height="160"><br>_________________________________<br><?= strtoupper(""); ?><br>Rector(a)</td>

		<td align="center"><p style="height:0px;"></p>_________________________________<br><?= strtoupper(""); ?><br>Director(a) de grupo</td>

    </tr>

</table> 





        <!--

<br>

<div align="center">

<table width="100%" cellspacing="0" cellpadding="0"  border="1" style="text-align:center; font-size:8px; background:#FFFFCC;">

  <tr style="text-transform:uppercase;">

    <td style="font-weight:bold;" align="right">ESCALA NACIONAL</td><td>Desempe&ntilde;o Superior</td><td>Desempe&ntilde;o Alto</td><td>Desempe&ntilde;o B&aacute;sico</td><td>Desempe&ntilde;o Bajo</td>

  </tr>

  

  <tr>

  	<td style="font-weight:bold;" align="right">RANGO INSTITUCIONAL</td>

  	<td>NO HAY</td><td>NO HAY</td><td>NO HAY</td><td>NO HAY</td>  

  </tr>



</table>

-->



        </div>

        <?php

        /*if ($periodoActual == 4) {

            if ($materiasPerdidas >= $config["conf_num_materias_perder_agno"])

                $msj = "<center>EL (LA) ESTUDIANTE " . strtoupper($datosUsr[3] . " " . $datosUsr[4] . " " . $datosUsr["mat_nombres"]) . " NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";

            elseif ($materiasPerdidas < $config["conf_num_materias_perder_agno"] and $materiasPerdidas > 0)

                $msj = "<center>EL (LA) ESTUDIANTE " . strtoupper($datosUsr[3] . " " . $datosUsr[4] . " " . $datosUsr["mat_nombres"]) . " DEBE NIVELAR LAS MATERIAS PERDIDAS</center>";

            else

                $msj = "<center>EL (LA) ESTUDIANTE " . strtoupper($datosUsr[3] . " " . $datosUsr[4] . " " . $datosUsr["mat_nombres"]) . " FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";
        }*/

        ?>

        <p align="center">

            <div style="font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-style:italic; font-size:12px;" align="center">

                <?= $msj; ?>

            </div>

        </p>



        <div align="center" style="font-size:10px; margin-top:5px; margin-bottom: 10px;">

            <img src="https://plataformasintia.com/images/logo.png" height="50"><br>

            ESTE DOCUMENTO FUE GENERADO POR:<br>

            SINTIA - SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL

        </div>



        <div id="saltoPagina"></div>

    <?php

} // FIN DE TODOS LOS MATRICULADOS

    ?>

    <script type="application/javascript">
        print();
    </script>

    </body>



    </html>