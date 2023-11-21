<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Inscripciones_" . date("d/m/Y") . "-SINTIA.xls");
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
?>

<html>

<head>
    <meta charset="utf-8">
</head>


<?php
include(ROOT_PATH."/config-general/config-admisiones.php");

$consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat
INNER JOIN ".$baseDatosAdmisiones.".aspirantes ON asp_id=mat.mat_solicitud_inscripcion
WHERE mat.mat_estado_matricula=5 AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]} ORDER BY mat.mat_primer_apellido");
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
            while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
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