<?php
include("session.php");

date_default_timezone_set("America/New_York");//Zona horaria

//Variables necesarias
$nueva = $_GET['nueva']; //VALORES: 1 SI ES NUEVA Y 0 SI ES ANTIGUA

//DATOS BASICOS DE LA INSTITUCIÓN
$idInsti = $_GET['idInsti'];//LE MODIFICAMOS EL VALOR SOLO CUANDO LA INSTITUCION ES ANTIGUA
$siglasBD = $_GET['siglasBD'];//AQUI COLOCAMOS LAS SIGLAS QUE VAN AL INTERMEDIO DEL NOMBRE DE LA BD EJE: monbiliar_{[$siglasBD]}_{[$year]}
$nombreInsti = $_GET['nombreInsti'];//NOMBRE PARA LA NUEVA INSTITUCIÓN
$siglasInst = $_GET['siglasInst'];//SIGLAS PARA LA NUEVA INSTITUCIÓN

$year = $_GET['year'];//AQUI COLOCAMOS EL AÑO QUE VAN AL FINAL DEL NOMBRE DE LA BD EJE: monbiliar_{[$siglasBD]}_{[$year]}
$yearAnterior=($year-1);//CALCULAMOS AÑO ANTERIOR PARA CUANDO SE CONSULTA LOS DATOS DEL AÑO ANTERIOR DE LA INSTITUCIÓN ANTIGUA

//FECHAS NECESARIAS PARA LOS DATOS
$fecha=date("Y-m-d");
$fechaCompleta = date("Y-m-d H:i:s");

$bd=BD_PREFIX.$siglasBD.'_'.$year;//BD NUEVA
$bdAnterior= BD_PREFIX.$siglasBD.'_'.$yearAnterior;//BD ANTIGUA PARA EL TRASPASO DE DATOS

//Creamos y seleccionamos BD
// mysqli_query($conexion, "CREATE DATABASE /*!32312 IF NOT EXISTS*/ $bd");
mysqli_select_db($conexion, $bd) or die('Error al selccionar la bd');
echo "<pre>Se estableción la conexión con la BD.</pre>";

$fichero = 'crear-bd-nueva.sql';  // Ruta al fichero que vas a cargar.

// Linea donde vamos montando la sentencia actual
$temp = '';

// Flag para controlar los comentarios multi-linea
$comentario_multilinea = false;

// Leemos el fichero SQL al completo
$lineas = file($fichero);

// Procesamos el fichero linea a linea
foreach ($lineas as $linea) {

    $linea = trim($linea); // Quitamos espacios/tabuladores por delante y por detrás

    // Si es una linea en blanco o tiene un comentario nos la saltamos
    if ( (substr($linea, 0, 2) == '--') or (substr($linea, 0, 1) == '#') or ($linea == '') )
        continue;

    // Saltamos los comentarios multilinea /* texto */ Se detecta cuando empiezan y cuando acaban mediante estos dos ifs  
    if ( substr($linea, 0, 2) == '/*' ) $comentario_multilinea = true;

    if ( $comentario_multilinea ) {
       if ( (substr($linea, -2, 2) == '*/') or (substr($linea, -3, 3) == '*/;') ) $comentario_multilinea = false;
       continue;
    }

    // Añadimos la linea actual a la sentencia en la que estamos trabajando 
    $temp .= $linea;

    // Si la linea acaba en ; hemos encontrado el final de la sentencia
    if (substr($linea, -1, 1) == ';') {
        // Ejecutamos la consulta
        mysqli_query($conexion, $temp) or print('<strong>Error en la consulta</strong> \'' . $temp . '\' - ' . mysqli_error($conexion) . "<br /><br />\n");
        // Limpiamos sentencia temporal
        $temp = '';
    }
}
echo "<pre>Se creó la estructura de la base de datos.</pre>";
include('ingresar-datos-bd.php');
echo "Todo el proceso concluyó exitosamente";
exit();

echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
