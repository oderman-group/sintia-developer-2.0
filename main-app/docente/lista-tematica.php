<?php
include("session.php");
$idPaginaInterna = 'DC0046';
include("../compartido/historial-acciones-guardar.php");
include("verificar-carga.php");
include("../compartido/head.php");

$disabled = '';
if( !CargaAcademica::validarPermisoPeriodosDiferentes($datosCargaActual, $periodoConsultaActual) ) { 
    $disabled = 'disabled';
}

$consultaTematica=mysqli_query($conexion, "SELECT * FROM academico_indicadores WHERE ind_carga='".$cargaConsultaActual."' AND ind_periodo='".$periodoConsultaActual."' AND ind_tematica=1");
$tematica = mysqli_fetch_array($consultaTematica, MYSQLI_BOTH);
?>
</head>

<div class="panel">
    <header class="panel-heading panel-heading-purple"><?=$frases[119][$datosUsuarioActual[8]];?> </header>
    <div class="panel-body">

    
    <form name="formularioGuardar" action="guardar.php?carga=<?=$cargaConsultaActual;?>&periodo=<?=$periodoConsultaActual;?>" method="post">
        <input type="hidden" value="39" name="id">

        <div class="form-group row">
            <label class="col-sm-2 control-label">Descripción</label>
            <div class="col-sm-10">
                <textarea name="contenido" class="form-control" rows="5" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;" required <?=$disabled;?>><?=$tematica['ind_nombre'];?></textarea>
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-2 control-label">Fecha creación</label>
            <div class="col-sm-4">
                <input type="text" name="fecha" class="form-control" value="<?=$tematica['ind_fecha_creacion'];?>" readonly>
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-2 control-label">Última edición</label>
            <div class="col-sm-4">
                <input type="text" name="fecha" class="form-control" value="<?=$tematica['ind_fecha_modificacion'];?>" readonly>
            </div>
        </div>

        <input type="submit" class="btn btn-primary" value="Guardar cambios" <?=$disabled;?>>&nbsp;
    </form>
</div>

<?php include("../compartido/guardar-historial-acciones.php");?>