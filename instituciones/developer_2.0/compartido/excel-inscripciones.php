<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Inscripciones_" . date("d/m/Y") . "-SINTIA.xls");
include("../modelo/conexion.php");
?>

<html>

<head>
    <meta charset="utf-8">
</head>


<?php
$estadosSolicitud = array(
    1 => 'VERIFICACIÓN DE PAGO',
    2 => 'PAGO RECHAZADO',
    3 => 'PENDIENTE POR DILIGENCIAR EL FORMULARIO',
    4 => 'EN PROCESO',
    5 => 'EXAMEN Y ENTREVISTA',
    6 => 'APROBADO',
    7 => 'NO APROBADO'
);

$fondoSolicitud = array(
    1 => 'yellow',
    2 => 'tomato',
    3 => 'orange',
    4 => '#AFB372',
    5 => 'aquamarine',
    6 => 'green',
    7 => 'red'
);

$consulta = mysql_query("SELECT * FROM academico_matriculas
INNER JOIN mobiliar_sintia_admisiones.aspirantes ON asp_id=mat_solicitud_inscripcion
  WHERE mat_estado_matricula=5 ORDER BY mat_primer_apellido
  ", $conexion);
?>
<div align="center">
    <table width="100%" border="1" rules="all">
        <thead>
            <tr>
                <th colspan="7" style="background:#060; color:#FFF;">INSCRIPCIONES ACTUALES</th>
            </tr>
            <tr>
                <th>ID</th>

                <th>Fecha</th>

                <th>Documento</th>

                <th>Aspirante</th>

                <th>Año</th>

                <th>Estado</th>

                <th>Comprobante</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $conta = 1;
            while ($resultado = mysql_fetch_array($consulta)) {
            ?>
                <tr>
                    <td><?= $resultado["mat_id"]; ?></td>

                    <td><?= $resultado["asp_fecha"]; ?></td>

                    <td><?= $resultado["mat_documento"]; ?></td>

                    <td><?= strtoupper($resultado["mat_nombres"] . " " . $resultado["mat_primer_apellido"]); ?></td>

                    <td><?= $resultado["asp_agno"]; ?></td>

                    <td bgcolor="<?= $fondoSolicitud[$resultado["asp_estado_solicitud"]]; ?>"><?= $estadosSolicitud[$resultado["asp_estado_solicitud"]]; ?></td>

                    <td><a href="https://plataformasintia.com/admisiones/files/comprobantes/<?= $resultado["asp_comprobante"]; ?>" target="_blank" style="text-decoration: underline;"><?= $resultado["asp_comprobante"]; ?></a></td>
                </tr>

            <?php
                $conta++;
            }
            ?>
        </tbody>
    </table>

</html>