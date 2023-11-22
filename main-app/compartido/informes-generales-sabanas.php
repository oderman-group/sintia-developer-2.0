<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once("../class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");
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

$num_asg=mysqli_num_rows($asig);
$consultaGrados=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados gra, ".BD_ACADEMICA.".academico_grupos gru WHERE gra_id='".$curso."' AND gru.gru_id='".$grupo."' AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]} AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}");

$grados = mysqli_fetch_array($consultaGrados, MYSQLI_BOTH);
?>
<head>
	<title>Sabanas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../files/images/ico.png">
</head>
<body style="font-family:Arial;">
	
<div style="margin: 10px;">
		<img src="../../files-general/instituciones/informes/sabanas.jpg" style="width: 100%;">
	</div>
	
<div align="center" style="margin-bottom:20px;">
    <?=$informacion_inst["info_nombre"]?><br>
    PERIODO: <?=$per;?></br>
    <b><?=strtoupper($grados["gra_nombre"]." ".$grados["gru_nombre"]);?></b><br>

    <?php if($informacion_inst["info_institucion"]==ICOLVEN){?>
    <p><a href="reportes-sabanas-indicador.php?curso=<?=$_GET["curso"];?>&grupo=<?=$_GET["grupo"];?>&per=<?=$_GET["per"];?>" target="_blank">VER SABANAS CON INDICADORES</a></p>
    <?php } ?>
</div>  
<div style="margin: 10px;">
  <table bgcolor="#FFFFFF" width="100%" cellspacing="5" cellpadding="5" rules="all" border="<?php echo $config[13] ?>" style="border:solid; border-color:<?php echo $config[11] ?>;" align="center">
  <tr style="font-weight:bold; font-size:12px; height:30px; background:#6017dc; color:white;">
        <td align="center">No</b></td>
        <td align="center">C&oacute;digo</td>
        <td align="center">Estudiante</td>
        <!--<td align="center">Gru</td>-->
        <?php
		$materias1=mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso=".$curso." AND car_grupo='".$grupo."' AND car_activa=1");
		while($mat1=mysqli_fetch_array($materias1, MYSQLI_BOTH)){
			$nombresMat=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_materias WHERE mat_id='".$mat1[4]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
			$Mat=mysqli_fetch_array($nombresMat, MYSQLI_BOTH);
		?>
        	<td align="center"><?=strtoupper($Mat['mat_siglas']);?></td>      
  		<?php
		}
		?>
        <td align="center" style="font-weight:bold;">PROM</td>
  </tr>
  <?php
  $cont=1;
  $mayor=0;
  $nombreMayor="";
  while($fila=mysqli_fetch_array($asig, MYSQLI_BOTH)){
    $nombre = Estudiantes::NombreCompletoDelEstudiante($fila);	  
  	// $cuentaest=mysqli_query($conexion, "SELECT * FROM academico_boletin WHERE bol_estudiante=".$fila['mat_id']." AND bol_periodo=".$_GET["per"]." GROUP BY bol_carga");
		// $numero=mysqli_num_rows($cuentaest);
		
  ?>
  <tr style="font-size:13px;">
      <td align="center"> <?php echo $cont;?></td>
      <td align="center"> <?php echo $fila['mat_id'];?></td>
      <td><?=$nombre?></td> 
      <!--<td align="center"><?php if($fila[7]==1)echo "A"; else echo "B";?></td> -->
       <?php
		$suma=0;
		$materias1=mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso=".$curso." AND car_grupo='".$grupo."' AND car_activa=1");
		$numero=mysqli_num_rows($materias1);
		$def='0.0';
		while($mat1=mysqli_fetch_array($materias1, MYSQLI_BOTH)){
			$notas=mysqli_query($conexion, "SELECT * FROM academico_boletin WHERE bol_estudiante=".$fila['mat_id']." AND bol_carga=".$mat1[0]." AND bol_periodo=".$per);
			$nota=mysqli_fetch_array($notas, MYSQLI_BOTH);
      $defini = 0;
      if(!empty($nota[4])){$defini = $nota[4];$suma=($suma+$defini);}
			if($defini<$config[5]) $color='red'; else $color='blue';

      $notaEstudiante="";
      if(!empty($nota[4])){
        $notaEstudiante=$nota[4];
      }

      $notaEstudianteFinal=$notaEstudiante;
      $title='';
      if($notaEstudiante!="" && $config['conf_forma_mostrar_notas'] == CUALITATIVA){
        $title='title="Nota Cuantitativa: '.$notaEstudiante.'"';
        $estiloNotaEstudiante = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaEstudiante);
        $notaEstudianteFinal= !empty($estiloNotaEstudiante['notip_nombre']) ? $estiloNotaEstudiante['notip_nombre'] : "";
      }
		?>
        	<td align="center" style="color:<?=$color;?>;" <?=$title;?>><?=$notaEstudianteFinal?></td>      
  		<?php
		}
		if($numero>0) {
			$def=round(($suma/$numero),2);
		}
		if($def==1)	$def="1.0"; if($def==2)	$def="2.0"; if($def==3)	$def="3.0"; if($def==4)	$def="4.0"; if($def==5)	$def="5.0"; 	
		if($def<$config[5]) $color='red'; else $color='blue'; 
		$notas1[$cont] = $def;
		$grupo1[$cont] = $nombre;

    $defFinal=$def;
    $title='';
    if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
      $title='title="Nota Cuantitativa: '.$def.'"';
      $estiloDef = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $def);
      $defFinal= !empty($estiloDef['notip_nombre']) ? $estiloDef['notip_nombre'] : "";
    }
		?>
      <td align="center" style="font-weight:bold; color:<?=$color;?>;" <?=$title;?>><?=$defFinal;?></td>  
