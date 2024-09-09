<?php
$_SERVER['DOCUMENT_ROOT'] = dirname(dirname(dirname(dirname(__FILE__))));
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once ROOT_PATH."/main-app/class/Conexion.php";

$conexionPDO = Conexion::newConnection('PDO');
$conexion    = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion);

require_once(ROOT_PATH."/main-app/class/Sysjobs.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/servicios/GradoServicios.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");
require_once(ROOT_PATH."/main-app/class/RedisInstance.php");
require_once(ROOT_PATH."/main-app/class/BindSQL.php");
require_once(ROOT_PATH."/main-app/class/Tables/BDT_temp_calculo_boletin_estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Tables/BDT_academico_cargas.php");

$parametrosBuscar = [
    "tipo"   => JOBS_TIPO_GENERAR_INFORMES,
    "estado" => JOBS_ESTADO_PROCESADO
];

BindSQL::iniciarTransacion();

try {
    $listadoCrobjobs = SysJobs::listar($parametrosBuscar);

    while ($resultadoJobs = mysqli_fetch_array($listadoCrobjobs, MYSQLI_BOTH)) {

        $parametros    = json_decode($resultadoJobs["job_parametros"], true);
        $institucionId = $resultadoJobs["job_id_institucion"];
        $anio          = $resultadoJobs["job_year"];

        $_SESSION["id"]            = $resultadoJobs["job_responsable"];
        $_SESSION["bd"]            = $resultadoJobs["job_year"];
        $_SESSION["idInstitucion"] = $resultadoJobs["job_id_institucion"];

        $grado   = $parametros["grado"];
        $grupo   = $parametros["grupo"];
        $carga   = $parametros["carga"];
        $periodo = $parametros["periodo"];

        $informacionAdicional = [
            'carga'   => $carga,
            'periodo' => $periodo
        ];

        $config = RedisInstance::getSystemConfiguration();

        $cursoActual      = GradoServicios::consultarCurso($grado);
        $contenidoMensaje = "<h1>Resultado final</h1>";
        $contenidoMensaje .= '<table border="1" width="100%" rules="group">';
        $contenidoMensaje .= '<thead>';
        $contenidoMensaje .= '<tr>
        <th>ID Estudiante</th>
        <th>Nombre Estudiante</th>
        <th>Estado</th>
        <th>Comentario</th>
        </tr>';
        $contenidoMensaje .= '</thead>';

        $predicado = [
            'tcbe_id_job_referencia' => $resultadoJobs['job_id'],
        ];

        $consultaListaEstudante  = BDT_tempCalculoBoletinEstudiantes::Select($predicado, null, BD_ADMIN);

        $contenidoMensaje .= '<tbody>';

        while ($estudianteResultado = $consultaListaEstudante->fetch(PDO::FETCH_ASSOC)) {
            $nombreEstudiante = 'NN';
            if (!empty($estudianteResultado['tcbe_datos_estudiante'])) {
                $datosEstudiante = json_decode($estudianteResultado['tcbe_datos_estudiante'], true);
                $nombreEstudiante = Estudiantes::NombreCompletoDelEstudiante($datosEstudiante);
            }

            $contenidoMensaje .= '<tr>';
            $contenidoMensaje .= '<td>'.$estudianteResultado['tcbe_id_estudiante'].'</td>';
            $contenidoMensaje .= '<td>'.$nombreEstudiante.'</td>';
            $contenidoMensaje .= '<td>'.$estudianteResultado['tcbe_estado'].'</td>';
            $contenidoMensaje .= '<td>'.$estudianteResultado['tcbe_error_msg'].'</td>';
            $contenidoMensaje .= '</tr>';
        }

        $contenidoMensaje .= '</tbody>';
        $contenidoMensaje .= '</table>';

        //Enviamos notificación al responsable del job
        SysJobs::enviarMensaje(
            $resultadoJobs['job_responsable'],
            $contenidoMensaje,
            $resultadoJobs['job_id'],
            JOBS_TIPO_GENERAR_INFORMES,
            JOBS_ESTADO_FINALIZADO, 
            $informacionAdicional
        );

        $periodoSiguiente = $periodo + 1;
        $carHistoricoArray = [];

        try {
            $predicado = [
                'car_id'      => $carga,
                'institucion' => $config['conf_id_institucion'],
                'year'        => $_SESSION["bd"],
            ];

            $campos = "car_periodo, car_estado, car_historico";

            $datosCargaActualConsulta = BDT_AcademicoCargas::select($predicado, $campos, BD_ACADEMICA);
            $datosCargaActual         = $datosCargaActualConsulta->fetchAll();
            $carHistoricoCampo        = $datosCargaActual[0]['car_historico'];

            if (!empty($carHistoricoCampo)) {

                $carHistoricoArray     = json_decode($carHistoricoCampo, true);
                $keys                  = array_keys($carHistoricoArray);
                $lastKey               = end($keys);
                $lastCarHistoricoArray = $carHistoricoArray[$lastKey];

                if (
                    $datosCargaActual[0]['car_estado'] == 'DIRECTIVO' && 
                    !empty($lastCarHistoricoArray['car_periodo_anterior']) && 
                    $lastCarHistoricoArray['car_periodo_anterior'] != $datosCargaActual[0]['car_periodo']
                ) {
                    $periodoSiguiente = $lastCarHistoricoArray['car_periodo_anterior'];
                }

            }

            $carHistoricoArray[$carga.':'.time()] = [
                'car_periodo_anterior' => $datosCargaActual[0]['car_periodo'],
                'car_estado_anterior'  => $datosCargaActual[0]['car_estado'],
                'car_forma_generacion' => 'JOB',
            ];
        } catch (PDOException $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }

        $update = [
            'car_estado'    => 'SINTIA',
            'car_periodo'   => $periodoSiguiente,
            'car_historico' => json_encode($carHistoricoArray),
        ];

        CargaAcademica::actualizarCargaPorID($config, $carga, $update);

        $datos = [
            "id"      => $resultadoJobs['job_id'],
            "estado"  => JOBS_ESTADO_FINALIZADO,
        ];

        SysJobs::actualizar($datos);

    }
    BindSQL::finalizarTransacion();
} catch (Exception $e) {
    Utilidades::logError($e);
    BindSQL::revertirTransacion();
}