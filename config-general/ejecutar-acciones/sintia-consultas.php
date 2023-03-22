<?php
include($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");

define('ENVIROMENT', 'PROD');

switch (ENVIROMENT) {
	case 'LOCAL':
		include(ROOT_PATH."/conexion-datos.php");
		break;

	case 'DEV':
		include(ROOT_PATH."/conexion-datos-developer.php");
		break;

	case 'PROD':
		include(ROOT_PATH."/conexion-datos-production.php");
		break;
}


//AGREGAR/MODIFICAR COLUMNAS
$sql = "
ALTER TABLE academico_matriculas_retiradas MODIFY COLUMN matret_fecha datetime DEFAULT NULL NULL;
";
echo "<pre>".$sql."</pre>";

try {
    //consulta a instituciones no bloqueadas
    $conexionAdmin = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
    $consultaInstituciones = mysqli_query($conexionAdmin, "SELECT * FROM instituciones
    WHERE ins_bloqueada='0'
    ");

    if(empty($_GET["continue"]) || $_GET["continue"]!=1 || empty($_SERVER['HTTP_REFERER'])){
        
        echo "<h3>Este es el listado de instituciones, con sus años, a los cuales se aplicará el script mostrado arriba</h3>";
		$num = 1;
        while($listaInstituciones = mysqli_fetch_array($consultaInstituciones, MYSQLI_BOTH)) {
            echo $num.") {$listaInstituciones['ins_siglas']} - {$listaInstituciones['ins_bd']} ({$listaInstituciones['ins_years']})<br>";
			$num++;
        }
?>
        <p><a href="sintia-consultas.php?continue=1">CONTINUAR</a></p>
<?php 
        exit();
    }
} catch (Exception $e) {
	echo $e->getMessage();
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

			$conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $CURRENTDB);

			$resultado = mysqli_query($conexion, "SHOW DATABASES LIKE '{$CURRENTDB}';");
			
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
