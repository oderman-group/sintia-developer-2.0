<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DV0014';
include("../compartido/historial-acciones-guardar.php");

include($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");

define('ENVIROMENT_TWO', $_POST['enviroment']);

switch (ENVIROMENT_TWO) {
	case 'LOCAL':
		include(ROOT_PATH."/conexion-datos.php");
		break;

	case 'DEV':
		include(ROOT_PATH."/conexion-datos-developer.php");
		break;

	case 'PROD':
		include(ROOT_PATH."/conexion-datos-production.php");
		break;

	default:
		include(ROOT_PATH."/conexion-datos.php");
		break;	
}


//AGREGAR/MODIFICAR COLUMNAS
$sql = $_POST['script'];

echo "<pre>".$sql."</pre>";

try {
    //consulta a instituciones no bloqueadas
    $conexionAdmin = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
    $consultaInstituciones = mysqli_query($conexionAdmin, "SELECT * FROM instituciones
    WHERE ins_bloqueada='0'
    ");

    if(empty($_POST["continue"]) || $_POST["continue"]!=1 || empty($_SERVER['HTTP_REFERER'])){
        
        echo "<h3>Este es el listado de instituciones, con sus años, a los cuales se aplicará el script mostrado arriba</h3>";
		$num = 1;
        while($listaInstituciones = mysqli_fetch_array($consultaInstituciones, MYSQLI_BOTH)) {
            echo $num.") {$listaInstituciones['ins_siglas']} - {$listaInstituciones['ins_bd']} ({$listaInstituciones['ins_years']})<br>";
			$num++;
        }
?>
        <form class="form-horizontal" action="dev-ejecutar-scripts-guardar.php" method="post">
			<input type="hidden" name="enviroment" value="<?=$_POST['enviroment'];?>">
			<input type="hidden" name="script" value="<?=$_POST['script'];?>">
			<input type="hidden" name="continue" value="1">

			<input type="submit" class="btn btn-primary" value="Continuar">
		</form>
<?php 
        exit();
    }
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

$num = 1;
while($datosInstitucion = mysqli_fetch_array($consultaInstituciones, MYSQLI_BOTH)){
	
	if(empty($datosInstitucion['ins_years']) || empty($datosInstitucion['ins_bd'])) {
		continue;
	}

	$yearArray = explode(",", $datosInstitucion['ins_years']);
	$yearStart = $yearArray[0];
	$yearEnd = $yearArray[1];
	
	while($yearStart <= $yearEnd){
		try {
			$CURRENTDB = $datosInstitucion['ins_bd']."_".$yearStart;

			try{
				$conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $CURRENTDB);
				$resultado = mysqli_query($conexion, "SHOW DATABASES LIKE '{$CURRENTDB}';");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			
			if(mysqli_num_rows($resultado) > 0){

				mysqli_query($conexion, "{$sql}");
				$filasAfectadas = mysqli_affected_rows($conexion);

				if($filasAfectadas > 0){
					echo $num." <span style='color:blue;'>".$filasAfectadas." filas afectadas para ".$CURRENTDB."</span><br>";
				} else {
					echo $num." <span style='color:black; background-color:yellow;'>No aparecen columnas afectadas, pero es muy probable que si haya aplicado los cambios para ".$CURRENTDB."</span><br>";
				}
				

			} else {
				echo $num." <span style='color:red; font-weight:bold;'>La base de datos no existe: ".$CURRENTDB."</span><br>";
			}

			echo "<br>";
			$num ++;
			$yearStart ++;

		} catch (Exception $e) {
			echo "<span style='color:black; background-color:gold;'>Exception caught for database: </span> <b>{$CURRENTDB}</b>:  CODE: {$e->getCode()} - MESSAGE: ".$e->getMessage()."<br>";

			echo "<br>";
			$num ++;
			$yearStart ++;
		}

		
	}

	echo "<hr>";
}

	include("../compartido/guardar-historial-acciones.php");
	echo '<a href="dev-ejecutar-scripts.php">Volver</a>';
	exit();