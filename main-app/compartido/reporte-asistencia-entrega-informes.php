<?php
include("../directivo/session.php");
require_once("../class/Estudiantes.php");
require_once("../class/Usuarios.php");
require_once("../class/UsuariosPadre.php");
require_once("../class/servicios/GradoServicios.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");
$Plataforma = new Plataforma;

$year = $_SESSION["bd"];
if (isset($_REQUEST["year"])) {
    $year = $_REQUEST["year"];
}
$BD = $_SESSION["inst"] . "_" . $year;
$bdConsulta = $BD.".";

if (empty($_REQUEST["periodo"])) {
    $periodoActual = 1;
} else {
    $periodoActual = $_REQUEST["periodo"];
}

switch ($periodoActual) {
    case 1:
        $periodoActuales = "Uno";
        $acomulado = 0.25;
        break;
    case 2:
        $periodoActuales = "Dos";
        $acomulado = 0.50;
        break;
    case 3:
        $periodoActuales = "Tres";
        $acomulado = 0.75;
        break;
    case 4:
        $periodoActuales = "Final";
        $acomulado = 0.10;
        break;
}
$colspan=1;
if($config['conf_firma_estudiante_informe_asistencia']==1){
    $colspan=2;
}

$consultaNombreMaterias= mysqli_query($conexion,"SELECT mat_nombre, car_docente, car_director_grupo FROM ".BD_ACADEMICA.".academico_materias am
INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id = am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$year}
INNER JOIN $BD.academico_cargas on car_materia = am.mat_id and car_curso = '" . $_REQUEST["curso"] . "' AND car_grupo = '" . $_REQUEST["grupo"] . "'
WHERE am.institucion={$config['conf_id_institucion']} AND am.year={$year}
ORDER BY am.mat_id");
$numMaterias=mysqli_num_rows($consultaNombreMaterias);
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <title>Asistencia Acudientes</title>
    <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
    <!-- favicon -->
    <link rel="shortcut icon" href="../sintia-icono.png" />
    <style>
        #saltoPagina {
            PAGE-BREAK-AFTER: always;
        }
		.vertical {
			writing-mode: vertical-lr; /* o vertical-lr */
			text-orientation: mixed; /* para que los caracteres se roten correctamente */
            transform: rotate(180deg);
		}
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
</head>

