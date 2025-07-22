document.getElementById('login-form').addEventListener('submit', function (e) {
  e.preventDefault();
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  alert(`Bienvenido ${email}!`);
  // Aquí puedes agregar autenticación real más adelante
});