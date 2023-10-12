<?php

class Paginas
{

    /**
     * Esta función Lista las paginas 
     * @param string $filtro 
     * @param string $limit
     * */
    public static function listarPaginas($filtro = '', $limit = '')
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        $sqlExecute = "SELECT * FROM " . $baseDatosServicios . ".paginas_publicidad
       LEFT JOIN " . $baseDatosServicios . ".modulos ON mod_id=pagp_modulo
       LEFT JOIN " . $baseDatosServicios . ".general_perfiles ON pes_id=pagp_tipo_usuario
       WHERE pagp_id=pagp_id $filtro
       ORDER BY pagp_id $limit";
        try {
            $resultado = mysqli_query($conexion, $sqlExecute);
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
            exit();
        }
        return $resultado;
    }
}