</tr>
  <?php
  $cont++;
  }//Fin mientras que
  ?>
  </table>
  
<?php
$puestos = mysqli_query($conexion, "SELECT SUM(bol_nota) AS suma, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_nombre2 FROM academico_boletin
INNER JOIN ".BD_ACADEMICA.".academico_matriculas mat ON mat.mat_id=bol_estudiante AND mat.institucion={$config['conf_id_institucion']} AND mat.year={$_SESSION["bd"]}
INNER JOIN academico_cargas ON car_id=bol_carga AND car_curso='".$curso."' AND car_grupo='".$grupo."'
WHERE bol_periodo='".$per."'
GROUP BY bol_estudiante
ORDER BY suma DESC
");
?>

<?php if ( ($config['conf_ver_promedios_sabanas_docentes'] == 1 && $datosUsuarioActual['uss_tipo'] == TIPO_DOCENTE) || 
           ($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO || $datosUsuarioActual['uss_tipo'] == TIPO_DEV) ) {?>
    <p>&nbsp;</p>
      <table width="100%" border="1" rules="all" align="center">
      <tr style="font-weight:bold; font-size:12px; height:30px;">
          <td colspan="3" align="center">PUESTOS</td>
      </tr> 
      
      <tr style="font-weight:bold; font-size:14px; height:40px;">
          <td align="center">Puesto</b></td>
          <td align="center">Estudiante</td>
          <td align="center">Promedio</td>
      </tr> 
    <?php
    $j=1;
      while($ptos = mysqli_fetch_array($puestos, MYSQLI_BOTH)){	
        $prom=round(($ptos['suma']/$numero),2);	

        $promFinal=$prom;
        $title='';
        if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
          $title='title="Nota Cuantitativa: '.$prom.'"';
          $estiloProm = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $prom);
          $promFinal= !empty($estiloProm['notip_nombre']) ? $estiloProm['notip_nombre'] : "";
        }
    ?>	
      <tr style="font-weight:bold; font-size:12px;">
          <td align="center"><?=$j;?></td>
          <td><?=Estudiantes::NombreCompletoDelEstudiante($ptos);?></td>
          <td align="center"  <?=$title;?>><?=$promFinal;?></td>
      </tr>
    <?php	
      $j++;
    }
    ?>
      
    </table>  
  <?php }?>

</div>

</body>
</html>


