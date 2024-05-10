<?php 
include("session.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");

$doctSinPuntos = strpos($_POST["nDoct"], '.') == true ? str_replace('.', '', $_POST["nDoct"]) : $_POST["nDoct"];
$doctConPuntos = strpos($_POST["nDoct"], '.') !== true && is_numeric($_POST["nDoct"]) ? str_replace('.', '', $_POST["nDoct"]) : $_POST["nDoct"];

$consultaDoc = Estudiantes::obtenerListadoDeEstudiantes(" AND (mat_documento ='".$doctSinPuntos."' OR mat_documento ='".$doctConPuntos."') AND mat_id!='".$_POST["idEstudiante"]."' AND mat_eliminado=0");

$numDatos=mysqli_num_rows($consultaDoc);
if ($numDatos > 0) {
    $datosEstudiantes = mysqli_fetch_array($consultaDoc, MYSQLI_BOTH);
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
         Este número de documento ya se encuentra registrado y asociado al estudiante <b><?=$datosEstudiantes["mat_nombres"]?></b> <b><?=$datosEstudiantes["mat_nombre2"]?></b> <b><?=$datosEstudiantes["mat_primer_apellido"];?></b> <b><?=$datosEstudiantes["mat_segundo_apellido"];?></b> <br>
         ¿Desea mostrar toda la información del estudiante?.
        </p>
        <p style="margin-top:10px;">
            <div class="btn-group">
                <a href="estudiantes-editar.php?id=<?=base64_encode($datosEstudiantes['mat_id']);?>" id="addRow" class="btn deepPink-bgcolor">
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