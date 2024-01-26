<?php
    $_SERVER['DOCUMENT_ROOT'] = dirname(dirname(dirname(dirname(__FILE__))));
    require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
    require_once(ROOT_PATH."/main-app/class/Movimientos.php");

    date_default_timezone_set("America/New_York");
    $diaActual = date('d');
    $mesActual = date('m');
    $yearActual = date('Y');
    $fechaActual = date('Y-m-d');
    $diasMes = cal_days_in_month(CAL_GREGORIAN, $mesActual, $yearActual);

    $conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion);

    $consultaJobs = Movimientos::listarRecurrentesJobs($conexion);
    while($resultadoJobs = mysqli_fetch_array($consultaJobs, MYSQLI_BOTH)){

        $diasEnMes = explode(",", $resultadoJobs['days_in_month']);
        $indiceDiaActual = array_search($diaActual, $diasEnMes);

        if ($indiceDiaActual !== false && (empty($resultadoJobs['next_generation_date']) || $resultadoJobs['next_generation_date'] == "0000-00-00")) {

            Movimientos::generarRecurrentes($conexion, $resultadoJobs);

        } elseif ($resultadoJobs['next_generation_date'] == $fechaActual){

            Movimientos::generarRecurrentes($conexion, $resultadoJobs);

        }
        
        if ($diaActual == $diasMes) {
            foreach ($diasEnMes as $dia) {
                if ($dia > $diaActual) {
                    Movimientos::generarRecurrentes($conexion, $resultadoJobs);
                }
            }
        }
        
        $totalDias = count($diasEnMes);
        // Calcular el índice del siguiente día
        $indiceSiguienteDia = ($indiceDiaActual + 1) % $totalDias;
        // Obtener el siguiente día
        $diaSiguiente = $diasEnMes[$indiceSiguienteDia];
        
        $mesSiguiente = $mesActual;
        if ($indiceDiaActual == $totalDias){
            $mesSiguiente = $mesActual+$resultadoJobs['frequency'];
        }

        $proximaFecha = $yearActual."-".$mesSiguiente."-".$diaSiguiente;

        try {
            mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".recurring_invoices SET next_generation_date='".$proximaFecha."' WHERE id_order='".$resultadoJobs['id_order']."'");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

    }

    exit();