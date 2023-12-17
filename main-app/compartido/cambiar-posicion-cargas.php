<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0058';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

function posicion ($conexion, $config, $idRegistro, $posicionInicial, $docente) {
    global $filtroMT;

    try {
        $consulta = mysqli_query($conexion, "SELECT car_id, car_posicion_docente FROM ".BD_ACADEMICA.".academico_cargas car
        INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
        INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]} {$filtroMT}
        INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
        WHERE car_id <> '{$idRegistro}' AND car_posicion_docente >= {$posicionInicial}
        AND car_docente = '{$docente}'  AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]}
        ORDER BY CAST(car_posicion_docente AS SIGNED)
        ");
        $registro = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_cargas SET car_posicion_docente='" . $posicionInicial . "' 
        WHERE car_id='" . $idRegistro . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");

        if( !empty($registro) ) {
            return posicion($conexion, $config, $registro['car_id'], $posicionInicial + 1, $docente);
        }

        return 1;
    } catch (Exception $e) {
        return $e;
    }
}

echo posicion($conexion, $config, $_GET["idCarga"], $_GET["posicionNueva"], $_GET["docente"]);