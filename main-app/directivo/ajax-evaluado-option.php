<?php
    include("session.php");

    Modulos::validarAccesoDirectoPaginas();
    $idPaginaInterna = 'DT0252';
    require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
    require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");


    if($_GET['tipoEncuesta'] == DIRECTIVO){
        $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_GENERAL.".usuarios WHERE uss_tipo=5 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
            $selected = !empty($_GET['idEvaluado']) && $_GET['idEvaluado'] == $resultado['uss_id'] ? "selected": "";
            echo '<option value="'.$resultado['uss_id'].'" '.$selected.'>'.UsuariosPadre::nombreCompletoDelUsuario($resultado).'</option>';
        }
    }

    if($_GET['tipoEncuesta'] == DOCENTE){
        $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_GENERAL.".usuarios WHERE uss_tipo=2 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
            $selected = !empty($_GET['idEvaluado']) && $_GET['idEvaluado'] == $resultado['uss_id'] ? "selected": "";
            echo '<option value="'.$resultado['uss_id'].'" '.$selected.'>'.UsuariosPadre::nombreCompletoDelUsuario($resultado).'</option>';
        }
    }

    if($_GET['tipoEncuesta'] == AREA){
        $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_areas WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
            $selected = !empty($_GET['idEvaluado']) && $_GET['idEvaluado'] == $resultado['ar_id'] ? "selected": "";
            echo '<option value="'.$resultado['ar_id'].'" '.$selected.'>'.$resultado['ar_nombre'].'</option>';
        }
    }

    if($_GET['tipoEncuesta'] == MATERIA){
        require_once(ROOT_PATH."/main-app/class/Asignaturas.php");
        $consulta = Asignaturas::consultarTodasAsignaturas($conexion, $config);
        while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
            $selected = !empty($_GET['idEvaluado']) && $_GET['idEvaluado'] == $resultado['mat_id'] ? "selected": "";
            echo '<option value="'.$resultado['mat_id'].'" '.$selected.'>'.$resultado['mat_nombre'].'</option>';
        }
    }

    if($_GET['tipoEncuesta'] == CURSO){
        $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
            $selected = !empty($_GET['idEvaluado']) && $_GET['idEvaluado'] == $resultado['gra_id'] ? "selected": "";
            echo '<option value="'.$resultado['gra_id'].'" '.$selected.'>'.$resultado['gra_nombre'].'</option>';
        }
    }

    require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
?>