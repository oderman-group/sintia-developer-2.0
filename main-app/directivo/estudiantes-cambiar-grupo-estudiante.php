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

while ($cargasDatos = mysqli_fetch_array($cargasConsulta, MYSQLI_BOTH)) {
    $cargasConsultaNuevo = CargaAcademica::traerCargasMateriasPorCursoGrupoMateria($config, $_POST["cursoActual"], $_POST["grupoNuevo"], $cargasDatos["car_materia"]);

    if (!is_null($cargasConsultaNuevo)) {
        $update = [
            'bol_carga' => $cargasConsultaNuevo["car_id"]
        ];

        // Actualizar las referencias en la tabla boletín para el estudiante con la nueva carga del nuevo grupo al que fue movido
        Boletin::actualizarBoletinCargaEstudiante($config, $cargasDatos['car_id'], $_POST["estudiante"], $update);

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