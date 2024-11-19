const togglePassword = document.querySelector('.toggle-password');

// Función para alternar la visibilidad de la contraseña
function togglePasswordVisibility() {
  const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
  password.setAttribute('type', type);
  togglePassword.querySelector('i').classList.toggle('bi-eye');
  togglePassword.querySelector('i').classList.toggle('bi-eye-slash');
}

// Agregar un controlador de eventos para el clic en el botón del "ojo"
if (togglePassword) {
  togglePassword.addEventListener('click', togglePasswordVisibility);
}

let intento = 1;

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      email = document.getElementById('email').value;
      document.getElementById('emailCode').innerHTML = email;
      enviarCodigo();
    }
  });
});

const activarCuenta = document.getElementById('emailCode');
observer.observe(activarCuenta);

document.querySelectorAll('.code-input').forEach((input, index, inputs) => {
  // Manejar el evento de pegar (paste)
  input.addEventListener('paste', (e) => {
      e.preventDefault(); // Prevenir el comportamiento por defecto

      // Obtener el texto pegado
      const pasteData = e.clipboardData.getData('text');

      // Validar que los datos sean números y de longitud correcta
      if (/^\d{6}$/.test(pasteData)) {
          // Dividir los caracteres y asignarlos a los inputs
          pasteData.split('').forEach((char, i) => {
              if (inputs[i]) {
                  inputs[i].value = char;
              }
          });

          // Enfocar el siguiente input después de pegar
          const lastFilledInput = inputs[Math.min(pasteData.length - 1, inputs.length - 1)];
          if (lastFilledInput) lastFilledInput.focus();
      } else {
          alert('Por favor, pega un código válido de 6 dígitos.');
      }
  });
  
  input.addEventListener('input', (e) => {
    if (e.target.value.length === 1 && index < inputs.length - 1) {
      inputs[index + 1].focus(); // Saltar al siguiente campo automáticamente
    }
  });

  input.addEventListener('keydown', (e) => {
    if (e.key === 'Backspace' && index > 0 && !e.target.value) {
      inputs[index - 1].focus();
    }
  });
});

// Función para iniciar la cuenta regresiva
function startCountdown(durationInSeconds) {
  const contMinElement = document.getElementById('contMin');
  const textMinElement = document.getElementById('textMin');
  const intNuevoElement = document.getElementById('intNuevo');
  var colorCambio = intNuevoElement.getAttribute('data-colo-cambio')
  let remainingTime = durationInSeconds;

  // Actualiza cada segundo
  const interval = setInterval(() => {
    const minutes = Math.floor(remainingTime / 60); // Calcula los minutos
    const seconds = remainingTime % 60; // Calcula los segundos

    // Muestra el tiempo en formato MM:SS
    contMinElement.innerHTML = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

    if (minutes === 1) {
      textMinElement.innerHTML = `minuto`;
    } else if (minutes === 0) {
      textMinElement.innerHTML = `segundos`;
    }

    if (remainingTime === 0) {
      clearInterval(interval); // Detén la cuenta regresiva al llegar a 0

      // Cambiar el color del texto
      intNuevoElement.style.color = colorCambio;
      intNuevoElement.onclick = function () {
        intento++;
        enviarCodigo();
      };
    }

    remainingTime -= 1; // Reduce el tiempo restante en 1 segundo
  }, 1000);
}

async function miFuncionConDelay(element, alert = '') {
  await new Promise(resolve => setTimeout(resolve, 10000));
  element.style.visibility = 'hidden';
  element.innerHTML = '-';
  if (alert !== '') {
    element.classList.remove(alert);
  }
  element.classList.remove('animate__animated', 'animate__flash', 'animate__repeat-2');
}

function enviarCodigo() {
  // Capturar el correo electrónico ingresado
  nombre      =   document.getElementById('nombre').value;
  apellidos   =   document.getElementById('apellidos').value;
  email       =   document.getElementById('email').value;
  celular     =   document.getElementById('celular').value;

  // Enviar el código al correo electrónico
  fetch('enviar-codigo.php?nombre=' + nombre + '&apellidos=' + apellidos + '&email=' + email + '&celular=' + celular, {
    method: 'GET'
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {

        // Mostrar mensaje si es un nuevo intento
        if (intento > 1) {
          const errorMessage = document.getElementById('message');

          errorMessage.innerHTML = 'Hemos enviado un nuevo código a tu correo electrónico,<br> si no ves el correo revisa tu carpeta de spam o<br> verifica que hayas ingresado bien tu correo electrónico.';
          errorMessage.style.visibility = 'visible';
          errorMessage.classList.add('alert-success', 'animate__animated', 'animate__flash', 'animate__repeat-2');
          miFuncionConDelay(errorMessage, 'alert-success');
        }

        startCountdown(10 * 60); // Inicia la cuenta regresiva con 10 minutos
        console.log(data.message);
      } else {
        alert(data.message);
      }
    });
}

$(document).ready(function () {
  $('.form-select').select2({
    theme: 'bootstrap-5'
  });

  $('.select2').on('select2:open', function () {
    $(this).parent().find('.select2-selection--single').addClass('form-control');
  });

  $('form').on('submit', function (e) {
    if (!this.checkValidity()) {
      $('#institution').addClass('is-invalid');
      this.classList.add('was-validated');
      e.preventDefault();
    } else {
      $('#institution').removeClass('is-invalid');
    }
  });
});