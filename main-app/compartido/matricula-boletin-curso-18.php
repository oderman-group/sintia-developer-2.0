<?php
include("session-compartida.php");
$idPaginaInterna = 'DT0224';

if ($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && !Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
    exit();
}
include(ROOT_PATH . "/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH . "/main-app/class/Boletin.php");
require_once(ROOT_PATH . "/main-app/class/Estudiantes.php");
require_once(ROOT_PATH . "/main-app/class/Usuarios.php");
require_once(ROOT_PATH . "/main-app/class/UsuariosPadre.php");
require_once(ROOT_PATH . "/main-app/class/Boletin.php");
require_once(ROOT_PATH . "/main-app/class/Indicadores.php");
require_once(ROOT_PATH . "/main-app/class/Utilidades.php");
require_once(ROOT_PATH . "/main-app/class/CargaAcademica.php");

$year = $_SESSION["bd"];
if (isset($_GET["year"])) {
    $year = base64_decode($_GET["year"]);
}

if (empty($_GET["periodo"])) {

    $periodoSeleccionado = 1;
} else {

    $periodoSeleccionado = base64_decode($_GET["periodo"]);
}

if (!empty($_GET["curso"])) {
    $grado = base64_decode($_GET["curso"]);
}

$grupo = 1;
if (!empty($_GET["grupo"])) {
    $grupo = base64_decode($_GET["grupo"]);
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

switch ($periodoSeleccionado) {
    case 1:
        $periodoActuales = "Primero";
        $celdas = 2;
        break;
    case 2:
        $periodoActuales = "Segundo";
        $celdas = 4;
        break;
    case 3:
        $periodoActuales = "Tercero";
        $celdas = 6;
        break;
    case 4:
        $periodoActuales = "Final";
        $celdas = 8;
        break;
}

$periodos = [];
for ($i = 1; $i <= $periodoSeleccionado; $i++) {
    $periodos[$i] = $i;
}
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
    <title>Boletín</title>
    <style>
        #saltoPagina {
            PAGE-BREAK-AFTER: always;
        }
    </style>
</head>
<?php
// Cosnultas iniciales
$listaDatos = [];
$tiposNotas = [];
$cosnultaTiposNotas = Boletin::listarTipoDeNotas($config["conf_notas_categoria"], $year);
while ($row = $cosnultaTiposNotas->fetch_assoc()) {
    $tiposNotas[] = $row;
}

if (!empty($grado) && !empty($grupo) && !empty($periodoSeleccionado) && !empty($year)) {
    $datos = Boletin::datosBoletin($grado, $grupo, $periodos, $year, $idEstudiante);
    while ($row = $datos->fetch_assoc()) {
        $listaDatos[] = $row;
    }
}
$rector = Usuarios::obtenerDatosUsuario($informacion_inst["info_rector"]);

$puestos = Boletin::obtenerPuestoYpromedioEstudiante($periodoSeleccionado, $grado, $grupo, $year);
$puestoCurso = [];
while ($puesto = mysqli_fetch_array($puestos, MYSQLI_BOTH)) {
    $puestoCurso[$puesto['bol_estudiante']] = $puesto['puesto'];
}

$listaCargas = [];
$conCargasDos = CargaAcademica::traerCargasMateriasPorCursoGrupo($config, $grado, $grupo, $year);
while ($row = $conCargasDos->fetch_assoc()) {

    $indicadores = Indicadores::traerCargaIndicadorPorPeriodo($conexion, $config, $row['car_id'], $periodoSeleccionado, $year);
    $listaIndicadores = [];
    while ($row2 = $indicadores->fetch_assoc()) {
        $listaIndicadores[] = $row2;
    }
    $row['indicadores'] = $listaIndicadores;
    $listaCargas[] = $row;
}
$colspan = 5 + $celdas;
?>

<?php include("../compartido/agrupar-datos-boletin-periodos_mejorado.php") ?>

<body style="font-family:Arial;">
    <?php foreach ($estudiantes  as  $estudiante) {
        $totalNotasPeriodo = [];
        $fallasPeriodo     = [];
        $materiasPerdidas  =0;
    ?>
        <div align="center" style="margin-bottom:20px;">
            <img src="../files/images/logo/<?= $informacion_inst["info_logo"] ?>" height="50"><br>
            <?= $informacion_inst["info_nombre"] ?><br>BOLETÍN DE CALIFICACIONES<br>
        </div>
        <table width="100%" cellspacing="5" cellpadding="5" border="0" rules="none">
            <tr>
                <td>Documento:<br> <?= strpos($estudiante["mat_documento"], '.') !== true && is_numeric($estudiante["mat_documento"]) ? number_format($estudiante["mat_documento"], 0, ",", ".") : $estudiante["mat_documento"]; ?></td>
                <td>Nombre:<br> <?= $estudiante["nombre"]; ?></td>
                <td>Grado:<br> <?= $estudiante["gra_nombre"] . " " . $estudiante["gru_nombre"]; ?></td>
                <td>Puesto Curso:<br> <?= isset($puestoCurso[$estudiante["mat_id"]]) ? $puestoCurso[$estudiante["mat_id"]] : 'N/A' ?></td>
            </tr>
            <tr>
                <td>Jornada:<br> Mañana</td>
                <td>Sede:<br> <?= $informacion_inst["info_nombre"] ?></td>
                <td>Periodo (Año):<br> <b><?= $periodoActuales . " (" . $year . ")"; ?></b></td>
                <td>Fecha Impresión:<br> <?= date("d/m/Y H:i:s"); ?></td>
            </tr>
        </table>
        <br>
        <table width="100%" style="margin-bottom: 15px;" cellspacing="0" cellpadding="0" rules="all" border="1" align="left">
            <tr style="font-weight:bold; background-color:#00adefad; border-color:#000; color:#000; font-size:12px;">
                <td width="1%" align="center" rowspan="2">Nº</td>
                <td width="20%" align="center" rowspan="2">AREAS/ ASIGNATURAS</td>
                <td width="2%" align="center" rowspan="2">I.H</td>
                <?php for ($j = 1; $j <= $periodoSeleccionado; $j++) { ?>
                    <td width="2%" align="center" colspan="2">Periodo <?= $j ?></td>
                <?php } ?>
                <td width="3%" colspan="3" align="center">Acumulado</td>
            </tr>
            <tr style="font-weight:bold; text-align:center; background-color:#00adefad; border-color:#000; color:#000; font-size:12px;">
                <?php for ($j = 1; $j <= $periodoSeleccionado; $j++) { ?>
                    <td width="1%">Nota</td>
                    <td width="1%">Desempeño</td>
                <?php } ?>
                <td width="1%">Nota</td>
                <td width="1%">Desempeño</td>
            </tr>
            <?php
            $cantidadMaterias = 0;
            foreach ($areas[$estudiante["mat_id"]]  as  $area) {  ?>
                <tr style="background-color: #EAEAEA" style="font-size:12px;">
                    <td colspan="<?= $colspan ?>" style="font-size:12px; font-weight:bold;"><?= $area["ar_nombre"]; ?></td>
                </tr>
                <?php
                foreach ($cargas[$estudiante["mat_id"]][$area["ar_id"]]  as  $carga) {
                    $cantidadMaterias++; ?>
                    <tr>
                        <td align="center"><?= $carga["nro"]; ?></td>
                        <td style="font-size:12px; font-weight:bold;"><?= $carga["mat_nombre"]; ?></td>
                        <td align="center" style="font-size:12px;"><?= $carga["car_ih"]; ?></td>
                        <?php
                        $promedioMateria = 0;
                        for ($j = 1; $j <= $periodoSeleccionado; $j++) {
                            $nota = isset($notasPeriodos[$estudiante["mat_id"]][$area["ar_id"]][$carga["car_id"]][$j]["bol_nota"])
                                ? $notasPeriodos[$estudiante["mat_id"]][$area["ar_id"]][$carga["car_id"]][$j]["bol_nota"]
                                : 0;
                            $fallas = isset($notasPeriodos[$estudiante["mat_id"]][$area["ar_id"]][$carga["car_id"]][$j]["aus_ausencias"])
                                ? $notasPeriodos[$estudiante["mat_id"]][$area["ar_id"]][$carga["car_id"]][$j]["aus_ausencias"]
                                : 0;
                            $desempeno = Boletin::determinarRango($nota, $tiposNotas);
                            $promedioMateria += $nota;

                            if (isset($totalNotasPeriodo[$j])) {
                                $totalNotasPeriodo[$j] += $nota;
                            } else {
                                $totalNotasPeriodo[$j] = $nota;
                            }
                            if (isset($fallasPeriodo[$j])) {
                                $fallasPeriodo[$j] += $fallas;
                            } else {
                                $fallasPeriodo[$j] = $fallas;
                            }

                        ?>
                            <td align="center" style=" font-size:12px;"><?= Boletin::agregarDecimales($nota);  ?></td>
                            <td align="center" style=" font-size:12px;"><?= $desempeno["notip_nombre"] ?></td>
                        <?php }
                        $notaAcumulada = $promedioMateria / $periodoSeleccionado;
                        $notaAcumulada = round($notaAcumulada, 2);
                        $desempenoAcumulado = Boletin::determinarRango($notaAcumulada, $tiposNotas);
                        if ($notaAcumulada  < $config['conf_nota_minima_aprobar']) {
                            $materiasPerdidas++;
                        }
                        ?>
                        <td align="center" style=" font-size:12px;"><?= $notaAcumulada ?></td>
                        <td align="center" style=" font-size:12px;"><?= $desempenoAcumulado["notip_nombre"] ?></td>
                    </tr>
                    <?php if (!empty($notasPeriodos[$estudiante["mat_id"]][$area["ar_id"]][$carga["car_id"]][$periodoSeleccionado]["bol_observaciones_boletin"])) { ?>
                        <tr>
                            <td colspan="<?= $colspan ?>">
                                <h5 align="center" style="margin: 0">Observaciones</h5>
                                <p style="margin: 0 0 0 10px; font-size: 11px; font-style: italic;">
                                    <?= $notasPeriodos[$estudiante["mat_id"]][$area["ar_id"]][$carga["car_id"]][$periodoSeleccionado]["bol_observaciones_boletin"] ?>
                                </p>
                            </td>
                        </tr>

                    <?php } ?>

                <?php } ?>
            <?php } ?>
            <tr bgcolor="#EAEAEA" style="font-size:12px; text-align:center;">
                <td colspan="3" style="text-align:left;  font-size:12px;">PROMEDIO GENERAL</td>

                <?php
                $promedioFinal = 0;
                for ($j = 1; $j <= $periodoSeleccionado; $j++) {
                    $avumuladoPj = ($totalNotasPeriodo[$j] / $cantidadMaterias);
                    $avumuladoPj = round($avumuladoPj, 2);
                    $promedioFinal += $avumuladoPj;
                    $desempenoAcumuladoTotal = Boletin::determinarRango($avumuladoPj, $tiposNotas);
                ?>

                    <td style=" font-size:12px;"><?= $avumuladoPj ?></td>
                    <td style=" font-size:12px;"><?= $desempenoAcumuladoTotal["notip_nombre"] ?></td>
                <?php
                }
                $promedioFinal = ($promedioFinal / $periodoSeleccionado);
                $promedioFinal = round($promedioFinal, 2);
                $desempenoTotal = Boletin::determinarRango($promedioFinal, $tiposNotas);
                ?>
                <td style=" font-size:12px;"><?= $promedioFinal ?></td>
                <td style=" font-size:12px;"><?= $desempenoTotal["notip_nombre"] ?></td>
            </tr>
            <tr bgcolor="#EAEAEA" style="font-size:12px; text-align:center;">
                <td colspan="3" style="text-align:left;  font-size:12px;">AUSENCIAS</td>

                <?php
                $fallasFinal = 0;
                for ($j = 1; $j <= $periodoSeleccionado; $j++) {
                    $fallasAcumuladas = $fallasPeriodo[$j] ;
                    $fallasFinal+=$fallasAcumuladas;
                ?>

                    <td colspan="2" style=" font-size:12px;"><?= $fallasAcumuladas ?> Aús.</td>
                <?php
                }
                ?>
                <td colspan="2" style=" font-size:12px;"><?= $fallasAcumuladas ?> Aús.</td>
            </tr>
        </table>
        <td>&nbsp;</td>
        <table width="100%" cellspacing="0" cellpadding="0" rules="all" border="1" align="center">
            <tr>
                <td style=" font-size:12px;">Tabla de desempeño:</td>
                <?php
                $consulta = Boletin::listarTipoDeNotas($config["conf_notas_categoria"], $year);
                while ($estiloNota = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                ?>
                    <td align="center" style="font-size:12px;"><?= $estiloNota['notip_nombre'] . ": " . $estiloNota['notip_desde'] . " - " . $estiloNota['notip_hasta']; ?></td>
                <?php
                }
                ?>
            </tr>
        </table>
        <?php if (!empty($observacionesConvivencia[$estudiante["mat_id"]])) {
            usort($observacionesConvivencia[$estudiante["mat_id"]], function ($a, $b) {
                return $a['periodo'] - $b['periodo']; // Orden ascendente por 'periodo'
            });
        ?>
            <table width="100%" style="margin-top: 15px;" cellspacing="0" cellpadding="0" rules="all" border="1" align="center">
                <tr style=" background:#00adefad; border-color:#036; font-size:12px; text-align:center">
                    <td colspan="3">OBSERVACIONES DE CONVIVENCIA</td>
                </tr>
                <tr style=" background:#EAEAEA; color:#000; font-size:12px; text-align:center">
                    <td width="8%">Periodo</td>
                    <td>Observaciones</td>
                </tr>
                <?php
                foreach ($observacionesConvivencia[$estudiante["mat_id"]] as $observacion) {
                    if ($observacion["estudiante"] == $estudiante["mat_id"]) {
                ?>
                        <tr align="center" style="font-weight:bold; font-size:12px; height:20px;">
                            <td><?= $observacion["periodo"] ?></td>
                            <td align="left"><?= $observacion["observacion"] ?></td>
                        </tr>

                <?php  }
                } ?>
            </table>
        <?php } ?>
        <?php 
        echo Boletin::mensajeFinalEstudainte($periodoActual,$materiasPerdidas,$estudiante["nombre"],$estudiante["genero"]);
        include("../compartido/firmas-informes.php");?>
        <p>&nbsp;</p>
        <table width="100%" cellspacing="5" cellpadding="5" rules="all" border="1" align="center" style="font-size:10px;">
            <thead>
                <tr style="font-weight:bold; text-align:center; background-color: #00adefad;">
                    <td width="30%">Asignatura</td>
                    <td width="70%">Indicadores de desempeño</td>
                </tr>
            </thead>

            <?php
            foreach ($listaCargas as $carga) { ?>
                <tbody>
                    <tr style="color:#000;">
                        <td><?= $carga['mat_nombre']; ?><br><span style="color:#C1C1C1;"><?= UsuariosPadre::nombreCompletoDelUsuario($carga); ?></span></td>
                        <td>
                            <?php
                            //INDICADORES
                            foreach ($carga["indicadores"] as $indicador) { ?>
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
        <?php 
        
        include("../compartido/footer-informes.php");      
        ?>
        <div id="saltoPagina"></div>
    <?php  }  ?>
</body>

<script type="application/javascript">
    print();
</script>

</html>