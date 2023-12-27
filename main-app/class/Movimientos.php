<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class Movimientos {

    /**
     * Este metodo me calcula el total neto de un Movimiento
     * @param mysqli $conexion
     * @param array $config
     * @param string $idTransaction
     * @param float $valorAdicional
     * 
     * @return float $totalNeto
    **/
    public static function calcularTotalNeto (
        mysqli $conexion, 
        array $config, 
        string $idTransaction, 
        float $valorAdicional = 0
    )
    {
        $totalNeto = $valorAdicional;

        try {
            $consulta = mysqli_query($conexion,"SELECT SUM(ti.subtotal) AS totalItems FROM ".BD_FINANCIERA.".transaction_items ti
            WHERE ti.id_transaction = '{$idTransaction}'
            AND ti.type_transaction = 'INVOICE'
            AND ti.institucion = {$config['conf_id_institucion']}
            AND ti.year = {$_SESSION["bd"]}
            GROUP BY ti.id_transaction");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        if(mysqli_num_rows($consulta)>0) {
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
            $totalNeto += $resultado['totalItems'];
        }

        return $totalNeto;
    }
}