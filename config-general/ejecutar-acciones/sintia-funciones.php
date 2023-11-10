<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SINTIA - FUNCTIONS EXECUTE</title>
    <style>
        body {
            background-color: #333; /* Fondo oscuro */
            color: #fff; /* Texto blanco */
            font-family: Arial, sans-serif; /* Tipo de fuente (puedes cambiarlo según tus preferencias) */
            margin: 0; /* Elimina el margen predeterminado del cuerpo */
            padding: 0; /* Elimina el relleno predeterminado del cuerpo */
        }

        /* Puedes agregar más estilos CSS según sea necesario */
    </style>
</head>
<body>

<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
//include(ROOT_PATH."/conexion-datos.php");
$conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);


$arrayTables = [
	'academico_matriculas', 
	'academico_areas', 
	'academico_ausencias', 
	'academico_cargas', 
	'academico_categorias_notas', 
	'academico_grados'
];

foreach($arrayTables as $table) {

	echo "<h1>EJECUCIÓN PARA LA TABLA {$table}</H1>";

	mysqli_query($conexion, "USE {$baseDatosServicios}");

	$existeTrigger = mysqli_num_rows(mysqli_query($conexion, "SELECT trigger_name
	FROM information_schema.triggers
	WHERE event_object_table = '{$table}'
	AND trigger_name = 'tr_{$table}_unique_id'
	"));

	if($existeTrigger > 0) {
		if(!mysqli_query($conexion, "DROP TRIGGER {$baseDatosServicios}.tr_{$table}_unique_id")){
			echo "No se pudo BORRAR EL TRIGGER tr_{$table}_unique_id<br>";
		}
	}

	$camposDestino = null;
	$tablaDestino  = null;
	$tablaOrigen   = null;
	$camposOrigen  = null;
	$PREFIJO = null;
	$CAMPOTRIGGER = null;

	$columns = mysqli_query($conexion, "SHOW COLUMNS FROM {$baseDatosServicios}.{$table}");
	echo "COLUMNS NUM: ".$numColumns = mysqli_num_rows($columns)."<br>";

	$cont = 1;
	while( $columnsResult = mysqli_fetch_array($columns, MYSQLI_BOTH) ) {
		if($columnsResult['Field'] == 'id_nuevo') {
			continue;
		}

		if($cont == 1) {
			$PREFIJO      = substr($columnsResult['Field'],0,3);
			$CAMPOTRIGGER = $columnsResult['Field'];
		}
		
		$camposDestino .= $columnsResult['Field'].",";

		if($columnsResult['Field'] == 'institucion'  || $columnsResult['Field'] == 'year') {
			continue;
		}
		$camposOrigen  .= $columnsResult['Field'].",";

		$cont ++;
	}

	$camposDestino = substr($camposDestino,0,-1);
	echo "CAMPOS DESTINO: {$camposDestino}<br><br>";
	$tablaDestino  = $table;
	$tablaOrigen   = $table;


	if(!mysqli_query($conexion, "TRUNCATE TABLE {$baseDatosServicios}.{$tablaDestino}")){
		echo "No se pudo TRUNCAR la tabla {$baseDatosServicios}.{$tablaDestino}<br>";
	}

	//consulta a instituciones activas
	$consultaInstituciones = mysqli_query($conexion, "SELECT * FROM {$baseDatosServicios}.instituciones
	WHERE ins_estado=1 AND ins_enviroment='".ENVIROMENT."'
	");

	$totalCompleto = 0;
	while($datosInstitucion = mysqli_fetch_array($consultaInstituciones, MYSQLI_BOTH)){
		$yearArray = explode(",", $datosInstitucion['ins_years']);
		$yearStart = $yearArray[0];
		$yearEnd = $yearArray[1];
		
		while($yearStart <= $yearEnd){

			mysqli_query($conexion, "USE {$datosInstitucion['ins_bd']}_{$yearStart}");
			$sqlTable = "SHOW TABLES LIKE '{$tablaOrigen}'";
			$result = mysqli_query($conexion, $sqlTable);

			if ($result->num_rows > 0) {

				try {

					$getDataQuerySQL = "INSERT INTO $baseDatosServicios.".$tablaDestino."(".$camposDestino.") SELECT ".$camposOrigen."{$datosInstitucion['ins_id']},{$yearStart} FROM ".$datosInstitucion['ins_bd']."_".$yearStart.".".$tablaOrigen;
					$getDataQuery = mysqli_query($conexion, $getDataQuerySQL);
					
					$totalRegistros = mysqli_affected_rows($conexion);
					$totalCompleto += $totalRegistros;

					echo "Se completó correctamente la inserción de <b>".$totalRegistros."</b> registros desde <b>".$tablaOrigen." ".$datosInstitucion['ins_bd']."_".$yearStart."</b><br>";

				} catch (Exception $e) {
					echo "<span style='color:red;'>--LA SIGUIENTE SENTENCIA NO SE EJECUTÓ BIEN: <b>".$getDataQuerySQL."</b>-- porque {$e}</span><br>";
				}
					
			} else {
				echo "<span style='color:red;'>LA SIGUIENTE TABLA NO EXISTE EN EL ORIGEN: <b>".$tablaOrigen." ".$datosInstitucion['ins_bd']."_".$yearStart."</b></span><br>";
			}

			$yearStart ++;
		}
	}

	echo "En total fueron <b>$totalCompleto</b> registros<hr>";

	mysqli_query($conexion, "USE {$baseDatosServicios}");

	$sqlTrigger = "
		CREATE TRIGGER tr_{$table}_unique_id
		BEFORE INSERT ON {$table}
		FOR EACH ROW
		BEGIN
		DECLARE nuevo_id INT;
		SET nuevo_id = UNIX_TIMESTAMP(NOW());
		
		SET NEW.{$CAMPOTRIGGER} = CONCAT('{$PREFIJO}', nuevo_id);
		END
	";
	mysqli_query($conexion, $sqlTrigger);

}
?>

</body>
</html>