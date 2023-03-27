<?php include("../directivo/session.php");
include("../class/Estudiantes.php");

$modulo = 1;

?>

<!doctype html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->

<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->

<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->

<!--[if gt IE 8]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->

<head>

    <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
    <link rel="shortcut icon" href="<?= $Plataforma->logo; ?>">
    <title>SINTIA - Certificados</title>

</head>



<body style="font-family:Arial;">


    <?php
    $nombreInforme = "CERTIFICADO DE ESTUDIOS" . "<br>" . " No. 12114";
    include("../compartido/head-informes.php") ?>

    <div align="left" style="margin-bottom:20px;">

        CÓDIGO DEL DANE 305001003513</b><br><br>

        Los suscritos Rector y Secretaria del Instituto Colombo Venezolano, establecimiento de carácter privado, calendario A, con sus estudios aprobados de Primaria y Bachillerato, según Resolución 8339 del 25 de octubre de 1993, por los años de 1993 a 1997 y 008965 del 21 de junio de 1994.

    </div>



    <p align="center">C E R T I F I C A N</p>



    <?php

    $horas[0] = 'CERO';
    $horas[1] = 'UNO';
    $horas[2] = 'DOS';
    $horas[3] = 'TRES';
    $horas[4] = 'CUATRO';
    $horas[5] = 'CINCO';
    $horas[6] = 'SEIS';
    $horas[7] = 'SIETE';
    $horas[8] = 'OCHO';
    $horas[9] = 'NUEVE';
    $horas[10] = 'DIEZ';


    $restaAgnos = ($_POST["hasta"] - $_POST["desde"]) + 1;

    $i = 1;

    $inicio = $_POST["desde"];

    $grados = "";

    while ($i <= $restaAgnos) {

	mysqli_select_db($conexion, $config['conf_base_datos']."_".$inicio);
	$estudiante = Estudiantes::obtenerDatosEstudiante($_POST["id"]);
	$nombre = Estudiantes::NombreCompletoDelEstudiante($estudiante);

        if ($i < $restaAgnos)

            $grados .= $estudiante["gra_nombre"] . ", ";

        else

            $grados .= $estudiante["gra_nombre"];

        $inicio++;

        $i++;
    }

    ?>



    <p>Que, <b><?=$nombre?></b> cursó en esta Institución <b><?=$grados;?></b> grado(s) de educación básica primaria  y obtuvo las siguientes calificaciones:</p>



    <?php

    $restaAgnos = ($_POST["hasta"] - $_POST["desde"]) + 1;

    $i = 1;

    $inicio = $_POST["desde"];

    while ($i <= $restaAgnos) {

	mysqli_select_db($conexion, $config['conf_base_datos']."_".$inicio);
	$matricula = Estudiantes::obtenerDatosEstudiante($_POST["id"]);

    ?>


         <?= strtoupper(Utilidades::getToString($matricula["mat_grupo"])); ?>
        <p align="center" style="font-weight:bold;">
            <?= strtoupper(Utilidades::getToString($matricula["mat_grupo"])); ?> GRADO DE EDUCACIÓN BÁSICA SECUNDARIA <?= $inicio; ?><br>
            MATRÍCULA <?= strtoupper(Utilidades::getToString($matricula["mat_matricula"])); ?> FOLIO <?= strtoupper(Utilidades::getToString($matricula["mat_folio"])); ?>
        </p>




        <?php if ($inicio < $config[1] and $config[3] < 5) { ?>

            <table width="100%" cellspacing="0" cellpadding="0" rules="all" border="1" align="left">

                <tr style="font-weight:bold;">

                    <td>ÁREAS/ASIGNATURAS</td>

                    <td>CALIFICACIONES</td>

                    <td>HORAS</td>

                </tr>

                <?php

                //SELECCION LAS CARGAS DEL ESTUDIANTE, MATERIAS, AREAS

                $cargasAcademicas = mysqli_query($conexion, "SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area FROM academico_cargas 

                                            INNER JOIN academico_materias ON mat_id=car_materia

                                            INNER JOIN academico_areas ON ar_id=mat_area

                                            WHERE car_curso='" . $matricula["mat_grado"] . "' AND car_grupo='" . Utilidades::getToString($matricula["mat_grupo"]) . "'");

                $materiasPerdidas = 0;

                while ($cargas = mysqli_fetch_array($cargasAcademicas, MYSQLI_BOTH)) {

                    //OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES

                    $consultaBoletin = mysqli_query($conexion, "SELECT avg(bol_nota) FROM academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' and bol_carga='" . $cargas["car_id"] . "'");
                    $boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);

                $nota = 0;
                if(!empty($boletin[0])){
                    $nota = round($boletin[0],1);
                }

                    if ($nota < $config[5]) {

                        $materiasPerdidas++;
                    }

                ?>

                    <tr>

                        <td><?= strtoupper($cargas["mat_nombre"]); ?></td>

                        <td><?= $nota; ?></td>

                        <td><?= $cargas["car_ih"] . " (" . $horas[$cargas["car_ih"]] . ")"; ?></td>

                    </tr>

                <?php

                }

                ?>



            </table>



            <p>&nbsp;</p>

            <?php

            $nivelaciones = mysqli_query($conexion, "SELECT niv_definitiva, niv_acta, niv_fecha_nivelacion, mat_nombre FROM academico_nivelaciones 

									INNER JOIN academico_cargas ON car_id=niv_id_asg

									INNER JOIN academico_materias ON mat_id=car_materia

									WHERE niv_cod_estudiante='" . $_POST["id"] . "'");



            $numNiv = mysqli_num_rows($nivelaciones);

            if ($numNiv > 0) {

                echo "El(la) Estudiante niveló las siguientes materias:<br>";

                while ($niv = mysqli_fetch_array($nivelaciones, MYSQLI_BOTH)) {

                    echo "<b>" . strtoupper($niv["mat_nombre"]) . " (" . $niv["niv_definitiva"] . ")</b> Segun acta " . $niv["niv_acta"] . " en la fecha de " . $niv["niv_fecha_nivelacion"] . "<br>";
                }
            }

            ?>



            <?php

            if ($materiasPerdidas == 0)

                $msj = "<center>EL (LA) ESTUDIANTE " . strtoupper($datos_usr[3] . " " . $datos_usr[4] . " " . $datos_usr["mat_nombres"]) . " FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";

            else

                $msj = "<center>EL (LA) ESTUDIANTE " . strtoupper($datos_usr[3] . " " . $datos_usr[4] . " " . $datos_usr["mat_nombres"]) . " NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";

            ?>



            <?php if ($numNiv == 0) { ?><div align="left" style="font-weight:bold; font-style:italic; font-size:12px; margin-bottom:20px;"><?= $msj; ?></div><?php } ?>



            <!-- SI ESTÁ EN EL AÑO ACTUAL Y ESTE NO HA TERMINADO -->

        <?php } else { ?>

            <table width="100%" cellspacing="0" cellpadding="0" rules="all" border="1" align="left">

                <tr style="font-weight:bold; text-align:center;">

                    <td>ÁREAS/ASIGNATURAS</td>

                    <td>HS</td>

                    <?php

                    $p = 1;

                    //PERIODOS

                    while ($p <= $config[19]) {

                        echo '<td>' . $p . 'P</td>';

                        $p++;
                    }

                    ?>

                    <td>DEF</td>

                    <td>DESEMPEÑO</td>

                </tr>

                <?php

                //SELECCION LAS CARGAS DEL ESTUDIANTE, MATERIAS, AREAS

                $cargasAcademicas = mysqli_query($conexion, "SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area FROM academico_cargas 

                                            INNER JOIN academico_materias ON mat_id=car_materia

                                            INNER JOIN academico_areas ON ar_id=mat_area

                                            WHERE car_curso='" . $matricula["mat_grado"] . "' AND car_grupo='" . Utilidades::getToString($matricula["mat_grupo"]) . "'");

                while ($cargas = mysqli_fetch_array($cargasAcademicas, MYSQLI_BOTH)) {

                    //OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES

                    $consultaBoletin = mysqli_query($conexion, "SELECT avg(bol_nota) FROM academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' AND bol_carga='" . $cargas["car_id"] . "'");
                    $boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);

                $nota = 0;
                if(!empty($boletin[0])){
                    $nota = round($boletin[0],1);
                }

                    $consultaDesempeno = mysqli_query($conexion, "SELECT * FROM academico_notas_tipos WHERE notip_categoria='" . $config[22] . "' AND " . $nota . ">=notip_desde AND " . $nota . "<=notip_hasta");
                    $desempeno = mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH);

                ?>

                    <tr style="text-align:center;">

                        <td style="text-align:left;"><?= strtoupper($cargas["mat_nombre"]); ?></td>

                        <td><?= $cargas["car_ih"]; ?></td>

                        <?php

                        $p = 1;

                        //PERIODOS

                        while ($p <= $config[19]) {

                            $consultaNotasPeriodos = mysqli_query($conexion, "SELECT bol_nota FROM academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' AND bol_carga='" . $cargas["car_id"] . "' AND bol_periodo='" . $p . "'");
                            $notasPeriodo = mysqli_fetch_array($consultaNotasPeriodos, MYSQLI_BOTH);

                            echo '<td>' . $notasPeriodo[0] . '</td>';

                            $p++;
                        }

                        ?>

                        <td><?= $nota; ?></td>

                        <td><?= $desempeno[1]; ?></td>

                    </tr>

                <?php

                }

                ?>



            </table>



        <?php } ?>







    <?php

        $inicio++;

        $i++;
    }

    ?>





    <p>&nbsp;</p>

    PLAN DE ESTUDIOS: Ley 115 de Educación, artículo 23, Decreto 1860 de 1994. Decreto 1290 de 2009 y Decreto 3055 del 12 de diciembre de 2002. Intensidad horaria 35 horas semanales de 55 minutos.<br>

    Se expide el presente certificado en Medellín el <?= date("d"); ?> de <?= date("M"); ?> de <?= date("Y"); ?>.





    <p>&nbsp;</p>

    <table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">

        <tr>

            <td align="center">_________________________________<br><!--<?= strtoupper(""); ?><br>-->Rector(a)</td>

            <td align="center">_________________________________<br><!--<?= strtoupper(""); ?><br>-->Director(a) de grupo</td>

        </tr>

    </table>






    <?php include("../compartido/footer-informes.php") ?>;



</body>

</html>