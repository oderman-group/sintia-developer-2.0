<?php
include("session-compartida.php");
$idPaginaInterna = 'DT0227';

if ($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && !Modulos::validarSubRol([$idPaginaInterna])) {
	echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH . "/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH . "/main-app/compartido/overlay.php");
require_once(ROOT_PATH . "/main-app/class/Estudiantes.php");
require_once(ROOT_PATH . "/main-app/class/Plataforma.php");
require_once(ROOT_PATH . "/main-app/class/Usuarios.php");
require_once(ROOT_PATH . "/main-app/class/UsuariosPadre.php");
require_once(ROOT_PATH . "/main-app/class/servicios/GradoServicios.php");
require_once(ROOT_PATH . "/main-app/class/Asignaturas.php");
require_once(ROOT_PATH . "/main-app/class/Calificaciones.php");
require_once(ROOT_PATH . "/main-app/class/Boletin.php");
require_once(ROOT_PATH . "/main-app/class/CargaAcademica.php");
$Plataforma = new Plataforma;

$year = $_SESSION["bd"];
if (isset($_POST["year"])) {
	$year = $_POST["year"];
}
if (isset($_GET["year"])) {
	$year = base64_decode($_GET["year"]);
}

$periodoActual = 4;
if (isset($_POST["periodo"])) {
	$periodoActual = $_POST["periodo"];
}
if (isset($_GET["periodo"])) {
	$periodoActual = base64_decode($_GET["periodo"]);
}

switch ($periodoActual) {
	case 1:
		$periodoActuales = "Primero";
		break;
	case 2:
		$periodoActuales = "Segundo";
		break;
	case 3:
		$periodoActuales = "Tercero";
		break;
	case 4:
		$periodoActuales = "Final";
		break;
	case 5:
		$periodoActual = 4;
		$periodoActuales = "Final";
		break;
}
//CONSULTA ESTUDIANTES MATRICULADOS
$curso = '';
if (isset($_POST["curso"])) {
	$curso = $_POST["curso"];
}
if (isset($_GET["curso"])) {
	$curso = base64_decode($_GET["curso"]);
}

$grupo = '';
if (isset($_POST["grupo"])) {
	$grupo = $_POST["grupo"];
}
if (isset($_GET["grupo"])) {
	$grupo = base64_decode($_GET["grupo"]);
}

$id = '';
if (isset($_POST["id"])) {
	$id = $_POST["id"];
}
if (isset($_GET["id"])) {
	$id = base64_decode($_GET["id"]);
}
if (!empty($id)) {
	$filtro               = " AND mat_id='" . $id . "'";
	$matriculadosPorCurso = Estudiantes::estudiantesMatriculados($filtro, $year);
	$estudiante           = $matriculadosPorCurso->fetch_assoc();
	if (!empty($estudiante)) {
		$idEstudiante = $estudiante["mat_id"];
		$curso        = $estudiante["mat_grado"];
		$grupo        = $estudiante["mat_grupo"];
	}
}
$periodoFinal = $config["conf_periodos_maximos"];

$tiposNotas         = [];
$cosnultaTiposNotas = Boletin::listarTipoDeNotas($config["conf_notas_categoria"], $year);
while ($row = $cosnultaTiposNotas->fetch_assoc()) {
	$tiposNotas[] = $row;
}


$listaDatos         = [];
if (!empty($curso) && !empty($grupo) && !empty($periodoFinal) && !empty($year)) {
	$periodos = [];
	for ($i = 1; $i <= $periodoFinal; $i++) {
		$periodos[$i] = $i;
	}
	$datos = Boletin::datosBoletin($curso, $grupo, $periodos, $year, $id,false);
	while ($row = $datos->fetch_assoc()) {
		$listaDatos[] = $row;
	}
	include("../compartido/agrupar-datos-boletin-periodos-mejorado.php");
}


?>
<!doctype html>

<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<link rel="shortcut icon" href="<?= $Plataforma->logo; ?>">
	<link href="../../config-general/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    	<!-- notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style type="text/css">
	        .page {
            page-break-after: always;
            /* Crea un salto de página después de este div */
        }
        .swal2-container {
        z-index:19999 !important;
        }

        #saltoPagina {
            PAGE-BREAK-AFTER: always;
        }
        #guardarPDF {
            cursor: pointer;
        }

        .divBordeado {
            height: 3px;
            border: 3px solid #9ed8ed;
            background-color: #00ACFB;
        }

        .btn-flotante {
            font-size: 16px;
            /* Cambiar el tamaño de la tipografia */
            text-transform: uppercase;
            /* Texto en mayusculas */
            font-weight: bold;
            /* Fuente en negrita o bold */
            color: #ffffff;
            /* Color del texto */
            border-radius: 5px;
            /* Borde del boton */
            letter-spacing: 2px;
            /* Espacio entre letras */
            background-color: #E91E63;
            /* Color de fondo */
            padding: 18px 30px;
            /* Relleno del boton */
            position: fixed;
            bottom: 40px;
            right: 40px;
            transition: all 300ms ease 0ms;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
            z-index: 99;
        }

        .btn-flotante:hover {
            background-color: #2c2fa5;
            /* Color de fondo al pasar el cursor */
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.3);
            transform: translateY(-7px);
        }

        @media only screen and (max-width: 600px) {
            .btn-flotante {
                font-size: 14px;
                padding: 12px 20px;
                bottom: 20px;
                right: 20px;
            }
        }
	</style>
