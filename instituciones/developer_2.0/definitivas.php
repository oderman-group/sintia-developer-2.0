<?php

//$notasEstudiante = mysql_num_rows(mysql_query("SELECT * FROM academico_calificaciones WHERE cal_id_estudiante='".$datosEstudianteActual[0]."'",$conexion));

//CALCULO DEFINITIVA

$consultaD = mysql_query("SELECT * FROM academico_actividades 
WHERE act_id_carga='".$carga."' AND act_registrada=1 AND act_estado=1 AND act_periodo='".$periodo."' $filtro ",$conexion);

if(mysql_errno()!=0){echo mysql_error(); exit();}

$numConsultaD = mysql_num_rows($consultaD);

	$acumulaValor = 0;

	$sumaNota = 0;

	$definitiva = 0;

	$porNuevo = 0;

	$notaMultiplicada = 0;

	while($resultadoD = mysql_fetch_array($consultaD)){

		$nota = mysql_fetch_array(mysql_query("SELECT * FROM academico_calificaciones WHERE cal_id_actividad='".$resultadoD[0]."' AND cal_id_estudiante='".$estudiante."'",$conexion));

		if(mysql_errno()!=0){echo mysql_error(); exit();}

		if($nota[3]!=""){

			$porNuevo = ($resultadoD[3] / 100);

			$acumulaValor = ($acumulaValor + $porNuevo);

			$notaMultiplicada = ($nota[3] * $porNuevo);

			$sumaNota = ($sumaNota + $notaMultiplicada);

		}

	}

	if($acumulaValor>0){// SI EL VALOR ACUMULADO DE LOS PORCENTAJES ES MAYOR QUE 0

		$definitiva = round(($sumaNota / $acumulaValor),$config['conf_decimales_notas']);//NOTA DEFINITIVA

		$nn++;

	}	

	$porcentajeActual = ($acumulaValor * 100);

	//REGLA DE 3 PARA LA BARRA DE PROGRESO

	@$progreso = ($definitiva * 100);

	@$progreso = ($progreso / $config[4]);

	//COLOR DE LA BARRA Y DE LAS DEFINITIVAS

	if($definitiva<$config[5]){

		$colorDefinitiva = $config[6];

		$colorProgreso = "danger";

	}	

	if($definitiva==$config[5]){	

		$colorDefinitiva = $config[7];

		$colorProgreso = "warning";

	}	

	if($definitiva>$config[5]){	

		$colorDefinitiva = $config[7];

		$colorProgreso = "striped";

	}	

	//NUMEROS ENTEROS DE DEFINITIVAS AGREGARLES EL .0 AL FINAL 4 = 4.0

	for($i=$config[3]; $i<=$config[4]; $i++){

		if($definitiva==$i)

		$definitiva = $definitiva.".0";

	}

?>