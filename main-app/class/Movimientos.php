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

    /**
     * Este metodo me trae los items de una factura
     * @param mysqli $conexion
     * @param array $config
     * @param string $idTransaction
     * 
     * @return mysqli_result $consulta
    **/
    public static function listarItemsTransaction (
        mysqli $conexion, 
        array $config, 
        string $idTransaction
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT ti.id AS idtx, i.id AS idit, i.name, i.price AS priceItem, ti.price AS priceTransaction, ti.cantity, ti.subtotal, ti.description
            FROM ".BD_FINANCIERA.".transaction_items ti
            INNER JOIN ".BD_FINANCIERA.".items i ON i.id = ti.id_item
            WHERE ti.id_transaction = '{$idTransaction}'
            AND ti.type_transaction = 'INVOICE'
            AND ti.institucion = {$config['conf_id_institucion']}
            AND ti.year = {$_SESSION["bd"]}
            ORDER BY id_autoincremental");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me trae todos los items
     * @param mysqli $conexion
     * @param array $config
     * 
     * @return mysqli_result $consulta
    **/
    public static function listarItems (
        mysqli $conexion, 
        array $config
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".items WHERE status=0 AND institucion = {$config['conf_id_institucion']} AND year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me guarda un item
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * 
     * @return string $codigo
    **/
    public static function guardarItems (
        mysqli $conexion, 
        array $config, 
        array $POST
    )
    {

        $codigo=Utilidades::generateCode("IT");
        try {
            mysqli_query($conexion, "INSERT INTO ".BD_FINANCIERA.".items (id, name, price, tax, description, institucion, year)VALUES('".$codigo."', '".$POST["nombre"]."', ".$POST["precio"].", '".$POST["iva"]."', '".$POST["descrip"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $codigo;
    }

    /**
     * Este metodo me trae la informacion de un item
     * @param mysqli $conexion
     * @param array $config
     * @param string $idItem
     * 
     * @return array $resultado
    **/
    public static function traerDatosItems (
        mysqli $conexion, 
        array $config,
        string $idItem
    )
    {
        $resultado = [];
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".items WHERE id='{$idItem}' AND institucion = {$config['conf_id_institucion']} AND year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me actualiza un item
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
    **/
    public static function actualizarItems (
        mysqli $conexion, 
        array $config, 
        array $POST
    )
    {

        try {
            mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".items SET name='".$POST["nombre"]."', price=".$POST["precio"].", tax='".$POST["iva"]."', description='".$POST["descrip"]."' WHERE id='".$POST["id"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me actualiza un item
     * @param mysqli $conexion
     * @param array $config
     * @param string $idItem
    **/
    public static function eliminarItems (
        mysqli $conexion, 
        array $config, 
        string $idItem
    )
    {

        try {
            mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".items SET status=1 WHERE id='{$idItem}' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me consulta si existe un item en la tabla de relación con las transacciones
     * @param mysqli $conexion
     * @param array $config
     * @param string $idItem
     * 
     * @return int $num
    **/
    public static function validarExistenciaItemsEnTransaction (
        mysqli $conexion, 
        array $config, 
        string $idItem
    )
    {

        try {
            $consulta = mysqli_query($conexion, "SELECT id_item FROM ".BD_FINANCIERA.".transaction_items WHERE id_item='{$idItem}' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $num = mysqli_num_rows($consulta);

        return $num;
    }
}