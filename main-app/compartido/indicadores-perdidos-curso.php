<?php 
include("session-compartida.php");
$idPaginaInterna = 'DT0250';

if($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && !Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once("../class/Grados.php");
require_once("../class/Grupos.php");
require_once("../class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");
require_once(ROOT_PATH."/main-app/class/Indicadores.php");


if (empty($_REQUEST["periodo"])) {
    $periodoActual = 1;
} else {
    $periodoActual = base64_decode($_REQUEST["periodo"]);
}

if ($periodoActual == 1) { $periodoActuales = "Primero"; $condicion = "1"; $condicion2 = "1";}
if ($periodoActual == 2) { $periodoActuales = "Segundo"; $condicion = "1,2"; $condicion2 = "2";}
if ($periodoActual == 3) { $periodoActuales = "Tercero"; $condicion = "1,2,3"; $condicion2 = "3";}
if ($periodoActual == 4) { $periodoActuales = "Cuarto"; $condicion = "1,2,3,4"; $condicion2 = "4";}
?>
    <a href="indicadores-perdidos-curso.php?curso=<?php if(!empty($_REQUEST["curso"])) echo $_REQUEST["curso"];?>&periodo=<?=base64_encode(1)?>">Periodo 1</a>&nbsp;&nbsp;
    <a href="indicadores-perdidos-curso.php?curso=<?php if(!empty($_REQUEST["curso"])) echo $_REQUEST["curso"];?>&periodo=<?=base64_encode(2)?>">Periodo 2</a>&nbsp;&nbsp;
    <a href="indicadores-perdidos-curso.php?curso=<?php if(!empty($_REQUEST["curso"])) echo $_REQUEST["curso"];?>&periodo=<?=base64_encode(3)?>">Periodo 3</a>&nbsp;&nbsp;
    <a href="indicadores-perdidos-curso.php?curso=<?php if(!empty($_REQUEST["curso"])) echo $_REQUEST["curso"];?>&periodo=<?=base64_encode(4)?>">Periodo 4</a>&nbsp;&nbsp;
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<?php

$filtroAdicional = " AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
if (!empty($_REQUEST["id"])) {
    $filtroAdicional .= " AND mat_id='" . base64_decode($_REQUEST["id"]) . "'";
}
if (!empty($_REQUEST["curso"])) {
    $filtroAdicional .= " AND mat_grado='" . base64_decode($_REQUEST["curso"]) . "'";
}

$cursoActual=GradoServicios::consultarCurso(base64_decode($_REQUEST["curso"]));
$matriculadosPorCurso =Estudiantes::listarEstudiantes(0,$filtroAdicional,"",$cursoActual);
while ($matriculadosDatos = mysqli_fetch_array($matriculadosPorCurso, MYSQLI_BOTH)) {

    //contador materias
    $cont_periodos = 0;
    $contador_indicadores = 0;
    $materiasPerdidas = 0;

    $idCurso=$matriculadosDatos["mat_grado"];
    $idGrupo=$matriculadosDatos["mat_grupo"];
    if($cursoActual["gra_tipo"]==GRADO_INDIVIDUAL){
        $idCurso=$matriculadosDatos["matcur_id_curso"];
        $idGrupo=$matriculadosDatos["matcur_id_grupo"];
    }
    $contador_periodos = 0;
    ?>

    <!doctype html>
    <html class="no-js" lang="en">
    <head>
        <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
        <title>Indicadores perdidos</title>
        <style>
            #saltoPagina {
                PAGE-BREAK-AFTER: always;
            }
        </style>
    </head>
    <body style="font-family:Arial;">
        <?php
        //CONSULTA QUE ME TRAE LAS areas DEL ESTUDIANTE
        $consulta_mat_area_est = mysqli_query($conexion, "SELECT am.mat_id, am.mat_nombre, ar.ar_id, ar.ar_nombre, car.car_id, ind.ind_nombre, aic.ipc_periodo, ROUND(SUM(aac.cal_nota * (aa.act_valor / 100)) / SUM(aa.act_valor / 100), 2) AS nota, rind_nota, ind.ind_id
        FROM ".BD_ACADEMICA.".academico_cargas car
        INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id = car.car_materia AND am.institucion = car.institucion  AND am.year = car.year
        INNER JOIN ".BD_ACADEMICA.".academico_areas ar ON ar.ar_id = am.mat_area AND ar.institucion = car.institucion  AND ar.year = car.year
        INNER JOIN ".BD_ACADEMICA.".academico_boletin bol ON bol.bol_carga = car.car_id AND bol.institucion = car.institucion  AND bol.year = car.year
        INNER JOIN ".BD_ACADEMICA.".academico_indicadores_carga aic ON aic.ipc_carga = car.car_id AND aic.institucion = car.institucion  AND aic.year = car.year
        INNER JOIN ".BD_ACADEMICA.".academico_indicadores ind ON ind.ind_id = aic.ipc_indicador AND ind.institucion = car.institucion  AND ind.year = car.year
        INNER JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id_tipo = aic.ipc_indicador AND aa.act_id_carga = car.car_id AND aa.act_estado = 1 AND aa.act_registrada = 1 AND aa.institucion = car.institucion  AND aa.year = car.year
        INNER JOIN ".BD_ACADEMICA.".academico_calificaciones aac ON aac.cal_id_actividad = aa.act_id AND aac.institucion = car.institucion  AND aac.year = car.year
        LEFT JOIN ".BD_ACADEMICA.".academico_indicadores_recuperacion rec ON rind_estudiante='{$matriculadosDatos['mat_id']}' AND rind_carga=car.car_id AND rind_periodo=" . $condicion2 . " AND rind_indicador=ind_id AND rec.institucion=car.institucion AND rec.year=car.year
        WHERE car.car_curso = '{$idCurso}' AND car.car_grupo = '{$idGrupo}' AND car.institucion = {$config['conf_id_institucion']}  AND car.year = {$_SESSION["bd"]} AND bol.bol_estudiante = '{$matriculadosDatos['mat_id']}' AND bol.bol_periodo IN (" . $condicion . ") AND aac.cal_id_estudiante = '{$matriculadosDatos['mat_id']}' AND aa.act_periodo = " . $condicion2 . "
        GROUP BY ar.ar_id, am.mat_id, ind.ind_id
        HAVING nota < {$config['conf_nota_minima_aprobar']} AND (rind_nota IS NULL OR (rind_nota < nota AND rind_nota < {$config['conf_nota_minima_aprobar']}))
        ORDER BY ar.ar_posicion ASC");
        $numdatos = mysqli_num_rows($consulta_mat_area_est);
        if ($numdatos > 0) {
        ?>
        <div align="center" style="margin-bottom:20px;">
        </div>

        <table width="100%" cellspacing="5" cellpadding="5" border="0" rules="none">
            <tr>
                <td>Documento:<br> <?=strpos($matriculadosDatos["mat_documento"], '.') !== true && is_numeric($matriculadosDatos["mat_documento"]) ? number_format($matriculadosDatos["mat_documento"],0,",",".") : $matriculadosDatos["mat_documento"];?></td>
                <td>Nombre:<br> <?= Estudiantes::NombreCompletoDelEstudiante($matriculadosDatos); ?></td>
                <td>Grado:<br> <?= $cursoActual["gra_nombre"] . " " . $matriculadosDatos["gru_nombre"]; ?></td>
                <td>Periodo:<br> <b><?= $periodoActuales . " (" . date("Y") . ")"; ?></b></td>
            </tr>
        </table>

        <p>&nbsp;</p>
        <table width="100%" id="tblBoletin" cellspacing="0" cellpadding="0" rules="all" border="1" align="left">
            <tr style="font-weight:bold; background-color:#4c9858; border-color:#000; height:40px; color:#000; font-size:12px;">
                <td width="2%" align="center">NO</td>
                <td width="20%" align="center">ASIGNATURAS</td>
                <td width="2%" align="center">NOTA</td>
            </tr>

            <!-- Aca ira un while con los indiracores, dentro de los cuales debera ir otro while con las notas de los indicadores-->
            <?php
            $contador = 1;
            $idMatAnterior = "";
            while ($fila = mysqli_fetch_array($consulta_mat_area_est, MYSQLI_BOTH)) {

                $leyendaRI = '';
                if(!empty($fila['rind_nota']) && $fila['rind_nota']>$fila["nota"]){
                    $nota_indicador = round($fila['rind_nota'], 1);
                    $leyendaRI = '<br><span style="color:navy; font-size:9px;">Recuperado.</span>';
                }else{
                    $nota_indicador = round($fila["nota"], 1);
                }

                if ($nota_indicador == 1)    $nota_indicador = "1.0";
                if ($nota_indicador == 2)    $nota_indicador = "2.0";
                if ($nota_indicador == 3)    $nota_indicador = "3.0";
                if ($nota_indicador == 4)    $nota_indicador = "4.0";
                if ($nota_indicador == 5)    $nota_indicador = "5.0";
                if($nota_indicador >= $config['conf_nota_minima_aprobar']){continue;}

                if ($idMatAnterior != $fila["mat_id"]) {
                    $idMatAnterior = $fila["mat_id"];
                $contador_periodos = 0;
            ?>
                <tr bgcolor="#EAEAEA" style="font-size:12px;">
                    <td align="center"><?= $contador; ?></td>
                    <td style="font-size:12px; height:35px; font-weight:bold;background:#EAEAEA;"><?=$fila["mat_nombre"]; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <?php
                    }
                ?>
                    <tr bgcolor="#FFF" style="font-size:12px;">
                        <td align="center">&nbsp;</td>
                        <td style="font-size:12px; height:15px;"><?=$fila["ind_nombre"]; ?></td>
                        <td align="center" style="font-weight:bold; font-size:12px;"><?= $nota_indicador." ".$leyendaRI; ?></td>
                    </tr>
                <?php
                $contador++;
            } //while fin areas
            ?>
        </table>
        <div id="saltoPagina"></div>
    <?php
        }
} // FIN DE TODOS LOS MATRICULADOS
    ?>
    <script type="application/javascript">
        print();
    </script>
    </body>
<?php
include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
?>
    </html>