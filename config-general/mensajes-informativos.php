<?php
if(isset($_GET['error']) and is_numeric($_GET['error'])){
    switch($_GET['error']){
        case 1:
            $tipo = 'danger';
            $mensaje = 'El usuario no fue encontrado para esta institución en este año. Por favor verifique.';
        break;

        case 2:
            $tipo = 'danger';
            $mensaje = 'La contraseña ingresada es incorrecta. Por favor verifique.';
        break;

        case 3:
            $tipo = 'primary';
            $mensaje = 'Ha superado el número máximo de intentos fallidos. Por favor comúniquese con la administración.';
        break;

        default:
            $tipo = 'secondary';
            $mensaje = 'Error desconocido: '.$_GET['error'];
        break;
    }  
?>
    
    <div class="alert alert-<?=$tipo;?>" role="alert">
        <?=$mensaje;?>
    </div>

<?php    
}
?>