</head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>

<body style="font-family:Arial;">
<div id="contenido">
	<?php foreach ($estudiantes  as  $estudiante) {
		$materiasPerdidas = 0;
	?><div class="page">
		<?php
		$nombreInforme = "REGISTRO DE VALORACIÓN";
		if ($config['conf_mostrar_encabezado_informes'] == 1) {
			include("../compartido/head-informes.php");
		} else {
		?>
			<div align="center" style="margin-bottom:10px; font-weight:bold;">
				<img class="img-thumbnail" src="../files/images/logo/<?= $informacion_inst["info_logo"] ?>" width="100%"><br><br>
				<b><?= $nombreInforme ?></b>
			</div>
		<?php } ?>
		<div>&nbsp;</div>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="font-size:10px;">
			<tr>
				<td>C&oacute;digo: <b><?= $estudiante["mat_matricula"]; ?></b></td>
				<td>Nombre: <b><?= $nombre ?></b></td>
				<td>Matricula: <b><?= $estudiante["mat_numero_matricula"]; ?></b></td>
			</tr>

			<tr>
				<td>Grado: <b><?= $estudiante["gra_nombre"] . " " . $estudiante["gru_nombre"]; ?></b></td>
				<td>Periodo: <b><?= strtoupper($periodoActuales); ?></b></td>
				<td>Folio: <b><?= $estudiante["mat_folio"]; ?></b></td>
			</tr>
		</table>
		<br>
		<table width="100%" align="left">
			<tr style="border:solid; font-weight:bold; color:#000; font-size:10px;border-color:<?= $Plataforma->colorUno; ?>;">
				<td width="20%" align="center">AREAS/ ASIGNATURAS</td>
				<td width="2%" align="center">I.H</td>
				<td width="4%" align="center">DEF</td>
				<td width="8%" align="center">DESEMPE&Ntilde;O</td>
				<td width="2%" align="center">AUS</td>
				<td width="15%" align="center">OBSERVACIONES</td>
			</tr>
			<tr>
				<td class="area"></td>
			</tr>
			<?php foreach ($estudiante["areas"]  as  $area) { ?>
				<tr style="font-weight:bold;font-size:10px;border-top: 1px solid black;">
					<td><?= $area["ar_nombre"]; ?></td>
					<td align="center"></td>
					<td align="center">
						<?php
						$notaArea = $area["nota_area_acumulada"] ;
						if ($estudiante["gra_id"] > 11 && $config['conf_id_institucion'] != EOA_CIRUELOS) {
							$notaFA = ceil($notaArea);
							switch ($notaFA) {
								case 1:
									echo "D";
									break;
								case 2:
									echo "I";
									break;
								case 3:
									echo "A";
									break;
								case 4:
									echo "S";
									break;
								case 5:
									echo "E";
									break;
							}
						} else {
							echo Boletin::formatoNota($notaArea);
						}

						?></td>
					<td align="center"></td>
					<td align="center"></td>

				</tr>

				<?php foreach ($area["cargas"]  as  $carga) {
					$cantidadAreas = count($area["cargas"]);

					$notaCarga = $carga["nota_carga_acumulada"] ;

					if ($notaCarga < $config['conf_nota_minima_aprobar']) {
						$materiasPerdidas++;
					}
					$notaCargaDeseno = Boletin::determinarRango($notaCarga, $tiposNotas);
					Utilidades::valordefecto($notaCargaDeseno["notip_desde"],0);
					Utilidades::valordefecto($notaCargaDeseno["notip_hasta"],1);
					if ($notaCarga >= $notaCargaDeseno["notip_desde"] && $notaCarga <= $notaCargaDeseno["notip_hasta"]) {
						if ($estudiante["gra_id"] > 11 && $config['conf_id_institucion'] != EOA_CIRUELOS) {
							$notaFD = ceil($notaCarga);
							switch ($notaFD) {
								case 1:
									$notaCarga                       = "D";
									$notaCargaDeseno["notip_nombre"] = "BAJO";
									break;
								case 2:
									$notaCarga                       = "I";
									$notaCargaDeseno["notip_nombre"] = "BAJO";
									break;
								case 3:
									$notaCarga                       = "A";
									$notaCargaDeseno["notip_nombre"] = "B&Aacute;SICO";
									break;
								case 4:
									$notaCarga                       = "S";
									$notaCargaDeseno["notip_nombre"] = "ALTO";
									break;
								case 5:
									$notaCarga                       = "E";
									$notaCargaDeseno["notip_nombre"] = "SUPERIOR";
									break;
							}
						}
					}
				?>
					<tr style="font-size:10px; font-weight:normal;">
						<td style="font-size:10px;"><?php echo $carga["mat_nombre"]; ?></td>
						<td align="center" style="font-size:10px;"><?= $carga["car_ih"] ?></td>
						<td align="center" style="font-size:10px;"><?= Boletin::formatoNota($notaCarga);  ?></td>
						<td align="center" style="font-size:10px;"><?= $notaCargaDeseno["notip_nombre"] ?></td>
						<td align="center" style="font-size:10px;"><?= $carga["fallas"] ?></td>
						<td align="center"></td>
					</tr>
				<?php } ?>
				<tr style="font-size:10px;border-top: 1px solid black;"></tr>
			<?php } ?>
		</table>

		<p>&nbsp;</p>


		<?php
		$msj = "";
		if ($estudiante["periodos"] == $config["conf_periodos_maximos"]) {
			if ($materiasPerdidas >= $config["conf_num_materias_perder_agno"]) {
				$msj = "EL (LA) ESTUDIANTE " . $estudiante["nombre"] . " NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE";
			} elseif ($materiasPerdidas < $config["conf_num_materias_perder_agno"] and $materiasPerdidas > 0) {
				$msj = "EL (LA) ESTUDIANTE " . $estudiante["nombre"] . " DEBE NIVELAR LAS MATERIAS PERDIDAS";
			} else {
				$msj = "EL (LA) ESTUDIANTE " . $estudiante["nombre"] . " FUE PROMOVIDO(A) AL GRADO SIGUIENTE";
			}

			if ($estudiante['mat_id'] == CANCELADO) {
				$msj = "EL(LA) ESTUDIANTE FUE RETIRADO SIN FINALIZAR AÑO LECTIVO.";
			}
		}
		?>
		<p align="left">
		<div style="font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-style:italic; font-size:10px;"><?= $msj; ?></div>
		</p>
		<table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">
			<tr>
				<td align="center">
					<?php
					$rector = UsuariosPadre::sesionUsuario($informacion_inst["info_rector"], "", $config['conf_id_institucion'], $year);
					$nombreRector = UsuariosPadre::nombreCompletoDelUsuario($rector);
					if (!empty($rector["uss_firma"]) && file_exists(ROOT_PATH . '/main-app/files/fotos/' . $rector['uss_firma'])) {
						echo '<img src="../files/fotos/' . $rector["uss_firma"] . '" width="200"><br>';
					} else {
						echo '<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>';
					}
					?>
					_________________________________<br>
					<p>&nbsp;</p>
					<?= $nombreRector ?><br>
					Rector(a)
				</td>
				<td align="center">
					<?php
					$secretario = UsuariosPadre::sesionUsuario($informacion_inst["info_secretaria_academica"], "", $config['conf_id_institucion'], $year);
					$nombreScretario = UsuariosPadre::nombreCompletoDelUsuario($secretario);
					if (!empty($secretario["uss_firma"]) && file_exists(ROOT_PATH . '/main-app/files/fotos/' . $secretario['uss_firma'])) {
						echo '<img src="../files/fotos/' . $secretario["uss_firma"] . '" width="100"><br>';
					} else {
						echo '<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>';
					}
					?>
					_________________________________<br>
					<p>&nbsp;</p>
					<?= $nombreScretario ?><br>
					Secretario(a) Académico
				</td>
			</tr>
		</table>
	</div>
	<?php }  ?>
