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
$areas                    = [];
$cargas                   = [];
$notasPeriodos            = [];
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
        $contarAreas = 0;
        $contarCargas = 0;

        $conteoEstudiante++;
        $nombre = Estudiantes::NombreCompletoDelEstudiante($registro);
        $estudiantes[$registro["mat_id"]] = [
            "mat_id"        => $registro["mat_id"],
            "nombre"        => $nombre,
            "mat_documento" => $registro["mat_documento"],
            "nro"           => $conteoEstudiante,
            "mat_matricula" => $registro["mat_matricula"],
            "gra_nombre"    => $registro["gra_nombre"],
            "gru_nombre"    => $registro["gru_nombre"],
        ];
        $mat_id = $registro["mat_id"];
    }
    // Datos de las areas
    if ($mat_ar != $registro["mat_id"] . '-' . $registro["ar_id"]) {
        $contarAreas++;
        $areas[$registro["mat_id"]][$registro["ar_id"]] = [
            "ar_id"        => $registro['ar_id'],
            "nro"          => $contarAreas,
            "ar_nombre"    => $registro['ar_nombre']
        ];
        $mat_ar = $registro["mat_id"] . '-' . $registro["ar_id"];
    }
    // Datos de las cargas
    if ($mat_ar_car != $registro["mat_id"] . '-' . $registro["ar_id"] . '-' . $registro["car_id"]) {
        $contarCargas++;
        if ($registro["car_director_grupo"] == 1) {
            $directorGrupo = $registro;
        }
        $cargas[$registro["mat_id"]][$registro["ar_id"]][$registro['car_id']] = [
            "car_id"                    => $registro['car_id'],
            "nro"                       => $contarCargas,
            "mat_nombre"                => $registro['mat_nombre'],
            "mat_valor"                 => $registro['mat_valor'],
            "docente"                   => $registro,
            "bol_periodo"               => $registro["bol_periodo"],
            "bol_nota"                  => $registro['bol_nota'],
            "bol_tipo"                  => $registro['bol_tipo'],
            "bol_observaciones_boletin" => $registro['bol_observaciones_boletin'],
            "car_ih"                    => $registro['car_ih'],
        ];
        $mat_ar_car =  $registro["mat_id"] . '-' . $registro["ar_id"] . '-' . $registro["car_id"];
    }
    // Datos de los periodos
    if ($mat_ar_car_periodo != $registro["mat_id"] . '-' . $registro["ar_id"] . '-' . $registro["car_id"] . '-' . $registro["bol_periodo"]) {
        $notasPeriodos[$registro["mat_id"]][$registro["ar_id"]][$registro['car_id']][$registro["bol_periodo"]] = [
            "car_id"                    => $registro['car_id'],
            "bol_periodo"               => $registro["bol_periodo"],
            "bol_nota"                  => $registro['bol_nota'],
            "bol_tipo"                  => $registro['bol_tipo'],
            "bol_observaciones_boletin" => $registro['bol_observaciones_boletin'],
            "aus_ausencias"             => $registro['aus_ausencias']
            
        ];
        $mat_ar_car_periodo =  $registro["mat_id"] . '-' . $registro["ar_id"] . '-' . $registro["car_id"] . '-' . $registro["bol_periodo"];
    }
}