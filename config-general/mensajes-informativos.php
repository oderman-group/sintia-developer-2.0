<?php
if(isset($_GET['error']) || isset($_GET['success'])){
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

            case 6:
                $tipo = 'danger';
                $mensaje = 'Su usuario se encuentra bloqueado. Por favor comúniquese con la administración.';
            break;

            case 7:
                $tipo = 'danger';
                $mensaje = 'Verifica que hayas seleccionado la institución y el año correctamente.';
            break;

            case 'ER_DT_1':
                $tipo = 'danger';
                $mensaje = 'Este usuario(<b>' . $_GET["usuario"] . '</b>) ya existe para otra persona. Cambie el nombre de usuario por favor.';
            break;

            case 'ER_DT_2':
                $tipo = 'danger';
                $mensaje = 'No tienes permiso para editar a este usuario: <b>' . $_GET["usuario"] . '</b>';
            break;

            case 'ER_DT_3':
                $tipo = 'success';
                $mensaje = 'El registro se eliminó exitosamente.';
            break;

            case 'ER_DT_4':
                $tipo = 'warning';
                $mensaje = 'Debe llenar todos los campos.';
            break;

            case 'ER_DT_5':
                $tipo = 'warning';
                $mensaje = 'Este estudiante ya se ecuentra creado.';
            break;

            case 'ER_DT_6':
                $tipo = 'warning';
                $mensaje = 'El acudiente no existe, por tanto debe llenar todos los campos para registrarlo.';
            break;

            case 'ER_DT_7':
                $tipo = 'danger';
                $mensaje = $_GET['msj'];
            break;

            case 'ER_DT_8':
                $tipo = 'danger';
                $mensaje = 'El archivo enviado es invalido. Por favor vuelva a intentarlo.';
            break;

            case 'ER_DT_9':
                $tipo = 'danger';
                $mensaje = 'El estudiante o curso seleccionado no existe.';
            break;

            case 'ER_DT_10':
                $tipo = 'danger';
                $mensaje = 'Lo sentimos este curso no tiene grado siguiente, por favor asígnele uno desde la opción de editar en cursos.';
            break;

            case 'ER_DT_11':
                $tipo = 'danger';
                $mensaje = 'Este documento(<b>' . $_GET["documento"] . '</b>) ya existe para otra persona. Cambie el numero de documento por favor.';
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

            case 'SC_DT_3':
                $tipo = 'success';
                $mensaje = 'El registro fue eliminado correctamente para el ID único: <b>' . $_GET["id"] . '</b>';
            break;

            case 'SC_DT_4':
                $tipo = 'primary';
                $mensaje = $_GET["summary"];
            break;

            case 'SC_DT_5':
                $tipo = 'success';
                $mensaje = 'Una nueva contraseña fue generada y enviada a tu correo electrónico: <b>' . $_GET["email"] . '</b>';
            break;

            case 'SC_DT_6':
                $tipo = 'success';
                $mensaje = '
                Fueron creadas <b>' . $_GET["creadas"] . '</b> cargas académicas nuevas.
                ';
                if($_GET["noCreadas"] > 0) {
                    $mensaje .= '
                    <br>No se pudieron crear <b>' . $_GET["noCreadas"] . '</b> cargas académicas porque ya existía ese registro en el sistema. Por favor verifique.
                    ';
                }
            break;

            case 'SC_DT_7':
                $tipo = 'success';
                $mensaje = 'Del curso <b>'.$_GET["curso"].'</b> se promovieron <b>'.$_GET["numEstudiantesPromocionados"].'</b> estudiantes al curso <b>'.$_GET["siguiente"].'</b> correctamente.';
            break;

            case 'SC_DT_8':
                $tipo = 'success';
                $mensaje = 'La contraseña se genero correctamente para los usuarios escogidos.</b>';
            break;

            case 'SC_DT_9':
                $tipo = 'success';
                $mensaje = '
                Todos los estudiantes que NO estaban en estado <b>Matriculado</b> fueron removidos de la plataforma. TOTAL: <b>'.$_GET['numRegistros'].'</b><br>
                Ahora la plataforma está más limpia y puedes trabajar con los estudiantes que necesitas para este año.</b>
                ';
            break;


            default:
                $tipo = 'secondary';
                $mensaje = 'Error desconocido: '.$_GET['error'];
            break;
        }
    }
?>
    
    <div class="alert alert-block alert-<?=$tipo;?>">
        <p><?=$mensaje;?></p>
    </div>

<?php    
}
?>