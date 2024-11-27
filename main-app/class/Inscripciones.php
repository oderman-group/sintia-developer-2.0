<?php
require_once("servicios/Servicios.php");
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/BindSQL.php");


class Inscripciones extends BindSQL{

    public const MAXIMO_PESO_ARCHIVO_MB = 5 * 1024 * 1024;
    public const ARCHIVOS_PERMITIDOS    = ['pdf', 'jpg', 'png', 'docx', 'txt', 'xls', 'xlsx', 'jpeg', 'zip', 'rar', 'gz'];


        /**
     * Lista todas  las Inscripciones con información adicional.
     *
     * @param array|null $parametrosArray Arreglo de parámetros para filtrar la consulta (opcional).
     *
     * @return array|mysqli_result|false Arreglo de datos del resultado, objeto mysqli_result o false si hay un error.
     */
    public static function listarTodos($parametrosArray = null)
    {
        global $config;
        if(empty($parametrosArray["institucion"])){
            $institucion=$config['conf_id_institucion'];
        }
        if(empty($parametrosArray["year"])){
            $year=$_SESSION["bd"];
        }
        $busqueda='';
        $sqlFinal ='';
        if(!empty($parametrosArray["valor"])){
            $busqueda=$parametrosArray["valor"];
            $sqlFinal = " AND (
                mat_id LIKE '%" . $busqueda . "%' 
                OR mat_nombres LIKE '%" . $busqueda . "%' 
                OR mat_nombre2 LIKE '%" . $busqueda . "%' 
                OR mat_primer_apellido LIKE '%" . $busqueda . "%' 
                OR mat_segundo_apellido LIKE '%" . $busqueda . "%' 
                OR mat_documento LIKE '%" . $busqueda . "%' 
                OR mat_email LIKE '%" . $busqueda . "%'
                OR CONCAT(TRIM(mat_primer_apellido), ' ', TRIM(mat_segundo_apellido), ' ', TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
                OR CONCAT(TRIM(mat_primer_apellido), TRIM(mat_segundo_apellido), TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
                OR CONCAT(TRIM(mat_primer_apellido), ' ', TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
                OR CONCAT(TRIM(mat_primer_apellido), TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
                OR CONCAT(TRIM(mat_nombres), ' ', TRIM(mat_primer_apellido)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat_nombres), '', TRIM(mat_primer_apellido)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat_primer_apellido), '', TRIM(mat_nombres)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat_nombres), ' ', TRIM(mat_nombre2)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat_nombres), ' ', TRIM(mat_segundo_apellido)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat_nombre2), ' ', TRIM(mat_nombres)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat_segundo_apellido), ' ', TRIM(mat_nombres)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat_segundo_apellido), ' ', TRIM(mat_nombre2)) LIKE '%".$busqueda."%'
                OR CONCAT(TRIM(mat_nombre2), ' ', TRIM(mat_segundo_apellido)) LIKE '%".$busqueda."%'
                OR gra_nombre LIKE '%" . $busqueda . "%'
                OR asp_email_acudiente LIKE '%" . $busqueda . "%'
                OR asp_nombre_acudiente LIKE '%" . $busqueda . "%'
                OR asp_nombre LIKE '%" . $busqueda . "%'
                OR asp_documento_acudiente LIKE '%" . $busqueda . "%'              
            )";
        }
      $sqlFiltro ='';
      if(!empty($parametrosArray["filtro"])){
        $sqlFiltro =$parametrosArray["filtro"];
      }
      $sqlInicial ="SELECT * FROM ".BD_ACADEMICA.".academico_matriculas mat
                    INNER JOIN ".BD_ADMISIONES.".aspirantes ON asp_id=mat_solicitud_inscripcion
                    LEFT JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=asp_grado AND gra.institucion={$institucion} AND gra.year={$year}
                    WHERE mat_estado_matricula=5 AND mat.institucion={$institucion} AND mat.year={$year} ".$sqlFinal." ".$sqlFiltro." 
                    ORDER BY mat_primer_apellido";     
      $sql = $sqlInicial ;
      return Servicios::SelectSql($sql);
    }
    /**
     * Este metodo me busca la configuración de la institución para admisiones
     * @param mysqli $conexion
     * @param string $baseDatosAdmisiones
     * @param int $idInsti
     * @param int $year
     * 
     * @return array $resultado
    **/
    public static function configuracionAdmisiones($conexion, $baseDatosAdmisiones, $idInsti, $year){
        $resultado = [];

        try {
            $configConsulta = mysqli_query($conexion,"SELECT * FROM {$baseDatosAdmisiones}.config_instituciones WHERE cfgi_id_institucion = ".$idInsti." AND cfgi_year = ".$year);
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($configConsulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo trae los documentos de un inscrito
     * @param PDO $conexionPDO
     * @param array $config
     * @param string $id
     * @param string $year
     * 
     * @return array $datos
    **/
    public static function traerDocumentos( PDO $conexionPDO, array $config, string $id, string $year= ""){

        try {

            //Documentos
            $documentosQuery = "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas_documentos 
            WHERE matd_matricula = :id 
            AND institucion= :idInstitucion 
            AND year= :year
            ";
            $documentos = $conexionPDO->prepare($documentosQuery);
            $documentos->bindParam(':id', $id, PDO::PARAM_STR);
            $documentos->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
            $documentos->bindParam(':year', $year, PDO::PARAM_STR);

            if ($documentos) {
                $documentos->execute();
                $datosDocumentos = $documentos->fetch();
                return $datosDocumentos;
            } else {
                throw new Exception("Error al preparar la consulta.");
            }
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo actualiza los documentos de un inscrito
     * @param PDO $conexionPDO
     * @param array $config
     * @param array $FILES
     * @param array $POST
     * 
     * @return array $documentos
    **/
    public static function actualizarDocumentos(
        PDO $conexionPDO, 
        array $config, 
        array $FILES, 
        array $POST, 
        string $year= ""
    ) {

        try {

            //Documentos
            if (!empty($FILES['pazysalvo']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['pazysalvo']['name']);
                $extension = end($explode);
                $pazysalvo = uniqid('pyz_') . "." . $extension;
                @unlink($destino . "/" . $pazysalvo);

                if ($FILES['pazysalvo']['size'] > self::MAXIMO_PESO_ARCHIVO_MB) {
                    echo "El tamaño del paz y salvo excede el límite permitido. " . $FILES['pazysalvo']['size'];
                    exit();
                } 
        
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }
        
                if (move_uploaded_file($FILES['pazysalvo']['tmp_name'], $destino . "/" . $pazysalvo)) {
                    echo "El paz y salvo se movió correctamente a: " . $destino . "<br>";
                } else {
                    echo "Hubo un error al mover el paz y salvo.<br>";
                }

            } else {
                $pazysalvo = $POST['pazysalvoA'];
            }

            if (!empty($FILES['observador']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['observador']['name']);
                $extension = end($explode);
                $observador = uniqid('obs_') . "." . $extension;
                @unlink($destino . "/" . $observador);

                if ($FILES['pazysalvo']['size'] > self::MAXIMO_PESO_ARCHIVO_MB) {
                    echo "El tamaño del observador excede el límite permitido. " . $FILES['observador']['size'];
                    exit();
                } 
        
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }
        
                if (move_uploaded_file($FILES['observador']['tmp_name'], $destino . "/" . $observador)) {
                    echo "El observador se movió correctamente a: " . $destino. "<br>";
                } else {
                    echo "Hubo un error al mover el paz y salvo.<br>";
                }

            } else {
                $observador = $POST['observadorA'];
            }

            if (!empty($FILES['eps']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['eps']['name']);
                $extension = end($explode);
                $eps = uniqid('eps_') . "." . $extension;
                @unlink($destino . "/" . $eps);

                if ($FILES['eps']['size'] > self::MAXIMO_PESO_ARCHIVO_MB) {
                    echo "El tamaño de la eps excede el límite permitido. " . $FILES['eps']['size'];
                    exit();
                } 
        
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }
        
                if (move_uploaded_file($FILES['eps']['tmp_name'], $destino . "/" . $eps)) {
                    echo "La eps se movió correctamente a: " . $destino. "<br>";
                } else {
                    echo "Hubo un error al mover la eps.<br>";
                }

            } else {
                $eps = $POST['epsA'];
            }

            if (!empty($FILES['recomendacion']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['recomendacion']['name']);
                $extension = end($explode);
                $recomendacion = uniqid('rec_') . "." . $extension;
                @unlink($destino . "/" . $recomendacion);

                if ($FILES['recomendacion']['size'] > self::MAXIMO_PESO_ARCHIVO_MB) {
                    echo "La recomendacion del eps excede el límite permitido. " . $FILES['recomendacion']['size'];
                    exit();
                } 
        
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }
        
                if (move_uploaded_file($FILES['recomendacion']['tmp_name'], $destino . "/" . $recomendacion)) {
                    echo "La recomendacion se movió correctamente a: " . $destino. "<br>";
                } else {
                    echo "Hubo un error al mover la recomendacion.<br>";
                }

            } else {
                $recomendacion = $POST['recomendacionA'];
            }

            if (!empty($FILES['vacunas']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['vacunas']['name']);
                $extension = end($explode);
                $vacunas = uniqid('vac_') . "." . $extension;
                @unlink($destino . "/" . $vacunas);

                if ($FILES['vacunas']['size'] > self::MAXIMO_PESO_ARCHIVO_MB) {
                    echo "La vacunas excede el límite permitido. " . $FILES['vacunas']['size'];
                    exit();
                } 
        
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }

                if (move_uploaded_file($FILES['vacunas']['tmp_name'], $destino . "/" . $vacunas)) {
                    echo "La vacuna se movió correctamente a: " . $destino. "<br>";
                } else {
                    echo "Hubo un error al mover la vacunas.<br>";
                }

            } else {
                $vacunas = $POST['vacunasA'];
            }

            if (!empty($FILES['boletines']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['boletines']['name']);
                $extension = end($explode);
                $boletines = uniqid('bol_') . "." . $extension;
                @unlink($destino . "/" . $boletines);

                if ($FILES['boletines']['size'] > self::MAXIMO_PESO_ARCHIVO_MB) {
                    echo "El boletin excede el límite permitido. " . $FILES['boletines']['size'];
                    exit();
                } 
        
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }

                if (move_uploaded_file($FILES['boletines']['tmp_name'], $destino . "/" . $boletines)) {
                    echo "El boletin se movió correctamente a: " . $destino. "<br>";
                } else {
                    echo "Hubo un error al mover el boletin.<br>";
                }

            } else {
                $boletines = $POST['boletinesA'];
            }

            if (!empty($FILES['documentoIde']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['documentoIde']['name']);
                $extension = end($explode);
                $documentoIde = uniqid('doc_') . "." . $extension;
                @unlink($destino . "/" . $documentoIde);

                if ($FILES['documentoIde']['size'] > self::MAXIMO_PESO_ARCHIVO_MB) {
                    echo "El DNI excede el límite permitido. " . $FILES['documentoIde']['size'];
                    exit();
                } 
        
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }

                if (move_uploaded_file($FILES['documentoIde']['tmp_name'], $destino . "/" . $documentoIde)) {
                    echo "El DNI se movió correctamente a: " . $destino. "<br>";
                } else {
                    echo "Hubo un error al mover el DNI.<br>";
                }
            } else {
                $documentoIde = $POST['documentoIdeA'];
            }

            if (!empty($FILES['certificado']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['certificado']['name']);
                $extension = end($explode);
                $certificado = uniqid('cert_') . "." . $extension;
                @unlink($destino . "/" . $certificado);

                if ($FILES['certificado']['size'] > self::MAXIMO_PESO_ARCHIVO_MB) {
                    echo "El certificado excede el límite permitido. " . $FILES['certificado']['size'];
                    exit();
                } 
        
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }

                if (move_uploaded_file($FILES['certificado']['tmp_name'], $destino . "/" . $certificado)) {
                    echo "El certificado se movió correctamente a: " . $destino. "<br>";
                } else {
                    echo "Hubo un error al mover El certificado.<br>";
                }

            } else {
                $certificado = $POST['certificadoA'];
            }

            if (!empty($FILES['cartaLaboral']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['cartaLaboral']['name']);
                $extension = end($explode);
                $cartaLaboral = uniqid('cert_') . "." . $extension;
                @unlink($destino . "/" . $cartaLaboral);

                if ($FILES['cartaLaboral']['size'] > self::MAXIMO_PESO_ARCHIVO_MB) {
                    echo "La carta laboral excede el límite permitido. " . $FILES['cartaLaboral']['size'];
                    exit();
                } 
        
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }

                if (move_uploaded_file($FILES['cartaLaboral']['tmp_name'], $destino . "/" . $cartaLaboral)) {
                    echo "La carta laboral se movió correctamente a: " . $destino. "<br>";
                } else {
                    echo "Hubo un error al mover La carta laboral.<br>";
                }

            } else {
                $cartaLaboral = !empty($POST['cartaLaboral']) ? $POST['cartaLaboral'] : null;
            }

            $documentosQuery = "UPDATE ".BD_ACADEMICA.".academico_matriculas_documentos SET
            matd_pazysalvo = :pazysalvo, 
            matd_observador = :observador, 
            matd_eps = :eps, 
            matd_recomendacion = :recomendacion, 
            matd_vacunas = :vacunas, 
            matd_boletines_actuales = :boletines,
            matd_documento_identidad = :documentoIde,
            matd_certificados = :certificado,
            matd_carta_laboral  = :cartaLaboral
            WHERE matd_matricula = :idMatricula AND institucion= :idInstitucion AND year= :year";
            $documentos = $conexionPDO->prepare($documentosQuery);

            $documentos->bindParam(':idMatricula', $POST['idMatricula'], PDO::PARAM_STR);
            $documentos->bindParam(':pazysalvo', $pazysalvo, PDO::PARAM_STR);
            $documentos->bindParam(':observador', $observador, PDO::PARAM_STR);
            $documentos->bindParam(':eps', $eps, PDO::PARAM_STR);
            $documentos->bindParam(':vacunas', $vacunas, PDO::PARAM_STR);
            $documentos->bindParam(':boletines', $boletines, PDO::PARAM_STR);
            $documentos->bindParam(':documentoIde', $documentoIde, PDO::PARAM_STR);
            $documentos->bindParam(':recomendacion', $recomendacion, PDO::PARAM_STR);
            $documentos->bindParam(':certificado', $certificado, PDO::PARAM_STR);
            $documentos->bindParam(':cartaLaboral', $cartaLaboral, PDO::PARAM_STR);
            $documentos->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
            $documentos->bindParam(':year', $year, PDO::PARAM_STR);

            if ($documentos) {
                $documentos->execute();
                return $documentos;
            } else {
                throw new Exception("Error al preparar la consulta.");
            }

        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo guarda los documentos de un inscrito
     * @param PDO $conexionPDO
     * @param array $config
     * @param string $id
     * 
     * @return string $codigo
    **/
    public static function guardarDocumentos( PDO $conexionPDO, array $config, string $id){

        try {

            //Documentos
            $documentosQuery = "INSERT INTO ".BD_ACADEMICA.".academico_matriculas_documentos(matd_id, matd_matricula, institucion, year)VALUES(:codigo, :matricula, :idInstitucion, :year)";
            $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_matriculas_documentos');
            $documentos = $conexionPDO->prepare($documentosQuery);
            $documentos->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $documentos->bindParam(':matricula', $id, PDO::PARAM_STR);
            $documentos->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
            $documentos->bindParam(':year', $config['conf_agno'], PDO::PARAM_STR);

            if ($documentos) {
                $documentos->execute();
                return $codigo;
            } else {
                throw new Exception("Error al preparar la consulta.");
            }
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo elimina los documentos de un inscrito
     * @param mysqli $conexion
     * @param array $config
     * @param string $id
    **/
    public static function eliminarDocumentos(mysqli $conexion, array $config, string $id)
    {
        $sql = "UPDATE " . BD_ACADEMICA . ".academico_matriculas_documentos SET matd_fecha_eliminados=now(), matd_usuario_elimados=? WHERE matd_matricula=? AND institucion=? AND year=?";

        $parametros = [$_SESSION["id"], $id, $config['conf_id_institucion'], $_SESSION["bd"]];
        
        $resultado = BindSQL::prepararSQL($sql, $parametros);
    }

    /**
     * Actualiza el estado de un aspirante a oculto.
     *
     * @param string $aspiranteId El ID del aspirante que se desea actualizar.
     *
     * @return bool Retorna true si la actualización fue exitosa, de lo contrario false.
     */
    public static function actualizarEstadoAspirante(string $aspiranteId): bool {

        $conexionPDO = Conexion::newConnection('PDO');

        $query = "UPDATE ".BD_ADMISIONES.".aspirantes SET asp_oculto = ".BDT_Aspirante::ESTADO_OCULTO_VERDADERO." 
        WHERE asp_id=:aspirante";
        $pdo = $conexionPDO->prepare($query);

        $pdo->bindParam(':aspirante', $aspiranteId, PDO::PARAM_STR);

        if ($pdo) {
            $pdo->execute();
            return true;
        } else {
            return false;
        }

    }

}