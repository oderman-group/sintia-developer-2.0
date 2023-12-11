<?php
class BDT_Tablas {

    public static $tableName;

    /**
     * Obtiene el nombre de la tabla asociada a la clase.
     *
     * @return string - Nombre de la tabla asociada a la clase.
     *
     * @example
     * ```php
     * // Ejemplo de uso para obtener el nombre de la tabla asociada a la clase
     * $tableName = MiClase::getTableName();
     * // $tableName contendrá el nombre de la tabla asociada a la clase MiClase.
     * ```
     */
    public static function getTableName() {
        return static::$tableName;
    }

    /**
     * Realiza una consulta en la base de datos utilizando una tabla específica y predicados opcionales.
     *
     * @param Array $predicado Un arreglo opcional que contiene los predicados para filtrar los resultados.
     *
     * @return PDOStatement|false Un objeto PDOStatement que contiene los resultados de la consulta o false en caso de error.
     * @throws Exception Si ocurre un error al preparar la consulta.
     */
    public static function Select(Array $predicado = []) {
        global $conexionPDO;
        $where = '';

        if( !empty($predicado) ) {
            $where = "WHERE ";
            foreach( $predicado as $clave => $valor ) {
                $where .= $clave ."='".$valor."' AND ";
            }
            $where = substr($where, 0, -5);
        }
        
        try {
            $consulta = "SELECT * FROM ".static::$tableName." {$where}";
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

    /**
     * Obtiene el número de filas resultantes de una consulta en la base de datos.
     *
     * @param Array $predicado Un arreglo opcional de predicados para filtrar los resultados.
     *
     * @return int El número de filas resultantes de la consulta.
     */
    public static function numRows(Array $predicado = []) {
        $consulta   = self::Select($predicado);
        $numRecords = $consulta->rowCount();

        return $numRecords;
    }
}