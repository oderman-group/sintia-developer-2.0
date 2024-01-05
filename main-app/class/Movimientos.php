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
     * @param string $tipo
     * 
     * @return mysqli_result $consulta
    **/
    public static function listarItemsTransaction (
        mysqli $conexion, 
        array $config, 
        string $idTransaction, 
        string $tipo = TIPO_FACTURA
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT ti.id AS idtx, i.id AS idit, i.name, i.price AS priceItem, ti.price AS priceTransaction, ti.cantity, ti.subtotal, ti.description
            FROM ".BD_FINANCIERA.".transaction_items ti
            INNER JOIN ".BD_FINANCIERA.".items i ON i.id = ti.id_item
            WHERE ti.id_transaction = '{$idTransaction}'
            AND ti.type_transaction = '{$tipo}'
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

    /**
     * Este metodo me trae todos los abonos
     * @param mysqli $conexion
     * @param array $config
     * 
     * @return mysqli_result $consulta
    **/
    public static function listarAbonos (
        mysqli $conexion, 
        array $config
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".payments pay
            INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=responsible_user AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            WHERE is_deleted=0 AND pay.institucion = {$config['conf_id_institucion']} AND pay.year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me trae las facturas para listar en un select
     * @param mysqli            $conexion
     * @param array             $config
     * @param string            $filtro || OPCIONAL
     * 
     * @return mysqli_result    $consulta
    **/
    public static function listarInvoicedSelect (
        mysqli  $conexion, 
        array   $config, 
        string   $filtro = ""
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".finanzas_cuentas fcu
            INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=fcu_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ADMIN.".localidad_ciudades ON ciu_id=uss_lugar_nacimiento
            LEFT JOIN ".BD_ADMIN.".localidad_departamentos ON dep_id=ciu_departamento
            WHERE fcu_anulado=0 {$filtro} AND fcu.institucion = {$config['conf_id_institucion']} AND fcu.year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me guarda un abono
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * 
     * @return string $codigo
    **/
    public static function guardarAbonos (
        mysqli $conexion, 
        array $config, 
        array $POST
    )
    {

        try {
            mysqli_query($conexion, "INSERT INTO ".BD_FINANCIERA.".payments (responsible_user, invoiced, payment, payment_method, observation, institucion, year)VALUES({$_SESSION["id"]}, '".$POST["idFactura"]."', ".$POST["valor"].", '".$POST["metodoPago"]."', '".$POST["obser"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $idRegistro = mysqli_insert_id($conexion);

        return $idRegistro;
    }

    /**
     * Este metodo me trae la informacion de un Abono
     * @param mysqli $conexion
     * @param array $config
     * @param string $idAbono
     * 
     * @return array $resultado
    **/
    public static function traerDatosAbonos (
        mysqli $conexion, 
        array $config,
        string $idAbono
    )
    {
        $resultado = [];
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".payments pay
            INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=responsible_user AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ADMIN.".localidad_ciudades ON ciu_id=uss_lugar_nacimiento
            LEFT JOIN ".BD_ADMIN.".localidad_departamentos ON dep_id=ciu_departamento
            WHERE id='{$idAbono}' AND pay.institucion = {$config['conf_id_institucion']} AND pay.year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me actualiza un abono
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
    **/
    public static function actualizarAbono (
        mysqli $conexion, 
        array $config, 
        array $POST
    )
    {

        try {
            mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".payments SET invoiced='".$POST["idFactura"]."', payment=".$POST["valor"].", payment_method='".$POST["metodoPago"]."', observation='".$POST["obser"]."' WHERE id='".$POST["id"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me actualiza un abono
     * @param mysqli $conexion
     * @param array $config
     * @param string $idAbono
    **/
    public static function eliminarAbono (
        mysqli $conexion, 
        array $config, 
        string $idAbono
    )
    {

        try {
            mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".payments SET is_deleted=1 WHERE id='{$idAbono}' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me trae la informacion de una cotizacion
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCotizacion
     * 
     * @return array $resultado
    **/
    public static function traerDatosCotizacion (
        mysqli $conexion, 
        array $config,
        string $idCotizacion
    )
    {
        $resultado = [];
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".quotes cotiz
            INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=user AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
            LEFT JOIN ".BD_ADMIN.".localidad_ciudades ON ciu_id=uss_lugar_nacimiento
            LEFT JOIN ".BD_ADMIN.".localidad_departamentos ON dep_id=ciu_departamento
            WHERE id='{$idCotizacion}' AND cotiz.institucion = {$config['conf_id_institucion']} AND cotiz.year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me valida si ya existe una configuración de la institución para finanzas
     * @param mysqli $conexion
     * @param array $config
     * 
     * @return int $num
    **/
    public static function validarConfiguracionFinanzas(
        mysqli $conexion,
        array $config
    )
    {

        try {
            $configConsulta = mysqli_query($conexion,"SELECT * FROM ".BD_FINANCIERA.".configuration WHERE institucion = {$config['conf_id_institucion']} AND year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $num = mysqli_num_rows($configConsulta);

        return $num;
    }

    /**
     * Este metodo me busca la configuración de la institución para finanzas
     * @param mysqli $conexion
     * @param array $config
     * 
     * @return array $resultado
    **/
    public static function configuracionFinanzas(
        mysqli $conexion,
        array $config
    )
    {
        $resultado = [];

        try {
            $configConsulta = mysqli_query($conexion,"SELECT * FROM ".BD_FINANCIERA.".configuration WHERE institucion = {$config['conf_id_institucion']} AND year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($configConsulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me guarda la configuración de la institución para finanzas
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * 
    **/
    public static function guardarConfiguracionFinanzas(
        mysqli $conexion,
        array $config,
        array $POST
    )
    {

        try {
            mysqli_query($conexion,"INSERT INTO ".BD_FINANCIERA.".configuration(consecutive_start, institucion, year) VALUES('".$POST['consecutivo']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me actualiza la configuración de la institución para finanzas
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * 
    **/
    public static function actualizarConfiguracionFinanzas(
        mysqli $conexion,
        array $config,
        array $POST
    )
    {

        try {
            mysqli_query($conexion,"UPDATE ".BD_FINANCIERA.".configuration SET consecutive_start='".$POST['consecutivo']."' WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me actualiza la configuración de la institución para finanzas
     * @param mysqli $conexion
     * @param array $config
     * @param string $firma
     * 
    **/
    public static function actualizarFirmaConfiguracionFinanzas(
        mysqli $conexion,
        array $config,
        string $firma
    )
    {

        try {
            mysqli_query($conexion,"UPDATE ".BD_FINANCIERA.".configuration SET signature='".$firma."' WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }
}