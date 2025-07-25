<!-- Contenedor principal -->
<div class="bg-white rounded-2xl shadow-2xl w-[900px] max-w-[95%] flex flex-row md:flex-row overflow-hidden z-10 mx-auto">
  <!-- Formulario -->
  <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">
    <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Inicia Sesión</h2>

    <?php if (isset($_GET['error'])): ?>
      <p class="text-red-500 text-center mb-4">Usuario o contraseña incorrectos</p>
    <?php endif; ?>

    <form method="POST" action="/procesar-login" class="space-y-6">
      <!-- Usuario -->
      <div>
        <label for="user" class="block font-medium text-gray-700 mb-1">Usuario</label>
        <input type="text" name="user" id="user" required class="input input-bordered w-full text-black" />
      </div>

      <!-- Contraseña -->
      <div>
        <label for="password" class="block font-medium text-gray-700 mb-1">Contraseña</label>
        <div class="relative">
          <input type="password" name="password" id="password" required
            class="input input-bordered w-full pr-10 text-black" />
          <!-- Icono de ojo para mostrar/ocultar contraseña -->
          <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-yellow-500"
            onclick="togglePassword()" aria-label="Mostrar u ocultar contraseña">
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Botón -->
      <button type="submit" class="btn btn-warning w-full text-lg font-semibold text-black">
        Ingresar
      </button>
    </form>
  </div>

  <!-- Lado derecho -->
  <div class="w-full md:w-1/2 bg-black text-white flex flex-col justify-center items-center p-10 text-center">
    <h1 class="text-5xl font-extrabold leading-tight tracking-wide mb-4">
      LUM <br /><span class="text-yellow-400">Logistic</span>
    </h1>
    <p class="text-lg text-gray-300">¡Tu logística, nuestra velocidad!</p>
  </div>
</div>

<script>
  function togglePassword() {
    const input = document.getElementById("password");
    const icon = document.getElementById("eyeIcon");

    if (input.type === "password") {
      input.type = "text";
      icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 
              9.978 0 012.241-3.592M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
          `;
    } else {
      input.type = "password";
      icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 
              7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
          `;
    }
  }
</script>