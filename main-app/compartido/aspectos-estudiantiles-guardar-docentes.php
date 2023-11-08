<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0017';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;

//CONSUTLAR CARGA PARA DIRECTOR DE GRUPO
try{
    $carga = mysqli_fetch_array( mysqli_query($conexion, "SELECT * FROM academico_cargas
    WHERE car_curso='".$_POST["curso"]."' AND car_director_grupo=1"), MYSQLI_BOTH);
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

//PARA NOTAS DE COMPORTAMIENTO
try{
    $numD = mysqli_num_rows( mysqli_query($conexion, "SELECT * FROM disiplina_nota
    WHERE dn_cod_estudiante='".$_POST["estudiante"]."' AND dn_periodo='".$_POST["periodo"]."'"));
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

if($numD==0){
    try{
        mysqli_query($conexion, "DELETE FROM disiplina_nota WHERE dn_cod_estudiante='".$_POST["estudiante"]."' AND dn_periodo='".$_POST["periodo"]."'");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
    
    try{
        mysqli_query($conexion, "INSERT INTO disiplina_nota(dn_cod_estudiante, dn_aspecto_academico, dn_aspecto_convivencial, dn_periodo, dn_id_carga)VALUES('".$_POST["estudiante"]."','".$_POST["academicos"]."','".$_POST["convivenciales"]."', '".$_POST["periodo"]."', '".$carga['car_id']."')");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
}else{
    try{
        mysqli_query($conexion, "UPDATE disiplina_nota SET dn_aspecto_academico='".$_POST["academicos"]."', dn_aspecto_convivencial='".$_POST["convivenciales"]."', dn_fecha_aspecto=now() WHERE dn_cod_estudiante='".$_POST["estudiante"]."' AND dn_periodo='".$_POST["periodo"]."';");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'aspectos-estudiantiles.php?idR='.$_POST["idR"]);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
exit();