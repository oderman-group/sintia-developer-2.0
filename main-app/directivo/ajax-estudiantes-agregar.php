<?php 
include("session.php");
$consultaDoc=mysqli_query($conexion, "SELECT mat_documento FROM academico_matriculas
WHERE mat_documento ='".$_POST["nDoct"]."' AND mat_eliminado=0");
$numDotos=mysqli_num_rows($consultaDoc);
if ($numDotos > 0) {
    include("../class/Estudiantes.php");
    $datosEstudianteActual = Estudiantes::obtenerDatosEstudiante($_POST["nDoct"]);
    $nombreEstudiante = Estudiantes::NombreCompletoDelEstudiante($datosEstudianteActual);
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
            Este número de documento se encuentra registrado y asociado al estudiante <b><?=$nombreEstudiante;?></b>.<br>
            ¿Desea mostrar toda la información del estudiante?
        </p>
        
        <p style="margin-top:10px;">
            <div class="btn-group">
                <a href="estudiantes-editar.php?id=<?=$datosEstudianteActual['mat_id'];?>" id="addRow" class="btn deepPink-bgcolor">
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