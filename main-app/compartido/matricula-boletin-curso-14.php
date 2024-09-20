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
require_once(ROOT_PATH . "/main-app/class/CargaAcademica.php");

$year = $_SESSION["bd"];
if (isset($_GET["year"])) {
    $year = base64_decode($_GET["year"]);
}

if (empty($_GET["periodo"])) {

    $periodoActual = 1;
} else {

    $periodoActual = base64_decode($_GET["periodo"]);
}

if (!empty($_GET["curso"])) {
    $grado = base64_decode($_GET["curso"]);
}

if (!empty($_GET["grupo"])) {
    $grupo = base64_decode($_GET["grupo"]);
}

if (!empty($_GET["periodo"])) {
    $periodo = base64_decode($_GET["periodo"]);
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


if ($periodoActual == 1) $periodoActuales = "Primero";

if ($periodoActual == 2) $periodoActuales = "Segundo";

if ($periodoActual == 3) $periodoActuales = "Tercero";

if ($periodoActual == 4) $periodoActuales = "Final";

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



<body style="font-family:Arial;">


    <?php
    $listaDatos = [];
    $tiposNotas = [];
    $cosnultaTiposNotas = Boletin::listarTipoDeNotas($config["conf_notas_categoria"], $year);
    while ($row = $cosnultaTiposNotas->fetch_assoc()) {
        $tiposNotas[] = $row;
    }
    if (!empty($grado) && !empty($grupo) && !empty($periodo) && !empty($year)) {
        $datos = Boletin::datosBoletin($grado, $grupo, $periodo, $year, $idEstudiante);
        while ($row = $datos->fetch_assoc()) {
            $listaDatos[] = $row;
        }
    } ?>


    <table width="100%" cellspacing="0" cellpadding="0" rules="all" border="0" align="center">

        <?php
        $conteoEstudiante = 0;
        $contarIndicadores = 0;
        $ultimoRegistro = false;
        $contarCargas = 0;
        $contarAreas = 0;
        $mat_id = "";
        $mat_area = "";
        $mat_area_car = "";
        $mat_area_car_ind = "";
        $directorGrupo = "";
        $observacionesConvivencia = [];
        $indicadores = [];
        $length = count($listaDatos);
        foreach ($listaDatos  as $index => $registro) {
            if ($index < $length - 1) {
                $siguienteRegistro = $listaDatos[$index + 1];
            } else {
                $ultimoRegistro = true;
            }

            if (!empty($registro["dn_id"])) {
                if (!array_key_exists($registro["dn_id"], $observacionesConvivencia)) {
                    $observacionesConvivencia[$registro["dn_id"]] = [
                        "observacion" => $registro["dn_observacion"],
                        "periodo" => $registro["dn_periodo"]
                    ];
                }
            }
            if ($mat_id != $registro["mat_id"]) {
                $contarAreas = 0;
                $contarCargas = 0;
                $conteoEstudiante++;
                $nombre = Estudiantes::NombreCompletoDelEstudiante($registro);?>
                <tr>
                    <td>
                        <div align="center" style="margin-bottom:20px;">
                            <img src="../files/images/logo/<?= $informacion_inst["info_logo"] ?>" width="<?= $tamañoLogo ?>%"><br>
                        </div>
                    </td>
                </tr>

                <table width="100%" cellspacing="5" cellpadding="5" border="0" rules="none">
                    <tr>

                        <td>Documento:<br> <?= strpos($registro["mat_documento"], '.') !== true && is_numeric($registro["mat_documento"]) ? number_format($registro["mat_documento"], 0, ",", ".") : $registro["mat_documento"]; ?></td>

                        <td>Nombre:<br> <?= $nombre ?></td>

                        <td>Grado:<br> <?= $registro["gra_nombre"] . " " . $registro["gru_nombre"]; ?></td>

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
                <table width="100%" cellspacing="0" cellpadding="0" rules="all" border="1" align="left">
                    <tr style="font-weight:bold; background-color:#2e537dab; border-color:#000; height:40px; color:#000; font-size:12px;">
                        <td width="2%"  align="center"> NO</td>
                        <td width="20%" align="center"> AREAS/ ASIGNATURAS</td>
                        <td width="2%"  align="center"> I.H</td>
                        <td width="2%"  align="center"> NOTA</td>
                    </tr>
                   <?php $mat_id = $registro["mat_id"]; }?>
                   <?php if ($mat_area != $registro["mat_id"] . '-' . $registro["ar_id"]) {
                             $contarAreas++;?>
                    <tr style="background-color: #b9b91730" style="font-size:12px;">
                        <td colspan="4" style="font-size:12px; height:25px; font-weight:bold;"><?php echo  $registro["ar_nombre"]; ?></td>
                    </tr>
                    <?php $mat_area = $registro["mat_id"] . '-' . $registro["ar_id"]; } ?>
                    <?php if ( $mat_area_car != $registro["mat_id"] . '-' .  $registro["ar_id"] . '-' . $registro["car_id"]) {
                            $contarCargas++;
                            $contarIndicadores = 0;
                            if ($registro["car_director_grupo"] == 1) {
                                $directorGrupo = $registro;
                            } ?>
                    <tr bgcolor="#EAEAEA" style="font-size:12px;">
                        <td width="2%" align="center"><?= $contarCargas; ?></td>
                        <td width="20%" style="font-size:12px; height:35px; font-weight:bold;background:#EAEAEA;"><?php echo $registro["mat_nombre"]; ?></td>
                        <td width="2%" align="center" style="font-weight:bold; font-size:12px;background:#EAEAEA;"><?php echo $registro["car_ih"]; ?></td>
                        <td width="2%">&nbsp;</td>
                    </tr>
                    <?php $mat_area_car = $registro["mat_id"] . '-' . $registro["ar_id"] . '-' . $registro["car_id"]; } ?>
                    <?php if ($mat_area_car_ind != $registro["mat_id"] . '-' .  $registro["ar_id"] . '-' . $registro["car_id"] . '-' . $registro["ind_id"]) {
                            $contarIndicadores++;
                            $nota_indicador = $registro["valor_indicador"];
                            $desempeno = Boletin::determinarRango($nota_indicador, $tiposNotas);
                            $leyendaRI = "";
                            if ($config["conf_forma_mostrar_notas"] == CUANTITATIVA) {
                                $valorNota =  $nota_indicador;
                            } else {
                                $valorNota = $desempeno["notip_nombre"];
                            }
                            if ($nota_indicador == 1) $nota_indicador = "1.0";

                            if ($nota_indicador == 2) $nota_indicador = "2.0";

                            if ($nota_indicador == 3) $nota_indicador = "3.0";

                            if ($nota_indicador == 4) $nota_indicador = "4.0";

                            if ($nota_indicador == 5) $nota_indicador = "5.0";
                        ?>

                        <tr bgcolor="#FFF" style="font-size:12px;">
                            <td width="2%" align="center">&nbsp;</td>
                            <td width="20%" style="font-size:12px; height:15px;"><?php echo "    " . $contarIndicadores . "." . $registro["ind_nombre"]; ?></td>
                            <td width="2%">&nbsp;</td>
                            <td width="2%" align="center" style="font-weight:bold; font-size:12px;"><?= $valorNota . " " . $leyendaRI; ?></td>
                        </tr>                
                        <?php $mat_area_car_ind =  $registro["mat_id"] . '-' .  $registro["ar_id"] . '-' . $registro["car_id"] . '-' . $registro["ind_id"]; } ?>           
                        <?php if (!empty($registro['bol_observaciones_boletin']) && $mat_area_car != $siguienteRegistro["mat_id"] . '-' .  $siguienteRegistro["ar_id"] . '-' . $siguienteRegistro["car_id"] ||  $ultimoRegistro == 1) { ?>
                        <tr>
                            <td colspan="4">
                                <h5 align="center">Observaciones</h5>
                                <p style="margin-left: 5px; font-size: 11px; margin-top: -10px; margin-bottom: 5px; font-style: italic;">
                                    <?= $registro['bol_observaciones_boletin']; ?>
                                </p>
                            </td>
                        </tr>                       
                        <?php  } ?>
                        <?php if ($mat_id != $siguienteRegistro["mat_id"] ||  $ultimoRegistro == 1) {?>
                        </table>
                        <?php if (!empty($observacionesConvivencia)) { ?>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <table width="100%" cellspacing="0" cellpadding="0" rules="all" border="1" align="center">
                            <tr style="font-weight:bold; background:#2e537dab; border-color:#036; height:40px; font-size:12px; text-align:center">
                                <td colspan="3">OBSERVACIONES DE CONVIVENCIA</td>
                            </tr>
                            <tr style="font-weight:bold; background:#b9b91730; height:25px; color:#000; font-size:12px; text-align:center">
                                <td width="8%">Periodo</td>
                                <td>Observaciones</td>
                            </tr>

                            <?php
                            foreach ($observacionesConvivencia as $observacion) {
                            ?>

                                <tr align="center" style="font-weight:bold; font-size:12px; height:20px;">

                                    <td><?= $observacion["periodo"] ?></td>

                                    <td align="left"><?= $observacion["observacion"] ?></td>

                                </tr>

                            <?php  } ?>

                        </table>
                        <?php } ?>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">
                            <tr>
                                <td align="center" width="50%">
                                    <?php

                                    $nombreDirectorGrupo = UsuariosPadre::nombreCompletoDelUsuario($directorGrupo);
                                    if (!empty($directorGrupo["uss_firma"])) {
                                        echo '<img src="../files/fotos/' . $directorGrupo["uss_firma"] . '" width="15%"><br>';
                                    } else {
                                        echo '<p>&nbsp;</p>
                                            <p>&nbsp;</p>
                                            <p>&nbsp;</p>';
                                    }
                                    ?>
                                    _________________________________<br>
                                    <p>&nbsp;</p>
                                    <?= $nombreDirectorGrupo ?><br>
                                    Director(a) de grupo
                                </td>
                                <td align="center" width="50%">
                                    <?php
                                    $rector = Usuarios::obtenerDatosUsuario($informacion_inst["info_rector"]);
                                    $nombreRector = UsuariosPadre::nombreCompletoDelUsuario($rector);
                                    if (!empty($rector["uss_firma"])) {
                                        echo '<img src="../files/fotos/' . $rector["uss_firma"] . '" width="25%"><br>';
                                    } else {
                                        echo '<p>&nbsp;</p>
                                            <p>&nbsp;</p>
                                            <p>&nbsp;</p>';
                                    }
                                    ?>
                                    _________________________________<br>
                                    <p>&nbsp;</p>
                                    <?= $nombreRector ?><br>
                                    Rector(a)
                                </td>
                            </tr>
                        </table>
                        <div align="center" style="font-size:10px; margin-top:5px; margin-bottom: 10px;">

                            <img src="https://plataformasintia.com/images/logo.png" height="50"><br>

                            ESTE DOCUMENTO FUE GENERADO POR:<br>

                            SINTIA - SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL

                        </div>
                        <?php } ?>
        <?php } ?>


    </table>

    </div>


</body>

<script type="application/javascript">
    print();
</script>

</html>