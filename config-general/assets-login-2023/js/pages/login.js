const form = document.querySelector('form');
const emailInput = document.querySelector('#emailInput');
const password = document.querySelector('#password');
const institution = document.querySelector('#institution');
const year = document.querySelector('#year');
const capsLockMessage = document.querySelector('#caps-lock-message');

const togglePassword = document.querySelector('.toggle-password');

form.addEventListener('submit', function (event) {
  if (!form.checkValidity()) {
    event.preventDefault();
    event.stopPropagation();
  }

  form.classList.add('was-validated');

  if (!emailInput.checkValidity() || !password.checkValidity()) {
    emailInput.classList.add('is-invalid');
  } else {
    emailInput.classList.remove('is-invalid');
  }

  // Verificar que se haya seleccionado una opción válida en el campo de Institución
  if (institution.value === "") {
    institution.classList.add('is-invalid');
  } else {
    institution.classList.remove('is-invalid');
  }

  // Verificar que se haya seleccionado una opción válida en el campo de Año
  if (year.value === "") {
    year.classList.add('is-invalid');
  } else {
    year.classList.remove('is-invalid');
  }
});

emailInput.addEventListener('input', function (event) {
  if (emailInput.checkValidity()) {
    emailInput.classList.remove('is-invalid');
  }
});

password.addEventListener('input', function (event) {
  if (password.checkValidity()) {
    password.classList.remove('is-invalid');
  }
});

institution.addEventListener('change', function (event) {
  if (institution.value !== "") {
    institution.classList.remove('is-invalid');
  }
});

year.addEventListener('change', function (event) {
  if (year.value !== "") {
    year.classList.remove('is-invalid');
  }
});

password.addEventListener('keydown', function (event) {
  if (event.getModifierState('CapsLock')) {
    capsLockMessage.style.display = "block";
  } else {
    capsLockMessage.style.display = "none";
  }
});


// Función para alternar la visibilidad de la contraseña
function togglePasswordVisibility() {
  const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
  password.setAttribute('type', type);
  togglePassword.querySelector('i').classList.toggle('bi-eye');
  togglePassword.querySelector('i').classList.toggle('bi-eye-slash');
}

// Agregar un controlador de eventos para el clic en el botón del "ojo"
togglePassword.addEventListener('click', togglePasswordVisibility);