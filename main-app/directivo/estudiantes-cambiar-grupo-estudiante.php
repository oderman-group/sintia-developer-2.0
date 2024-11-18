<?php
include("session.php");

require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");

$consultaEstudiante = Estudiantes::obtenerListadoDeEstudiantes(" AND mat_id='" . $_POST["estudiante"] . "'");
$estudiante         = mysqli_fetch_array($consultaEstudiante, MYSQLI_BOTH);

// Consultar cargas del curso y grupo al cual pertenece el estudiante actualmente
$cargasConsulta = CargaAcademica::traerCargasMateriasPorCursoGrupo($config, $estudiante["mat_grado"], $estudiante["mat_grupo"]);

$contadorCargasActualizadas = 0;
if($pasarNotas == 1 ){
    foreach ($notasNuevas as $carga => $nuevaCarga){
        $cargaNueva = explode("|", $nuevaCarga);
        $update = ['bol_carga' => $cargaNueva[0]];
        Boletin::actualizarBoletinCargaEstudiante($config, $carga, $_POST["estudiante"], $update,$_SESSION["bd"]);
        $contadorCargasActualizadas ++;
    }
}

$update = [
    'mat_grupo' => $_POST["grupoNuevo"]
];

Estudiantes::actualizarMatriculasPorId($config, $_POST["estudiante"], $update);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");

$msj = "Se actualizaron (".$contadorCargasActualizadas.") cargas para el estudiante ".Estudiantes::NombreCompletoDelEstudiante($estudiante);

echo '<script type="text/javascript">window.location.href="estudiantes.php?success=SC_DT_4&summary='.base64_encode($msj).'&id='.base64_encode($_POST["estudiante"]).'";</script>';
exit();