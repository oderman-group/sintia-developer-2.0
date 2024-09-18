<?php
require_once 'BDT_tablas.php';
require_once ROOT_PATH."/main-app/class/Conexion.php";

class BDT_SysJobs extends BDT_Tablas {

    public static $tableName = 'sys_jobs';

    public static $primaryKey = 'job_id';

    public static function updateStautusToPendingWhenLongTimeInProcess(): bool|int {
        $conexionPDO = Conexion::newConnection('PDO');

        $sql = "UPDATE ".BD_ADMIN.".".self::$tableName."
                SET job_estado = '".JOBS_ESTADO_PENDIENTE."', 
                    job_fecha_modificacion = NOW(), 
                    job_mensaje = 'Actualizado a PENDIENTE por demasiado tiempo en proceso'
                WHERE job_estado = '".JOBS_ESTADO_PROCESO."'
                AND TIMESTAMPDIFF(MINUTE, job_fecha_modificacion, NOW()) > 15";

        try {
            $stmt = $conexionPDO->prepare($sql);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException  $e) {
            echo "Excepción capturada: ". $e->getMessage();
            return false;
        }

    }

}