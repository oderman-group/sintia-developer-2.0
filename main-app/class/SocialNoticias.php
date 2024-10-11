<?php
require_once("servicios/Servicios.php");

class SocialNoticias extends Servicios
{

    /**
     * Obtiene los datos de un usuario por ID de usuario o nombre de usuario.
     *
     * @param int|string $idNoticia ID de usuario o nombre de usuario a consultar.
     *
     * @return array|string Devuelve un conjunto de resultados de la consulta de usuarios o una cadena vacía si no se encuentra ningún usuario.
     */
    public static function consultarNoticia($id_noticia = 0)
    {
        global $config;
        $resultado = [];

        $sql = "
        SELECT * FROM " . BD_ADMIN . ".social_noticias noti 

        LEFT JOIN " . BD_GENERAL . ".usuarios uss 
        ON  uss_id          = not_usuario 
        AND uss.institucion = noti.not_institucion
        AND uss.year        = noti.not_year

        WHERE  not_id       = ?
        AND not_institucion = ?
        AND not_year        = ?";

        $parametros = [$id_noticia,$config['conf_id_institucion'], $_SESSION["bd"]];
        $consulta = BindSQL::prepararSQL($sql, $parametros);
        
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }
}
