<div class="container mx-auto px-6 py-10 max-w-7xl">
  <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">Validador de Productos</h1>

  <!-- Sección de formulario y conversor -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- Formulario -->
    <form id="formValidador" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
      <h2 class="text-2xl font-semibold text-gray-700 mb-4">Ingresar Datos</h2>

      <div class="mb-4">
        <label for="ean" class="block text-gray-700 font-medium mb-1">Código EAN:</label>
        <input type="text" id="ean" name="ean" required
          class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      <div class="mb-6">
        <label for="fecha_vencimiento" class="block text-gray-700 font-medium mb-1">Fecha de Vencimiento:</label>
        <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" required
          class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      <button type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
        Validar Producto
      </button>
    </form>

    <!-- Conversor Juliano -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
      <h2 class="text-2xl font-semibold text-gray-700 mb-4">Conversor Fecha Juliana</h2>

      <div class="mb-4">
        <label for="julianaInput" class="block text-gray-700 font-medium mb-1">Fecha Juliana (5 dígitos):</label>
        <input type="text" id="julianaInput" placeholder="Ej: 24123"
          class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
      </div>

      <button type="button" onclick="convertirJuliana()"
        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">
        Convertir
      </button>

      <div id="resultadoJuliana" class="mt-4 hidden">
        <p class="text-sm text-gray-600">Fecha convertida: <span id="fechaConvertida" class="font-medium"></span></p>
      </div>
    </div>
  </div>

  <!-- Botones de descarga -->
  <div class="mt-12 flex flex-col md:flex-row justify-center gap-4">
    <a href="/descargar/validador"
      class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-6 py-2 rounded-xl text-center">
      Descargar Validador
    </a>
    <a href="/descargar/catalogo"
      class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-2 rounded-xl text-center">
      Descargar Catálogo
    </a>
    <a href="/descargar/vu"
      class="bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-2 rounded-xl text-center">
      Descargar V.U
    </a>
  </div>
</div>

<!-- Modal -->
<!-- Modal -->
<input type="checkbox" id="resultadoModal" class="modal-toggle" />
<div class="modal">
  <div class="modal-box relative max-w-2xl rounded-2xl shadow-xl p-0 overflow-hidden">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-4 flex items-center justify-between">
      <h3 class="text-white text-lg font-semibold flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h6l6 6v10a2 2 0 01-2 2z" />
        </svg>
        Resultado de Validación
      </h3>
      <label for="resultadoModal" class="btn btn-sm btn-circle bg-white text-gray-700 hover:bg-gray-200">✕</label>
    </div>

    <!-- Loader -->
    <div id="loader" class="flex flex-col items-center justify-center py-10">
      <span class="loading loading-spinner loading-lg text-primary"></span>
      <p class="mt-4 text-gray-500 animate-pulse">Procesando, por favor espera...</p>
    </div>

    <!-- Contenido -->
    <div id="resultadoContenido" class="hidden p-6 space-y-4">

      <!-- Grid de datos -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-gray-50 p-3 rounded-lg border">
          <p class="text-xs text-gray-500">EAN</p>
          <p id="resEAN" class="font-medium text-gray-800">—</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border sm:col-span-2">
          <p class="text-xs text-gray-500">Descripción</p>
          <p id="resDescripcion" class="font-medium text-gray-800">—</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border">
          <p class="text-xs text-gray-500">Categoría</p>
          <p id="resCategoria" class="font-medium text-gray-800">—</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border">
          <p class="text-xs text-gray-500">Días de vida útil</p>
          <p id="resVidaUtil" class="font-medium text-gray-800">—</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border">
          <p class="text-xs text-gray-500">Fecha de bloqueo</p>
          <p id="resBloqueo" class="font-medium text-gray-800">—</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border">
          <p class="text-xs text-gray-500">Concepto de bloqueo</p>
          <p id="resConcepto" class="font-medium text-gray-800">—</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border sm:col-span-2">
          <p class="text-xs text-gray-500">Observación</p>
          <p id="resObservacion" class="font-medium text-gray-800">—</p>
        </div>
      </div>

    </div>
  </div>
</div>
<a href="http://localhost/proyecto/LUM-Logistic-Prueba/public/plantillas/plantilla.xlsx"
download="plantilla.xlsx" 
   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
   Descargar plantilla
</a>

<!-- Bloque de cargue masivo -->
<div class="mt-12 bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
  <h2 class="text-2xl font-semibold text-gray-700 mb-4">Cargue Masivo de Productos</h2>
  <form id="formCargueMasivo" enctype="multipart/form-data">
    <div class="mb-4">
      <label for="archivoMasivo" class="block text-gray-700 font-medium mb-1">Seleccionar archivo:</label>
      <input type="file" id="archivoMasivo" name="archivo" accept=".csv" required
        class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
    </div>
    <button type="submit"
      class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 rounded-lg transition">
      Subir archivo
    </button>
  </form>
  <div id="mensajeCargue" class="mt-4 text-sm font-medium hidden"></div>
  <ul id="listaErrores" class="mt-2 text-red-500 text-sm list-disc pl-5 hidden"></ul>
