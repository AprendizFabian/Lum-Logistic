<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-6 py-10 max-w-7xl">
  <div class="flex items-center justify-between border-b border-gray-300 pb-3 mb-8">
    <h1 class="text-4xl font-bold text-[#404141] flex items-center gap-3">
      <i class="fas fa-search text-[#FEE000]"></i> Validador de Productos
    </h1>
    <a href="/fecha"
      class="bg-[#404141] hover:bg-black text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
      <i class="fas fa-arrow-left"></i> Volver
    </a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
  
    <form id="formValidador" class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-[#FEE000]">
      <h2 class="text-2xl font-semibold text-[#404141] mb-4 flex items-center gap-2">
        <i class="fas fa-barcode text-[#FEE000]"></i> Ingresar Datos
      </h2>
      <?php if ($_SESSION['user']['type'] === 'user'): ?>
  <div class="mb-4">
    <label class="block text-gray-700 font-semibold mb-1">
      Selecciona la tienda antes de validar el producto
    </label>
    <select name="id_store" required
      class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FEE000] text-[#404141]">
      <option value="">-- Selecciona una tienda --</option>
      <?php foreach ($tiendas as $t): ?>
        <option value="<?= $t['id_store'] ?>">
          <?= htmlspecialchars($t['store_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
<?php endif; ?>

      <div class="mb-4">
        <label for="ean" class="block text-[#404141] font-medium mb-1">Código EAN:</label>
        <input type="text" id="ean" name="ean" required
          class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FEE000]">
      </div>

      <div class="mb-6">
        <label for="fecha_vencimiento" class="block text-[#404141] font-medium mb-1">Fecha de Vencimiento:</label>
        <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" required
          class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FEE000]">
      </div>

      <button type="submit"
        class="w-full bg-[#404141] hover:bg-black text-white font-semibold py-2 rounded-lg transition">
        <i class="fas fa-check-circle mr-2"></i> Validar Producto
      </button>
    </form>

    <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-[#FEE000]">
      <h2 class="text-2xl font-semibold text-[#404141] mb-4 flex items-center gap-2">
        <i class="fas fa-calendar-alt text-[#FEE000]"></i> Conversor Fecha Juliana
      </h2>

      <div class="mb-4">
        <label for="julianaInput" class="block text-[#404141] font-medium mb-1">Fecha Juliana (5 dígitos):</label>
        <input type="text" id="julianaInput" placeholder="Ej: 24123"
          class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FEE000]">
      </div>

      <button type="button" onclick="convertirJuliana()"
        class="w-full bg-[#404141] hover:bg-black text-white font-semibold py-2 rounded-lg transition">
        <i class="fas fa-exchange-alt mr-2"></i> Convertir
      </button>

      <div id="resultadoJuliana" class="mt-4 hidden">
        <p class="text-sm text-gray-600">Fecha convertida: <span id="fechaConvertida" class="font-medium"></span></p>
      </div>
    </div>
  </div>
  <input type="checkbox" id="resultadoModal" class="modal-toggle" />
  <div class="modal">
    <div class="modal-box relative max-w-2xl rounded-2xl shadow-xl p-0 overflow-hidden">
      <div class="bg-[#404141] p-4 flex items-center justify-between">
        <h3 class="text-white text-lg font-semibold flex items-center gap-2">
          <i class="fas fa-clipboard-check"></i> Resultado de Validación
        </h3>
        <label for="resultadoModal" class="btn btn-sm btn-circle bg-green text-gray-700 hover:bg-gray-200">✕</label>
      </div>

      <div id="loader" class="flex flex-col items-center justify-center py-10">
        <span class="loading loading-spinner loading-lg text-[#FEE000]"></span>
        <p class="mt-4 text-gray-500 animate-pulse">Procesando, por favor espera...</p>
      </div>

      <div id="resultadoContenido" class="hidden p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="bg-gray-50 p-3 rounded-lg border">
            <p class="text-xs text-gray-500">EAN</p>
            <p id="resEAN" class="font-medium text-[#404141]">—</p>
          </div>
          <div class="bg-gray-50 p-3 rounded-lg border sm:col-span-2">
            <p class="text-xs text-gray-500">Descripción</p>
            <p id="resDescripcion" class="font-medium text-[#404141]">—</p>
          </div>
          <div class="bg-gray-50 p-3 rounded-lg border">
            <p class="text-xs text-gray-500">Categoría</p>
            <p id="resCategoria" class="font-medium text-[#404141]">—</p>
          </div>
          <div class="bg-gray-50 p-3 rounded-lg border">
            <p class="text-xs text-gray-500">Días de vida útil</p>
            <p id="resVidaUtil" class="font-medium text-[#404141]">—</p>
          </div>
          <div class="bg-gray-50 p-3 rounded-lg border">
            <p class="text-xs text-gray-500">Fecha de bloqueo</p>
            <p id="resBloqueo" class="font-medium text-[#404141]">—</p>
          </div>
          <div class="bg-gray-50 p-3 rounded-lg border">
            <p class="text-xs text-gray-500">Concepto de bloqueo</p>
            <p id="resConcepto" class="font-medium text-[#404141]">—</p>
          </div>
          <div class="bg-gray-50 p-3 rounded-lg border sm:col-span-2">
            <p class="text-xs text-gray-500">Observación</p>
            <p id="resObservacion" class="font-medium text-[#404141]">—</p>
          </div>
          <div class="bg-gray-50 p-3 rounded-lg border">
            <p class="text-xs text-gray-500">Días para vencimiento</p>
            <p id="resDiasRestantes" class="font-medium">—</p>
          </div>
           <div class="bg-gray-50 p-3 rounded-lg border">
            <p class="text-xs text-gray-500">sincid de este producto</p>
            <p id="resSincid" class="font-medium">—</p>
          </div>
        </div>
      </div>
    </div>
  </div>
<script>
  const formValidador = document.getElementById("formValidador");
  const loader = document.getElementById("loader");
  const contenido = document.getElementById("resultadoContenido");
  const modalResultado = document.getElementById("resultadoModal");

  formValidador.addEventListener("submit", async function (e) {
    e.preventDefault();
    loader.classList.remove("hidden");
    contenido.classList.add("hidden");
    modalResultado.checked = true;
    const formData = new FormData(formValidador);
    try {
      const resp = await fetch("/validar", { method: "POST", body: formData });
      const datos = await resp.json();

      if (datos.error) {
        loader.classList.add("hidden");
        Swal.fire({ icon: "error", title: "Error", text: datos.error });
        modalResultado.checked = false;
        return;
      }

      document.getElementById("resEAN").textContent = datos.ean ?? "—";
      document.getElementById("resDescripcion").textContent = datos.descripcion ?? "—";
      document.getElementById("resCategoria").textContent = datos.categoria ?? "—";
      document.getElementById("resVidaUtil").textContent = datos.dias_vida_util ?? "—";
      document.getElementById("resBloqueo").textContent = datos.fecha_bloqueo ?? "—";
      document.getElementById("resConcepto").textContent = datos.concepto_bloqueo ?? "—";
      document.getElementById("resObservacion").textContent = datos.observacion ?? "—";
      document.getElementById("resSincid").textContent = datos.sync_id ?? "—";

      const hoy = new Date();
      const fechaVenc = new Date(datos.fecha_vencimiento);
      const diffTime = fechaVenc - hoy;
      let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      const diasVidaUtil = parseInt(datos.dias_vida_util) || 0;
      diffDays = diffDays - diasVidaUtil;
      const diasRestantesEl = document.getElementById("resDiasRestantes");
      const conceptoEl = document.getElementById("resConcepto");
      const observacionEl = document.getElementById("resObservacion");
      const pintar = (clase) => {
        diasRestantesEl.className = `font-medium ${clase}`;
        conceptoEl.className = `font-medium ${clase}`;
        observacionEl.className = `font-medium ${clase}`;
      };

      if (isNaN(diffDays)) {
        diasRestantesEl.textContent = "—";
        pintar("text-gray-400");
      } else {
        diasRestantesEl.textContent = diffDays + " días";

        if (diffDays < 1) {
          pintar("text-red-600");
        }
        else if (
          datos.fecha_bloqueo &&
          new Date(datos.fecha_bloqueo).toDateString() === hoy.toDateString()
        ) {
          pintar("text-orange-600");
        }

        else if (diffDays <= diasVidaUtil || diffDays <= 3) {
          pintar("text-yellow-600");
        }

        else {
          pintar("text-green-600");
        }
      }

      loader.classList.add("hidden");
      contenido.classList.remove("hidden");
    } catch (err) {
      loader.classList.add("hidden");
      modalResultado.checked = false;
      Swal.fire({ icon: "error", title: "Error de conexión", text: err.message });
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
      fechaOutput.textContent = fecha.toLocaleDateString('es-ES');
      fechaOutput.className = 'text-base font-medium text-gray-800';
    }
    resultadoDiv.classList.remove('hidden');
  }
</script>
