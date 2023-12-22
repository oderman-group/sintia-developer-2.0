<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class Inscripciones {

    /**
     * Este metodo me busca la configuración de la institución para admisiones
     * @param mysqli $conexion
     * @param string $baseDatosAdmisiones
     * @param int $idInsti
     * @param int $year
     * 
     * @return array $resultado
    **/
    public static function configuracionAdmisiones($conexion,$baseDatosAdmisiones,$idInsti,$year){
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
     * 
     * @return array $datos
    **/
    public static function traerDocumentos( PDO $conexionPDO, array $config, string $id){

        try {

            //Documentos
            $documentosQuery = "SELECT * FROM ".BD_ACADEMICA.".academico_matriculas_documentos WHERE matd_matricula = :id AND institucion= :idInstitucion AND year= :year";
            $documentos = $conexionPDO->prepare($documentosQuery);
            $documentos->bindParam(':id', $id, PDO::PARAM_STR);
            $documentos->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
            $documentos->bindParam(':year', $config['conf_agno'], PDO::PARAM_STR);

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
    public static function actualizarDocumentos( PDO $conexionPDO, array $config, array $FILES, array $POST){

        try {

            //Documentos
            if (!empty($FILES['pazysalvo']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['pazysalvo']['name']);
                $extension = end($explode);
                $pazysalvo = uniqid('pyz_') . "." . $extension;
                @unlink($destino . "/" . $pazysalvo);
                move_uploaded_file($FILES['pazysalvo']['tmp_name'], $destino . "/" . $pazysalvo);
            } else {
                $pazysalvo = $POST['pazysalvoA'];
            }

            if (!empty($FILES['observador']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['observador']['name']);
                $extension = end($explode);
                $observador = uniqid('obs_') . "." . $extension;
                @unlink($destino . "/" . $observador);
                move_uploaded_file($FILES['observador']['tmp_name'], $destino . "/" . $observador);
            } else {
                $observador = $POST['observadorA'];
            }

            if (!empty($FILES['eps']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['eps']['name']);
                $extension = end($explode);
                $eps = uniqid('eps_') . "." . $extension;
                @unlink($destino . "/" . $eps);
                move_uploaded_file($FILES['eps']['tmp_name'], $destino . "/" . $eps);
            } else {
                $eps = $POST['epsA'];
            }

            if (!empty($FILES['recomendacion']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['recomendacion']['name']);
                $extension = end($explode);
                $recomendacion = uniqid('rec_') . "." . $extension;
                @unlink($destino . "/" . $recomendacion);
                move_uploaded_file($FILES['recomendacion']['tmp_name'], $destino . "/" . $recomendacion);
            } else {
                $recomendacion = $POST['recomendacionA'];
            }

            if (!empty($FILES['vacunas']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['vacunas']['name']);
                $extension = end($explode);
                $vacunas = uniqid('vac_') . "." . $extension;
                @unlink($destino . "/" . $vacunas);
                move_uploaded_file($FILES['vacunas']['tmp_name'], $destino . "/" . $vacunas);
            } else {
                $vacunas = $POST['vacunasA'];
            }

            if (!empty($FILES['boletines']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['boletines']['name']);
                $extension = end($explode);
                $boletines = uniqid('bol_') . "." . $extension;
                @unlink($destino . "/" . $boletines);
                move_uploaded_file($FILES['boletines']['tmp_name'], $destino . "/" . $boletines);
            } else {
                $boletines = $POST['boletinesA'];
            }

            if (!empty($FILES['documentoIde']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['documentoIde']['name']);
                $extension = end($explode);
                $documentoIde = uniqid('doc_') . "." . $extension;
                @unlink($destino . "/" . $documentoIde);
                move_uploaded_file($FILES['documentoIde']['tmp_name'], $destino . "/" . $documentoIde);
            } else {
                $documentoIde = $POST['documentoIdeA'];
            }

            if (!empty($FILES['certificado']['name'])) {
	            $destino = ROOT_PATH.'/main-app/admisiones/files/otros';
                $explode = explode(".", $FILES['certificado']['name']);
                $extension = end($explode);
                $certificado = uniqid('cert_') . "." . $extension;
                @unlink($destino . "/" . $certificado);
                move_uploaded_file($FILES['certificado']['tmp_name'], $destino . "/" . $certificado);
            } else {
                $certificado = $POST['certificadoA'];
            }

            $documentosQuery = "UPDATE ".BD_ACADEMICA.".academico_matriculas_documentos SET
            matd_pazysalvo = :pazysalvo, 
            matd_observador = :observador, 
            matd_eps = :eps, 
            matd_recomendacion = :recomendacion, 
            matd_vacunas = :vacunas, 
            matd_boletines_actuales = :boletines,
            matd_documento_identidad = :documentoIde,
            matd_certificados = :certificado
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
            $documentos->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
            $documentos->bindParam(':year', $config['conf_agno'], PDO::PARAM_STR);

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
     * @return array $datos
    **/
    public static function guardarDocumentos( PDO $conexionPDO, array $config, string $id){

        try {

            //Documentos
            $documentosQuery = "INSERT INTO ".BD_ACADEMICA.".academico_matriculas_documentos(matd_id, matd_matricula, institucion, year)VALUES(:codigo, :matricula, :idInstitucion, :year)";
            $codigo=Utilidades::generateCode("MTD");
            $documentos = $conexionPDO->prepare($documentosQuery);
            $documentos->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $documentos->bindParam(':matricula', $id, PDO::PARAM_STR);
            $documentos->bindParam(':idInstitucion', $config['conf_id_institucion'], PDO::PARAM_INT);
            $documentos->bindParam(':year', $config['conf_agno'], PDO::PARAM_STR);
            $documentos->execute();

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
     * 
     * @return array $datos
    **/
    public static function eliminarDocumentos( mysqli $conexion, array $config, string $id){

        try {
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_matriculas_documentos SET matd_fecha_eliminados=now(), matd_usuario_elimados='".$_SESSION["id"]."' WHERE matd_matricula='".$id."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }
}