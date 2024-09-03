<?php
require_once 'BDT_tablas.php';

class BDT_AcademicoAsignacionesEstudiantes extends BDT_Tablas {

    public const TIPO_TAREA = 'TAREA';

    public static $tableName = 'academico_asignaciones_estudiantes';

    public static function mostrarActividadesEstudiante(string $idEstudiante, string $idActividad): bool
    {
        $conexionPDO = Conexion::newConnection('PDO');
        $sql = "SELECT 
            COUNT(*) AS cantActividad,
            SUM(CASE 
            WHEN asgest_id_estudiante = '".$idEstudiante."' THEN 1 
            ELSE 0 
            END) AS cantEstudiante
            FROM ".BD_ACADEMICA.".".self::$tableName."
            WHERE asgest_tipo = '".self::TIPO_TAREA."'
            AND asgest_id_asignacion = '".$idActividad."'
        ";

        try {
            $stmt = $conexionPDO->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['cantActividad'] > 0 && $result['cantEstudiante'] == 0 ? false : true;
        } catch (PDOException $e) {
            $conexionPDO = null;
            return true;
        }
    }

}