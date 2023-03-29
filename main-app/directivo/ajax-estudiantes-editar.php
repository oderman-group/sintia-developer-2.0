<?php 
include("session.php");
$consultaDoc=mysqli_query($conexion, "SELECT * FROM academico_matriculas
WHERE mat_documento ='".$_POST["nDoct"]."' AND mat_eliminado=0");
$datosEstudiantes = mysqli_fetch_array($consultaDoc, MYSQLI_BOTH);
$numDatos=mysqli_num_rows($consultaDoc);
if ($numDatos > 0) {
?>
    <script type="application/javascript">
        document.getElementById('apellido1').disabled = 'disabled';
        document.getElementById('apellido2').disabled = 'disabled';
        document.getElementById('nombres').disabled = 'disabled';
        document.getElementById('nDoc').style.backgroundColor = "#f8d7da";
    </script>   
    
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>

        <p>
         Este número de documento ya se encuentra registrado y asociado al estudiante <b><?=$datosEstudiantes["mat_nombres"]?></b> <b><?=$datosEstudiantes["mat_nombre2"]?></b> <b><?=$datosEstudiantes["mat_primer_apellido"]?></b> <b><?=$datosEstudiantes["mat_segundo_apellido"];?></b> <br>
         ¿Desea mostrar toda la información del estudiante?.
        </p>
        <p style="margin-top:10px;">
            <div class="btn-group">
                <a href="estudiantes-editar.php?id=<?=$datosEstudiantes['mat_id'];?>" id="addRow" class="btn deepPink-bgcolor">
                    Sí, deseo mostrar la información
                </a>
            </div>
        </p>
        

    </div>
<?php
    exit();
}else{
?>
    <script type="application/javascript">
        document.getElementById('apellido1').disabled = '';
        document.getElementById('apellido2').disabled = '';
        document.getElementById('nombres').disabled = '';
        document.getElementById('nDoc').style.backgroundColor = "";
    </script> 
<?php    
}