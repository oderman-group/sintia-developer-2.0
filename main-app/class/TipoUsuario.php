<?php
require_once 'Plataforma.php';
require_once 'Tables/BDT_general_perfiles.php';

class TipoUsuario {

    public static function listarTiposUsuarios($baseDatosServicios)
    {
        $conexion = Plataforma::getConexion();
        $tableName = BDT_GeneralPerfiles::getTableName();

        try {
            $consulta = "SELECT * FROM {$baseDatosServicios}.{$tableName}";
            $stmt = mysqli_prepare($conexion, $consulta);

            if ($stmt) {
                mysqli_stmt_execute($stmt);
                $resultado = mysqli_stmt_get_result($stmt);

                return $resultado;
            } else {
                throw new Exception("Error al preparar la consulta.");
            }
        } catch (Exception $e) {
            echo "Excepción capturada: " . $e->getMessage();
            return null;
        }
    }
}