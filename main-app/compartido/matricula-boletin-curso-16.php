<?php
include("session-compartida.php");
$idPaginaInterna = 'DT0224';

if ($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && !Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
    exit();
}
include(ROOT_PATH . "/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH . "/main-app/class/Estudiantes.php");
require_once(ROOT_PATH . "/main-app/class/Boletin.php");
require_once(ROOT_PATH . "/main-app/class/CargaAcademica.php");
require_once(ROOT_PATH . "/main-app/class/Ausencias.php");
require_once(ROOT_PATH . "/main-app/class/Utilidades.php");

$year = $_SESSION["bd"];
if (isset($_GET["year"])) {
    $year = base64_decode($_GET["year"]);
}



if (!empty($_GET["curso"])) {
    $grado = base64_decode($_GET["curso"]);
}

if (!empty($_GET["grupo"])) {
    $grupo = base64_decode($_GET["grupo"]);
}
$periodos = [];
if (empty($_GET["periodo"])) {
    $periodoSeleccionado = 1;
    $periodo = "1";
} else {
    $periodo = base64_decode($_GET["periodo"]);
    $periodoSeleccionado = base64_decode($_GET["periodo"]);
}
for ($i = 1; $i <= $periodoSeleccionado; $i++) {
    $periodos[$i] = $i;
}

if (!empty($_GET["year"])) {
    $year = base64_decode($_GET["year"]);
}
$idEstudiante = '';
if (!empty($_GET["id"])) {

    $filtro = " AND mat_id='" . base64_decode($_GET["id"]) . "'";
    $matriculadosPorCurso = Estudiantes::estudiantesMatriculados($filtro, $year);
    $estudiante = $matriculadosPorCurso->fetch_assoc();
    if (!empty($estudiante)) {
        $idEstudiante = $estudiante["mat_id"];
        $grado        = $estudiante["mat_grado"];
        $grupo        = $estudiante["mat_grupo"];
    } else {
        echo "Excepción catpurada: Estudiante no encontrado ";
        exit();
    }
}


$tamañoLogo = $_SESSION['idInstitucion'] == ICOLVEN ? 100 : 50;


if ($periodoSeleccionado == 1) $periodoActuales = "Primero";

if ($periodoSeleccionado == 2) $periodoActuales = "Segundo";

if ($periodoSeleccionado == 3) $periodoActuales = "Tercero";

if ($periodoSeleccionado == 4) $periodoActuales = "Final";

?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>



<!doctype html>

<html class="no-js" lang="en">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>

<head>

    <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">

    <title>Boletín</title>

    <style>
        #saltoPagina {

            PAGE-BREAK-AFTER: always;

        }
    </style>

</head>



