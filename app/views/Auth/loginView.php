<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div
  class="min-h-screen w-full flex items-center justify-center bg-gradient-to-br from-black via-gray-900 to-yellow-500 px-4 py-10">
  <div class="w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden p-8 space-y-4.5">
    <div class="text-center">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-800">Bienvenido de nuevo</h2>
      <p class="text-gray-500 mt-2">Inicia sesión para continuar</p>
    </div>

    <form method="POST" action="/auth/processLogin" class="space-y-5">
      <div>
        <label for="user" class="block text-sm font-medium text-gray-700">Correo</label>
        <div class="relative">
          <input type="text" name="user" id="user" required
            class="input input-bordered w-full border-yellow-400 focus:border-yellow-500 focus:ring-yellow-500 text-black pr-12">
          <i
            class="fa-solid fa-envelope absolute right-3 top-1/2 -translate-y-1/2 text-yellow-500 text-lg pointer-events-none"></i>
        </div>
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
        <div class="relative">
          <input type="password" name="password" id="password"
            class="input input-bordered w-full border-yellow-400 focus:border-yellow-500 focus:ring-yellow-500 text-black pr-12">
          <div
            class="absolute right-3 top-1/2 -translate-y-1/2 text-yellow-500 text-lg cursor-pointer pointer-events-auto"
            onclick="togglePassword(this)">
            <i class="fa-solid fa-lock"></i>
          </div>
        </div>
      </div>

      <button type="submit"
        class="btn w-full bg-[#FFD700] hover:bg-yellow-500 text-black font-bold text-lg rounded-xl py-3">
        <i class="fa-solid fa-right-to-bracket mr-2"></i>Ingresar
      </button>
    </form>
  </div>
</div>

<?php
$errorMessages = [
  'empty_fields' => 'Por favor, completa todos los campos.',
  'invalid_email' => 'El formato del correo electrónico no es válido.',
  'credentials' => 'Correo o contraseña incorrectos.',
  'inactive' => 'Tu cuenta está inactiva, por favor contacta al administrador.',
];

$successMessages = [
  'login' => 'Has iniciado sesión correctamente.',
];

$errorType = $_GET['error'] ?? null;
$errorMessage = $errorMessages[$errorType] ?? null;

$successType = $_GET['success'] ?? null;
$successMessage = $successMessages[$successType] ?? null;
?>

<?php if ($successMessage): ?>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      Swal.fire({
        icon: 'success',
        title: 'Acceso concedido',
        html: '<?= addslashes($successMessage) ?>',
        confirmButtonColor: '#FFD700',
        confirmButtonText: 'Entendido'
      }).then(() => {
        window.location.href = '/catalogo';
      });
    });
  </script>
<?php elseif ($errorMessage): ?>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      Swal.fire({
        icon: 'error',
        title: 'Acceso denegado',
        html: '<?= addslashes($errorMessage) ?>',
        confirmButtonColor: '#FFD700',
        confirmButtonText: 'Entendido'
      }).then(() => {
        window.history.replaceState({}, '', '/login');
      });
    });
  </script>
<?php endif; ?>

<script>
  function togglePassword(iconContainer) {
    const input = document.getElementById("password");
    const icon = iconContainer.querySelector('i');
    const type = input.getAttribute("type") === "password" ? "text" : "password";
    input.setAttribute("type", type);
    icon.classList.toggle("fa-lock");
    icon.classList.toggle("fa-lock-open");
  }
</script>