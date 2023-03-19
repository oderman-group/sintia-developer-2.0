
<?php
    include("../directivo/session.php");
    include("../class/Plataforma.php");
    include("../class/Estudiantes.php");
    include("../class/Boletin.php");
    $Plataforma = new Plataforma;

    $year=$agnoBD;
    if(isset($_REQUEST["year"])){
    $year=$_REQUEST["year"];
    }
    $BD=$_SESSION["inst"]."_".$year;

    $modulo = 1;

    if (empty($_REQUEST["periodo"])) {
        $periodoActual = 1;
    } else {
        $periodoActual = $_REQUEST["periodo"];
    }

    switch($periodoActual){
        case 1:
            $periodoActuales = "Primero";
            break;
        case 2:
            $periodoActuales = "Segundo";
            break;
        case 3:
            $periodoActuales = "Tercero";
            break;
        case 4:
            $periodoActuales = "Final";
            break;
    }

    if (is_numeric($_REQUEST["id"])) {
        $filtro .= " AND mat_id='" . $_REQUEST["id"] . "'";
    }

    if (is_numeric($_REQUEST["curso"])) {
        $filtro .= " AND mat_grado='" . $_REQUEST["curso"] . "'";
    }

    if(is_numeric($_REQUEST["grupo"])){
        $filtro .= " AND mat_grupo='".$_REQUEST["grupo"]."'";
    }
    $matriculadosPorCurso = Estudiantes::estudiantesMatriculados($filtro, $BD);
    $numeroEstudiantes = mysqli_num_rows($matriculadosPorCurso);
    if ($numeroEstudiantes == 0) {
    ?>
        NO HAY REGISTROS...
    <?php
        exit();
    }
    $colspan=4+$periodoActual;
    while ($matriculadosDatos = mysqli_fetch_array($matriculadosPorCurso, MYSQLI_BOTH)) {
        //METODO QUE ME TRAE EL NOMBRE COMPLETO DEL ESTUDIANTE
        $nombreEstudainte=Estudiantes::NombreCompletoDelEstudiante($matriculadosDatos);
        //CONSULTA QUE ME TRAE LAS AREAS DEL ESTUDIANTE
        $consultaAreaEstudiante = Boletin::obtenerAreasDelEstudiante($matriculadosDatos['mat_grado'], $matriculadosDatos['mat_grupo'], $BD);

?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <title>Boletín</title>
        <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
        <!-- favicon -->
        <link rel="shortcut icon" href="../sintia-icono.png" />
        <style>
            #saltoPagina {
                PAGE-BREAK-AFTER: always;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    </head>
    <body style="font-family:Arial; font-size:9px;">
        <div style="margin: 15px 0;">
            <table width="100%" cellspacing="5" cellpadding="5" border="1" rules="all" style="font-size: 13px;">
                <tr>
                    <td rowspan="2"><?=$informacion_inst["info_nombre"]?></td>
                    <td colspan="2">Nombre: <b><?=$nombreEstudainte?></b></td>
                    <td rowspan="2" width="20%"><img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" width="100%"></td>
                </tr>
                <tr>
                    <td width="20%">Codigo: <b><?=$matriculadosDatos["mat_id"]?></b></td>
                    <td width="25%">Sede: <b><?=$informacion_inst["info_nombre"]?></b></td>
                </tr>
            </table>
            <table width="100%" cellspacing="5" cellpadding="5" border="1" rules="all" style="font-size: 13px;">
                <tr>
                    <td width="25%">Periodo: <b><?=$periodoActuales?></b></td>
                    <td width="25%">Grado: <b><?=$matriculadosDatos["gra_nombre"]." ".$matriculadosDatos["gru_nombre"]?></b></td>
                    <td width="25%">Nivel: <b>Bachiller</b></td>
                    <td width="25%">Jornada: <b><?=$informacion_inst["info_jornada"]?></b></td>
                </tr>
                <tr style="text-align:center; font-size: 13px;">
                    <td colspan="4"><h3 style="margin: 0 10%;"><b>AÑO LECTIVO <?=$year?> - INFORME DEFINITIVO DE NOTAS</b></h3></td>
                </tr>
            </table>
        </div>
        <table width="100%" cellspacing="5" cellpadding="5" rules="all" style="font-size: 13px;">
            <tr style="text-align:center; font-size: 13px;">
                <td style="color: #b2adad;">
                    <?php
                        $consultaEstiloNota = Boletin::listarTipoDeNotas($config["conf_notas_categoria"], $BD);
                        $numEstiloNota=mysqli_num_rows($consultaEstiloNota);
                        $i=1;
                        while($estiloNota = mysqli_fetch_array($consultaEstiloNota, MYSQLI_BOTH)){
                            $diagonal=" / ";
                            if($i==$numEstiloNota){
                                $diagonal="";
                            }
                            echo $estiloNota['notip_nombre'].": ".$estiloNota['notip_desde']." - ".$estiloNota['notip_hasta'].$diagonal;
                            $i++;
                        }
                    ?>
                </td>
            </tr>
        </table>
        <table width="100%" rules="all" border="1" style="font-size: 15px;">
            <thead style="background-color: #00adefad;">
                <tr style="font-weight:bold; text-align:center;">
                    <td width="2%" rowspan="2">Nº</td>
                    <td width="20%" rowspan="2">ASIGNATURAS</td>
                    <td width="3%" colspan="<?=$periodoActual?>"><a href="#" style="color:#000; text-decoration:none;">Periodo Cursados</a></td>
                    <td width="3%" colspan="2">Valoración Final</td>
                </tr>
                <tr style="font-weight:bold; text-align:center;">
                    <?php
                        for($i=1;$i<=$periodoActual;$i++){
                    ?>
                        <td width="3%"><?=$i?></td>
                    <?php
                        }
                    ?>
                    <td width="3%">Nota</td>
                    <td width="3%">Desempeño</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $contador=1;
                $promedioGeneral1=0;
                $promedioGeneral2=0;
                $promedioGeneral3=0;
                $promedioGeneral4=0;
                while ($area = mysqli_fetch_array($consultaAreaEstudiante, MYSQLI_BOTH)) {
                    switch($periodoActual){
                        case 1:
                            $condicion = "1";
                            $condicion2 = "1";
                            break;
                        case 2:
                            $condicion = "1,2";
                            $condicion2 = "2";
                            break;
                        case 3:
                            $condicion = "1,2,3";
                            $condicion2 = "3";
                            break;
                        case 4:
                            $condicion = "1,2,3,4";
                            $condicion2 = "4";
                            break;
                    }
                    //CONSULTA QUE ME TRAE EL NOMBRE Y EL PROMEDIO DEL AREA
                    $consultaArea = Boletin::obtenerDatosDelArea($matriculadosDatos['mat_id'], $area["ar_id"], $condicion, $BD);
                    $datosArea = mysqli_fetch_array($consultaArea, MYSQLI_BOTH);
                ?>
                <tr style="background: #EAEAEA">
                    <td colspan="<?=$colspan?>"><?=$datosArea["ar_nombre"]?></td>
                </tr>
                <?php
                    //CONSULTA QUE ME TRAE LA DEFINITIVA POR MATERIA Y NOMBRE DE LA MATERIA
                    $consultaDefinitivaNombreMateria = Boletin::obtenerDefinitivaYnombrePorMateria($matriculadosDatos['mat_id'], $area["ar_id"], $condicion, $BD);
                    while ($materia = mysqli_fetch_array($consultaDefinitivaNombreMateria, MYSQLI_BOTH)) {
                ?>
                <tr>
                    <td align="center" style="background: #9ed8ed"><?=$contador?></td>
                    <td><?=$materia["mat_nombre"]?></td>
                    <?php
                        $notaGeneral1=0;
                        $notaGeneral2=0;
                        $notaGeneral3=0;
                        $notaGeneral4=0;
                        $promedioMateria = 0;
                        for($i=1;$i<=$periodoActual;$i++){
                            $consultaBoletin=Boletin::obtenerObservaciones($materia["car_id"], $i, $matriculadosDatos['mat_id'], $BD);
                            $datosBoletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);

                            $notaBoletin=0;
                            if (!empty($datosBoletin['bol_nota'])) {
                                $notaBoletin = (round($datosBoletin['bol_nota'], 1));
                            }
                            $formula=($materia['mat_valor']/100);
                            switch($i){
                                case 1:
                                    $notaGeneral1+=($notaBoletin*$formula);
                                    break;
                                case 2:
                                    $notaGeneral2+=($notaBoletin*$formula);
                                    break;
                                case 3:
                                    $notaGeneral3+=($notaBoletin*$formula);
                                    break;
                                case 4:
                                    $notaGeneral4+=($notaBoletin*$formula);
                                    break;
                            }

                            $promedioMateria += $notaBoletin;
                    ?>
                    <td align="center"><?=$notaBoletin?></td>
                    <?php
                        }//FIN FOR
                        $promedioMateria = round($promedioMateria / ($i - 1), 1);
                        $promedioMateriaFinal = $promedioMateria;

                        // SI PERDIÓ LA MATERIA A FIN DE AÑO
                        if ($promedioMateria < $config["conf_nota_minima_aprobar"]) {
                            $consultaNivelacion = Boletin::obtenerNivelaciones($materia['car_id'], $matriculadosDatos['mat_id'], $BD);
                            $nivelacion = mysqli_fetch_array($consultaNivelacion, MYSQLI_BOTH);

                            $promedioMateriaFinal = $nivelacion['niv_definitiva'];
                        }

                        $promediosMateriaEstiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $promedioMateriaFinal, $BD);
                    ?>
                    <td align="center"><?=$promedioMateriaFinal;?></td>
                    <td align="center"><?=$promediosMateriaEstiloNota['notip_nombre'];?></td>
                </tr>
                <?php
                    $consultaObsevacion=Boletin::obtenerObservaciones($materia["car_id"], $periodoActual, $matriculadosDatos['mat_id'], $BD);
                    $observacion = mysqli_fetch_array($consultaObsevacion, MYSQLI_BOTH);
                    if (!empty($observacion['bol_observaciones_boletin'])) {
                ?>
                <tr style="background: #9ed8ed">
                    <td colspan="<?=$colspan?>">
                        <h5 align="center" style="margin: 0 10%;">Observaciones</h5>
                        <p style="margin-left: 5px; font-size: 11px; margin-top: 5px; margin-bottom: 5px; font-style: italic;">
                            <?=$observacion['bol_observaciones_boletin']?>
                        </p>
                    </td>
                </tr>
                <?php
                            }//FIN IF OBSERVACIONES
                        $contador++;
                        $promedioGeneral1+=$notaGeneral1;
                        $promedioGeneral2+=$notaGeneral2;
                        $promedioGeneral3+=$notaGeneral3;
                        $promedioGeneral4+=$notaGeneral4;
                        }//FIN WHILE MATERIAS
                    }//FIN WHILE AREAS
                ?>
            </tbody>
            <tfoot style="font-weight:bold; font-size: 13px;">
                <tr style="background: #9ed8ed">
                    <td colspan="<?=$colspan?>"></td>
                </tr>
                <tr style="background: #EAEAEA">
                    <td colspan="2">PROMEDIO GENERAR SEGÚN EL PORCENTAJE DE LAS MATERIAS</td>
                    <?php
                    $promedioFinal = 0;
                    for ($j = 1; $j <= $periodoActual; $j++) {
                        switch($j){
                            case 1:
                                $promediosPeriodos=$promedioGeneral1;
                                break;
                            case 2:
                                $promediosPeriodos=$promedioGeneral2;
                                break;
                            case 3:
                                $promediosPeriodos=$promedioGeneral3;
                                break;
                            case 4:
                                $promediosPeriodos=$promedioGeneral4;
                                break;
                        }
                        $promediosPeriodos = $promediosPeriodos/($contador-1);
                        $promediosPeriodos = round($promediosPeriodos,1);
                    ?>
                    <td align="center"><?=$promediosPeriodos;?></td>
                    <?php 
                        $promedioFinal +=$promediosPeriodos;
                    }// FIN FOR

                    $promedioFinal = round($promedioFinal/$periodoActual,1);
                    $promedioFinalEstiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $promedioFinal, $BD);
                    ?>
                    <td align="center"><?=$promedioFinal;?></td>
                    <td align="center"><?= $promedioFinalEstiloNota['notip_nombre']; ?></td>
                </tr>
            </tfoot>
        </table>

        <p>&nbsp;</p>

        <table style="font-size: 15px;" width="100%" cellspacing="5" cellpadding="5" rules="all" border="1">
            <thead>
                <tr style="font-weight:bold; text-align:left; background-color: #00adefad;">
                    <td><b>Observaciones:</b></td>
                </tr>
            </thead>
            <tbody>
                <tr style="color:#000; text-align:center">
                    <td>
                        <?php 
                        for ($j = 1; $j <= $periodoActual; $j++) {
                            $cndisiplina = mysqli_query($conexion, "SELECT * FROM disiplina_nota WHERE dn_cod_estudiante='".$matriculadosDatos[0]."' AND dn_periodo<='".$j."'");
                            while($rndisiplina=mysqli_fetch_array($cndisiplina, MYSQLI_BOTH)){
                                echo $rndisiplina["dn_periodo"]." Per. - ".$rndisiplina["dn_observacion"].".<br>";
                            }
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>  

        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>      

        <table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">
            <tr>
                <td align="center"><br>_________________________________<br><?= strtoupper(""); ?><br>Rector(a)</td>
                <td align="center"><p style="height:0px;"></p>_________________________________<br><?= strtoupper(""); ?><br>Director(a) de grupo</td>
            </tr>
        </table>

        <p>&nbsp;</p>
        <div align="center" style="font-size:10px; margin-top:5px; margin-bottom: 10px;">
            <img src="<?=$Plataforma->logo;?>" height="50"><br>
            ESTE DOCUMENTO FUE GENERADO POR:<br>
            SINTIA - SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL
        </div>

        <div id="saltoPagina"></div>

<?php
    }//FIN WHILE MATRICULADOS
?>

        <script type="application/javascript">
            print();
        </script>
    </body>
</html>