<?php
include("session.php");

date_default_timezone_set("America/New_York");//Zona horaria

//Variables necesarias
$nueva = $_POST['tipoInsti']; //VALORES: 1 SI ES NUEVA Y 0 SI ES ANTIGUA

//FECHAS NECESARIAS PARA LOS DATOS
$fecha=date("Y-m-d");
$fechaCompleta = date("Y-m-d H:i:s");

if($nueva==0){//PARA ANTIGUAS
    //DATOS BASICOS DE LA INSTITUCIÓN
    $idInsti = $_POST['idInsti'];//LE MODIFICAMOS EL VALOR SOLO CUANDO LA INSTITUCION ES ANTIGUA
    
    $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones 
    WHERE ins_id = ".$idInsti." AND ins_enviroment='".ENVIROMENT."'");
    $datosInsti = mysqli_fetch_array($consulta, MYSQLI_BOTH);
    
    $siglasBD = $datosInsti['ins_bd'];//AQUI COLOCAMOS LAS SIGLAS QUE VAN AL INTERMEDIO DEL NOMBRE DE LA BD EJE: monbiliar_{[$siglasBD]}_{[$year]}

    $year = $_POST['yearA'];//AQUI COLOCAMOS EL AÑO QUE VAN AL FINAL DEL NOMBRE DE LA BD EJE: monbiliar_{[$siglasBD]}_{[$year]}
    $yearAnterior=($year-1);//CALCULAMOS AÑO ANTERIOR PARA CUANDO SE CONSULTA LOS DATOS DEL AÑO ANTERIOR DE LA INSTITUCIÓN ANTIGUA

    $bd=$siglasBD.'_'.$year;//BD NUEVA
    $bdAnterior=$siglasBD.'_'.$yearAnterior;//BD ANTIGUA PARA EL TRASPASO DE DATOS

    $bdInstitucion=$siglasBD;//SE USARA PARA VALIDAR EXISTENCIA DE LA INSTITUCIÓN
}

if($nueva==1){//PARA NUEVAS
    //DATOS BASICOS DE LA INSTITUCIÓN
    $siglasBD = $_POST['siglasBD'];//AQUI COLOCAMOS LAS SIGLAS QUE VAN AL INTERMEDIO DEL NOMBRE DE LA BD EJE: monbiliar_{[$siglasBD]}_{[$year]}
    $nombreInsti = $_POST['nombreInsti'];//NOMBRE PARA LA NUEVA INSTITUCIÓN
    $siglasInst = $_POST['siglasInst'];//SIGLAS PARA LA NUEVA INSTITUCIÓN

    $year = $_POST['yearN'];//AQUI COLOCAMOS EL AÑO QUE VAN AL FINAL DEL NOMBRE DE LA BD EJE: monbiliar_{[$siglasBD]}_{[$year]}

    $bd=BD_PREFIX.$siglasBD.'_'.$year;//BD NUEVA

    $bdInstitucion=BD_PREFIX.$siglasBD;//SE USARA PARA VALIDAR EXISTENCIA DE LA INSTITUCIÓN
}

try {

    if(empty($_POST["continue"]) || $_POST["continue"]!=1 || empty($_SERVER['HTTP_REFERER'])){

        include("dev-crear-bd-informacion.php");
        exit();

    }
} catch (Exception $e) {
	echo $e->getMessage();
}

//Creamos y seleccionamos BD
// mysqli_query($conexion, "CREATE DATABASE /*!32312 IF NOT EXISTS*/ $bd");
mysqli_select_db($conexion, $bd) or die('Error al selccionar la bd');

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
include('ingresar-datos-bd.php');

echo '<script type="text/javascript">window.location.href="dev-crear-nueva-bd.php?success=SC_DT_10";</script>';
