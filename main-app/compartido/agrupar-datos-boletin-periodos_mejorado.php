<?php
// se reparte la informacion en arrays
$conteoEstudiante         = 0;
$contarIndicadores        = 0;
$contarAreas              = 0;
$contarCargas             = 0;
$mat_id                   = "";
$mat_ar                   = "";
$mat_ar_car               = "";
$mat_ar_car_periodo       = "";
$directorGrupo            = "";
$observacionesConvivencia = [];
$nivelaciones             = [];
$estudiantes              = [];
foreach ($listaDatos  as $registro) {
    // Observacion por estudainte
    if (!empty($registro["dn_id"]) && !empty($registro["dn_observacion"])) {
        $observacionesConvivencia[$registro["mat_id"]][$registro["dn_periodo"]] = [
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
    }

    // Datos del estudiante
    if ($mat_id != $registro["mat_id"]) {
        $contarAreas     = 0;
        $contarCargas    = 0;
        $periodos        = 0;
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
            "periodos"             => $periodos,
            "areas"                => [],

        ];
        $mat_id = $registro["mat_id"];
    }
    // Datos de las areas
    if ($mat_ar != $registro["mat_id"] . '-' . $registro["ar_id"]) {
        $notaAre          = 0;
        $contarNotasAreas = 1;
        $contarFallasArea = 0;
        $contarAreas++;

        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']] = [
            "ar_id"                  => $registro['ar_id'],
            "nro"                    => $contarAreas,
            "ar_nombre"              => $registro['ar_nombre'],
            "nota_area_acumulada"    => 0,
            "cantidad_notas"         => 0,
            "fallas"                 => 0,
            "equitativa"             => true,
            "cargas"                 => []
        ];
        $mat_ar = $registro["mat_id"] . '-' . $registro["ar_id"];
    }
    // Datos de las cargas
    if ($mat_ar_car != $registro["mat_id"] . '-' . $registro["ar_id"] . '-' . $registro["car_id"]) {
        $notaCarga         = 0;
        $contarNotasCarga  = 1;
        $contarFallasCarga = 0;
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
            "fallas"                    => 0,
            "periodos"                  => []
        ];

        $mat_ar_car =  $registro["mat_id"] . '-' . $registro["ar_id"] . '-' . $registro["car_id"];
    }
    // Datos de los periodos
    if ($mat_ar_car_periodo != $registro["mat_id"] . '-' . $registro["ar_id"] . '-' . $registro["car_id"] . '-' . $registro["bol_periodo"]) {
        $porcentaje = $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["mat_valor"];
        $porcentaje = empty($porcentaje) ? 100 : $porcentaje;

        if ($porcentaje != 100) {
            $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["equitativa"] = false;  
        }        


        $notaAre           = $notaAre + $registro['bol_nota'];
        $contarFallasArea  = $contarFallasArea + $registro['aus_ausencias'];
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["nota_area_acumulada"] = $notaAre;
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cantidad_notas"]      = $contarNotasAreas++;
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["fallas"]              = $contarFallasCarga;


        $notaCarga         = $notaCarga + $registro['bol_nota'];
        $contarFallasCarga = $contarFallasCarga + $registro['aus_ausencias'];
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["nota_carga_acumulada"] = $notaCarga;
        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["cantidad_notas"] = $contarNotasCarga++;

        $estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["periodos"][$registro["bol_periodo"]] = [
            "bol_periodo"               => $registro["bol_periodo"],
            "bol_nota"                  => $registro['bol_nota'],
            "bol_tipo"                  => $registro['bol_tipo'],
            "bol_observaciones_boletin" => $registro['bol_observaciones_boletin'],
            "aus_ausencias"             => $registro['aus_ausencias']
        ];
        $estudiantes[$registro["mat_id"]]["periodos"]=count($estudiantes[$registro["mat_id"]]["areas"][$registro['ar_id']]["cargas"][$registro['car_id']]["periodos"]);

        $mat_ar_car_periodo =  $registro["mat_id"] . '-' . $registro["ar_id"] . '-' . $registro["car_id"] . '-' . $registro["bol_periodo"];
    }
}
