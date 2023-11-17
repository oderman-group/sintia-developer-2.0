<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php"); 
require_once("../class/Estudiantes.php");

$consultaDatos = mysqli_query($conexion, "SELECT * FROM academico_grados
LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id='" . $_POST["grupo"] . "' AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
WHERE gra_id='" . $_POST["grado"] . "'");
$datos = mysqli_fetch_array($consultaDatos, MYSQLI_BOTH);
?>

<head>
  <title>Estudiantes</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="shortcut icon" href="<?= $Plataforma->logo; ?>">
</head>

<body style="font-family:Arial;">
  <?php
  $nombreInforme = "REPORTES DISCIPLINARIOS" . "<br>" . strtoupper(Utilidades::getToString($datos['gra_nombre']). " " . Utilidades::getToString($datos['gru_nombre'])) . "<br> DESDE " . $_POST["desde"] . " HASTA " . $_POST["hasta"];;
  include("../compartido/head-informes.php") ?>

  <table width="100%" cellspacing="5" cellpadding="5" rules="all" style="
  border:solid; 
  border-color:<?= $Plataforma->colorUno; ?>; 
  font-size:11px;
  ">

    <tr style="font-weight:bold; height:30px; background:<?= $Plataforma->colorUno; ?>; color:#FFF;">
      <th>#</th>
      <th>Fecha</th>
      <th>Estudiante</th>
      <th>Curso</th>
      <th>Categoría</th>
      <th>Cod</th>
      <th>Observaciones</th>
      <th>Usuario</th>
      <th title="Firma y aprobación del estudiante">F.E</th>
      <th title="Firma y aprobación del acudiente">F.A</th>
    </tr>
    <?php
    $cont = 1;
    $filtro = '';
    if (!empty($_POST["est"])) {
      $filtro .= " AND dr_estudiante='" . $_POST["est"] . "'";
    }
    if (!empty($_POST["falta"])) {
      $filtro .= " AND dr_falta='" . $_POST["falta"] . "'";
    }
    if (!empty($_POST["usuario"])) {
      $filtro .= " AND dr_usuario='" . $_POST["usuario"] . "'";
    }

    $filtroMat = '';
    if (!empty($_POST["grado"])) {
      $filtroMat .= " AND mat_grado='" . $_POST["grado"] . "'";
    }
    if (!empty($_POST["grupo"])) {
      $filtroMat .= " AND mat_grupo='" . $_POST["grupo"] . "'";
    }

    if($datos['gra_tipo']==GRADO_GRUPAL){
      $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disciplina_reportes
      INNER JOIN ".BD_DISCIPLINA.".disciplina_faltas ON dfal_id=dr_falta AND dfal_institucion={$config['conf_id_institucion']} AND dfal_year={$_SESSION["bd"]}
      INNER JOIN ".BD_DISCIPLINA.".disciplina_categorias ON dcat_id=dfal_id_categoria AND dcat_institucion={$config['conf_id_institucion']} AND dcat_year={$_SESSION["bd"]}
      INNER JOIN academico_matriculas ON mat_id_usuario=dr_estudiante $filtroMat
      LEFT JOIN academico_grados ON gra_id=mat_grado
      LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=mat_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
      INNER JOIN usuarios ON uss_id=dr_usuario
      WHERE dr_fecha>='" . $_POST["desde"] . "' AND dr_fecha<='" . $_POST["hasta"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} $filtro
      ");
    }else{
      $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disciplina_reportes
      INNER JOIN ".BD_DISCIPLINA.".disciplina_faltas ON dfal_id=dr_falta AND dfal_institucion={$config['conf_id_institucion']} AND dfal_year={$_SESSION["bd"]}
      INNER JOIN ".BD_DISCIPLINA.".disciplina_categorias ON dcat_id=dfal_id_categoria AND dcat_institucion={$config['conf_id_institucion']} AND dcat_year={$_SESSION["bd"]}
      INNER JOIN academico_matriculas ON mat_id_usuario=dr_estudiante
      INNER JOIN ".$baseDatosServicios.".mediatecnica_matriculas_cursos ON matcur_id_matricula=mat_id AND matcur_id_curso='" . $_POST["grado"] . "'
      LEFT JOIN academico_grados ON gra_id=matcur_id_curso
      LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=matcur_id_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
      INNER JOIN usuarios ON uss_id=dr_usuario
      WHERE dr_fecha>='" . $_POST["desde"] . "' AND dr_fecha<='" . $_POST["hasta"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} $filtro
      ");
    }
    while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
    ?>

      <tr style="border-color:<?= $Plataforma->colorDos; ?>;">
        <td><?= $cont; ?></td>
        <td><?= $resultado['dr_fecha']; ?></td>
        <td><?= Estudiantes::NombreCompletoDelEstudiante($resultado); ?></td>
        <td><?= $resultado['gra_nombre'] . " " . $resultado['gru_nombre']; ?></td>
        <td><?= $resultado['dcat_nombre']; ?></td>
        <td><?= $resultado['dfal_codigo']; ?></td>
        <td><?= $resultado['dr_observaciones']; ?></td>
        <td><?= $resultado['uss_nombre']; ?></td>
        <td>
          <?php if ($resultado['dr_aprobacion_estudiante'] == 0) {
            echo "-";
          } else { ?>
            <i class="fa fa-check-circle" title="<?= $resultado['dr_aprobacion_estudiante_fecha']; ?>">OK</i>
          <?php } ?>
        </td>
        <td>
          <?php if ($resultado['dr_aprobacion_acudiente'] == 0) {
            echo "-";
          } else { ?>
            <i class="fa fa-check-circle" title="<?= $resultado['dr_aprobacion_acudiente_fecha']; ?>">OK</i>
          <?php } ?>
        </td>
      </tr>
    <?php
      $cont++;
    } //Fin mientras que
    ?>
  </table>
  </center>
</body>
<?php include("../compartido/footer-informes.php") ?>;
</html>