<body style="font-family:Arial; font-size:9px;">
    <table width="100%" cellspacing="5" cellpadding="5" rules="all" style="border:solid; font-size:11px;">
        <tr style="font-weight:bold;">
            <td align="center" colspan="3" style="font-weight:bold;"><?=strtoupper($informacion_inst["info_nombre"])?></td>
            <td align="center" colspan="<?=$numMaterias?>">PERIODO <?=strtoupper($periodoActuales)?></td>
            <td align="center" colspan="<?=$colspan?>">REPORTE DE ASISTENCIA A ENTREGA DE INFORMES PERIODO <?=strtoupper($periodoActuales)?></td>
        </tr>

        <tr style="font-weight:bold; background:#d8e285;">
            <td align="center" colspan="3">PERIODO ESCOLAR <?=date("Y")?></td>
            <?php
                $idDirector="";
                $conMaterias=0;
                while($nombreMaterias = mysqli_fetch_array($consultaNombreMaterias, MYSQLI_BOTH)){
                    //DIRECTOR DE GRUPO
                    if($nombreMaterias["car_director_grupo"]==1){
                        $idDirector=$nombreMaterias["car_docente"];
                    }
                    $conMaterias++;
            ?>
            <td class="vertical" rowspan="5" width="1%"><?=$conMaterias.". ".$nombreMaterias['mat_nombre']?></td>
            <?php
                }
                $directorGrupo = Usuarios::obtenerDatosUsuario($idDirector);
                $nombreDirectorGrupo = UsuariosPadre::nombreCompletoDelUsuario($directorGrupo);
            ?>
            <td colspan="<?=$colspan?>">FECHA: <?=date("d/m/Y")?></td>
        </tr>

        <tr style="font-weight:bold;">
            <td colspan="2">SEDE:</td>
            <td>Principal</td>
            <?php
                if($config['conf_firma_estudiante_informe_asistencia']==1){
            ?>
            <td align="center" rowspan="4">FIRMA DE<br>ESTUDIANTE</td>
            <?php } ?>
            <td align="center" rowspan="4">FIRMA DE<br>ACUDIENTE</td>
        </tr>

        <tr style="font-weight:bold;">
            <td colspan="2">DOCENTE:</td>
            <td><?=$nombreDirectorGrupo?></td>
        </tr>

        <tr style="font-weight:bold;">
            <td align="center" colspan="3">DATOS GENERALES DE LOS ESTUDIANTES</td>
        </tr>

        <tr style="font-weight:bold;">
            <td width="1%">Nº</td>
            <td width="5%">Grado</td>
            <td align="center" width="20%">Estudiante</td>
        </tr>

        <?php
        $grupo="";
        if (!empty($_REQUEST["curso"]) and !empty($_REQUEST["grupo"])) {
            $adicional = "AND mat_grado='" . $_REQUEST["curso"] . "' AND mat_grupo='" . $_REQUEST["grupo"] . "'";
            $grupo=$_REQUEST["grupo"];
        } elseif (!empty($_REQUEST["curso"])) {
            $adicional = "AND mat_grado='" . $_REQUEST["curso"] . "'";
        } else {
            $adicional = "";
        }
        $cont = 1;
        $filtroAdicional = $adicional . " AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
        $cursoActual=GradoServicios::consultarCurso($_REQUEST["curso"]);
        $consulta =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"",$cursoActual,$bdConsulta,$grupo);
        $numE = mysqli_num_rows($consulta);
        while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
            $nombre = Estudiantes::NombreCompletoDelEstudiante($resultado);
        ?>
            <tbody>
                <td align="center"><?=$cont?></td>
                <td><?=$resultado["gra_nombre"]." ".$resultado["gru_nombre"]?></td>
                <td><?= $nombre ?></td>
                <?php
                    $consultaNotaMaterias= mysqli_query($conexion,"SELECT bol_nota FROM ".BD_ACADEMICA.".academico_materias am
                    INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id = am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$year}
                    INNER JOIN academico_cargas on car_materia = am.mat_id and car_curso = '".$_REQUEST["curso"]."' AND car_grupo = '".$_REQUEST["grupo"]."'
                    LEFT JOIN academico_boletin ON bol_carga=car_id AND bol_periodo = '".$_REQUEST["periodo"]."' AND bol_estudiante = '".$resultado["mat_id"]."'
                    AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}
                    ORDER BY am.mat_id;");
                    $numNotas = mysqli_num_rows($consultaNotaMaterias);
                    if($numNotas>0){
                        // $notaMateria= 0;
                        while($notaMaterias = mysqli_fetch_array($consultaNotaMaterias, MYSQLI_BOTH)){
                            if(!is_null($notaMaterias['bol_nota'])){
                                $notaMateria= round($notaMaterias['bol_nota'],$config['conf_decimales_notas']);

                                $notaMateriaFinal=$notaMateria;
                                $diseñoCelda='';
                                if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                                    $diseñoCelda='class="vertical" title="Nota Cuantitativa: '.$notaMateria.'"';
                                    $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaMateria, $BD);
                                    $notaMateriaFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
                                }

                                $estiloNota="";
                                if($notaMateria<$config['conf_nota_minima_aprobar']){
                                    $estiloNota='style="font-weight:bold; color:#008e07; background:#abf4af;"';
                                }
                                echo '<td align="center" '.$estiloNota.' '.$diseñoCelda.'>'.$notaMateriaFinal.'</td>';
                            }else{
                                echo '<td align="center">&nbsp;</td>';
                            } 
                        }
                    }else{
                        for($i=1;$i<=$numMaterias;$i++){
                            echo '<td align="center">&nbsp;</td>';
                        } 
                    }

                ?>
                <?php
                    if($config['conf_firma_estudiante_informe_asistencia']==1){
                ?>
                <td style="height:20px;">&nbsp;</td>
                <?php } ?>
                <td style="height:20px;">&nbsp;</td>
            </tbody>
        <?php
            $cont++;
        } //Fin mientras que
        ?>
    </table>

    <script type="application/javascript">
        print();
    </script>
</body>

</html>