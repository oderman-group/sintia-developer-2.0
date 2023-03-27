<?php

class Boletin {

    public static function listarTiposUsuarios()
    {

        

    }

    public static function listarTipoDeNotas($categoria, string $BD    = ''){
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM $BD.academico_notas_tipos 
            WHERE notip_categoria='".$categoria."'");
            
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function agregarDecimales($nota){
        
    
        if(strlen($nota) === 1 || $nota == 10){
            $nota = $nota.".00";
        }

        $explode = explode(".", $nota);
        $decimales = end($explode);
        if(!empty($decimales) && strlen($decimales) === 1){
            $nota = $nota."0";
        }

        return $nota;
    }

    public static function obtenerDatosTipoDeNotas($categoria, $nota, string $BD    = ''){
        global $conexion;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM $BD.academico_notas_tipos 
            WHERE notip_categoria='".$categoria."' AND '".$nota."'>=notip_desde AND '".$nota."'<=notip_hasta");
            
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerPuestoYpromedioEstudiante(
        int    $periodo      = 0,
        int    $grado      = 0,
        int    $grupo      = 0,
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT mat_id, bol_estudiante, bol_carga, mat_nombres, mat_grado, bol_periodo, avg(bol_nota) as prom, ROW_NUMBER() OVER(ORDER BY prom desc) as puesto FROM $BD.academico_matriculas
            INNER JOIN $BD.academico_boletin ON bol_estudiante=mat_id AND bol_periodo='".$periodo."'
            WHERE  mat_grado='".$grado."' AND mat_grupo='".$grupo."'
            GROUP BY mat_id 
            ORDER BY prom DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerAreasDelEstudiante(
        int    $grado      = 0,
        int    $grupo      = 0,
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT ar_id, car_ih FROM $BD.academico_cargas ac
            INNER JOIN $BD.academico_materias am ON am.mat_id=ac.car_materia
            INNER JOIN $BD.academico_areas ar ON ar.ar_id= am.mat_area
            WHERE  car_curso=".$grado." AND car_grupo=".$grupo." GROUP BY ar.ar_id ORDER BY ar.ar_posicion ASC;");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDatosDelArea(
        int    $estudiante      = 0,
        int    $area      = 0,
        string    $condicion      = '',
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre FROM $BD.academico_materias am
            INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
            INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
            INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
            WHERE bol_estudiante='" . $estudiante . "' and a.ar_id=" . $area . " and bol_periodo in (" . $condicion . ")
            GROUP BY ar_id;");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerPromedioPorTodosLosPeriodos(
        int    $estudiante      = 0,
        int    $periodo      = 0,
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT ROUND(AVG(bol_nota), 2) as promedio FROM $BD.academico_boletin 
            WHERE bol_estudiante='" . $estudiante . "' AND bol_periodo='" . $periodo . "'");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerNivelaciones(
        $carga,
        $estudiante,
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM $BD.academico_nivelaciones WHERE niv_id_asg='" . $carga . "' AND niv_cod_estudiante='" . $estudiante . "'");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerNotaDisciplina(
        $estudiante,
        $condicion,
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM $BD.disiplina_nota WHERE dn_cod_estudiante='" . $estudiante . "' AND dn_periodo in(" . $condicion . ");");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerRecuperacionPorIndicador(
        int    $estudiante      = 0,
        int    $carga      = 0,
        int    $periodo      = 0,
        int    $indicador      = 0,
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM $BD.academico_indicadores_recuperacion WHERE rind_estudiante='" . $estudiante . "' AND rind_carga='" . $carga . "' AND rind_periodo='" . $periodo . "' AND rind_indicador='" . $indicador . "'");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerObservaciones(
        int    $carga      = 0,
        int    $periodo      = 0,
        int    $estudiante      = 0,
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM $BD.academico_boletin WHERE bol_carga='" . $carga . "' AND bol_periodo='" . $periodo . "' AND bol_estudiante='" . $estudiante . "'");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDefinitivaYnombrePorMateria(
        int    $estudiante      = 0,
        int    $area      = 0,
        string    $condicion      = '',
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre,mat_nombre,mat_valor,mat_id,car_id,car_ih,car_director_grupo FROM $BD.academico_materias am
            INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
            INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
            INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
            WHERE bol_estudiante='" . $estudiante . "' and a.ar_id=" . $area . " and bol_periodo in (" . $condicion . ")
            GROUP BY mat_id
            ORDER BY mat_id;");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDefinitivaPorPeriodo(
        int    $estudiante      = 0,
        int    $area      = 0,
        string    $condicion      = '',
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT bol_nota,bol_periodo,ar_nombre,mat_nombre,mat_id FROM $BD.academico_materias am
            INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
            INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
            INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
            WHERE bol_estudiante='" . $estudiante . "' and a.ar_id=" . $area . " and bol_periodo in (" . $condicion . ")
            ORDER BY mat_id,bol_periodo
            ;");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerIndicadoresPorMateria(
        int    $grado      = 0,
        int    $grupo      = 0,
        int    $area      = 0,
        string    $condicion      = '',
        int    $estudiante      = 0,
        string    $condicion2      = '',
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT mat_nombre,mat_area,mat_id,ind_nombre,ipc_periodo,
            ROUND(SUM(cal_nota*(act_valor/100)) / SUM(act_valor/100),2) as nota, ind_id FROM $BD.academico_materias am
            INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
            INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
            INNER JOIN $BD.academico_indicadores_carga aic ON aic.ipc_carga=ac.car_id
            INNER JOIN $BD.academico_indicadores ai ON aic.ipc_indicador=ai.ind_id
            INNER JOIN $BD.academico_actividades aa ON aa.act_id_tipo=aic.ipc_indicador AND act_id_carga=car_id AND act_estado=1 AND act_registrada=1
            INNER JOIN $BD.academico_calificaciones aac ON aac.cal_id_actividad=aa.act_id
            WHERE car_curso=" . $grado . "  and car_grupo=" . $grupo . " and mat_area=" . $area . " AND ipc_periodo in (" . $condicion . ") AND cal_id_estudiante='" . $estudiante . "' and act_periodo=" . $condicion2 . "
            group by act_id_tipo, act_id_carga
            order by mat_id,ipc_periodo,ind_id;");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDatosAusencias(
        int    $grado      = 0,
        int    $materia      = 0,
        int    $periodo      = 0,
        int    $estudiante      = 0,
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT sum(aus_ausencias) as sumAus FROM $BD.academico_ausencias
            INNER JOIN $BD.academico_cargas ON car_curso='".$grado."' AND car_materia='".$materia."'
            INNER JOIN $BD.academico_clases ON cls_id=aus_id_clase AND cls_id_carga=car_id AND cls_periodo='".$periodo."'
            WHERE aus_id_estudiante='".$estudiante."'");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerIndicadoresDeMateriaPorPeriodo(
        int    $grado      = 0,
        int    $grupo      = 0,
        int    $area      = 0,
        int    $periodo      = 0,
        int    $estudiante      = 0,
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT mat_nombre,mat_area,mat_id,ind_nombre,ipc_periodo,
            ROUND(SUM(cal_nota*(act_valor/100)) / SUM(act_valor/100),2) as nota, ind_id FROM $BD.academico_materias am
            INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
            INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
            INNER JOIN $BD.academico_indicadores_carga aic ON aic.ipc_carga=ac.car_id
            INNER JOIN $BD.academico_indicadores ai ON aic.ipc_indicador=ai.ind_id
            INNER JOIN $BD.academico_actividades aa ON aa.act_id_tipo=aic.ipc_indicador AND act_id_carga=car_id AND act_estado=1 AND act_registrada=1
            INNER JOIN $BD.academico_calificaciones aac ON aac.cal_id_actividad=aa.act_id
            WHERE car_curso=" . $grado . "  and car_grupo=" . $grupo . " and mat_area=" . $area . " AND ipc_periodo= " . $periodo . " AND cal_id_estudiante='" . $estudiante . "' and act_periodo=" . $periodo . "
            group by act_id_tipo, act_id_carga
            order by mat_id,ipc_periodo,ind_id;");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

}