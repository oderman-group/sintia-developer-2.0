const togglePassword = document.querySelector('.toggle-password');

// Función para alternar la visibilidad de la contraseña
function togglePasswordVisibility() {
  const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
  password.setAttribute('type', type);
  togglePassword.querySelector('i').classList.toggle('bi-eye');
  togglePassword.querySelector('i').classList.toggle('bi-eye-slash');
}

// Agregar un controlador de eventos para el clic en el botón del "ojo"
if(togglePassword){
  togglePassword.addEventListener('click', togglePasswordVisibility);
}