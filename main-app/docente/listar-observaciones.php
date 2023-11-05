<?php include("session.php");?>
<?php $idPaginaInterna = 'DC0080';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("verificar-carga.php");?>
<?php //include("verificar-periodos-diferentes.php");?>
<?php include("../compartido/head.php");?>
<?php
require_once("../class/Estudiantes.php");
$disabled = '';
if( !CargaAcademica::validarPermisoPeriodosDiferentes($datosCargaActual, $periodoConsultaActual) ) { 
    $disabled = 'disabled';
}
?>
</head>

<div class="card card-topline-purple">
    <div class="card-head">
        <header>Observaciones</header>
        <div class="tools">
            <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
        </div>
    </div>
    
    

    
    <div class="card-body">
        

    <span style="color: blue; font-size: 15px;" id="respOBS"></span>
        
        
    <div class="table-responsive">
        <table class="table table-striped custom-table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th><?=$frases[61][$datosUsuarioActual[8]];?></th>
                    <th>DEF</th>
                    <th><?=$frases[109][$datosUsuarioActual[8]];?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $consulta = Estudiantes::escogerConsultaParaListarEstudiantesParaDocentes($datosCargaActual);
                    $contReg = 1;
                    $colorNota = "black";
                    while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
                        $consultaNotas=mysqli_query($conexion, "SELECT * FROM academico_boletin WHERE bol_estudiante=".$resultado['mat_id']." AND bol_periodo='".$periodoConsultaActual."' AND bol_carga='".$cargaConsultaActual."'");
                    $notas = mysqli_fetch_array($consultaNotas, MYSQLI_BOTH);
                    $definitiva = isset($notas['bol_nota']) ? $notas['bol_nota'] : null;
                    if($definitiva<$config[5] and $definitiva!="") $colorNota = $config[6]; elseif($definitiva>=$config[5]) $colorNota = $config[7]; else {$colorNota = 'black'; $definitiva='';} 
                    
                    ?>
                
                <tr>
                    <td><?=$contReg;?></td>
                    <td><?=$resultado['mat_id'];?></td>
                    <td width="60%">
                        <img src="../files/fotos/<?=$resultado['uss_foto'];?>" width="50">
                        <?=Estudiantes::NombreCompletoDelEstudiante($resultado);?>
                    </td>
                    
                    <td><a href="calificaciones-estudiante.php?usrEstud=<?=base64_encode($resultado['mat_id_usuario']);?>&periodo=<?=base64_encode($periodoConsultaActual);?>&carga=<?=base64_encode($cargaConsultaActual);?>" style="text-decoration:underline; color:<?=$colorNota;?>;"><?=$definitiva;?></a><br><span style="font-size: 10px;"><?php if(isset($notas)) echo $notas['bol_observaciones'];?></span></td>
                    
                    <td width="25%">
                        
                        <textarea rows="7" cols="80" 
                        id="<?=$resultado['mat_id'];?>" 
                        name="<?=$cargaConsultaActual;?>" title="<?=$periodoConsultaActual;?>" 
                        onChange="observacionesBoletin(this)" 
                        <?=$disabled;?>
                        ><?php if(isset($notas)) echo $notas['bol_observaciones_boletin'];?></textarea>
                    </td>
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