</div>

<script>
  const formCargueMasivo = document.getElementById("formCargueMasivo");
  const mensajeCargue = document.getElementById("mensajeCargue");
  const listaErrores = document.getElementById("listaErrores");

  formCargueMasivo.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(formCargueMasivo);

    // Mostrar mensaje de carga
    mensajeCargue.classList.remove("hidden");
    mensajeCargue.textContent = "⏳ Subiendo archivo, por favor espera...";
    mensajeCargue.className = "mt-4 text-sm font-medium text-blue-600";
    listaErrores.classList.add("hidden");
    listaErrores.innerHTML = "";

    try {
      const resp = await fetch("/validar-masivo", { 
        method: "POST",
        body: formData
      });

      if (!resp.ok) throw new Error("Error al subir archivo");

      const datos = await resp.json();

      if (datos.error) {
        mensajeCargue.textContent = `❌ ${datos.error}`;
        mensajeCargue.className = "mt-4 text-sm font-medium text-red-600";
        return;
      }

      // Mostrar éxito
      mensajeCargue.textContent = `✅ ${datos.mensaje || 'Cargue realizado con éxito'} | Insertados: ${datos.insertados ?? 0}`;
      mensajeCargue.className = "mt-4 text-sm font-medium text-green-600";

      // Mostrar errores si hay
      if (datos.errores && datos.errores.length > 0) {
        listaErrores.classList.remove("hidden");
        datos.errores.forEach(err => {
          const li = document.createElement("li");
          li.textContent = err;
          listaErrores.appendChild(li);
        });
      }

    } catch (err) {
      mensajeCargue.textContent = `❌ Error: ${err.message}`;
      mensajeCargue.className = "mt-4 text-sm font-medium text-red-600";
    }
  });

  const formValidador = document.getElementById("formValidador");
  const loader = document.getElementById("loader");
  const contenido = document.getElementById("resultadoContenido");

  formValidador.addEventListener("submit", async function (e) {
    e.preventDefault();

    
    document.getElementById("resultadoModal").checked = true;
    loader.classList.remove("hidden");
    contenido.classList.add("hidden");

    const formData = new FormData(formValidador);

    try {
      const resp = await fetch("/validar", {
        method: "POST",
        body: formData
      });

      if (!resp.ok) throw new Error("Error en la validación");

      const datos = await resp.json();

      // Rellenar datos
      document.getElementById("resEAN").textContent = datos.ean ?? "—";
      document.getElementById("resDescripcion").textContent = datos.descripcion ?? "—";
      document.getElementById("resCategoria").textContent = datos.categoria ?? "—";
      document.getElementById("resVidaUtil").textContent = datos.dias_vida_util ?? "—";
      document.getElementById("resBloqueo").textContent = datos.fecha_bloqueo ?? "—";
      document.getElementById("resConcepto").textContent = datos.concepto_bloqueo ?? "—";
      document.getElementById("resObservacion").textContent = datos.observacion ?? "—";

      // Mostrar contenido y ocultar loader
      loader.classList.add("hidden");
      contenido.classList.remove("hidden");

    } catch (err) {
      loader.classList.add("hidden");
      contenido.classList.remove("hidden");

      contenido.querySelectorAll("p").forEach(p => p.textContent = "—"); 
      const errorMsg = document.createElement("p");
      errorMsg.className = "text-red-500 font-medium";
      errorMsg.textContent = `Hubo un problema al validar: ${err.message}`;
      contenido.prepend(errorMsg);
    }
  });

  function convertirJuliana() {
    const input = document.getElementById('julianaInput').value;
    const resultadoDiv = document.getElementById('resultadoJuliana');
    const fechaOutput = document.getElementById('fechaConvertida');

    if (!/^\d{5}$/.test(input)) {
      fechaOutput.textContent = 'Formato inválido';
      fechaOutput.className = 'text-base font-medium text-red-500';
    } else {
      const año = input.substring(0, 2);
      const dia = input.substring(2);
      const añoFull = (año < 50) ? '20' + año : '19' + año;
      const fecha = new Date(añoFull, 0);
      fecha.setDate(parseInt(dia));
      const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
      fechaOutput.textContent = fecha.toLocaleDateString('es-ES', options);
      fechaOutput.className = 'text-base font-medium text-gray-800';
    }

    resultadoDiv.classList.remove('hidden');
  }
</script> 