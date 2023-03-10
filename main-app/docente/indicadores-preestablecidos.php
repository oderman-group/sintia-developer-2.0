<?php
// include("session.php");
// include("verificar-carga.php");

// mysqli_query($conexion, "UPDATE academico_cargas SET car_valor_indicador=1, car_configuracion=1 WHERE car_id='".$cargaConsultaActual."'");

// $consultaSumaIndicadores=mysqli_query($conexion, "SELECT count(*) FROM academico_indicadores_carga WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1");
// $sumaIndicadores = mysqli_fetch_array($consultaSumaIndicadores, MYSQLI_BOTH);

// if ($sumaIndicadores[0]>0) {

//     $consultaIndicadores=mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1");

//     while($indicadores = mysqli_fetch_array($consultaIndicadores, MYSQLI_BOTH)){

//         $actividadesRelacionadasConsulta = mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_tipo='".$indicadores["ipc_indicador"]."' AND act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1");

//         while($actividadesRelacionadasDatos = mysqli_fetch_array($actividadesRelacionadasConsulta, MYSQLI_BOTH)){

//             mysqli_query($conexion, "UPDATE academico_actividades SET act_estado=0, act_fecha_eliminacion=now(), act_motivo_eliminacion='Eliminar indicadores de carga: ".$cargaConsultaActual.", del P: ".$periodoConsultaActual."' WHERE act_id='".$actividadesRelacionadasDatos['act_id']."'");

//         }

//         mysqli_query($conexion, "DELETE FROM academico_indicadores_carga WHERE ipc_id='".$indicadores["ipc_id"]."'");
//     }


// }

// //INSERTAMOS EL INDICADOR COGNITIVO***************************************************************************************************************************************
// mysqli_query($conexion, "INSERT INTO academico_indicadores(ind_nombre, ind_obligatorio) VALUES('Cognitivo', 0)");
// $idCognitivo = mysqli_insert_id($conexion);

// //INSERTAMOS EL INDICADOR PROCEDIMENTAL**************************************************************************************************************************************
// mysqli_query($conexion, "INSERT INTO academico_indicadores(ind_nombre, ind_obligatorio) VALUES('Procedimental', 0)");
// $idProcedimental = mysqli_insert_id($conexion);

// //INSERTAMOS EL INDICADOR ACTITUDINAL*****************************************************************************************************************************************
// mysqli_query($conexion, "INSERT INTO academico_indicadores(ind_nombre, ind_obligatorio) VALUES('Actitudinal', 0)");
// $idActitudinal = mysqli_insert_id($conexion);

// //CREAMOS LA RELACION INDICADOR <--> CARGA**************************************************************************************************************************************
// mysqli_query($conexion, "INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado, ipc_evaluacion) VALUES 
// ('".$cargaConsultaActual."', '".$idCognitivo."', 80, '".$periodoConsultaActual."', 0, 0),
// ('".$cargaConsultaActual."', '".$idProcedimental."', 10, '".$periodoConsultaActual."', 0, 0),
// ('".$cargaConsultaActual."', '".$idActitudinal."', 10, '".$periodoConsultaActual."', 0, 0)");

// //CREAMOS LAS ACTIVIDADES PARA LOS INDICADORES***********************************************************************************************************************************
// mysqli_query($conexion, "INSERT INTO academico_actividades(act_descripcion, act_fecha, act_valor, act_id_tipo, act_id_carga, act_fecha_creacion, act_estado, act_periodo, act_id_evidencia, act_compartir, act_creado)"." VALUES 
// ('Evaluacion Final', now(), 30,'".$idCognitivo."','".$cargaConsultaActual."', now(), 1, '".$periodoConsultaActual."', '0', 0, 0),
// ('Actividad #1', now(), 10,'".$idCognitivo."','".$cargaConsultaActual."', now(), 1, '".$periodoConsultaActual."', '0', 0, 1),
// ('Actividad #2', now(), 10,'".$idCognitivo."','".$cargaConsultaActual."', now(), 1, '".$periodoConsultaActual."', '0', 0, 1),
// ('Actividad #3', now(), 10,'".$idCognitivo."','".$cargaConsultaActual."', now(), 1, '".$periodoConsultaActual."', '0', 0, 1),
// ('Actividad #4', now(), 10,'".$idCognitivo."','".$cargaConsultaActual."', now(), 1, '".$periodoConsultaActual."', '0', 0, 1),
// ('Actividad #5', now(), 10,'".$idCognitivo."','".$cargaConsultaActual."', now(), 1, '".$periodoConsultaActual."', '0', 0, 1),
// ('Nota Procedimental', now(), 10,'".$idProcedimental."','".$cargaConsultaActual."', now(), 1, '".$periodoConsultaActual."', '0', 0, 0),
// ('Nota Actitudinal', now(), 10,'".$idActitudinal."','".$cargaConsultaActual."', now(), 1, '".$periodoConsultaActual."', '0', 0, 0)");

echo '<script type="text/javascript">window.location.href="indicadores.php";</script>';
exit();