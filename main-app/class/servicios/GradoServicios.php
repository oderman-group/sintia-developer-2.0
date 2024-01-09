<?php
require_once("Servicios.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
class GradoServicios 
{
    /**
    * Lista los cursos académicos con información adicional.
    *
    * @param array|null $parametrosArray Arreglo de parámetros para filtrar la consulta (opcional).
    *
    * @return array|mysqli_result|false Arreglo de datos del resultado, objeto mysqli_result o false si hay un error.
    */
    public static function listarCursos($parametrosArray = null)
    {
    $sqlInicial = "SELECT * FROM " . BD_ACADEMICA . ".academico_grados";
    if ($parametrosArray && count($parametrosArray) > 0) {
        $parametrosValidos = array('gra_tipo', 'gra_estado', 'institucion', 'year');
        $sqlInicial = Servicios::concatenarWhereAnd($sqlInicial, $parametrosValidos, $parametrosArray);
    };
    $sqlFinal = " ORDER BY gra_vocal";
    $sql = $sqlInicial . $sqlFinal;
    return Servicios::SelectSql($sql);
    }

    /**
     * Consulta la información de un curso específico.
     *
     * @param int $idCurso Identificador del curso.
     *
     * @return array|false Arreglo con la información del curso o false si hay un error.
     */
    public static function consultarCurso($idCurso = 1)
    {
        global $config;
        return Servicios::getSql("SELECT * FROM " . BD_ACADEMICA . ".academico_grados WHERE gra_id='" . $idCurso."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
    }

    /**
     * Edita la información de un curso.
     *
     * @param array $Post Datos del formulario.
     *
     * @return void
     */
    public static function editar($Post)
    {
        global $config;
        Servicios::UpdateSql(
            "UPDATE " . BD_ACADEMICA . ".academico_grados SET 
            gra_codigo='" . $Post["codigoC"] . "', 
            gra_nombre='" . $Post["nombreC"] . "', 
            gra_formato_boletin='" . $Post["formatoB"] . "', 
            gra_valor_matricula='" . $Post["valorM"] . "', 
            gra_valor_pension='" . $Post["valorP"] . "', 
            gra_grado_siguiente='" . $Post["graSiguiente"] . "', 
            gra_grado_anterior='" . $Post["graAnterior"] . "', 
            gra_nota_minima='" . $Post["notaMin"] . "', 
            gra_periodos='" . $Post["periodosC"] . "', 
            gra_nivel='" . $Post["nivel"] . "', 
            gra_estado='" . $Post["estado"] . "',
            gra_tipo='" . $Post["tipoG"] . "'
            WHERE gra_id='" . $Post["id_curso"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}"
        );
    }

    /**
     * Guarda un nuevo curso en la base de datos.
     *
     * @param array $Post Datos del formulario.
     * @param string $codigoCurso Código del curso.
     * @param array $config Configuración del sistema.
     *
     * @return string Código del nuevo curso.
     */
    public static function guardar($Post, $codigoCurso, $config)
    {
        global $config;
        $codigo = Utilidades::generateCode("GRA");

        $idRegistro = Servicios::InsertSql(
            "INSERT INTO " . BD_ACADEMICA . ".academico_grados 
            (
                gra_id,
                gra_codigo,
                gra_nombre,
                gra_formato_boletin,
                gra_valor_matricula,
                gra_valor_pension,
                gra_estado,
                gra_grado_siguiente,
                gra_periodos,
                gra_tipo, 
                institucion, 
                year
            )
                VALUES
            (
                '" . $codigo . "', 
                '" . $codigoCurso . "',
                '" . $Post["nombreC"] . "', 
                '1',
                " . $Post["valorM"] . ",
                " . $Post["valorP"] . ",
                1,
                '" . $Post["graSiguiente"] . "',
                '" . $config['conf_periodos_maximos'] . "',
                '" . $Post["tipoG"] . "', 
                {$config['conf_id_institucion']}, 
                {$_SESSION["bd"]}
            )"
        );
        return $codigo;
    }
}
