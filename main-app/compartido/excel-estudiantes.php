<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Estudiantes_".date("d/m/Y")."-SINTIA.xls");
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");

$consulta=mysqli_query($conexion, "SELECT * FROM academico_matriculas
INNER JOIN academico_grados ON gra_id=mat_grado
INNER JOIN academico_grupos ON gru_id=mat_grupo
LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_tipo_documento
LEFT JOIN ".$baseDatosServicios.".localidad_ciudades ON ciu_id=mat_lugar_nacimiento
WHERE mat_eliminado=0 ORDER BY mat_primer_apellido");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel</title>
</head>
<body>

<div align="center">  
<table  width="100%" border="1" rules="all">
    <thead>
    	<tr>
        	<th colspan="21" style="background:#060; color:#FFF;">MATRICULAS ACTUALES <?=date('Y');?></th>
        </tr>
    	<tr>
            <th scope="col" align="center">No.</th>
            <th scope="col" align="center">Estudiante</th>
            <th scope="col" align="center">Grado</th>
            <th scope="col" align="center">Grupo</th>
            <th scope="col" align="center">Estado</th>
            <th scope="col" align="center">Fecha Nacimiento</th>
            <th scope="col" align="center">Lugar Nacimiento</th>
            <th scope="col" align="center">Tipo de Documento</th>
            <th scope="col" align="center">Documento</th>            
        	<th scope="col" align="center">Lugar Expedicion</th>
            <th scope="col" align="center">Direccion</th>
            <th scope="col" align="center">Barrio</th>
            <th scope="col" align="center">Telefono</th>
            <th scope="col" align="center">Email</th>
            <th scope="col" align="center">Folio</th>
            <th scope="col" align="center">Cod. Tesoreria</th>
            <th scope="col" align="center">Num. Matrícula</th>

            <th scope="col" align="center">Tipo de documento</th>
            <th scope="col" align="center">Documento Acudiente</th>
            <th scope="col" align="center">Nombre Acudiente</th>
			<th scope="col" align="center">Email Acudiente</th>
        </tr>
    </thead>
    <tbody>
<?php 
$conta=1;
while($resultado=mysqli_fetch_array($consulta, MYSQLI_BOTH))
{
    $consultaDatosA=mysqli_query($conexion, "SELECT * FROM usuarios 
    LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=uss_tipo_documento
    WHERE uss_id='".$resultado['mat_acudiente']."'");

	$datosA = mysqli_fetch_array($consultaDatosA, MYSQLI_BOTH);
	switch($resultado['mat_estado_matricula']){
		case 1:
		$estadoM='Matriculado';
		break;
		
		case 2:
		$estadoM='Asistente';
		break;
		
		case 3:
		$estadoM='Cancelado';
		break;
		
		case 4:
		$estadoM='No Matriculado';
		break;
	}	
?>    
    	<tr>	
            <td align="center"><?=$conta;?></td>
            <td><?=strtoupper($resultado[3]." ".$resultado[4]." ".$resultado['mat_nombres']." ".$resultado['mat_nombre2']);?></td>
            <td align="center"><?=$resultado['gra_nombre'];?></td>
            <td align="center"><?=$resultado['gru_nombre'];?></td>
            <td align="center"><?=$estadoM;?></td>
            <td align="center"><?=$resultado[9];?></td>
            <td align="center"><?php 
            $lugarNacimiento = is_numeric($resultado['mat_lugar_nacimiento']) ?  $resultado['ciu_nombre']
            : $resultado['mat_lugar_nacimiento'];

            echo strtoupper($lugarNacimiento);
            ?></td>
            <td align="center"><?=$resultado['ogen_nombre'];?></td>
            <td align="center"><?=$resultado['mat_documento'];?></td>
            <td align="center"><?=$resultado[13];?></td>
            <td align="center"><?=$resultado[15];?></td>
            <td align="center"><?=$resultado[16];?></td>
            <td align="center"><?=$resultado[17];?></td>
            <td align="center"><?=strtolower($resultado[25]);?></td>
			<td align="center"><?=$resultado[34];?></td>
            <td align="center"><?=$resultado[35];?></td>
            <td align="center"><?=$resultado['mat_numero_matricula'];?></td>

            <td><?=$datosA['ogen_nombre'];?></td>
            <td><?=$datosA['uss_usuario'];?></td>
            <td align="center"><?php 
                $nombreCompleto = !empty($datosA['uss_apellido1']) ? 
                $datosA['uss_apellido1']." ".$datosA['uss_apellido2']." ".$datosA['uss_nombre']." ".$datosA['uss_nombre2'] 
                :  $datosA['uss_nombre'];

                echo strtoupper($nombreCompleto);
            ?></td>
			<td><?=strtolower($datosA['uss_email']);?></td>
        </tr>   

<?php
	$conta++;
}
?>        
    </tbody>
</table>

</body>
</html>