<div
  class="min-h-screen w-full flex items-center justify-center bg-gradient-to-br from-black via-gray-900 to-yellow-500 px-4 py-10">
  <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden p-8 space-y-4.5">
    <div class="text-center">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-800">Bienvenido de nuevo</h2>
      <p class="text-gray-500 mt-2">Inicia sesión para continuar</p>
    </div>

    <form method="POST" action="/procesar-login" class="space-y-5">
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
          <input type="password" name="password" id="password" required
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

    <div class="flex items-center justify-center gap-4">
      <hr class="w-full border-gray-300">
      <span class="text-gray-400 text-sm">O</span>
      <hr class="w-full border-gray-300">
    </div>

    <button
      class="btn w-full bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold flex items-center justify-center gap-2 rounded-xl py-3">
      <svg aria-label="Email icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="black">
          <rect width="20" height="16" x="2" y="4" rx="2"></rect>
          <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
        </g>
      </svg>
      Login with Email
    </button>

    <button
      class="btn w-full bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold flex items-center justify-center gap-2 rounded-xl py-3">
      <svg aria-label="Google logo" width="25" height="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
        <g>
          <path d="m0 0H512V512H0" fill="#fff"></path>
          <path fill="#34a853" d="M153 292c30 82 118 95 171 60h62v48A192 192 0 0190 341"></path>
          <path fill="#4285f4" d="m386 400a140 175 0 0053-179H260v74h102q-7 37-38 57"></path>
          <path fill="#fbbc02" d="m90 341a208 200 0 010-171l63 49q-12 37 0 73"></path>
          <path fill="#ea4335" d="m153 219c22-69 116-109 179-50l55-54c-78-75-230-72-297 55"></path>
        </g>
      </svg>
      Iniciar sesión con Google
    </button>
  </div>
</div>

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