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
require_once(ROOT_PATH."/main-app/class/Plataforma.php");

$Plataforma = new Plataforma();

$parametrosBuscar = [
    "tipo"   => JOBS_TIPO_GENERAR_INFORMES,
    "estado" => JOBS_ESTADO_PROCESADO
];

BindSQL::iniciarTransacion();

echo 'Comenzando el proceso de calcular las definitivas de los informes en estado procesado...'."<br>";

try {
    $listadoCrobjobs = SysJobs::listar($parametrosBuscar);

    echo 'Se encontraron '.mysqli_num_rows($listadoCrobjobs).' jobs para calcular las definitivas.'."<br>";

    while ($resultadoJobs = mysqli_fetch_array($listadoCrobjobs, MYSQLI_BOTH)) {

        echo 'Procesando job ID: '.$resultadoJobs['job_id']."<br>";

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
        $contenidoMensaje .= '<tr style="background-color:'.$Plataforma->colorUno.'; color:#FFF; text-align:center;">
        <th>Nro.</th>
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

        echo '<p>Se encontraron '.$consultaListaEstudante->rowCount().' estudiantes para el job ID: '.$resultadoJobs['job_id']."</p>";

        $contenidoMensaje .= '<tbody>';

        $contadorEstudiantes = 1;

        while ($estudianteResultado = $consultaListaEstudante->fetch(PDO::FETCH_ASSOC)) {
            $nombreEstudiante = 'NN';

            if (!empty($estudianteResultado['tcbe_datos_estudiante'])) {
                $datosEstudiante = json_decode($estudianteResultado['tcbe_datos_estudiante'], true);
                $nombreEstudiante = Estudiantes::NombreCompletoDelEstudiante($datosEstudiante);
            }

            echo 'Procesando estudiante: '.$estudianteResultado['tcbe_id_estudiante'].' - '.$nombreEstudiante."<br><br>";

            $contenidoMensaje .= '<tr>';
            $contenidoMensaje .= '<td style="text-align:center;">'.$contadorEstudiantes.'</td>';
            $contenidoMensaje .= '<td style="text-align:center;">'.$estudianteResultado['tcbe_id_estudiante'].'</td>';
            $contenidoMensaje .= '<td>'.$nombreEstudiante.'</td>';
            $contenidoMensaje .= '<td style="text-align:center;">'.$estudianteResultado['tcbe_estado'].'</td>';
            $contenidoMensaje .= '<td>'.$estudianteResultado['tcbe_error_msg'].'</td>';
            $contenidoMensaje .= '</tr>';

            $contadorEstudiantes ++;
        }

        BDT_tempCalculoBoletinEstudiantes::Delete($predicado);

        echo 'Eliminando datos de la tabla temporal...'."<br>";

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

        echo 'Enviando notificación al responsable del job finalizado...'."<br>";

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
                'car_forma_generacion' => BDT_AcademicoCargas::GENERACION_AUTO,
            ];

            echo 'Revisamos el historico de la carga...'."<br>";
        } catch (PDOException $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }

        $update = [
            'car_estado'    => BDT_AcademicoCargas::ESTADO_SINTIA,
            'car_periodo'   => $periodoSiguiente,
            'car_historico' => json_encode($carHistoricoArray),
        ];

        CargaAcademica::actualizarCargaPorID($config, $carga, $update);

        echo 'Actualizamos la carga en la base de datos...'."<br>";

        $datos = [
            "id"      => $resultadoJobs['job_id'],
            "estado"  => JOBS_ESTADO_FINALIZADO,
        ];

        SysJobs::actualizar($datos);

        echo 'Job finalizado correctamente...'."<br>";
        echo "<hr>";

    }
    BindSQL::finalizarTransacion();
} catch (Exception $e) {
    echo 'Error durante el proceso de calcular las definitivas de los informes en estado procesado: '.$e->getMessage()."\n";
    Utilidades::logError($e);
    BindSQL::revertirTransacion();
}