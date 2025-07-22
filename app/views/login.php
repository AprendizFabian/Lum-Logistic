<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Lum</title>
  <link rel="stylesheet" href="assets/login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <div class="container-login">
    <div class="box-login">
      <div class="form-side">
        <h2>Login</h2>
        <?php if (isset($_GET['error'])): ?>
  <p style="color: red; text-align: center;">Usuario o contraseña incorrectos</p>
<?php endif; ?>

        <form method="POST" action="/procesar-login">
          <div class="input-group">
            <label for="usuario">Usuario</label>
            <div class="input-icon">
              <input type="text" name="usuario" id="usuario" required>
              <i class="bi bi-person-fill"></i>
            </div>
          </div>

          <div class="input-group">
            <label for="contrasena">Contraseña</label>
            <div class="input-icon">
              <input type="password" name="contrasena" id="contrasena" required>
              <i class="bi bi-eye-fill toggle-password" onclick="togglePassword()"></i>
            </div>
          </div>

          <button type="submit" class="btn-login">Login</button>
        </form>
      </div>

      <div class="welcome-side">
        <h1>PEPE<br><span>LEPO</span></h1>
        <p>se callo el muchacho</p>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById("contrasena");
      const icon = document.querySelector(".toggle-password");
      if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
      } else {
        input.type = "password";
        icon.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
      }
    }
  </script>
<div id="footer">
  © 2025 Lum logistic SAS. Reservados todos los derechos. | 
</div>
</body>
</html>
