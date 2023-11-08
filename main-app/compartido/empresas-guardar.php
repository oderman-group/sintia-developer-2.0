<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0027';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;

$clave = rand(10000, 99999);

try{
    mysqli_query($conexion, "INSERT INTO " . $baseDatosMarketPlace . ".empresas(emp_nombre, emp_email, emp_telefono, emp_verificada, emp_estado, emp_clave, emp_usuario, emp_institucion)VALUES('" . mysqli_real_escape_string($conexion,$_POST["nombre"]) . "', '" . mysqli_real_escape_string($conexion,$_POST["email"]) . "', '" . mysqli_real_escape_string($conexion,$_POST["telefono"]) . "', 0, 1, '" . $clave . "', '" . $_SESSION["id"] . "', '" . $config['conf_id_institucion'] . "')");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$idRegistro = mysqli_insert_id($conexion);

if(!empty($_POST["sector"])){
    $cont = count($_POST["sector"]);
    $i = 0;
    while ($i < $cont) {
        try{
            mysqli_query($conexion, "INSERT INTO " . $baseDatosMarketPlace . ".empresas_categorias(excat_empresa, excat_categoria)VALUES('" . $idRegistro . "', '" . $_POST["sector"][$i] . "')");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $i++;
    }
}

$_SESSION["empresa"] = $idRegistro;

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'productos-agregar.php?pp=1');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
exit();