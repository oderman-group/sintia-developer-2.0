<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
include("../class/Estudiantes.php");
$resultado = Estudiantes::obtenerDatosEstudiante($_GET["id"]);
$nombreCompleto = Estudiantes::NombreCompletoDelEstudiante($resultado);
?>
<head>
	<title>SINTIA | Carnet Estudiantil</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../files/images/ico.png">
</head>
<body style="font-family:Arial;"> 
<center>
    <div style="border:double; width:300px; border-radius:15px; text-align:center" align="center">
      <table bgcolor="#FFFFFF" width="280px" cellspacing="5" cellpadding="5" align="center">
        
        <tr>
            <td align="center" colspan="2">
                <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" height="150" width="200"><br>
                <?=$informacion_inst["info_nombre"]?>
            </td>
        </tr>
        
        <tr>
            <td align="center" colspan="2"><img src="../files/fotos/<?=$resultado[20];?>" width="150" height="150" alt="Plataforma SINTIA"></td>
        </tr>
        
        <tr>
            <td align="center" colspan="2"><?=$nombreCompleto?></td>
        </tr>
        <tr>
            <td align="center" colspan="2"><b>IDENTIFICACIÓN</b><br><?=number_format($resultado[12],0,",",".");?></td>
        </tr>
        <tr>
            <td align="center"><b>Grado:</b> <?=$resultado["gra_nombre"];?></td>
            <td align="center"><b>Grupo:</b> <?=$resultado["gru_nombre"];?></td>
        </tr>
    
        
      </table>
    </div>
</center>

</body>
</html>


