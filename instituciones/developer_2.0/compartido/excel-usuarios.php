<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Usuarios_".date("d/m/Y")."-SINTIA.xls");
include("../modelo/conexion.php");
?>


<?php
$consulta=mysql_query("SELECT * FROM usuarios
	INNER JOIN perfiles ON pes_id=uss_tipo
	ORDER BY uss_tipo",$conexion);
?>
<div align="center">  
<table  width="100%" border="1" rules="all">
    <thead>
    	<tr>
        	<th colspan="4" style="background:#060; color:#FFF;">MATRICULAS ACTUALES <?=date('Y');?></th>
        </tr>
    	<tr>
            <th scope="col" align="center">No.</th>
            <th scope="col" align="center">Nombre</th>
            <th scope="col" align="center">Tipo</th>
            <th scope="col" align="center">Email</th>
        </tr>
    </thead>
    <tbody>
<?php 
$conta=1;
while($resultado=mysql_fetch_array($consulta))
{	
?>    
    	<tr>	
            <td align="center"><?=$conta;?></td>
            <td><?=$resultado['uss_nombre'];?></td>
            <td><?=$resultado['pes_nombre'];?></td>
            <td><?=strtolower($resultado['uss_email']);?></td>
        </tr>   

<?php
	$conta++;
}
?>        
    </tbody>
</table>