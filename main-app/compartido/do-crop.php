<?php
session_start();
include("../../config-general/config.php");
require_once("../class/UsuariosPadre.php");
include("../compartido/sintia-funciones.php");
$datosUsuarioActual =UsuariosPadre::sesionUsuario($_SESSION['id']);

if(isset($_POST['crop_image']))
{
  $y1=$_POST['top'];
  $x1=$_POST['left'];
  $w=$_POST['right'];
  $h=$_POST['bottom'];
  $image="../files/fotos/".$datosUsuarioActual['uss_foto'];

  list( $width,$height ) = getimagesize( $image );
  $newwidth = 800;
  $newheight = 600;

  $thumb = imagecreatetruecolor( $newwidth, $newheight );
  $source = imagecreatefromjpeg($image);

  imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
  imagejpeg($thumb,$image,100); 


  $im = imagecreatefromjpeg($image);
  $dest = imagecreatetruecolor($w,$h);
	
  imagecopyresampled($dest,$im,0,0,$x1,$y1,$w,$h,$w,$h);
  imagejpeg($dest,"../files/fotos/".$datosUsuarioActual['uss_foto'], 100);

  $destinos = validarUsuarioActual($datosUsuarioActual);
	
  echo '<script type="text/javascript">window.location.href="' .$destinos. 'perfil.php"</script>';
  exit();
}
?>