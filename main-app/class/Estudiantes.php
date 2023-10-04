<?php
class Estudiantes {

    public static function listarEstudiantes(
        int    $eliminados      = 0, 
        string $filtroAdicional = '', 
        string $filtroLimite    = 'LIMIT 0, 2000'
    )
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            LEFT JOIN ".$baseDatosServicios.".localidad_ciudades ON ciu_id=mat_lugar_nacimiento
            WHERE mat_eliminado IN (0, '".$eliminados."')
            ".$filtroAdicional."
            ORDER BY mat_grado, mat_grupo, mat_primer_apellido, mat_segundo_apellido, mat_nombres
            ".$filtroLimite."
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function listarEstudiantesEnGrados(
        string $filtroAdicional = '', 
        string $filtroLimite    = 'LIMIT 0, 2000'
    )
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            INNER JOIN academico_grados ON gra_id=mat_grado
            INNER JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE mat_eliminado = 0
            ".$filtroAdicional."
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres
            ".$filtroLimite."
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function listarEstudiantesNotasFaltantes(
        string $carga, 
        string $periodo,
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $sqlString= "SELECT *, sum(act_valor) as acumulado 
            FROM academico_matriculas
            LEFT JOIN academico_cargas on car_id='".$carga."'
            LEFT JOIN academico_calificaciones on cal_id_estudiante=mat_id 
            LEFT JOIN academico_actividades on act_id=cal_id_actividad and act_id_carga=car_id and act_periodo='".$periodo."' and act_registrada=1 and act_estado=1
            WHERE mat_eliminado=0 AND (mat_estado_matricula=1 OR mat_estado_matricula=2) AND mat_grado=car_curso AND mat_grupo=car_grupo
            GROUP BY mat_id
            HAVING acumulado<100 OR acumulado IS NULL
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres";
            $resultado = mysqli_query($conexion,$sqlString);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function listarEstudiantesParaDocentes(string $filtroDocentes = '',string $filtroLimite = '')
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE mat_eliminado=0 
            AND (mat_estado_matricula=1 OR mat_estado_matricula=2)
            ".$filtroDocentes."
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres
            $filtroLimite");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDatosEstudiante($estudiante = 0)
    {

        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE (mat_id='".$estudiante."' || mat_documento='".$estudiante."' || mat_matricula='".$estudiante."') AND mat_eliminado=0
            ");
            $num = mysqli_num_rows($consulta);
            if($num == 0){
                echo "Estás intentando obtener datos de un estudiante que no existe: ".$estudiante."<br>";
            }
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    public static function NombreCompletoDelEstudiante(array $estudiante){
        
        $nombre=$estudiante['mat_nombres'];
        if(!empty($estudiante['mat_nombre2'])){
            $nombre.=" ".$estudiante['mat_nombre2'];
        }
        if(!empty($estudiante['mat_segundo_apellido'])){
            $nombre=$estudiante['mat_segundo_apellido']." ".$nombre;
        }
        if(!empty($estudiante['mat_primer_apellido'])){
            $nombre=$estudiante['mat_primer_apellido']." ".$nombre;
        }
        return strtoupper($nombre);
    }

    public static function listarEstudiantesParaAcudientes($acudiente)
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            INNER JOIN usuarios_por_estudiantes ON upe_id_estudiante=mat_id AND upe_id_usuario='".$acudiente."'
            WHERE mat_eliminado=0 
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function listarEstudiantesParaEstudiantes(string $filtroEstudiantes = '')
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE mat_eliminado=0 
            AND (mat_estado_matricula=1 OR mat_estado_matricula=2)
            ".$filtroEstudiantes."
            ORDER BY mat_primer_apellido, mat_segundo_apellido, mat_nombres
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDatosEstudiantePorIdUsuario($estudianteIdUsuario = 0)
    {

        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            LEFT JOIN usuarios ON uss_id=mat_id_usuario
            LEFT JOIN academico_grados ON gra_id=mat_grado
            LEFT JOIN academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            WHERE mat_id_usuario='".$estudianteIdUsuario."' AND mat_eliminado=0
            ");
            $num = mysqli_num_rows($consulta);
            if($num == 0){
                echo "Este estudiante no existe: ".$estudianteIdUsuario;
                exit();
            }
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    public static function validarExistenciaEstudiante($estudiante = 0)
    {

        global $conexion;
        $num = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            WHERE (mat_id='".$estudiante."' || mat_documento='".$estudiante."') AND mat_eliminado=0
            ");
            $num = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $num;

    }

    public static function listarEstudiantesParaPlanillas(
        int    $eliminados      = 0, 
        string $filtroAdicional = '', 
        string $BD    = ''
    )
    {
        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM $BD.academico_matriculas
            LEFT JOIN $BD.usuarios ON uss_id=mat_id_usuario
            LEFT JOIN $BD.academico_grados ON gra_id=mat_grado
            LEFT JOIN $BD.academico_grupos ON gru_id=mat_grupo
            LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
            LEFT JOIN ".$baseDatosServicios.".localidad_ciudades ON ciu_id=mat_lugar_nacimiento
            WHERE mat_eliminado IN (0, '".$eliminados."')
            ".$filtroAdicional."
            ORDER BY mat_grado, mat_grupo, mat_primer_apellido, mat_segundo_apellido, mat_nombres");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function NombreCompletoDelEstudianteParaInformes(array $estudiante, $orden){
        
        $nombre=$estudiante['mat_nombres'];
        if(!empty($estudiante['mat_nombre2'])){
            $nombre.=" ".$estudiante['mat_nombre2'];
        }
        if(!empty($estudiante['mat_primer_apellido'])){
            $nombre.=" ".$estudiante['mat_primer_apellido'];
        }
        if(!empty($estudiante['mat_segundo_apellido'])){
            $nombre.=" ".$estudiante['mat_segundo_apellido'];
        }
        
        if($orden==2){
            $nombre=$estudiante['mat_nombres'];
            if(!empty($estudiante['mat_nombre2'])){
                $nombre.=" ".$estudiante['mat_nombre2'];
            }
            if(!empty($estudiante['mat_segundo_apellido'])){
                $nombre=$estudiante['mat_segundo_apellido']." ".$nombre;
            }
            if(!empty($estudiante['mat_primer_apellido'])){
                $nombre=$estudiante['mat_primer_apellido']." ".$nombre;
            }
        }
        return strtoupper($nombre);
    }

    public static function ActualizarEstadoMatricula($idEstudiante, $estadoMatricula)
    {
        global $conexion;

        try {
            mysqli_query($conexion, "UPDATE academico_matriculas SET mat_estado_matricula='".$estadoMatricula."' WHERE mat_id='".$idEstudiante."'");
        } catch (Exception $e) {
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
    }

    public static function retirarRestaurarEstudiante($idEstudiante, $motivo)
    {
        global $conexion;

        try {
            mysqli_query($conexion, "INSERT INTO academico_matriculas_retiradas (matret_estudiante, matret_fecha, matret_motivo, matret_responsable)VALUES('".$idEstudiante."', now(), '".$motivo."', '".$_SESSION["id"]."')");
        } catch (Exception $e) {
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
    }

    public static function estudiantesMatriculados(
        string    $filtro      = '',
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM $BD.academico_matriculas 
            INNER JOIN $BD.academico_grupos ON mat_grupo=gru_id
            INNER JOIN $BD.academico_grados ON mat_grado=gra_id 
            WHERE mat_eliminado=0 AND mat_estado_matricula=1 $filtro 
            GROUP BY mat_id
            ORDER BY mat_grupo, mat_primer_apellido");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDatosEstudiantesParaBoletin(
        int    $estudiante      = 0,
        string $BD    = ''
    )
    {
        global $conexion;
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM $BD.academico_matriculas am
            INNER JOIN $BD.academico_grupos ON mat_grupo=gru_id
            INNER JOIN $BD.academico_grados ON mat_grado=gra_id WHERE mat_id=" . $estudiante);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function validarRepeticionDocumento($documento, $idEstudiante)
    {

        global $conexion;
        $num = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas
            WHERE mat_id!='".$idEstudiante."' AND mat_documento='".$documento."' AND mat_eliminado=0
            ");
            $num = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $num;

    }

    /**
     * Esta función permite insertar los datos de los estudiantes en
     * las matrículas
     * 
     * @param $conexionPDO ConexionPDO
     * @param $POST Array
     * @param $fechaNacimiento String
     * @param $procedencia String
     * @param $pasosMatricula String
     */
    public static function insertarEstudiantes($conexionPDO, $POST, $result_numMat = '', $procedencia = '', $idAcudiente = '', $idEstudianteU)
    {
        $tipoD = isset($POST["tipoD"]) ? $POST["tipoD"] : "";
        $nDoc = isset($POST["nDoc"]) ? $POST["nDoc"] : "";
        $religion = isset($POST["religion"]) ? $POST["religion"] : "";
        $email = isset($POST["email"]) ? strtolower($POST["email"]) : "";
        $direccion = isset($POST["direccion"]) ? $POST["direccion"] : "";
        $barrio = isset($POST["barrio"]) ? $POST["barrio"] : "";
        $telefono = isset($POST["telefono"]) ? $POST["telefono"] : "";
        $celular = isset($POST["celular"]) ? $POST["celular"] : "";
        $estrato = isset($POST["estrato"]) ? $POST["estrato"] : "";
        $genero = isset($POST["genero"]) ? $POST["genero"] : "";
        $apellido1 = isset($POST["apellido1"]) ? $POST["apellido1"] : "";
        $apellido2 = isset($POST["apellido2"]) ? $POST["apellido2"] : "";
        $nombres = isset($POST["nombres"]) ? $POST["nombres"] : "";
        $grado = isset($POST["grado"]) ? $POST["grado"] : "";
        $grupo = isset($POST["grupo"]) ? $POST["grupo"] : "";
        $tipoEst = isset($POST["tipoEst"]) ? $POST["tipoEst"] : "";
        $lugarD = isset($POST["lugarD"]) ? $POST["lugarD"] : "";
        $matestM = isset($POST["matestM"]) ? $POST["matestM"] : "";
        $folio = isset($POST["folio"]) ? $POST["folio"] : "";
        $codTesoreria = isset($POST["codTesoreria"]) ? $POST["codTesoreria"] : "";
        $va_matricula = isset($POST["va_matricula"]) ? $POST["va_matricula"] : "";
        $inclusion = isset($POST["inclusion"]) ? $POST["inclusion"] : "";
        $extran = isset($POST["extran"]) ? $POST["extran"] : "";
        $tipoSangre = isset($POST["tipoSangre"]) ? $POST["tipoSangre"] : "";
        $eps = isset($POST["eps"]) ? $POST["eps"] : "";
        $celular2 = isset($POST["celular2"]) ? $POST["celular2"] : "";
        $ciudadR = isset($POST["ciudadR"]) ? $POST["ciudadR"] : "";
        $nombre2 = isset($POST["nombre2"]) ? $POST["nombre2"] : "";
        $fNac = isset($POST["fNac"]) ? $POST["fNac"] : "";

        try{

            $consulta = "INSERT INTO academico_matriculas(
                mat_matricula, mat_fecha, mat_tipo_documento, 
                mat_documento, mat_religion, mat_email, 
                mat_direccion, mat_barrio, mat_telefono, 
                mat_celular, mat_estrato, mat_genero, 
                mat_fecha_nacimiento, mat_primer_apellido, mat_segundo_apellido, 
                mat_nombres, mat_grado, mat_grupo, 
                mat_tipo, mat_lugar_nacimiento, mat_lugar_expedicion, 
                mat_acudiente, mat_estado_matricula, mat_id_usuario, 
                mat_folio, mat_codigo_tesoreria, mat_valor_matricula, 
                mat_inclusion, mat_extranjero, mat_tipo_sangre, 
                mat_eps, mat_celular2, mat_ciudad_residencia, 
                mat_nombre2, mat_estado_agno)
                VALUES(
                ".$result_numMat.", now(), :tipoD,
                :nDoc, :religion, :email,
                :direccion, :barrio, :telefono,
                :celular, :estrato, :genero, 
                :fNac, :apellido1, :apellido2, 
                :nombres, :grado, :grupo,
                :tipoEst, '".$procedencia."', :lugarD,
                ".$idAcudiente.", :matestM, '".$idEstudianteU."', 
                :folio, :codTesoreria, :va_matricula, 
                :inclusion, :extran, :tipoSangre, 
                :eps, :celular2, :ciudadR, 
                :nombre2, 3
                )";

            $stmt = $conexionPDO->prepare($consulta);

             // Asociar los valores a los marcadores de posición
            $stmt->bindParam(':tipoD', $tipoD, PDO::PARAM_INT);

            $stmt->bindParam(':nDoc', $nDoc, PDO::PARAM_STR);
            $stmt->bindParam(':religion', $religion);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':barrio', $barrio, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);

            $stmt->bindParam(':celular', $celular, PDO::PARAM_STR);
            $stmt->bindParam(':estrato', $estrato);
            $stmt->bindParam(':genero', $genero);

            $stmt->bindParam(':fNac', $fNac, PDO::PARAM_STR);
            $stmt->bindParam(':apellido1', $apellido1, PDO::PARAM_STR);
            $stmt->bindParam(':apellido2', $apellido2, PDO::PARAM_STR);

            $stmt->bindParam(':nombres', $nombres, PDO::PARAM_STR);
            $stmt->bindParam(':grado', $grado, PDO::PARAM_STR);
            $stmt->bindParam(':grupo', $grupo);

            $stmt->bindParam(':tipoEst', $tipoEst);
            $stmt->bindParam(':lugarD', $lugarD, PDO::PARAM_STR);

            $stmt->bindParam(':matestM', $matestM);

            $stmt->bindParam(':folio', $folio,PDO::PARAM_STR);
            $stmt->bindParam(':codTesoreria', $codTesoreria, PDO::PARAM_STR);
            $stmt->bindParam(':va_matricula', $va_matricula, PDO::PARAM_STR);

            $stmt->bindParam(':inclusion', $inclusion);
            $stmt->bindParam(':extran', $extran);
            $stmt->bindParam(':tipoSangre', $tipoSangre, PDO::PARAM_STR);

            $stmt->bindParam(':eps', $eps, PDO::PARAM_STR);
            $stmt->bindParam(':celular2', $celular2, PDO::PARAM_STR);
            $stmt->bindParam(':ciudadR', $ciudadR, PDO::PARAM_STR);

            $stmt->bindParam(':nombre2', $nombre2, PDO::PARAM_STR);

            if ($stmt) {
                $stmt->execute();
                $idEstudiante = $conexionPDO->lastInsertId();
                return $idEstudiante;
            } else {
                throw new Exception("Error al preparar la consulta.");
            }
        
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }	

    }


    /**
     * Esta función permite actualizar los datos de los estudiantes
     * 
     * @param $conexionPDO ConexionPDO
     * @param $POST Array
     * @param $fechaNacimiento String
     * @param $procedencia String
     * @param $pasosMatricula String
     */
    public static function actualizarEstudiantes($conexionPDO, $POST, $fechaNacimiento = '', $procedencia = '', $pasosMatricula = '')
    {
        $tipoD = isset($POST["tipoD"]) ? $POST["tipoD"] : "";
        $nDoc = isset($POST["nDoc"]) ? $POST["nDoc"] : "";
        $religion = isset($POST["religion"]) ? $POST["religion"] : "";
        $email = isset($POST["email"]) ? strtolower($POST["email"]) : "";
        $direccion = isset($POST["direccion"]) ? $POST["direccion"] : "";
        $barrio = isset($POST["barrio"]) ? $POST["barrio"] : "";
        $telefono = isset($POST["telefono"]) ? $POST["telefono"] : "";
        $celular = isset($POST["celular"]) ? $POST["celular"] : "";
        $estrato = isset($POST["estrato"]) ? $POST["estrato"] : "";
        $genero = isset($POST["genero"]) ? $POST["genero"] : "";
        $apellido1 = isset($POST["apellido1"]) ? $POST["apellido1"] : "";
        $apellido2 = isset($POST["apellido2"]) ? $POST["apellido2"] : "";
        $nombres = isset($POST["nombres"]) ? $POST["nombres"] : "";
        $grado = isset($POST["grado"]) ? $POST["grado"] : "";
        $grupo = isset($POST["grupo"]) ? $POST["grupo"] : "";
        $tipoEst = isset($POST["tipoEst"]) ? $POST["tipoEst"] : "";
        $lugarD = isset($POST["lugarD"]) ? $POST["lugarD"] : "";
        $matestM = isset($POST["matestM"]) ? $POST["matestM"] : "";
        $matricula = isset($POST["matricula"]) ? $POST["matricula"] : "";
        $folio = isset($POST["folio"]) ? $POST["folio"] : "";
        $codTesoreria = isset($POST["codTesoreria"]) ? $POST["codTesoreria"] : "";
        $va_matricula = isset($POST["va_matricula"]) ? $POST["va_matricula"] : "";
        $inclusion = isset($POST["inclusion"]) ? $POST["inclusion"] : "";
        $extran = isset($POST["extran"]) ? $POST["extran"] : "";
        $NumMatricula = isset($POST["NumMatricula"]) ? $POST["NumMatricula"] : "";
        $estadoAgno = isset($POST["estadoAgno"]) ? $POST["estadoAgno"] : "";
        $tipoSangre = isset($POST["tipoSangre"]) ? $POST["tipoSangre"] : "";
        $eps = isset($POST["eps"]) ? $POST["eps"] : "";
        $celular2 = isset($POST["celular2"]) ? $POST["celular2"] : "";
        $ciudadR = isset($POST["ciudadR"]) ? $POST["ciudadR"] : "";
        $nombre2 = isset($POST["nombre2"]) ? $POST["nombre2"] : "";
        $id = isset($POST["id"]) ? $POST["id"] : "";

        try{
            
            $consulta = "UPDATE academico_matriculas SET 
            mat_tipo_documento    = :tipoD, 
            mat_documento         = :nDoc, 
            mat_religion          = :religion, 
            mat_email             = :email, 
            mat_direccion         = :direccion, 
            mat_barrio            = :barrio, 
            mat_telefono          = :telefono, 
            mat_celular           = :celular, 
            mat_estrato           = :estrato, 
            mat_genero            = :genero,
            mat_primer_apellido   = :apellido1, 
            mat_segundo_apellido  = :apellido2, 
            mat_nombres           = :nombres, 
            mat_grado             = :grado, 
            mat_grupo             = :grupo, 
            mat_tipo              = :tipoEst,
            mat_lugar_expedicion  = :lugarD,
            mat_estado_matricula  = :matestM, 
            mat_matricula         = :matricula, 
            mat_folio             = :folio, 
            mat_codigo_tesoreria  = :codTesoreria, 
            mat_valor_matricula   = :va_matricula, 
            mat_inclusion         = :inclusion, 
            mat_extranjero        = :extran, 
            mat_fecha             = NOW(), 
            mat_numero_matricula  = :NumMatricula, 
            mat_estado_agno       = :estadoAgno,
            mat_tipo_sangre       = :tipoSangre, 
            mat_eps               = :eps, 
            mat_celular2          = :celular2, 
            mat_ciudad_residencia = :ciudadR,
            mat_lugar_nacimiento  = :procedencia,
            $pasosMatricula
            $fechaNacimiento
            mat_nombre2           = :nombre2

            WHERE mat_id = :id";

            $stmt = $conexionPDO->prepare($consulta);

             // Asociar los valores a los marcadores de posición
            $stmt->bindParam(':tipoD', $tipoD, PDO::PARAM_INT);
            $stmt->bindParam(':nDoc', $nDoc, PDO::PARAM_STR);
            $stmt->bindParam(':religion', $religion);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':barrio', $barrio, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':celular', $celular, PDO::PARAM_STR);
            $stmt->bindParam(':estrato', $estrato);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':apellido1', $apellido1, PDO::PARAM_STR);
            $stmt->bindParam(':apellido2', $apellido2, PDO::PARAM_STR);
            $stmt->bindParam(':nombres', $nombres, PDO::PARAM_STR);
            $stmt->bindParam(':grado', $grado, PDO::PARAM_STR);
            $stmt->bindParam(':grupo', $grupo);
            $stmt->bindParam(':tipoEst', $tipoEst);
            $stmt->bindParam(':lugarD', $lugarD, PDO::PARAM_STR);
            $stmt->bindParam(':matestM', $matestM);
            $stmt->bindParam(':matricula', $matricula);
            $stmt->bindParam(':folio', $folio,PDO::PARAM_STR);
            $stmt->bindParam(':codTesoreria', $codTesoreria, PDO::PARAM_STR);
            $stmt->bindParam(':va_matricula', $va_matricula, PDO::PARAM_STR);
            $stmt->bindParam(':inclusion', $inclusion);
            $stmt->bindParam(':extran', $extran);
            $stmt->bindParam(':NumMatricula', $NumMatricula);
            $stmt->bindParam(':estadoAgno', $estadoAgno, PDO::PARAM_INT);
            $stmt->bindParam(':tipoSangre', $tipoSangre, PDO::PARAM_STR);
            $stmt->bindParam(':eps', $eps, PDO::PARAM_STR);
            $stmt->bindParam(':celular2', $celular2, PDO::PARAM_STR);
            $stmt->bindParam(':ciudadR', $ciudadR, PDO::PARAM_STR);
            $stmt->bindParam(':procedencia', $procedencia);
            $stmt->bindParam(':nombre2', $nombre2, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt) {
                $stmt->execute();

                return $stmt;
            } else {
                throw new Exception("Error al preparar la consulta.");
            }
        
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }	

    }

}