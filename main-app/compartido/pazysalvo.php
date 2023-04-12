<?php
include("../directivo/session.php");
require_once("../class/Estudiantes.php");
?>
<head>
	<title>SINTIA | Saldos</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../sintia-icono.png">
    <style type="text/css">
	.pieP{
		margin-top:100px;
		display:flex;
		flex-direction:row;
		justify-content:space-between;
	}
	.headerP{
		display:flex;
		flex-direction:row;
		justify-content:space-between;
	}
    </style>
</head>
<body style="font-family:Arial;">
<div class="headerP">
    <div align="center" style="margin: 10px auto;">
        <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" height="150" width="250"><br>
        <?=$informacion_inst["info_nombre"]?><br>
        <h4 style="margin-top: 50px;">A QUIEN PUEDA INTERESAR</h4>
    </div>
    <div>&nbsp;</div>
    <div>&nbsp;</div>
</div>   
<?php
$usuario = Estudiantes::obtenerDatosEstudiantePorIdUsuario($_GET["id"]);
$nombre = Estudiantes::NombreCompletoDelEstudiante($usuario);
    switch($usuario['mat_tipo_documento']){
        case 105:
            $tipoD='CC.';
        break;
        case 106:
            $tipoD='NUIP.';
        break;
        case 107:
            $tipoD='TI.';
        break;
        case 108:
            $tipoD='RC.';
        break;
        case 109:
            $tipoD='CE.';
        break;
        case 110:
            $tipoD='PP.';
        break;
        case 139:
            $tipoD='PEP.';
        break;
    }
?>
    <div align="justify" style="margin-top: 20px;">
    <p>El <?=$informacion_inst["info_nombre"]?> hace constar que el estudiante <b><?=$nombre?></b> identificado con <?=$tipoD." ".$usuario['mat_documento']?> se encuentra a PAZ y SALVO por todo concepto.</p>
    <p>Se expide esta constancia a los <?=date("d");?> días del mes de <?=$mesesAgno[date("m")];?> del año <?=date("Y");?>.</p>
    </div>
    
    <?php
        $consultaTesorero=mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$informacion_inst["info_tesorero"]."'");
        $tesorero = mysqli_fetch_array($consultaTesorero, MYSQLI_BOTH);
    ?>
    <div class="pieP">
    	<div>
            <?=strtoupper($tesorero['uss_apellido1']." ".$tesorero['uss_apellido2']." ".$tesorero['uss_nombre']." ".$tesorero['uss_nombre2']);?><br>
            Contador(a)
        </div>
    </div>
	<div align="center" style="font-size:10px; margin-top:20px;">
      <img src="../sintia-logo-2023.png" height="50" width="100"><br>
      SINTIA -  SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL - <?=date("l, d-M-Y");?>
     </div>
     <script type="text/javascript">print();</script>
</body>
</html>


