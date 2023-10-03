<?php
require_once 'Plataforma.php';
require_once 'Tables/BDT_general_perfiles.php';

class TipoUsuario {

    public static function listarTiposUsuarios($baseDatosServicios, $conexionPDO)
    {
        $tableName = BDT_GeneralPerfiles::getTableName();

        try {
            $consulta = "SELECT * FROM {$baseDatosServicios}.{$tableName}";
            $stmt = $conexionPDO->prepare($consulta);

            if ($stmt) {
                $stmt->execute();

                return $stmt;
            } else {
                throw new Exception("Error al preparar la consulta.");
            }
        } catch (PDOException  $e) {
            echo "Excepción capturada: " . $e->getMessage();
            return null;
        }
    }
}