<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once("../class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");
$Plataforma = new Plataforma;

$curso='';
if(!empty($_GET["curso"])) {
  $curso = base64_decode($_GET["curso"]);
}
$grupo='';
if(!empty($_GET["grupo"])) {
  $grupo = base64_decode($_GET["grupo"]);
}
$per='';
if(!empty($_GET["per"])) {
  $per = base64_decode($_GET["per"]);
}

require_once("../class/servicios/GradoServicios.php");
$filtroAdicional= "AND mat_grado='".$curso."' AND mat_grupo='".$grupo."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
$cursoActual=GradoServicios::consultarCurso($curso);
$asig =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"",$cursoActual);
$num_asg = mysqli_num_rows($asig);

$consultaGrados=mysqli_query($conexion, "SELECT * FROM academico_grados, ".BD_ACADEMICA.".academico_grupos gru WHERE gra_id='" . $curso . "' AND gru.gru_id='" . $grupo . "' AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}");
$grados = mysqli_fetch_array($consultaGrados, MYSQLI_BOTH);
?>

<head>
	<title>Sabanas con indicador</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" href="../files/images/ico.png">
</head>

<body style="font-family:Arial;">
	<div align="center" style="margin-bottom:20px;">
		<img src="../files/images/logo/<?= $informacion_inst["info_logo"] ?>" height="150"><br>
		<?= $informacion_inst["info_nombre"] ?><br>
		INFORME DE SABANAS CON INDICADOR - PERIODO: <?= $per; ?></br>
		<b><?= strtoupper($grados["gra_nombre"] . " " . $grados["gru_nombre"]); ?></b><br>
	</div>
	<table bgcolor="#FFFFFF" width="80%" cellspacing="5" cellpadding="5" rules="all" border="<?= $config[13] ?>" style="border:solid; border-color:<?= $config[11] ?>;" align="center">
		<tr style="font-weight:bold; font-size:12px; height:30px; background:#6017dc; color:white;">
			<td align="center" rowspan="2">No</b></td>
			<td align="center" rowspan="2">Estudiante</td>
			<!--<td align="center">Gru</td>-->
			<?php
			$materias1 = mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso=" . $curso . " AND car_grupo='" . $grupo . "'");
			while ($mat1 = mysqli_fetch_array($materias1, MYSQLI_BOTH)) {
				
				$nombresMat = mysqli_query($conexion, "SELECT * FROM academico_materias WHERE mat_id=" . $mat1[4]);
				$Mat = mysqli_fetch_array($nombresMat, MYSQLI_BOTH);

				$consultaActividades=mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga WHERE ipc_carga='" . $mat1['car_id'] . "' AND ipc_periodo='" . $per . "'");
				$activivdadesNum = mysqli_num_rows($consultaActividades);
				if ($activivdadesNum == 0) {
					$activivdadesNum = 1;
				}
			?>
				<td align="center" colspan="<?= $activivdadesNum; ?>"><?= strtoupper($Mat[3]); ?></td>
			<?php
			}
			?>
		</tr>

		<tr style="font-weight:bold; font-size:12px; height:30px; background:#6017dc; color:white;">
			<?php
			$cargas = mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso=" . $curso . " AND car_grupo='" . $grupo . "'");
			while ($car = mysqli_fetch_array($cargas, MYSQLI_BOTH)) {
				$activivdades = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga
				INNER JOIN academico_indicadores ON ind_id=ipc_indicador
				WHERE ipc_carga='" . $car['car_id'] . "' AND ipc_periodo='" . $per . "' ");

				$activivdadesNum = mysqli_num_rows($activivdades);
				if ($activivdadesNum == 0) {
					echo '<td align="center">&nbsp;</td>';
				}
				while ($act = mysqli_fetch_array($activivdades, MYSQLI_BOTH)) {
			?>
					<td align="center" title="<?= $act['ind_nombre'] . " (" . $act['ipc_valor'] . "%)"; ?>"><?= $act['ipc_indicador']; ?></td>
			<?php
				}
			}
			?>
		</tr>

		<?php
		$cont = 1;
		$mayor = 0;
		$nombreMayor = "";
		while ($fila = mysqli_fetch_array($asig, MYSQLI_BOTH)) {
		$nombre = Estudiantes::NombreCompletoDelEstudiante($fila);	

		?>
			<tr style="font-size:13px;">
				<td align="center"> <?= $cont; ?></td>
				<td><?=$nombre?></td>
				<!--<td align="center"><?php if ($fila[7] == 1) echo "A";
										else echo "B"; ?></td> -->
				<?php
				$suma = 0;
				$materias1 = mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso=" . $curso . " AND car_grupo='" . $grupo . "'");
				while ($mat1 = mysqli_fetch_array($materias1, MYSQLI_BOTH)) {


					$activivdades = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga 
					WHERE ipc_carga='" . $mat1['car_id'] . "' AND ipc_periodo='" . $per . "'");
					$activivdadesNum = mysqli_num_rows($activivdades);
					if ($activivdadesNum == 0) {
						echo '<td align="center">-</td>';
					}
					while ($act = mysqli_fetch_array($activivdades, MYSQLI_BOTH)) {
						//Consulta de recuperaciones si ya la tienen puestas.
						$consultaNotas=mysqli_query($conexion, "SELECT * FROM academico_indicadores_recuperacion WHERE rind_estudiante=" . $fila['mat_id'] . " AND rind_indicador='" . $act['ipc_indicador'] . "' AND rind_periodo='" . $per . "' AND rind_carga='" . $mat1['car_id'] . "'");
						$notas = mysqli_fetch_array($consultaNotas, MYSQLI_BOTH);

						$notaRecuperacion = 0;
						if(!empty($notas['rind_valor_indicador_registro']) && $notas['rind_valor_indicador_registro']>0){
							$notaRecuperacion = round($notas['rind_nota_actual']/($notas['rind_valor_indicador_registro']/100),2);
						}
						
						if ((!empty($notas['rind_nota']) && !empty($notas['rind_nota_original'])) && ($notas['rind_nota'] > $notas['rind_nota_original'])) {
							$notaRecuperacion = round($notas['rind_nota'],2);
						}
						$notaRecuperacionFinal=$notaRecuperacion;
						$title='';
						if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
						  $title='title="Nota Cuantitativa: '.$notaRecuperacion.'"';
						  $estiloNotaRecuperacion = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaRecuperacion);
						  $notaRecuperacionFinal= !empty($estiloNotaRecuperacion['notip_nombre']) ? $estiloNotaRecuperacion['notip_nombre'] : "";
						}

						//Color nota
						if ($notaRecuperacion < $config[5] and $notaRecuperacion != "") $colorNota = $config[6];
						elseif ($notaRecuperacion >= $config[5]) $colorNota = $config[7];
				?>
						<td align="center" style="color:<?= $colorNota; ?>;" <?=$title;?>><?= $notaRecuperacionFinal; ?></td>
				<?php
					}
				}
				?>
			</tr>
		<?php
			$cont++;
		} //Fin mientras que
		?>
	</table>



	</center>
	<div align="center" style="font-size:10px; margin-top:10px;">
		<img src="<?=$Plataforma->logo;?>" height="100"><br>
		SINTIA - SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL
	</div>
</body>

</html>