</div>
<input type="button" class="btn btn-flotante" id="guardarPDF"
onclick="generatePDF()" value="Descargar PDF"></input>

	<?php include(ROOT_PATH . "/main-app/compartido/guardar-historial-acciones.php"); ?>
	<script type="application/javascript">
    async function generatePDFPart(start, end) {
        const element = document.getElementById('contenido');
        const estudiantes = [...document.querySelectorAll('.page')];
        const visibleEstudiantes = estudiantes.slice(start, end);

        // Crear un contenedor temporal con los elementos visibles
        const tempContainer = document.createElement('div');
        visibleEstudiantes.forEach(estudiante => tempContainer.appendChild(estudiante.cloneNode(true)));

        const options = {
            margin: [8, 15, 8, 8], // top: 8, right: 15, bottom: 8, left: 8
            filename: '.pdf',
            filename: `LIBRO-FINAL-F3-<?= $informacion_inst["info_id"] ?>_<?= $year ?>_<?= $grado ?>_<?= $grupo ?>_paginas_${start}-${end}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        Swal.fire({
                position: "bottom-end",
                title: 'Generando PDF',
                text: 'Sé generó archivo desde la pagina '+start+ ' '+end,
                icon: 'success',
                showCancelButton: false,
                confirmButtonText: 'Si!',
                cancelButtonText: 'No!',
                timer: 1500
            });

       return await html2pdf().set(options).from(tempContainer).save();

    }
    async function generatePDF() {
        const estudiantes = [...document.querySelectorAll('.page')];
        document.getElementById('overlay').style.display = 'flex';
        let cantidad = estudiantes.length;
        console.log(cantidad);
        if(cantidad>1){
            let start = 0;
            let end   = 0;
            let count = 0;
            let max = 20;
            let vaiable='';
            for (let i = 0; i < cantidad; i++){
                count ++;
                end = i;
                if(count == max){
                vaiable =  await generatePDFPart(start, end);
                    console.log(start +" hasta "+end );
                    start  = end+1;
                    count = 0;                       
                }
            }
            console.log(start +" hasta "+end );
            vaiable = await generatePDFPart(start, end);
        } else if(cantidad == 1){
            vaiable =  await generatePDF2();
           
        }    
        document.getElementById("overlay").style.display = "none";   
        

    }
    async function generatePDF2() {
        const element = document.getElementById('contenido');
        const options = {
            margin: 0,
            filename: 'LIBRO-FINAL-F3-<?= $informacion_inst["info_id"] ?>_<?= $year ?>_<?= $grado ?>_<?= $grupo ?>_<?=$idEstudiante?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
       variable = await html2pdf().set(options).from(element).save();  
        Swal.fire({
                position: "bottom-end",
                title: 'Generando PDF',
                text: 'se generó archivo del estudiante <?=$idEstudiante?>',
                icon: 'success',
                showCancelButton: false,
                confirmButtonText: 'Si!',
                cancelButtonText: 'No!',
                timer: 3500
            });    
    }
	</script>
</body>

</html>