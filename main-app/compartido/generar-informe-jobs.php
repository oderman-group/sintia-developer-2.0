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
require_once(ROOT_PATH."/main-app/class/Indicadores.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");
require_once(ROOT_PATH."/main-app/class/Calificaciones.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");
require_once(ROOT_PATH."/main-app/class/RedisInstance.php");
require_once(ROOT_PATH."/main-app/class/BindSQL.php");
require_once(ROOT_PATH."/main-app/class/Tables/BDT_temp_calculo_boletin_estudiantes.php");

$parametrosBuscar = [
    "tipo"   => JOBS_TIPO_GENERAR_INFORMES,
    "estado" => JOBS_ESTADO_PENDIENTE
];

BindSQL::iniciarTransacion();

try {
    $listadoCrobjobs = SysJobs::listar($parametrosBuscar);

    while ($resultadoJobs = mysqli_fetch_array($listadoCrobjobs, MYSQLI_BOTH)) {

        $datos = [
            "id"      => $resultadoJobs['job_id'],
            "estado"  => JOBS_ESTADO_PROCESO,
        ];

        SysJobs::actualizar($datos);

        // fecha1 es la primera fecha
        $fechaInicio   = new DateTime();
        $finalizado    = false;
        $parametros    = json_decode($resultadoJobs["job_parametros"], true);
        $institucionId = $resultadoJobs["job_id_institucion"];
        $anio          = $resultadoJobs["job_year"];
        $intento       = intval($resultadoJobs["job_intentos"]);

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

        //Consultamos los estudiantes del grado y grupo
        $filtroAdicional         = "AND mat_grado='".$grado."' 
                                    AND mat_grupo='".$grupo."' 
                                    AND (mat_estado_matricula=1 OR mat_estado_matricula=2)
                                    ";
        $cursoActual             = GradoServicios::consultarCurso($grado);
        $numEstudiantes          = 0;
        $finalizado              = true;
        $erroresNumero           = 0;
        $listadoEstudiantesError = "";
        $mensaje                 = "";

        if ($config['conf_porcentaje_completo_generar_informe'] == GENERAR_CON_PORCENTAJE_COMPLETO) {
            $consultaListaEstudantesError = Estudiantes::listarEstudiantesNotasFaltantes($carga, $periodo, $cursoActual["gra_tipo"]);

            //Verificamos que el estudiante tenga sus notas al 100%
            if (mysqli_num_rows($consultaListaEstudantesError) > 0) {
                $erroresNumero = mysqli_num_rows($consultaListaEstudantesError);
                $contador      = 0;

                while ($estudianteResultadoError = mysqli_fetch_array($consultaListaEstudantesError, MYSQLI_BOTH)) {
                    $contador ++;

                    $porcentajeAcumulado     = $estudianteResultadoError['acumulado'] > 0 ? $estudianteResultadoError['acumulado'] : 0;
                    $listadoEstudiantesError = $listadoEstudiantesError."<br><br>".$contador."): ".$estudianteResultadoError['mat_nombres']
                    ." ".$estudianteResultadoError['mat_primer_apellido']." ".$estudianteResultadoError['mat_segundo_apellido']
                    ." no tiene notas completas.<br>
                    ID: <b>".$estudianteResultadoError['mat_id']."</b><br>
                    Valor Actual: <b>".$porcentajeAcumulado."% </b>";
                }

                $finalizado = false;
            }
        }

        if ($finalizado) {

            $consultaListaEstudante  = Estudiantes::listarEstudiantesEnGrados($filtroAdicional, "", $cursoActual, $grupo, $anio);

            while ($estudianteResultado = mysqli_fetch_array($consultaListaEstudante, MYSQLI_ASSOC)) {
                $estudiante = $estudianteResultado["mat_id"];

                $porcentajeActual = Calificaciones::obtenerPorcentajeActualEstudiante($carga, $periodo, $estudiante);

                //Consultamos si tiene registros en el boletín
                $boletinDatos = Boletin::traerNotaBoletinCargaPeriodo($config, $periodo, $estudiante, $carga, $anio);

                if ($config['conf_porcentaje_completo_generar_informe'] == OMITIR_ESTUDIANTES_CON_PORCENTAJE_INCOMPLETO) {

                    //Verificamos que el estudiante tenga sus notas al porcentaje minimo permitido
                    if ($porcentajeActual < PORCENTAJE_MINIMO_GENERAR_INFORME && empty($boletinDatos['bol_nota'])) {
                        $erroresNumero ++;

                        $mensaje = $mensaje."<br><br>".$erroresNumero."): ".$estudianteResultado['mat_nombres']." ".$estudianteResultado['mat_primer_apellido']." ".$estudianteResultado['mat_segundo_apellido'] ." no tiene notas completas.<br>
                        ID: <b>".$estudianteResultado['mat_id']."</b><br>
                        Valor Actual: <b>".$porcentajeActual."% </b>";

                        $finalizado = false;

                        continue;
                    }
                }

                //Insertamos en la tabla temporal
                $datosParaInsertar = [
                    'tcbe_id_estudiante'      => $estudiante,
                    'tcbe_id_carga_academica' => $carga,
                    'tcbe_periodo'            => $periodo,
                    'tcbe_id_job_referencia'  => $resultadoJobs['job_id'],
                    'tcbe_estado'             => 'PENDIENTE',
                    'tcbe_datos_estudiante'   => json_encode($estudianteResultado),
                ];

                try {
                    BDT_tempCalculoBoletinEstudiantes::Insert($datosParaInsertar, BD_ADMIN);
                } catch (Exception $e) {
                    $mensaje = $mensaje."<br><br>".$e->getMessage();
                    $finalizado = false;
                }

                $numEstudiantes ++;

            }

            $datos = [
                "id"      => $resultadoJobs['job_id'],
                "estado"  => JOBS_ESTADO_PROCESADO,
            ];

            SysJobs::actualizar($datos);

        } else {
            if ($intento >= 3) {
                $mensaje = "<a target=\"_blank\" href=\"../docente/calificaciones-faltantes.php?
                carga=".base64_encode($carga)."
                &periodo=".base64_encode($periodo)."
                &get=".base64_encode(100)."\">
                    El informe no se pudo generar, coloque las notas a todos los estudiantes y vuelva a intentarlo.
                </a>
                ";

                SysJobs::actualizarMensaje(
                    $resultadoJobs['job_id'], 
                    $intento, 
                    $mensaje, 
                    JOBS_ESTADO_FINALIZADO
                );

                SysJobs::enviarMensaje(
                    $resultadoJobs['job_responsable'],
                    $mensaje.$listadoEstudiantesError,
                    $resultadoJobs['job_id'],
                    JOBS_TIPO_GENERAR_INFORMES,
                    JOBS_ESTADO_ERROR, 
                    $informacionAdicional
                );
            } else {
                $texto = "";

                if ($erroresNumero > 1) {
                    $texto = $erroresNumero."  estudiantes que les";
                } else {
                    $texto = $erroresNumero."  estudiante que le";
                }

                $mensaje = "<a target=\"_blank\" href=\"../docente/calificaciones-faltantes.php?carga=".base64_encode($carga)."&periodo=".base64_encode($periodo)."&get=".base64_encode(100)."\"> El informe no se ha podido generar porque hay ".$texto." faltan notas.</a>";

                SysJobs::actualizarMensaje(
                    $resultadoJobs['job_id'],
                    $intento,
                    $mensaje,
                    JOBS_ESTADO_PENDIENTE
                );
            }
        }

    }

    BindSQL::finalizarTransacion();
} catch (Exception $e) {

    echo $e->getMessage();

    //Utilidades::logError($e);

    $datos = [
        "id"      => $resultadoJobs['job_id'],
        "estado"  => JOBS_ESTADO_ERROR,
        "mensaje" => $e->getMessage(),
    ];

    SysJobs::actualizar($datos);

    BindSQL::revertirTransacion();
}

function minutosTranscurridos($fecha_i, $fecha_f)
{
    $intervalo = $fecha_i->diff($fecha_f);
    $minutos   = $intervalo->i;
    $segundos  = $intervalo->s;

    return " Finaliz&oacute; en: <i> {$minutos} min y {$segundos} seg.</i>";
}