<body style="font-family:Arial; font-size:9px;">


    <?php
    $listaDatos = [];
    $tiposNotas = [];
    $cosnultaTiposNotas = Boletin::listarTipoDeNotas($config["conf_notas_categoria"], $year);
    while ($row = $cosnultaTiposNotas->fetch_assoc()) {
        $tiposNotas[] = $row;
    }
    if (!empty($grado) && !empty($grupo) && !empty($periodos) && !empty($year)) {
        $datos = Boletin::datosBoletinPeriodos($grado,  $grupo, $periodos, $year, $idEstudiante);
        while ($row = $datos->fetch_assoc()) {
            $listaDatos[] = $row;
        }
    } ?>


    <!-- <table width="100%" cellspacing="0" cellpadding="0" rules="all" border="0" align="center"> -->

        <?php
        $conteoEstudiante = 0;
        $ultimoRegistro = false;
        $contarCargas = 0;
        $mat_id = "";
        $mat_car = "";
        $mat_car_periodo = "";
        $directorGrupo = "";
        $promedioMateria = 0;
        $totalIh = 0;
        $totalFallasPeriodo[1] = 0;
        $totalNotasPeriodo[1] = 0;
        $promedioMateria = 0;
        $observacionesConvivencia = [];
        $materiasPerdidas = 0;
        $length = count($listaDatos);
        foreach ($listaDatos  as $index => $registro) {
            if ($index < $length - 1) {
                $siguienteRegistro = $listaDatos[$index + 1];
            } else {
                $siguienteRegistro["bol_periodo"] = "";
                $siguienteRegistro["mat_id"] = "";
                $ultimoRegistro = true;
            }

            if (!empty($registro["dn_id"]) && !empty($registro["dn_observacion"])) {
                if (!array_key_exists($registro["mat_id"], $observacionesConvivencia)) {
                    $observacionesConvivencia[$registro["mat_id"]] = [
                        "id" => $registro["dn_id"],
                        "estudiante" => $registro["dn_cod_estudiante"],
                        "observacion" => $registro["dn_observacion"],
                        "periodo" => $registro["dn_periodo"]
                    ];
                }
            }
            if ($mat_id != $registro["mat_id"]) {
                $contarCargas = 0;
                $conteoEstudiante++;
                $nombre = Estudiantes::NombreCompletoDelEstudiante($registro);
        ?>

                <!-- <tr>
                    <td> -->
                        <div>
                            <div style="float:left; width:50%"><img src="../files/images/logo/<?= $informacion_inst["info_logo"] ?>" width="80"></div>
                            <div style="float:right; width:50%">
                                <table width="100%" cellspacing="5" cellpadding="5" border="1" rules="all">
                                    <tr>
                                        <td>C&oacute;digo:<br> <?= strpos($registro["mat_documento"], '.') !== true && is_numeric($registro["mat_documento"]) ? number_format($registro["mat_documento"], 0, ",", ".") : $registro["mat_documento"]; ?></td>
                                        <td>Nombre:<br> <?= $nombre ?></td>
                                        <td>Grado:<br> <?= $registro["gra_nombre"] . " " . $registro["gru_nombre"]; ?></td>
                                        <td>Puesto Curso:<br> <?= "1 de 2"; ?></td>
                                    </tr>

                                    <tr>
                                        <td>Jornada:<br> Mañana</td>
                                        <td>Sede:<br> <?= $informacion_inst["info_nombre"] ?></td>
                                        <td>Periodo:<br> <b><?= $periodoSeleccionado . " (" . $year . ")"; ?></b></td>
                                        <td>Fecha Impresión:<br> <?= date("d/m/Y H:i:s"); ?></td>
                                    </tr>
                                </table>
                                <p>&nbsp;</p>
                            </div>
                        </div>
                    <!-- </td>
                </tr> -->
                <!-- <tr>
                    <td> -->
                        <table width="100%" cellspacing="5" cellpadding="5" rules="all" border="1">
                            <thead>
                                <tr style="font-weight:bold; text-align:center;">
                                    <td width="20%" rowspan="2">ASIGNATURAS</td>
                                    <td width="2%" rowspan="2">I.H.</td>

                                    <?php for ($j = 1; $j <= $periodoSeleccionado; $j++) { ?>
                                        <td width="3%" colspan="3"> Periodo<?= $j ?></td>
                                    <?php } ?>
                                    <td width="3%" colspan="3">Final</td>
                                </tr>
                                <tr style="font-weight:bold; text-align:center;">
                                    <?php for ($j = 1; $j <= $periodoSeleccionado; $j++) { ?>
                                        <td width="3%">Fallas</td>
                                        <td width="3%">Nota</td>
                                        <td width="3%">Nivel</td>
                                    <?php } ?>
                                    <td width="3%">Nota</td>
                                    <td width="3%">Nivel</td>
                                    <td width="3%">Hab</td>
                                </tr>
                            </thead>
                        <?php $mat_id = $registro["mat_id"];
                    } ?>
                        <?php if ($mat_car != $registro["mat_id"] . '-' . $registro["car_id"]) {
                            $contarCargas++;
                            $fondoFila = ($contarCargas % 2 == 1) ? '#EAEAEA' : '#FFF';
                            $directorGrupo = ($registro["car_director_grupo"] == 1) ? $registro : null;
                            $totalIh += $registro['car_ih'];
                        ?>
                            <tbody>
                                <tr style="background:<?= $fondoFila; ?>">
                                    <td><?= $registro['mat_nombre']; ?></td>
                                    <td align="center"><?= $registro['car_ih']; ?></td>
                                <?php $mat_car = $registro["mat_id"] . '-' . $registro["car_id"];
                            } ?>
                                <?php if ($mat_car_periodo != $registro["mat_id"] . '-' . $registro["car_id"] . '-' . $registro["bol_periodo"]) {
                                    $nota = $registro["bol_nota"];
                                    if (isset($totalFallasPeriodo[$registro["bol_periodo"]])) {
                                        $totalFallasPeriodo[$registro["bol_periodo"]] += $registro['aus_ausencias'];
                                    } else {
                                        $totalFallasPeriodo[$registro["bol_periodo"]] = $registro['aus_ausencias'];
                                    }

                                    $promedioMateria += $nota;

                                    if (isset($totalNotasPeriodo[$registro["bol_periodo"]])) {
                                        $totalNotasPeriodo[$registro["bol_periodo"]] += $nota;
                                    } else {
                                        $totalNotasPeriodo[$registro["bol_periodo"]] = $nota;
                                    }
                                    $desempeno = Boletin::determinarRango($nota, $tiposNotas);
                                ?>
                                    <td align="center"><?= $registro['aus_ausencias']; ?></td>
                                    <td align="center"><?= $registro['bol_nota']; ?></td>
                                    <td align="center"><?= $desempeno["notip_nombre"] ?></td>
                                <?php $mat_car_periodo = $registro["mat_id"] . '-' . $registro["car_id"] . '-' . $registro["bol_periodo"];
                                } ?>
                                <?php if ($siguienteRegistro["bol_periodo"] <= $registro["bol_periodo"]) {
                                    $promedioFinal = round($promedioMateria / $periodoSeleccionado, 2);
                                    $desempenoFinal = Boletin::determinarRango($promedioFinal, $tiposNotas); ?>
                                    <td align="center"><?= $promedioFinal; ?></td>
                                    <td align="center"><?= $desempenoFinal["notip_nombre"]; ?></td>
                                    <td align="center">&nbsp;</td>
                                </tr>
                            </tbody>
                        <?php $promedioMateria = 0;
                                } else {
                                    continue;
                                } ?>
                        <?php if ($mat_id != $siguienteRegistro["mat_id"] ||  $ultimoRegistro == 1) { ?>
                            <tfoot>
                                <tr style="font-weight:bold; text-align:center;">
                                    <td style="text-align:left;">PROMEDIO/TOTAL</td>
                                    <td><?= $totalIh; ?></td>
                                    <?php
                                    $promedioTotal = 0;
                                    for ($j = 1; $j <= $periodoSeleccionado; $j++) {
                                        $promedioGeneral = round($totalNotasPeriodo[$j] / $contarCargas, 2);
                                        $promedioTotal += $promedioGeneral;
                                        $desempenoGeneral = Boletin::determinarRango($promedioGeneral, $tiposNotas);
                                    ?>
                                        <td><?= $totalFallasPeriodo[$j]; ?></td>
                                        <td><?= $promedioGeneral ?></td>
                                        <td><?= $desempenoGeneral["notip_nombre"] ?></td>
                                    <?php } ?>
                                    <?php
                                    $promedioTotal = round($promedioTotal / $periodoSeleccionado, 2);
                                    $desempenoTotal = Boletin::determinarRango($promedioTotal, $tiposNotas); ?>
                                    <td><?= $promedioTotal ?></td>
                                    <td><?= $desempenoTotal["notip_nombre"] ?></td>
                                    <td>-</td>
                                </tr>
                            </tfoot>
                        </table>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <table width="100%" cellspacing="5" cellpadding="5" rules="none" border="0">
                            <tr>
                                <td width="40%">
                                    ________________________________________________________________<br>
                                    <?php if (!empty($directorGrupo['uss_nombre'])) echo strtoupper($directorGrupo['uss_nombre']); ?><br>
                                    DIRECTOR DE CURSO
                                </td>
                                <td width="20%">
                                    <table width="100%" cellspacing="5" cellpadding="5" rules="all" border="1">
                                        <?php
                                        $contador = 1;
                                        foreach ($tiposNotas as $desemp) {
                                            if ($contador % 2 == 1) {
                                                $fondoFila = '#EAEAEA';
                                            } else {
                                                $fondoFila = '#FFF';
                                            } ?>
                                            <tr style="background:<?= $fondoFila; ?>">
                                                <td><?= $desemp['notip_nombre']; ?></td>
                                                <td align="center"><?= $desemp['notip_desde'] . " - " . $desemp['notip_hasta']; ?></td>
                                            </tr>
                                        <?php $contador++;
                                        } ?>
                                    </table>
                                </td>

                                <?php
                                $msjPromocion = '';
                                if ($periodoSeleccionado == $config['conf_periodos_maximos']) {
                                    if ($materiasPerdidas == 0) {
                                        $msjPromocion = 'PROMOVIDO';
                                    } else {
                                        $msjPromocion = 'NO PROMOVIDO';
                                    }
                                }

                                ?>
                                <td width="60%">
                                    <p style="font-weight:bold;">Observaciones: <b><?= $msjPromocion; ?></b></p>
                                    ______________________________________________________________________<br><br>
                                    ______________________________________________________________________<br><br>
                                    ______________________________________________________________________
                                </td>
                            </tr>
                        </table>
                        <div id="saltoPagina"></div>
                        <table width="100%" cellspacing="5" cellpadding="5" rules="all" border="1">
                            <thead>
                                <tr style="font-weight:bold; text-align:center;">
                                    <td width="30%">Asignaturas</td>
                                    <td width="70%">Contenidos Evaluados</td>
                                </tr>
                            </thead>

                            <?php
                            $conCargas = CargaAcademica::traerIndicadoresCargasPorCursoGrupo($config, $registro['mat_grado'], $registro['mat_grupo'], $periodoSeleccionado, $year);
                            while ($datosCargas = mysqli_fetch_array($conCargas, MYSQLI_BOTH)) {
                            ?>
                                <tbody>
                                    <tr style="color:#585858;">
                                        <td><?= $datosCargas['mat_nombre']; ?><br>
                                            <span style="color:#C1C1C1;"><?= UsuariosPadre::nombreCompletoDelUsuario($datosCargas); ?></span>
                                        </td>
                                        <td><?= $datosCargas['ind_nombre']; ?></td>
                                    </tr>
                                </tbody>
                            <?php
                            }
                            ?>
                        </table>

                        <div id="saltoPagina"></div>
                    <?php
                            $totalIh = 0;
                            $totalFallasPeriodo = [];
                            $totalNotasPeriodo = [];
                        } ?>
                    <!-- </td>
                </tr> -->






            <?php } ?>


    <!-- </table> -->




</body>

<script type="application/javascript">
    print();
</script>

</html>