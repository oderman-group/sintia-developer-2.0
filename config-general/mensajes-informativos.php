<?php
if(isset($_GET['error']) || isset($_GET['success']) || isset($_GET['msgCurso'])){
    /* MENSAJES DE ERROR O INFORMATIVOS */
    if(isset($_GET['error'])){
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

            case 4:
                $tipo = 'primary';
                $mensaje = 'No se encontró una sesión de usuario activa. Ingrese al sistema nuevamente.';
            break;

            case 5:
                $tipo = 'danger';
                $mensaje = 'La clave no cumple con todos los requerimientos:<br>
                            - Debe tener mínimo 8 caracteres.<br>
                            - Solo se admiten caracteres de la a-z, A-Z, números(0-9) y los siguientes simbolos(. y $).';
            break;

            case 'ER_DT_1':
                $tipo = 'danger';
                $mensaje = 'Este usuario(<b>' . $_GET["usuario"] . '</b>) ya existe para otra persona. Cambie el nombre de usuario por favor.';
            break;

            case 'ER_DT_2':
                $tipo = 'danger';
                $mensaje = 'No tienes permiso para editar a este usuario: <b>' . $_GET["usuario"] . '</b>';
            break;


            default:
                $tipo = 'secondary';
                $mensaje = 'Error desconocido: '.$_GET['error'];
            break;
        }
    }    
    
    /* MENSAJES DE EXITO */
    if(isset($_GET['success'])){
        switch($_GET['success']){
            case 'SC_DT_1':
                $tipo = 'success';
                $mensaje = 'El registro fue creado correctamente con el ID único: <b>' . $_GET["id"] . '</b>';
            break;

            case 'SC_DT_2':
                $tipo = 'success';
                $mensaje = 'El registro fue actualizado correctamente para el ID único: <b>' . $_GET["id"] . '</b>';
            break;


            default:
                $tipo = 'secondary';
                $mensaje = 'Error desconocido: '.$_GET['error'];
            break;
        }
    }  
    
    /* MENSAJES PARA CURSOS */
    if(isset($_GET['msgCurso'])){
        switch($_GET['msgCurso']){
            case 1:
                $tipo = 'success';
                $mensaje = 'El curso fue crado exitosamente.';
            break;

            case 2:
                $tipo = 'warning';
                $mensaje = 'Debe llenar todos los campos.';
            break;

            case 3:
                $tipo = 'success';
                $mensaje = 'El información del curso se actualizo exitosamente.';
            break;

            case 4:
                $tipo = 'danger';
                $mensaje = 'El curso se elimino exitosamente.';
            break;

            default:
                $tipo = 'secondary';
                $mensaje = 'Error desconocido: '.$_GET['error'];
            break;
        }
    }
?>
    
    <div class="alert alert-block <?=$tipo;?>">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h4 class="alert-heading">MENSAJE INFORMATIVO</h4>
        <p><?=$mensaje;?></p>
    </div>

<?php    
}
?>