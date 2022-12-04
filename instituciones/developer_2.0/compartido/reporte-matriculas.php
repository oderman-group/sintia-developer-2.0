<?php
//include("../modell/conexion.php");
include("informacion.php");
$consulta = mysql_query("SELECT * FROM matriculas, inscripciones WHERE inscripciones.id=matriculas.id_estudiante",$conexion);
?>
<head>
	<title>REPORTES - WolfSystem</title>
    <meta charset="utf-8">
</head>
<body style="font-family:Arial;">
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr height="40">
        <td align="center" colspan="12" style="font-size:16px;">
            <img src="../files-sgpa/images/<?=$infoI[5];?>" width="<?=$infoI[6];?>" height="<?=$infoI[7];?>"><br>
			<?=$infoI[1];?><br />
            LISTADO DE MATRICULAS<br>
        </td>
	</tr>   
</table>
<p>&nbsp;</p>  
<table bgcolor="#FFFFFF" width="100%" cellspacing="0" cellpadding="0" rules="all" border="1" align="center">
    <tr style="font-weight:bold; font-size:10px" align="center">
        <td>No</th>
                                <th>Estudiante</th>
                                <th>Matr&iacute;cula</th>
                                <th>Definitiva</th>
                                <th>Estado</th>
    </tr>
<?php
$con = 1;
while($resultado = mysql_fetch_array($consulta)){
$consultaA1 = mysql_query("SELECT * FROM programas WHERE programas.id='".$resultado[11]."'",$conexion);
									$resultadoA1 = mysql_fetch_array($consultaA1);
									//===========================
									$consultaAdicional = mysql_query("SELECT * FROM grupos, asignaturas, programas WHERE grupos.id='".$resultado[3]."' AND programas.id=grupos.id_programa AND asignaturas.id=grupos.id_asignatura",$conexion);
									$resultadoAdicional = mysql_fetch_array($consultaAdicional);
									//============================================================
									$consultaAdicional2 = mysql_query("SELECT * FROM carga_academica, usuarios WHERE carga_academica.id_grupo='".$resultadoAdicional[0]."' AND usuarios.id=carga_academica.id_docente",$conexion);
									$resultadoAdicional2 = mysql_fetch_array($consultaAdicional2);
									//============================================================
									$consultaAdicional3 = mysql_query("SELECT * FROM estados_matriculas WHERE id='".$resultado[4]."'",$conexion);
									$resultadoAdicional3 = mysql_fetch_array($consultaAdicional3);
?>
    <tr style="font-size:8px;">
        <td align="center"><?=$con;?></td>
                                <td><?=$resultado[8]."<br>".$resultado[18]." ".$resultado[19]." ".$resultado[20]."<br>".$resultado[28]."<br>".$resultado[32]."<br><b>".$resultadoA1[1]."</b>";?></td>
                                <td><?="<b>Fecha:</b> ".$resultado[1]."<br><b>Grupo:</b> ".$resultado[3]."<br><b>Curso:</b> ".$resultadoAdicional[4]."<br><b>Programa:</b> ".$resultadoAdicional[9]."<br><b>Docente:</b> ".$resultadoAdicional2[11]." ".$resultadoAdicional2[12];?></td>
                                <td align="center"><u><?=$resultado[5];?></u></td>
                                <td align="center"><u><?=$resultadoAdicional3[1];?></u></td>
    </tr>
<?php
	$con++;
}
?>
</table>
<p style="font-size:6px;">
    <img src="../files-sgpa/images/wolflsystem.png" height="50" align="absmiddle">
    SISTEMA GESTOR DE PROCESOS ACADEMICOS - SGPA
</p>
</body>
</html>
