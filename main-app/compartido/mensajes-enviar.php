<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0040';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
$usuariosClase = new Usuarios;

try{
    $remitente = UsuariosPadre::sesionUsuario($_SESSION["id"]);
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$cont = count($_POST["para"]);
$i = 0;
while ($i < $cont) {

    try{
        $destinatario = UsuariosPadre::sesionUsuario($_POST["para"][$i]);
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }

    try{
        mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".social_emails(ema_de, ema_para, ema_asunto, ema_contenido, ema_fecha, ema_visto, ema_eliminado_de, ema_eliminado_para, ema_institucion, ema_year) VALUES('" . $_SESSION["id"] . "', '" . $_POST["para"][$i] . "', '" . mysqli_real_escape_string($conexion,$_POST["asunto"]) . "', '" . mysqli_real_escape_string($conexion,$_POST["contenido"]) . "', now(), 0, 0, 0,'" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
    $i++;

    if ($_POST["para"][$i] == 1) {
        //INICIO ENVÍO DE MENSAJE
        $tituloMsj = $_POST["asunto"];
        $contenidoMsj = '
            <p style="color:navy;">
            Hola ' . strtoupper($destinatario['uss_nombre']) . ', has recibido un mensaje a través de la plataforma SINTIA.<br>
            <b>Remitente:</b> ' . strtoupper($remitente['uss_nombre']) . '.
            </p>

            <p>' . $_POST["contenido"] . '</p>
        ';

        $data = [
            'contenido_msj'   => $contenidoMsj,
            'usuario_email'    => 'tecmejia2010@gmail.com',
            'usuario_nombre'   => 'Jhon Oderman'
        ];
        $asunto = $tituloMsj;
        $bodyTemplateRoute = ROOT_PATH.'/config-general/plantilla-email-2.php';
        
        EnviarEmail::enviar($data, $asunto, $bodyTemplateRoute,null,null);
    }
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'mensajes.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
exit();