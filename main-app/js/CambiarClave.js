
function cambiarTipoInput(id, icoVer) {
    var campo = document.getElementById(id);
    var divIcoVer = document.getElementById(icoVer);

    if (campo.type === "password") {
        campo.type = "text";
        divIcoVer.classList.remove("fa-eye");
        divIcoVer.classList.add("fa-eye-slash");
    } else {
        campo.type = "password";
        divIcoVer.classList.remove("fa-eye-slash");
        divIcoVer.classList.add("fa-eye");
    }
}

function validarClaveActual(enviada){
    var clave = CryptoJS.SHA1(enviada.value);
    var claveActual = enviada.getAttribute('data-clave-actual');

    if (clave == claveActual) {
        document.getElementById("respuestaClaveActual").style.color = 'green';
        document.getElementById("respuestaClaveActual").style.display = 'block';
        document.getElementById("btnEnviar").style.display = 'block';
        $("#respuestaClaveActual").html('Contraseña Correcta');
    } else {
        document.getElementById("respuestaClaveActual").style.color = 'red';
        document.getElementById("respuestaClaveActual").style.display = 'block';
        document.getElementById("btnEnviar").style.display = 'none';
        $("#respuestaClaveActual").html('La contraseña actual es incorrecta, por favor verifique y vuelva a intentar');
    }
}

function validarClaveNueva(enviada) {
    var clave = enviada.value;
    var regex = /^[A-Za-z0-9.$*]{8,20}$/;
    document.getElementById("claveNuevaDos").value = '';
    $("#respuestaConfirmacionClaveNueva").html('');

    if (regex.test(clave)) {
        document.getElementById("respuestaClaveNueva").style.color = 'green';
        document.getElementById("respuestaClaveNueva").style.display = 'block';
        document.getElementById("btnEnviar").style.display = 'block';
        $("#respuestaClaveNueva").html('Contraseña Valida');
    } else {
        document.getElementById("respuestaClaveNueva").style.color = 'red';
        document.getElementById("respuestaClaveNueva").style.display = 'block';
        document.getElementById("btnEnviar").style.display = 'none';
        $("#respuestaClaveNueva").html('La clave no cumple con todos los requerimientos:<br>- Debe tener entre 8 y 20 caracteres.<br>- Solo se admiten caracteres de la a-z, A-Z, números(0-9) y los siguientes simbolos(. y $).');
    }
}

function claveNuevaConfirmar(enviada) {
    var valueConfirmar = enviada.value;
    var claveNueva = document.getElementById("claveNueva");

    if (valueConfirmar==claveNueva.value) {
        document.getElementById("respuestaConfirmacionClaveNueva").style.color = 'green';
        document.getElementById("respuestaConfirmacionClaveNueva").style.display = 'block';
        document.getElementById("btnEnviar").style.display = 'block';
        $("#respuestaConfirmacionClaveNueva").html('Las Contraseñas Coinciden');
    } else {
        document.getElementById("respuestaConfirmacionClaveNueva").style.color = 'red';
        document.getElementById("respuestaConfirmacionClaveNueva").style.display = 'block';
        document.getElementById("btnEnviar").style.display = 'none';
        $("#respuestaConfirmacionClaveNueva").html('Las Contraseñas No Coinciden');
    }
}