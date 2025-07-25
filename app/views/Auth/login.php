<style>
  body {
    background-color: #333333;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    overflow: hidden;
  }

  body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 215, 0, 0.05) 50%, rgba(255, 215, 0, 0) 100%);
    clip-path: polygon(0 0, 100% 0, 100% 100%, 70% 100%);
    z-index: 0;
  }

  #footer {
    position: absolute;
    bottom: 1rem;
    left: 1rem;
    font-size: 0.875rem;
    color: #ffffff;
    z-index: 10;
  }

  .toggle-password {
    cursor: pointer;
  }
</style>
</head>

<body class="font-sans">
  <div class="relative w-[900px] max-w-[95%] bg-white shadow-2xl rounded-2xl flex overflow-hidden z-10">
    <div class="flex w-full">
      <div class="w-1/2 p-10 bg-white flex flex-col justify-center">
        <h2 class="text-3xl font-bold text-black mb-8 text-center">Inicia Sesión</h2>
        <?php if (isset($_GET['error'])): ?>
          <p class="text-red-500 text-center mb-4">Usuario o contraseña incorrectos</p>
        <?php endif; ?>

        <form method="POST" action="/procesar-login">
          <div class="mb-5">
            <label for="user" class="block font-semibold mb-2 text-gray-800">Usuario</label>
            <div class="relative">
              <input type="text" name="user" id="user" required
                class="input input-bordered w-full pr-10 border-2 border-yellow-400 focus:border-yellow-500 focus:ring-yellow-500 text-black">
              <i class="bi bi-person-fill absolute right-3 top-1/2 -translate-y-1/2 text-yellow-500 text-lg"></i>
            </div>
          </div>

          <div class="mb-8">
            <label for="password" class="block font-semibold mb-2 text-gray-800">Contraseña</label>
            <div class="relative">
              <input type="password" name="password" id="password" required
                class="input input-bordered w-full pr-10 border-2 border-yellow-400 focus:border-yellow-500 focus:ring-yellow-500 text-black">
              <i class="bi bi-eye-fill toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-yellow-500 text-lg"
                onclick="togglePassword()"></i>
            </div>
          </div>

          <button type="submit"
            class="btn btn-warning w-full py-3 text-lg font-bold rounded-xl bg-yellow-400 hover:bg-yellow-500 text-black border-none">
            Ingresar
          </button>
        </form>
      </div>

      <div class="w-1/2 p-10 bg-black text-white flex flex-col justify-center items-center text-center">
        <h1 class="text-6xl font-extrabold leading-tight tracking-wide mb-4">LUM<br><span
            class="text-yellow-400">Logistic</span></h1>
        <p class="text-lg text-gray-300">¡Tu logística, nuestra velocidad!</p>
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
    © 2025 Lum Logistic SAS. Reservados todos los derechos.
  </div>
</body>

</html>