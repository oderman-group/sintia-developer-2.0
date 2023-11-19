<?php
include("../docente/session.php");
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

$filtroAdicional= "AND mat_grado='".$curso."' AND mat_grupo='".$grupo."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
$asig =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"");

$grados = mysqli_fetch_array($asig, MYSQLI_BOTH);		
$num_asg=mysqli_num_rows($asig);
$colspan=1;
if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
  $colspan=2;
}
?>
<head>
	<title>Sabanas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="https://plataformasintia.com/eduardoortega/assets/plugins/jquery/jquery-1.9.1.min.js?v1.3.1"></script>
	<!--bootstrap -->
	<link href="./../../config-general/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	  <script src="../js/Calificaciones.js" ></script>
    <?php include("sintia-funciones-js.php"); ?>


    <script type="text/javascript">
  function def(enviada){
  var nota = enviada.value;
  var codEst = enviada.id;
  var carga = enviada.name;
  var per = enviada.alt;
  if (alertValidarNota(nota)) {
		return false;
	} 
  notaCualitativa(nota,codEst,carga);
    $('#resp').empty().hide().html("Esperando...").show(1);
    datos = "nota="+(nota)+
          "&carga="+(carga)+
          "&codEst="+(codEst)+
          "&per="+(per);
        $.ajax({
          type: "POST",
          url: "ajax-definitivas-registrar.php",
          data: datos,
          success: function(data){
            $('#resp').empty().hide().html(data).show(1);
          }
        });

  }
  </script>
</head>
<body style="font-family:Arial;">
	

<!-- <div style="margin: 10px;">
  <img src="../../files-general/instituciones/informes/sabanas.jpg" style="width: 100%;">
</div> -->
	
<div align="center" style="margin-bottom:20px;">
    <?=$informacion_inst["info_nombre"]?><br>
    PERIODO: <?=$per;?></br>
    <b><?=strtoupper($grados["gra_nombre"]." ".$grados["gru_nombre"]);?></b><br>
</div>  
<div style="margin: 10px;">

  <span id="resp"></span>
		
  <p>
    <a href="../docente/pagina-opciones.php" type="button" class="btn btn-primary">Regresar</a>
  </p>

  <table bgcolor="#FFFFFF" width="100%" cellspacing="5" cellpadding="5" rules="all" border="<?php echo $config[13] ?>" style="border:solid; border-color:<?php echo $config[11] ?>;" align="center">
  <tr style="font-weight:bold; font-size:12px; height:30px; background:<?php echo $config[12] ?>;">
        <td align="center">No</b></td>
        <td align="center">C&oacute;digo</td>
        <td align="center">Estudiante</td>
        <!--<td align="center">Gru</td>-->
        <?php
		$materias1=mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso=".$curso." AND car_grupo='".$grupo."'");
		while($mat1=mysqli_fetch_array($materias1, MYSQLI_BOTH)){
			$nombresMat=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_materias WHERE mat_id='".$mat1[4]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
			$Mat=mysqli_fetch_array($nombresMat, MYSQLI_BOTH);
		?>
        	<td align="center" colspan="<?=$colspan?>"><?=strtoupper($Mat['mat_siglas']);?></td>      
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
  		$cuentaest=mysqli_query($conexion, "SELECT * FROM academico_boletin WHERE bol_estudiante=".$fila[0]." AND bol_periodo=".$per." GROUP BY bol_carga");
		$numero=mysqli_num_rows($cuentaest);
		$def='0.0';
		
  ?>
  <tr style="font-size:13px;">
      <td align="center"> <?php echo $cont;?></td>
      <td align="center"> <?php echo $fila[0];?></td>
      <td><?=$nombre?></td> 
      <!--<td align="center"><?php if($fila[7]==1)echo "A"; else echo "B";?></td> -->
       <?php
		$suma=0;
		$materias1 = mysqli_query($conexion, "SELECT * FROM academico_cargas 
    WHERE car_curso=".$curso." AND car_grupo='".$grupo."'");

		while($mat1=mysqli_fetch_array($materias1, MYSQLI_BOTH)){
			$notas=mysqli_query($conexion, "SELECT * FROM academico_boletin WHERE bol_estudiante=".$fila[0]." AND bol_carga=".$mat1[0]." AND bol_periodo=".$per);
			$nota=mysqli_fetch_array($notas, MYSQLI_BOTH);
      $defini = 0;
      if(!empty($nota[4])){$defini = $nota[4];$suma=($suma+$defini);}
			if($defini<$config[5]) $color='red'; else $color='blue';
		?>
        	<td align="center" style="color:<?=$color;?>; width: 50px;">
           
           <input style="text-align:center; width:40px; color:<?=$color;?>;" value="<?php if(!empty($nota[4])){ echo $nota[4];}?>" name="<?=$mat1[0];?>" id="<?=$fila[0];?>" onChange="def(this)" alt="<?=$per;?>">

          </td>
          <?php
            if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
              $notaFinal='';
              if(!empty($nota[4])){
                $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $nota[4]);
                $notaFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
              }
          ?>
        	  <td align="center" style="font-weight:bold; color:<?=$color;?>; width: 50px;" id="CU<?=$fila[0].$mat1[0];?>"><?=$notaFinal?></td>
  		<?php
            }
		}
		if($numero>0) {
			$def=round(($suma/$numero),2);
		}
		if($def==1)	$def="1.0"; if($def==2)	$def="2.0"; if($def==3)	$def="3.0"; if($def==4)	$def="4.0"; if($def==5)	$def="5.0"; 	
		if($def<$config[5]) $color='red'; else $color='blue'; 
		$notas1[$cont] = $def;
		$grupo1[$cont] = strtoupper($fila[3]." ".$fila[4]." ".$fila[5]);
      $defFinal= "";
      if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
          $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $def);
          $defFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "Bajo";
      }
		?>
      <td align="center" style="font-weight:bold; color:<?=$color;?>;"><a tabindex="0" role="button" data-toggle="popover" data-trigger="hover" data-content="<b>Nota Cuantitativa:</b><br><?=$def?>" data-html="true" data-placement="top" style="border-bottom: 1px dotted #000; color:<?=$color?>;"><?=$defFinal;?></a></td>  
</tr>
  <?php
  $cont++;
  }//Fin mientras que
  ?>
  </table>
   
</div>

    <!-- start js include path -->
    <script src="../../config-general/assets/plugins/jquery/jquery.min.js" ></script>
    <script src="../../config-general/assets/plugins/popper/popper.js" ></script>
    <!-- bootstrap -->
    <script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
<script>
		$(function () {
			$('[data-toggle="popover"]').popover();
		});

		$('.popover-dismiss').popover({trigger: 'focus'});
	</script>
</body>
</html>


