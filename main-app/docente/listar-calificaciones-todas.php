<?php
include("session.php");
$idPaginaInterna = 'DC0011';
include("../compartido/historial-acciones-guardar.php");
include("verificar-carga.php");
include("../compartido/head.php");
require_once("../class/Estudiantes.php");

$consultaValores=mysqli_query($conexion, "SELECT
(SELECT sum(act_valor) FROM academico_actividades 
WHERE act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1),
(SELECT count(*) FROM academico_actividades 
WHERE act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1)
");
$valores = mysqli_fetch_array($consultaValores, MYSQLI_BOTH);
$porcentajeRestante = 100 - $valores[0];
?>

<?php
	$deleteOculto = 'style="display:none;"';
    $disabledNotas = 'disabled';
	if( CargaAcademica::validarPermisoPeriodosDiferentes($datosCargaActual, $periodoConsultaActual) ) {
		$deleteOculto = 'style="display:block;"';
        $disabledNotas = '';
	}
?>
</head>

<div class="card card-topline-purple">
    <div class="card-head">
        <header><?=$frases[243][$datosUsuarioActual['uss_idioma']];?></header>
        <div class="tools">
            <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
        </div>
    </div>
    <div class="card-body">
        
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-12">
                
                
                
        <?php
        if( CargaAcademica::validarAccionAgregarCalificaciones($datosCargaActual, $valores, $periodoConsultaActual, $porcentajeRestante) ) {
        ?>
        
                <div class="btn-group">
                    <a href="calificaciones-agregar.php?carga=<?=base64_encode($cargaConsultaActual);?>&periodo=<?=base64_encode($periodoConsultaActual);?>" id="addRow" class="btn deepPink-bgcolor">
                        Agregar nuevo <i class="fa fa-plus"></i>
                    </a>
                </div>
                
                
        <?php
        }
        ?>
                
        <?php if($datosCargaActual['car_configuracion']==1 and $porcentajeRestante<=0){?>
            <p style="color: tomato;"> Has alcanzado el 100% de valor para las calificaciones. </p>
        <?php }?>
                    
        <?php if($datosCargaActual['car_maximas_calificaciones']<=$valores[1]){?>
            <p style="color: tomato;"> Has alcanzado el número máximo de calificaciones permitidas. </p>
        <?php }?>
        
        <?php if( CargaAcademica::validarPermisoPeriodosDiferentes($datosCargaActual, $periodoConsultaActual) ) {?>
                <div class="btn-group">
                    <a href="calificaciones-todas-rapido.php?carga=<?=base64_encode($cargaConsultaActual);?>&periodo=<?=base64_encode($periodoConsultaActual);?>" class="btn bg-purple">
                        LLenar más rápido las calificaciones
                    </a>
                </div>
        <?php }?>
        
            </div>
        </div>
        
    <div class="table-responsive">
        
        <span id="respRCT"></span>
        <?php
        $arrayEnviar = array("tipo"=>1, "descripcionTipo"=>"Para ocultar fila del registro.");
        $arrayDatos = json_encode($arrayEnviar);
        $objetoEnviar = htmlentities($arrayDatos);
        ?>
        
        <table class="table table-striped custom-table table-hover">
            <thead>
                <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 400px;"><?=$frases[61][$datosUsuarioActual[8]];?></th>
                <?php
                    $cA = mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_carga='".$cargaConsultaActual."' AND act_estado=1 AND act_periodo='".$periodoConsultaActual."'");
                    while($rA = mysqli_fetch_array($cA, MYSQLI_BOTH)){
                    echo '<th style="text-align:center; font-size:11px; width:100px;"><a href="calificaciones-editar.php?idR='.base64_encode($rA[0]).'" title="'.$rA[1].'">'.$rA[0].'<br>
                    '.$rA[1].'<br>
                    ('.$rA[3].'%)</a><br>
                    <a href="#" name="calificaciones-eliminar.php?idR='.base64_encode($rA[0]).'&idIndicador='.base64_encode($rA['act_id_tipo']).'&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'" onClick="deseaEliminar(this)" '.$deleteOculto.'><i class="fa fa-times"></i></a><br>
                    <input type="text" style="text-align: center; font-weight: bold;" maxlength="3" size="10" title="3" name="'.$rA[0].'" onChange="notas(this)" '.$disabledNotas.'>
                    </th>';
                    }
                ?>
                <th style="text-align:center; width:60px;">%</th>
                <th style="text-align:center; width:60px;"><?=$frases[118][$datosUsuarioActual[8]];?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $contReg = 1; 
                $consulta = Estudiantes::escogerConsultaParaListarEstudiantesParaDocentes($datosCargaActual);
                while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
                    //DEFINITIVAS
                    $carga = $cargaConsultaActual;
                    $periodo = $periodoConsultaActual;
                    $estudiante = $resultado['mat_id'];
                    include("../definitivas.php");
                    
                    $colorEstudiante = '#000;';
                    if($resultado['mat_inclusion']==1){$colorEstudiante = 'blue;';}
                ?>
                
                <tr>
                    <td style="text-align:center;" style="width: 100px;"><?=$contReg;?></td>
                    <td style="color: <?=$colorEstudiante;?>">
                        <img src="../files/fotos/<?=$resultado['uss_foto'];?>" width="50">
                        <?=Estudiantes::NombreCompletoDelEstudiante($resultado);?>
                    </td>

                    <?php
                        $cA = mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_carga='".$cargaConsultaActual."' AND act_estado=1 AND act_periodo='".$periodoConsultaActual."'");
                        while($rA = mysqli_fetch_array($cA, MYSQLI_BOTH)){
                        //LAS CALIFICACIONES
                        $consultaNotasResultados=mysqli_query($conexion, "SELECT * FROM academico_calificaciones WHERE cal_id_estudiante=".$resultado['mat_id']." AND cal_id_actividad=".$rA[0]);
                        $notasResultado = mysqli_fetch_array($consultaNotasResultados, MYSQLI_BOTH);
                    ?>
                        <td style="text-align:center;">
                            
                        <?php
                        $arrayEnviar = [
                            "tipo"=>5, 
                            "descripcionTipo"=>"Para ocultar la X y limpiar valor, cuando son diferentes actividades.", 
                            "idInput"=>$resultado['mat_id']."-".$rA[0]
                        ];
                        $arrayDatos = json_encode($arrayEnviar);
                        $objetoEnviar = htmlentities($arrayDatos);
                        ?>
                        <input size="5" maxlength="3" name="<?=$rA[0]?>" id="<?=$resultado['mat_id']."-".$rA[0];?>" value="<?php if(isset($notasResultado)) echo $notasResultado[3];?>" title="1" alt="<?=$resultado['mat_nombres'];?>" step="<?=$notasResultado[3];?>" onChange="notas(this)" tabindex="2" style="font-size: 13px; text-align: center; color:<?php if($notasResultado[3]<$config[5] and $notasResultado[3]!="")echo $config[6]; elseif($notasResultado[3]>=$config[5]) echo $config[7]; else echo "black";?>;" <?=$disabledNotas;?>>
                            
                        <?php if(isset($notasResultado) && $notasResultado[3]!=""){?>
                            <a href="#" title="<?=$objetoEnviar;?>" id="<?=$notasResultado['cal_id'];?>" name="calificaciones-nota-eliminar.php?id=<?=base64_encode($notasResultado['cal_id']);?>" onClick="deseaEliminar(this)" <?=$deleteOculto;?>><i class="fa fa-times"></i></a>
                            <?php if($notasResultado[3]<$config[5]){?>
                                <br><br><input size="5" maxlength="3" name="<?=$rA[0]?>" id="<?=$resultado['mat_id'];?>" title="4" alt="<?=$resultado['mat_nombres'];?>" step="<?=$notasResultado[3];?>" onChange="notas(this)" tabindex="2" style="font-size: 13px; text-align: center; border-color:tomato;" placeholder="Recup" <?=$disabledNotas;?>>
                            <?php }?>
                            
                        <?php }?>

                        </td>
                    <?php		
                        }
                    if($definitiva<$config[5] and $definitiva!="") $colorDef = $config[6]; elseif($definitiva>=$config[5]) $colorDef = $config[7]; else $colorDef = "black";
                    ?>

                    <td style="text-align:center;"><?=$porcentajeActual;?></td>
                    <td style="color:<?php if($definitiva<$config[5] and $definitiva!="")echo $config[6]; elseif($definitiva>=$config[5]) echo $config[7]; else echo "black";?>; text-align:center; font-weight:bold;"><a href="calificaciones-estudiante.php?usrEstud=<?=base64_encode($resultado['mat_id_usuario']);?>&periodo=<?=base64_encode($periodoConsultaActual);?>&carga=<?=base64_encode($cargaConsultaActual);?>" style="text-decoration:underline; color:<?=$colorDef;?>;"><?=$definitiva;?></a></td>
                </tr>
                <?php
                    $contReg++;
                    }
                    ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<?php include("../compartido/guardar-historial-acciones.php");?>