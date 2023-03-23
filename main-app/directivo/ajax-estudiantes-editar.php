<?php 
include("session.php");
$consultaDoc=mysqli_query($conexion, "SELECT mat_documento FROM academico_matriculas
WHERE mat_documento ='".$_POST["nDoct"]."' AND mat_eliminado=0");
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
         Este número de documento(<b><?=$_POST["nDoct"];?></b>) ya se encuentra registrado y asociado a un estudiante .<br>
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