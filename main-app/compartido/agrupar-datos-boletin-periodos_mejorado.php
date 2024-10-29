<?php

// se reparte la informacion en arrays
$conteoEstudiante             = 0;
$contarAreas                  = 0;
$contarCargas                 = 0;
$contarIndicadores            = 0;
$areasPerdidas                = 0;
$materiasPerdidas             = 0;
$indicadoresPerdidos          = 0; 
$mat_id                       = "";
$mat_ar                       = "";
$mat_ar_car                   = "";
$mat_ar_car_periodo           = "";
$mat_ar_car_periodo_indicador = "";
$directorGrupo                = "";
$observacionesConvivencia     = [];
$nivelaciones                 = [];
$estudiantes                  = [];
$porcPeriodo = array(0.25, 0.25, 0.25, 0.25);
foreach ($listaDatos  as $registro) {

    Utilidades::valordefecto($registro["ind_id"]);
    Utilidades::valordefecto($registro["ind_nombre"]);
    Utilidades::valordefecto($registro["rind_nota"]);
    Utilidades::valordefecto($registro["indicador_porcentual"],0);
    Utilidades::valordefecto($registro["valor_indicador"],0);
    Utilidades::valordefecto($registro["valor_porcentaje_indicador"],0);



    // Datos del estudiante
    if ($mat_id != $registro["mat_id"]) {
        $contarAreas         = 0;
        $contarCargas        = 0;
        $contarIndicadores   = 0;
        $periodos            = 0;
        $areasPerdidas       = 0;
        $materiasPerdidas    = 0;
        $indicadoresPerdidos = 0;
        $conteoEstudiante++;

        $nombre = Estudiantes::NombreCompletoDelEstudiante($registro);
        $estudiantes[$registro["mat_id"]] = [
            "mat_id"               => $registro["mat_id"],
            "nombre"               => $nombre,
            "mat_documento"        => $registro["mat_documento"],
            "nro"                  => $conteoEstudiante,
            "mat_matricula"        => $registro["mat_matricula"],
            "gra_id"               => $registro["mat_grado"],
            "gra_nombre"           => $registro["gra_nombre"],
            "gru_id"               => $registro["mat_grupo"],
            "gru_nombre"           => $registro["gru_nombre"],
            "mat_estado_matricula" => $registro["mat_estado_matricula"],
            "mat_numero_matricula" => $registro["mat_numero_matricula"],
            "mat_folio"            => $registro["mat_folio"],
            "periodo_selecionado"  => $periodos,
            "observaciones"        => [],
            "areas"                => []

        ];
        $mat_id = $registro["mat_id"];
    }
    // Datos de las areas
    if ($mat_ar != $mat_id . '-' . $registro["ar_id"]) {
        $notaAre           = 0;
        $notaAreaAcumulada = 0; // lleva la nota acumulada de las Areas por los periodos  
        $contarNotasAreas  = 1;
        $contarFallasArea  = 0;
        $contarAreas++;

        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']] = [
            "ar_id"                  => $registro['ar_id'],
            "nro"                    => $contarAreas,
            "ar_nombre"              => $registro['ar_nombre'],
            "nota_area_acumulada"    => 0,
            "cantidad_notas"         => 0,
            "fallas"                 => 0,
            "maneja_porcetaje"       => false,           
            "cargas"                 => []
        ];
        $mat_ar = $mat_id . '-' . $registro["ar_id"];
    }
    // Datos de las cargas
    if ($mat_ar_car !=  $mat_ar . '-' . $registro["car_id"]) {        
        $notaCargaAcumulada = 0; // lleva la nota acumulada de las cargas por los periodos         
        $contarNotasCarga   = 1;
        $contarFallasCarga  = 0;
        $contarIndicadores  = 0;
        $contarCargas++;
        if ($registro["car_director_grupo"] == 1) {
            $directorGrupo = $registro;
        }
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']] = [
            "car_id"                    => $registro['car_id'],
            "car_ih"                    => $registro['car_ih'],
            "ar_id"                     => $registro['ar_id'],
            "nro"                       => $contarCargas,
            "id_materia"                => $registro['id_materia'],
            "mat_nombre"                => $registro['mat_nombre'],
            "mat_valor"                 => $registro['mat_valor'],
            "docente"                   => $registro,
            "director_grupo"            => $registro["car_director_grupo"],
            "nota_carga_acumulada"      => 0,
            "cantidad_notas"            => 0,
            "carga_acumulada"          => 0,
            "fallas"                    => 0,
            "periodos"                  => []
        ];

        $mat_ar_car =   $mat_ar. '-' . $registro["car_id"];
    }
    // Datos de los periodos
    if ($mat_ar_car_periodo != $mat_ar_car . '-' . $registro["bol_periodo"]) {        
        $notaIndicadorAcumulado = 0; // lleva el conteo de las notas de los indicadores 
        $porcentaje             = $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["mat_valor"];
        $porcentaje             = empty($porcentaje) ? 100 : $porcentaje;

        if ($porcentaje != 100) {
            $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["maneja_porcetaje"] = true;  
        }        

        $registro['aus_ausencias'] = empty($registro['aus_ausencias'])?0:$registro['aus_ausencias'];
        
        $notaAre            += ($registro['bol_nota'] * $porcentaje)/100;
        $notaAreaAcumulada  +=  $registro['bol_nota'] ;
        $contarFallasArea   +=  $registro['aus_ausencias'];

        
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["nota_area_acumulada"] = $notaAreaAcumulada;
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cantidad_notas"]      = $contarNotasAreas++;
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["fallas"]              = $contarFallasArea;
       

       
        $notaCargaAcumulada +=  $registro['bol_nota'];
        $contarFallasCarga  =  $contarFallasCarga + $registro['aus_ausencias'];
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["nota_carga_acumulada"] = $notaCargaAcumulada;
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["cantidad_notas"]       = $contarNotasCarga++;
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["carga_acumulada"]     = $registro['promedio_acumulado'];

        // nota para el area
        if (empty($estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["periodos"][$registro["bol_periodo"]]["nota_area"])){
            $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["periodos"][$registro["bol_periodo"]]["nota_area"]=0;
        }
        if (empty($estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["periodos"][$registro["bol_periodo"]]["nota_area"])){
            $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["periodos"][$registro["bol_periodo"]]["ausencia_area"]=0;
        }
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["periodos"][$registro["bol_periodo"]] = [
            "periodo"               =>  $registro["bol_periodo"],
            "nota_area"             =>  $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["periodos"][$registro["bol_periodo"]]["nota_area"]+($registro['bol_nota'] * $porcentaje)/100,  
            "ausencia_area"         =>  $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["periodos"][$registro["bol_periodo"]]["ausencia_area"]+$registro['aus_ausencias']
        ];

        // nota para la carga
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["periodos"][$registro["bol_periodo"]] = [
            "bol_periodo"               => $registro["bol_periodo"],
            "bol_nota"                  => $registro['bol_nota'],
            "bol_tipo"                  => $registro['bol_tipo'], 
            "bol_nota_anterior"         => $registro['bol_nota_anterior'],            
            "bol_observaciones_boletin" => $registro['bol_observaciones_boletin'],
            "aus_ausencias"             => $registro['aus_ausencias'],
            "nota_indicadores"          => 0,
            "indicadores"               => []
        ];
        $estudiantes[$registro["mat_id"]]["periodo_selecionado"]=count($estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["periodos"]);

        $mat_ar_car_periodo = $mat_ar_car . '-' . $registro["bol_periodo"];
    }
    // Datos de los Indicadores por periodo
    if ($mat_ar_car_periodo_indicador != $mat_ar_car_periodo.'-'.$registro["ind_id"]) {
        $indicadorRecuperado=false;
        $contarIndicadores++;
        $notaIndicador = empty($registro['valor_indicador']) ? 0 : $registro['valor_indicador'];
        $notaIndicador_recuperacion = empty($registro['rind_nota']) ? 0 : $registro['rind_nota'];

        if ($notaIndicador_recuperacion > $notaIndicador) {
            $notaIndicador = $notaIndicador_recuperacion;
            $indicadorRecuperado=true;
        }
        $notaIndicadorAcumulado += $notaIndicador * ($registro['valor_porcentaje_indicador'] / 100);
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["periodos"][$registro["bol_periodo"]]['nota_indicadores'] = $notaIndicadorAcumulado;
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["periodos"][$registro["bol_periodo"]]['indicadores'][$registro["ind_id"]] = [
            "ind_id"                      => $registro["ind_id"],
            "nro"                         => $contarIndicadores,
            "ind_nombre"                  => $registro['ind_nombre'],
            "valor_indicador"             => $registro['valor_indicador'],
            "valor_indicador_recuperado"  => $registro['rind_nota'],            
            "valor_porcentaje_indicador"  => $registro['valor_porcentaje_indicador'],
            "nota_final"                  => $notaIndicador,
            "recuperado"                  => $indicadorRecuperado,
            "indicador_porcentual"        => $registro['indicador_porcentual']
        ];
        $mat_ar_car_periodo_indicador = $mat_ar_car_periodo.'-'.$registro["ind_id"];
    }

        // Observacion por estudainte
        if (!empty($registro["dn_id"]) && !empty($registro["dn_observacion"])) {
            $observacionesConvivencia[$registro["mat_id"]][$registro["dn_periodo"]] = [
                "id"          => $registro["dn_id"],
                "estudiante"  => $registro["dn_cod_estudiante"],
                "observacion" => $registro["dn_observacion"],
                "periodo"     => $registro["dn_periodo"]
            ];
            $estudiantes[$registro["mat_id"]]["observaciones"][$registro["dn_periodo"]]= [
                "id"          => $registro["dn_id"],
                "estudiante"  => $registro["dn_cod_estudiante"],
                "observacion" => $registro["dn_observacion"],
                "periodo"     => $registro["dn_periodo"]
            ];
        }
    
        if (!empty($registro["niv_id"]) && !empty($registro["niv_definitiva"])) {
            $nivelaciones[$registro["mat_id"]][$registro["niv_id_asg"]] = [
                "id"          => $registro["niv_id"],
                "estudiante"  => $registro["niv_cod_estudiante"],
                "definitiva"  => $registro["niv_definitiva"],
                "fecha"       => $registro["niv_fecha_nivelacion"]
            ];
            $estudiantes[$registro["mat_id"]]["nivelaciones"][$registro["niv_id_asg"]]= [
                "id"          => $registro["niv_id"],
                "estudiante"  => $registro["niv_cod_estudiante"],
                "definitiva"  => $registro["niv_definitiva"],
                "fecha"       => $registro["niv_fecha_nivelacion"]
            ];
        }
}
