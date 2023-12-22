<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Inscripciones.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0164';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

$matricula="";
if(!empty($_GET["matricula"])){ $matricula=base64_decode($_GET["matricula"]);}

$documentos = Inscripciones::traerDocumentos($conexionPDO, $config, $matricula);

$ruta = '../admisiones/files/otros';
if(!empty($documentos['matd_pazysalvo']) && file_exists($ruta."/".$documentos['matd_pazysalvo'])){	unlink($ruta."/".$documentos['matd_pazysalvo']);	}
if(!empty($documentos['matd_observador']) && file_exists($ruta."/".$documentos['matd_observador'])){	unlink($ruta."/".$documentos['matd_observador']);	}
if(!empty($documentos['matd_eps']) && file_exists($ruta."/".$documentos['matd_eps'])){	unlink($ruta."/".$documentos['matd_eps']);	}
if(!empty($documentos['matd_recomendacion']) && file_exists($ruta."/".$documentos['matd_recomendacion'])){	unlink($ruta."/".$documentos['matd_recomendacion']);	}
if(!empty($documentos['matd_vacunas']) && file_exists($ruta."/".$documentos['matd_vacunas'])){	unlink($ruta."/".$documentos['matd_vacunas']);	}
if(!empty($documentos['matd_boletines_actuales']) && file_exists($ruta."/".$documentos['matd_boletines_actuales'])){	unlink($ruta."/".$documentos['matd_boletines_actuales']);	}
if(!empty($documentos['matd_documento_identidad']) && file_exists($ruta."/".$documentos['matd_documento_identidad'])){	unlink($ruta."/".$documentos['matd_documento_identidad']);	}
if(!empty($documentos['matd_certificados']) && file_exists($ruta."/".$documentos['matd_certificados'])){	unlink($ruta."/".$documentos['matd_certificados']);	}

Inscripciones::eliminarDocumentos($conexion, $config, $matricula);

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="inscripciones.php?msg='.base64_encode(1).'";</script>';
exit();