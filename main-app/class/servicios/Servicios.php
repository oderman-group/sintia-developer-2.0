<?php
class Servicios
{
    public static function SelectSql($sql)
    {
        global $conexion;
        try {
            $resulsConsulta = mysqli_query($conexion, $sql);
        } catch (Exception $e) {
            echo "Excepción catpurada: " . $e->getMessage();
            exit();
        }
        return mysqli_fetch_array($resulsConsulta, MYSQLI_BOTH);
    }

    public static function InsertSql($sql)
    {
        global $conexion;
        try {
            mysqli_query($conexion, $sql);
            return mysqli_insert_id($conexion);
        } catch (Exception $e) {
            echo "Excepción catpurada: " . $e->getMessage();
            exit();
        }        
    }

    public static function UpdateSql($sql)
    {
        global $conexion;
        try {
            mysqli_query($conexion, $sql);
        } catch (Exception $e) {
            echo "Excepción catpurada: " . $e->getMessage();
            exit();
        }
    }
}
