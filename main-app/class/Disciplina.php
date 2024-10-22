<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/BindSQL.php");

class Disciplina {

    /**
     * Esta función lista la nota de comportamieno de los estudiantes según varios parámetros.
     *
     * @param string $filtroAdicional - Filtros adicionales para la consulta SQL.
     * @param string $filtroLimite - Límite de resultados para la consulta SQL.
     * @param string $valueIlike - Valor String que se utilizara para biuscar por cualuqier parametro definido (puede ser nulo).
     * @param array $selectConsulta - valores de los select que se van a nececitar para las consultas
     * 
     * @return mysqli_result - Un array con los resultados de la consulta.
     */
    public static function listarComportamiento(
        string $filtroAdicional = '', 
        string $filtroLimite    = 'LIMIT 0, 20',
        $valueIlike=null,
        array $selectConsulta=[]
    )
    {
        global $config;
        
        $stringSelect="*";
        if (!empty($selectConsulta)) {
            $stringSelect=implode(", ", $selectConsulta);
        };

        $resultado = [];
        if(!empty($valueIlike)){
            $busqueda=$valueIlike;
            $filtroAdicional .= " AND (
                mat.mat_id LIKE '%".$busqueda."%' 
                OR mat.mat_nombres LIKE '%".$busqueda."%' 
                OR mat.mat_nombre2 LIKE '%".$busqueda."%' 
                OR mat.mat_primer_apellido LIKE '%".$busqueda."%' 
                OR mat.mat_segundo_apellido LIKE '%".$busqueda."%' 
                OR mat.mat_documento LIKE '%".$busqueda."%' 
                OR mat.mat_email LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_primer_apellido), ' ', TRIM(mat.mat_segundo_apellido), ' ', TRIM(mat.mat_nombres)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_primer_apellido), TRIM(mat.mat_segundo_apellido), TRIM(mat.mat_nombres)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_primer_apellido), ' ', TRIM(mat.mat_nombres)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_primer_apellido), TRIM(mat.mat_nombres)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_nombres), ' ', TRIM(mat.mat_primer_apellido)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_nombres), '', TRIM(mat.mat_primer_apellido)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_primer_apellido), '', TRIM(mat.mat_nombres)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_nombres), ' ', TRIM(mat.mat_nombre2)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_nombres), ' ', TRIM(mat.mat_segundo_apellido)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_nombre2), ' ', TRIM(mat.mat_nombres)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_segundo_apellido), ' ', TRIM(mat.mat_nombres)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_segundo_apellido), ' ', TRIM(mat.mat_nombre2)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_segundo_apellido), ' ', TRIM(mat.mat_primer_apellido)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat.mat_nombre2), ' ', TRIM(mat.mat_segundo_apellido)) LIKE '%".$busqueda."%'
                OR gra.gra_nombre LIKE '%".$busqueda."%'
                OR mate.mat_nombre LIKE '%".$busqueda."%'
                OR uss.uss_usuario LIKE '%".$busqueda."%'
                OR uss.uss_nombre LIKE '%".$busqueda."%' 
                OR uss.uss_nombre2 LIKE '%".$busqueda."%' 
                OR uss.uss_apellido1 LIKE '%".$busqueda."%' 
                OR uss.uss_apellido2 LIKE '%".$busqueda."%' 
                OR uss.uss_documento LIKE '%".$busqueda."%' 
                OR CONCAT(TRIM(uss.uss_apellido1), ' ', TRIM(uss.uss_apellido2), ' ', TRIM(uss.uss_nombre)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_apellido1), TRIM(uss.uss_apellido2), TRIM(uss.uss_nombre)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_apellido1), ' ', TRIM(uss.uss_nombre)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_apellido1), TRIM(uss.uss_nombre)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_nombre), ' ', TRIM(uss.uss_apellido1)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_nombre), '', TRIM(uss.uss_apellido1)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_apellido1), '', TRIM(uss.uss_nombre)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_nombre), ' ', TRIM(uss.uss_nombre2)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_nombre), ' ', TRIM(uss.uss_apellido2)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_nombre2), ' ', TRIM(uss.uss_nombre)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_apellido2), ' ', TRIM(uss.uss_nombre)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_apellido2), ' ', TRIM(uss.uss_nombre2)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_apellido2), ' ', TRIM(uss.uss_apellido1)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(uss.uss_nombre2), ' ', TRIM(uss.uss_apellido2)) LIKE '%".$busqueda."%'
            )";
        }
        try {
            $sql = "SELECT 
                    $stringSelect 
                    FROM ".BD_DISCIPLINA.".disiplina_nota disc

                    LEFT JOIN ".BD_ACADEMICA.".academico_matriculas mat 
                    ON mat.mat_id           = disc.dn_cod_estudiante 
                    AND mat.institucion     = disc.institucion 
                    AND mat.year            = disc.year

                    LEFT JOIN ".BD_ACADEMICA.".academico_cargas car 
                    ON car_id               = disc.dn_id_carga 
                    AND car.institucion     = disc.institucion 
                    AND car.year            = disc.year
                    
                    LEFT JOIN ".BD_GENERAL.".usuarios uss 
                    ON uss_id               = car.car_docente 
                    AND uss.institucion     = disc.institucion 
                    AND uss.year            = disc.year

                    LEFT JOIN ".BD_ACADEMICA.".academico_grados gra 
                    ON gra_id               = car.car_curso 
                    AND gra.institucion     = disc.institucion 
                    AND gra.year            = disc.year

                    LEFT JOIN ".BD_ACADEMICA.".academico_grupos gru 
                    ON gru.gru_id           = car.car_grupo 
                    AND gru.institucion     = disc.institucion 
                    AND gru.year            = disc.year

                    LEFT JOIN ".BD_ACADEMICA.".academico_materias mate 
                    ON mate.mat_id          = car.car_materia 
                    AND mate.institucion    = disc.institucion 
                    AND mate.year           = disc.year
                    
                    WHERE disc.institucion  = ? 
                    AND disc.year           = ?
                    AND (dn_observacion IS NOT NULL OR dn_nota IS NOT NULL OR dn_observacion != '' OR dn_nota != '')
                    
                    {$filtroAdicional}
                    
                    ORDER BY 
                        dn_periodo DESC, 
                        mat.mat_grado ASC, 
                        mat.mat_grupo ASC, 
                        mat.mat_primer_apellido ASC, 
                        mat.mat_segundo_apellido ASC, 
                        mat.mat_nombres ASC

                    {$filtroLimite}";
    
            $parametros = [$config['conf_id_institucion'], $_SESSION["bd"]];
            
            $resultado = BindSQL::prepararSQL($sql, $parametros);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Este metodo actualiza el periodo en una observacion de comportamiento
     * 
     * @param array     $config 
     * @param int       $idNuevo
     * @param int       $periodo
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function actualizarPeriodoComportamiento($config, $idNuevo, $periodo){

        $sql = "UPDATE ".BD_DISCIPLINA.".disiplina_nota SET dn_periodo=? WHERE id_nuevo=? AND institucion=? AND year=?";
        $parametros = [$periodo, $idNuevo, $config['conf_id_institucion'], $_SESSION["bd"]];
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Este metodo actualiza el periodo en una observacion de comportamiento
     * 
     * @param array     $config 
     * @param int       $idNuevo
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function eliminarComportamiento($config, $idNuevo){

        $sql = "UPDATE ".BD_DISCIPLINA.".disiplina_nota SET dn_observacion=NULL, dn_nota=NULL WHERE id_nuevo=? AND institucion=? AND year=?";
        $parametros = [$idNuevo, $config['conf_id_institucion'], $_SESSION["bd"]];
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

}