<?php
include("../directivo/session.php");
require_once("../class/Estudiantes.php");
require_once("../class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");

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
     include("head-informes.php") ?>

    <div align="left" style="margin-bottom:20px;">

        CÓDIGO DEL DANE 305001003513</b><br><br>

        Los suscritos Rector y Secretaria del <b><?= $informacion_inst["info_nombre"] ?></b>, establecimiento de carácter privado, calendario A, con sus estudios aprobados de Primaria y Bachillerato, según Resolución 8339 del 25 de octubre de 1993, por los años de 1993 a 1997 y 008965 del 21 de junio de 1994.

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
	$estudiante = Estudiantes::obtenerDatosEstudiante($_POST["id"],$inicio);
	$nombre = Estudiantes::NombreCompletoDelEstudiante($estudiante);
	
	if($estudiante["mat_grado"]>=1 and $estudiante["mat_grado"]<=5) {$educacion = "BÁSICA PRIMARIA"; $horasT = 30;}	
	elseif($estudiante["mat_grado"]>=6 and $estudiante["mat_grado"]<=9) {$educacion = "BÁSICA SECUNDARIA"; $horasT = 35;}
	elseif($estudiante["mat_grado"]>=10 and $estudiante["mat_grado"]<=11) {$educacion = "MEDIA SECUNDARIA"; $horasT = 35;}	
	elseif($estudiante["mat_grado"]>=12 and $estudiante["mat_grado"]<=15) {$educacion = "PREESCOLAR"; $horasT = 25;}

        if ($i < $restaAgnos)

            $grados .= strtoupper($estudiante["gra_nombre"]) . ", ";

        else

            $grados .= strtoupper($estudiante["gra_nombre"]);

        $inicio++;

        $i++;
    }

    ?>



    <p>Que, <b><?=$nombre?></b> cursó en esta Institución <b><?=$grados;?></b> grado(s) de <?=$educacion;?>  y obtuvo las siguientes calificaciones:</p>



    <?php

    $restaAgnos = ($_POST["hasta"] - $_POST["desde"]) + 1;

    $i = 1;

    $inicio = $_POST["desde"];

    while ($i <= $restaAgnos) {
	$matricula = Estudiantes::obtenerDatosEstudiante($_POST["id"],$inicio);

    ?>


        <p align="center" style="font-weight:bold;">
            <?= strtoupper(Utilidades::getToString($matricula["gra_nombre"])); ?> GRADO DE EDUCACIÓN <?=$educacion;?> <?= $inicio; ?><br>
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

                $cargasAcademicas = mysqli_query($conexion, "SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area FROM ".BD_ACADEMICA.".academico_cargas car 

                                            INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}

                                            INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$inicio}

                                            WHERE car_curso='" . $matricula["mat_grado"] . "' AND car_grupo='" . Utilidades::getToString($matricula["mat_grupo"]) . "' AND car.institucion={$config['conf_id_institucion']} AND car.year={$inicio}");

                $materiasPerdidas = 0;

				$horasT = 0;
                while ($cargas = mysqli_fetch_array($cargasAcademicas, MYSQLI_BOTH)) {

                    //OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES

                    $consultaBoletin = mysqli_query($conexion, "SELECT avg(bol_nota) FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' and bol_carga='" . $cargas["car_id"] . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
                    $boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);

                $nota = 0;
                if(!empty($boletin[0])){
                    $nota = round($boletin[0],1);
                }

                if ($nota < $config[5]) {
                    $materiasPerdidas++;
                }

                $notaFinal=$nota;
                if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                    $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $nota,$inicio);
                    $notaFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
                }

                ?>

                    <tr>

                        <td><?= $cargas["car_id"] .") -". strtoupper($cargas["mat_nombre"]); ?></td>

                        <td><?= $notaFinal; ?></td>

                        <td><?= $cargas["car_ih"] . " (" . $horas[$cargas["car_ih"]] . ")"; ?></td>

                    </tr>

                <?php
                $horasT += $cargas["car_ih"];

                }

                //MEDIA TECNICA
                if (array_key_exists(10, $_SESSION["modulos"])){
                    $consultaEstudianteActualMT = MediaTecnicaServicios::existeEstudianteMT($config,$inicio,$_POST["id"]);
                    while($datosEstudianteActualMT = mysqli_fetch_array($consultaEstudianteActualMT, MYSQLI_BOTH)){
                        if(!empty($datosEstudianteActualMT)){
                //SELECCION LAS CARGAS DEL ESTUDIANTE, MATERIAS, AREAS DE MT

                $cargasAcademicas = mysqli_query($conexion, "SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area FROM ".BD_ACADEMICA.".academico_cargas car 

                                            INNER JOIN ".BD_ACADEMICA.".academico_materias am. ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}

                                            INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$inicio}

                                            WHERE car_curso='" . $datosEstudianteActualMT["matcur_id_curso"] . "' AND car_grupo='" . $datosEstudianteActualMT["matcur_id_grupo"] . "' AND car.institucion={$config['conf_id_institucion']} AND car.year={$inicio}");

                $materiasPerdidas = 0;

                while ($cargas = mysqli_fetch_array($cargasAcademicas, MYSQLI_BOTH)) {

                    //OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES

                    $consultaBoletin = mysqli_query($conexion, "SELECT avg(bol_nota) FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' and bol_carga='" . $cargas["car_id"] . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
                    $boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);

                $nota = 0;
                if(!empty($boletin[0])){
                    $nota = round($boletin[0],1);
                }

                if ($nota < $config[5]) {
                    $materiasPerdidas++;
                }

                $notaFinal=$nota;
                if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                    $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $nota,$inicio);
                    $notaFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
                }

                ?>

                    <tr>

                        <td><?= strtoupper($cargas["mat_nombre"]); ?></td>

                        <td><?= $notaFinal; ?></td>

                        <td><?= $cargas["car_ih"] . " (" . $horas[$cargas["car_ih"]] . ")"; ?></td>

                    </tr>

                <?php

                }}}}

                ?>



            </table>



            <p>&nbsp;</p>

            <?php

            $nivelaciones = mysqli_query($conexion, "SELECT niv_definitiva, niv_acta, niv_fecha_nivelacion, mat_nombre FROM ".BD_ACADEMICA.".academico_nivelaciones niv 

									INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car_id=niv.niv_id_asg AND car.institucion={$config['conf_id_institucion']} AND car.year={$inicio}

									INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}

									WHERE niv.niv_cod_estudiante='" . $_POST["id"] . "' AND niv.institucion={$config['conf_id_institucion']} AND niv.year={$inicio}");



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

                $msj = "<center>EL (LA) ESTUDIANTE " . $nombre . " FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";

            else

                $msj = "<center>EL (LA) ESTUDIANTE " . $nombre . " NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";

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

                $cargasAcademicas = mysqli_query($conexion, "SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area FROM ".BD_ACADEMICA.".academico_cargas car 

                                            INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}

                                            INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$inicio}

                                            WHERE car_curso='" . $matricula["mat_grado"] . "' AND car_grupo='" . Utilidades::getToString($matricula["mat_grupo"]) . "' AND car.institucion={$config['conf_id_institucion']} AND car.year={$inicio}");

                $materiasPerdidas = 0;
                $horasT = 0;
                while ($cargas = mysqli_fetch_array($cargasAcademicas, MYSQLI_BOTH)) {

                    //OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES

                    $consultaBoletin = mysqli_query($conexion, "SELECT avg(bol_nota) FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' AND bol_carga='" . $cargas["car_id"] . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
                    $boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);

                $nota = 0;
                if(!empty($boletin[0])){
                    $nota = round($boletin[0],1);
                }
                
				if ($nota < $config[5]) {
					$materiasPerdidas++;
				}

                    $consultaDesempeno = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_categoria='" . $config[22] . "' AND " . $nota . ">=notip_desde AND " . $nota . "<=notip_hasta AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
                    $desempeno = mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH);

                ?>

                    <tr style="text-align:center;">

                        <td style="text-align:left;"><?= strtoupper($cargas["mat_nombre"]); ?></td>

                        <td><?= $cargas["car_ih"]; ?></td>

                        <?php

                        $horasT += $cargas["car_ih"];
                        $p = 1;

                        //PERIODOS

                        while ($p <= $config[19]) {

                            $consultaNotasPeriodos = mysqli_query($conexion, "SELECT bol_nota FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' AND bol_carga='" . $cargas["car_id"] . "' AND bol_periodo='" . $p . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
                            $notasPeriodo = mysqli_fetch_array($consultaNotasPeriodos, MYSQLI_BOTH);

                            $notasPeriodoFinal='';
                            if(!empty($notasPeriodo[0])){
                                $notasPeriodoFinal=$notasPeriodo[0];
                                if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                                    $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notasPeriodo[0],$inicio);
                                    $notasPeriodoFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
                                }
                            }

                            echo '<td>' . $notasPeriodoFinal . '</td>';

                            $p++;
                        }

                        ?>

                        <td><?= $nota; ?></td>

                        <td><?= $desempeno['notip_nombre']; ?></td>

                    </tr>

                <?php

                }

                //MEDIA TECNICA
                if (array_key_exists(10, $_SESSION["modulos"])){
                    $consultaEstudianteActualMT = MediaTecnicaServicios::existeEstudianteMT($config,$inicio,$_POST["id"]);
                    while($datosEstudianteActualMT = mysqli_fetch_array($consultaEstudianteActualMT, MYSQLI_BOTH)){
                        if(!empty($datosEstudianteActualMT)){

                //SELECCION LAS CARGAS DEL ESTUDIANTE, MATERIAS, AREAS
                $cargasAcademicas = mysqli_query($conexion, "SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area FROM ".BD_ACADEMICA.".academico_cargas car 

                                            INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}

                                            INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$inicio}

                                            WHERE car_curso='" . $datosEstudianteActualMT["matcur_id_curso"] . "' AND car_grupo='" . $datosEstudianteActualMT["matcur_id_grupo"] . "' AND car.institucion={$config['conf_id_institucion']} AND car.year={$inicio}");

                while ($cargas = mysqli_fetch_array($cargasAcademicas, MYSQLI_BOTH)) {

                    //OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES

                    $consultaBoletin = mysqli_query($conexion, "SELECT avg(bol_nota) FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' AND bol_carga='" . $cargas["car_id"] . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
                    $boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);

                $nota = 0;
                if(!empty($boletin[0])){
                    $nota = round($boletin[0],1);
                }

                    $consultaDesempeno = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_categoria='" . $config[22] . "' AND " . $nota . ">=notip_desde AND " . $nota . "<=notip_hasta AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
                    $desempeno = mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH);

                ?>

                    <tr style="text-align:center;">

                        <td style="text-align:left;"><?= strtoupper($cargas["mat_nombre"]); ?></td>

                        <td><?= $cargas["car_ih"]; ?></td>

                        <?php

                        $p = 1;

                        //PERIODOS

                        while ($p <= $config[19]) {

                            $consultaNotasPeriodos = mysqli_query($conexion, "SELECT bol_nota FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' AND bol_carga='" . $cargas["car_id"] . "' AND bol_periodo='" . $p . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
                            $notasPeriodo = mysqli_fetch_array($consultaNotasPeriodos, MYSQLI_BOTH);

                            $notasPeriodoFinal='';
                            if(!empty($notasPeriodo[0])){
                                $notasPeriodoFinal=$notasPeriodo[0];
                                if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                                    $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notasPeriodo[0],$inicio);
                                    $notasPeriodoFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
                                }
                            }

                            echo '<td>' . $notasPeriodoFinal . '</td>';

                            $p++;
                        }

                        ?>

                        <td><?= $nota; ?></td>

                        <td><?= $desempeno['notip_nombre']; ?></td>

                    </tr>

                <?php

                }}}}

                ?>



            </table>
            <?php
            $msj='';
            if ($materiasPerdidas == 0)
                $msj = "<center>EL (LA) ESTUDIANTE " . $nombre . " FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";
            else
                $msj = "<center>EL (LA) ESTUDIANTE " . $nombre . " NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";
            ?>
            <div align="left" style="font-weight:bold; font-style:italic; font-size:12px; margin-bottom:20px;"><?= $msj; ?></div>



        <?php } ?>







    <?php

        $inicio++;

        $i++;
    }

    ?>





    <p>&nbsp;</p>

    PLAN DE ESTUDIOS: Ley 115 de Educación, artículo 23, Decreto 1860 de 1994. Decreto 1290 de 2009 y Decreto 3055 del 12 de diciembre de 2002. Intensidad horaria <?= $horasT; ?> horas semanales de 55 minutos.<br>

    Se expide el presente certificado en Medellín el <?= date("d"); ?> de <?= date("M"); ?> de <?= date("Y"); ?>.





    <p>&nbsp;</p>

    <table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">

        <tr>

            <td align="center">_________________________________<br><!--<?= strtoupper(""); ?><br>-->Rector(a)</td>

            <td align="center">_________________________________<br><!--<?= strtoupper(""); ?><br>-->Director(a) de grupo</td>

        </tr>

    </table>






    <?php include("../compartido/footer-informes.php") ?>



</body>

</html>