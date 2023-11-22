<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once("../class/Estudiantes.php");

if(!empty($_GET["carga"])) {
  $carga = base64_decode($_GET["carga"]);
}

if(!empty($_GET["docente"])) {
  $docente = base64_decode($_GET["docente"]);
}
?>

<head>

<meta charset="utf-8">

	<title>Planilla Docentes</title>

<style>

#saltoPagina {

    PAGE-BREAK-AFTER: always;

}

</style>

</head>

<body style="font-family:Arial;">

<?php



	

$filtro = '';

if(!empty($carga)) {$filtro .= " AND car_id='".$carga."'";}	

if(!empty($docente)) {$filtro .= " AND car_docente='".$docente."'";}

if(!empty($_GET["grado"])) {$filtro .= " AND car_curso='".$_GET["grado"]."'";}

if(!empty($_GET["grupo"])) {$filtro .= " AND car_grupo='".$_GET["grupo"]."'";}

if(!empty($_GET["periodo"])) {$filtro .= " AND car_periodo='".$_GET["periodo"]."'";}	

	

$con = mysqli_query($conexion, "SELECT * FROM academico_cargas

INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]} 

INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}

INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}

INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=car_docente AND uss_tipo=2 AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}

WHERE car_id=car_id $filtro");



while ( $rCargas = mysqli_fetch_array($con, MYSQLI_BOTH) ) {

    ?>

<div align="center" style="margin-bottom:20px;"> 


  <b>

  <?=$informacion_inst["info_nombre"]?>

  </b><br>

  <b>Evaluaci&oacute;n e Inasistencia

  </b><br>

</div>

	

	

	<table width="100%" cellspacing="5" cellpadding="5" border="0" rules="none">

            <tr>

                <td><strong>DOCENTE:</strong><br> <?=strtoupper($rCargas['uss_nombre']);?></td>

                <td><strong>GRADO:</strong><br> <?=$rCargas["gra_nombre"];?> <?=$rCargas["gru_nombre"];?></td> 

				<td>&nbsp;</td>

            </tr>

            

            <tr>

                <td><strong>ASIGNATURA:</strong><br> <?=strtoupper($rCargas['mat_nombre']);?></td>

                <td><strong>PERIODO:</strong><br> <?php echo $rCargas['car_periodo']." (".date("Y").")";?></td> 

                <td><strong>Fecha Impresión:</strong><br> <?=date("d/m/Y H:i:s");?></td>

            </tr>

        </table>

        <p>&nbsp;</p>	

	

<table bgcolor="#FFFFFF" width="100%" cellspacing="0" cellpadding="0" rules="all" border="1">

  <tr style="font-weight:bold; background:#4c9858; border-color:#666; height:20px; color:#000; font-size:12px;">

    <td align="center" width="20%" colspan="3">Tipo de Evaluaci&oacute;n y %</td>

    <td width="10%" colspan="5" align="center">&nbsp;</td>

    <td width="10%" colspan="5" align="center">&nbsp;</td>

    <td width="10%" colspan="5" align="center">&nbsp;</td>

    <td align="center" width="3%">Auto</td>

    <td align="center" width="3%">Coo</td>

    <td colspan="7" width="2%"></td>

  </tr>

  <tr style="height:150px; font-weight:bold; font-size:12px;">

    <td align="center" width="20%" colspan="3">TEMAS</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td align="center" width="2%">&nbsp;</td>

    <td colspan="7" align="center">______________________<br>

      Firma Docente</td>

  </tr>

  <tr style="font-weight:bold; font-size:12px; height:35px; background:#4c9858; border-color:#4c9858; color:#000;">

    <td align="center">No</b></td>

    <td align="center">C&oacute;digo</td>

    <td align="center">Estudiante</td>

    <td align="center" colspan="17" width="2%">&nbsp;</td>

    <td align="center" colspan="7">Inasistencia</td>

    <?php
		$estudiantes = Estudiantes::escogerConsultaParaListarEstudiantesParaDocentes($rCargas);

    $n = 1;

    while ( $e = mysqli_fetch_array($estudiantes, MYSQLI_BOTH) ) {

		$fondo = '#FFF';

		if($n %2 == 0) {$fondo = '#e0e0153b';}

    ?>

  <tr style="font-size:10px; height:25px; background-color: <?=$fondo;?>">

    <td align="center" width="2%"><?=$n;?></td>

    <td align="center" width="5%"><?=$e['mat_id'];?></td>

    <td width="20%"><?=Estudiantes::NombreCompletoDelEstudiante($e);?></td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="3%">&nbsp;</td>

    <td width="1%">&nbsp;</td>

    <td width="1%">&nbsp;</td>

    <td width="1%">&nbsp;</td>

    <td width="1%">&nbsp;</td>

    <td width="1%">&nbsp;</td>

    <td width="1%">&nbsp;</td>

    <td width="1%">&nbsp;</td>

  </tr>

  <?php

  $n++;

  } //fin estudiantes

  ?>

</table>

<p align="center">

<div style="font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-style:italic; font-size:12px;" align="center">

</div>

</p>



<div align="center" style="font-size:10px; margin-top:5px; margin-bottom: 10px;">

	<img src="https://plataformasintia.com/images/logo.png" height="50"><br>

	ESTE DOCUMENTO FUE GENERADO POR:<br>

	SINTIA -  SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL

</div>



<div id="saltoPagina"></div>

<?php

} //Fin de las cargas

?>

</center>

<script type="application/javascript">

print();

</script>

</body>

</html>