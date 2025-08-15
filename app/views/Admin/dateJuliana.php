<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-6 py-10 max-w-7xl">
  <h1 class="text-4xl font-bold text-center text-[#404141] mb-12">Validador de Productos</h1>

  <!-- Sección de formulario y conversor -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Formulario -->
    <form id="formValidador" class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-[#FEE000]">
      <h2 class="text-2xl font-semibold text-[#404141] mb-4 flex items-center gap-2">
        <i class="fas fa-barcode text-[#FEE000]"></i> Ingresar Datos
      </h2>

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

    <!-- Conversor Juliano -->
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

  <!-- Nueva sección de descargas -->
  <div
    class="mt-12 bg-[#FEE000] rounded-2xl shadow-lg p-6 flex flex-col md:flex-row items-center justify-between gap-4">
    <h3 class="text-xl font-bold text-[#404141] flex items-center gap-2">
      <i class="fas fa-download"></i> Descargar Bloqueos HOY
    </h3>
    <div class="flex flex-wrap gap-4">
      <a href="https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=pdf&gid=416131206"
        class="text-sm bg-[#404141] hover:bg-black text-white font-semibold px-6 py-2 rounded-xl flex items-center gap-2">
        <i class="fas fa-book"></i> Descargar Bloqueos de HOY PDF
      </a>
      <a href="/descargar/vu"
        class="text-sm  bg-[#404141] hover:bg-black text-white font-semibold px-6 py-2 rounded-xl flex items-center gap-2">
        <i class="fas fa-file-alt"></i> Descargar Bloqueos de HOY EXCEL
      </a>
    </div>
  </div>

  <!-- Bloque de cargue masivo -->
  <div class="mt-12 bg-white rounded-2xl shadow-xl p-6 border-t-4 border-[#FEE000]">
    <h2 class="text-2xl font-semibold text-[#404141] mb-4 flex items-center gap-2">
      <i class="fas fa-upload text-[#FEE000]"></i> Cargue Masivo de Productos
    </h2>
    <form id="formCargueMasivo" enctype="multipart/form-data">
      <div class="mb-4">
        <label for="archivoMasivo" class="block text-[#404141] font-medium mb-1"></label>
        <input type="file" id="archivoMasivo" name="archivo" accept=".csv, .xlsx" required
          class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FEE000]">
      </div>
      <button type="submit"
        class="w-full bg-[#404141] hover:bg-black text-white font-semibold py-2 rounded-lg transition">
        <i class="fas fa-cloud-upload-alt mr-2"></i> Subir archivo
      </button>
    </form>
    <div id="mensajeCargue" class="mt-4 text-sm font-medium hidden"></div>
    <ul id="listaErrores" class="mt-2 text-red-500 text-sm list-disc pl-5 hidden"></ul>
  </div>
</div>

<!-- Modal de Validación (con paleta adaptada) -->
<input type="checkbox" id="resultadoModal" class="modal-toggle" />
<div class="modal">
  <div class="modal-box relative max-w-2xl rounded-2xl shadow-xl p-0 overflow-hidden">
    <!-- Header -->
    <div class="bg-[#404141] p-4 flex items-center justify-between">
      <h3 class="text-white text-lg font-semibold flex items-center gap-2">
        <i class="fas fa-clipboard-check"></i> Resultado de Validación
      </h3>
      <label for="resultadoModal" class="btn btn-sm btn-circle bg-white text-gray-700 hover:bg-gray-200">✕</label>
    </div>

    <!-- Loader -->
    <div id="loader" class="flex flex-col items-center justify-center py-10">
      <span class="loading loading-spinner loading-lg text-[#FEE000]"></span>
      <p class="mt-4 text-gray-500 animate-pulse">Procesando, por favor espera...</p>
    </div>

    <!-- Contenido -->
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
      </div>
    </div>
  </div>
</div>

<script>

  const formCargueMasivo = document.getElementById("formCargueMasivo");

  formCargueMasivo.addEventListener("submit", async e => {
    e.preventDefault();

    // Modal de carga
    Swal.fire({
      title: 'Subiendo archivo...',
      text: 'Por favor espera',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    try {
      const resp = await fetch("/validar-masivo", {
        method: "POST",
        body: new FormData(formCargueMasivo)
      });

      if (!resp.ok) throw new Error("Error al subir archivo");
      const datos = await resp.json();

      // Error crítico
      if (datos.error) {
        return Swal.fire({
          icon: 'error',
          title: 'Error en el cargue',
          text: datos.error,
          confirmButtonColor: '#d33'
        });
      }

      const icono = datos.errores?.length ? 'error' : 'success';
      const titulo = icono === 'success' ? 'Cargue completado' : 'Cargue Fallido';
      const erroresHTML = datos.errores?.length
        ? `
    <p style="margin-bottom: 8px; font-weight: bold; color: #d9534f;">
      ⚠️ Errores encontrados:
    </p>
    <div style="
      max-height: 250px;
      overflow-y: auto;
      text-align: left;
      font-size: 14px;
      border: 1px solid #ccc;
      padding: 0;
      border-radius: 5px;
      background-color: #fff8f8;
    ">
      ${datos.errores.map((e, i) => `
        <div style="
          padding: 8px 10px;
          border-bottom: ${i < datos.errores.length - 1 ? '1px solid #eee' : 'none'};
          display: flex;
          align-items: flex-start;
        ">
          <span style="color: #d9534f; margin-right: 6px;">❌</span>
          <span>${e}</span>
        </div>
      `).join('')}
    </div>
  `
        : '';

      const mensajeHTML = `<p>${datos.mensaje || 'Cargue realizado con éxito'} | Insertados: ${datos.insertados ?? 0}</p>${erroresHTML}`;

      Swal.fire({
        icon: icono,
        title: titulo,
        html: mensajeHTML,
        customClass: {
          confirmButton: 'bg-[#FEE000] text-[#404141] font-bold px-4 py-2 rounded hover:bg-yellow-400'
        },
        buttonsStyling: false,
      });

    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Error inesperado',
        text: err.message,
        confirmButtonColor: 'rgba(255, 0, 0, 1)'
      });
    }
  });

  const archivoInput = document.getElementById('archivoMasivo');
  const urlPlantilla = '/plantillas/plantilla.xlsx';
  archivoInput.addEventListener('click', async function handler(e) {
    e.preventDefault();

    const resultado = await Swal.fire({
      title: 'Recuerda descargar la plantilla',
      text: '¿Deseas descargarla antes de subir el archivo?',
      icon: 'info',
      showCancelButton: true,
      confirmButtonText: 'Sí, descargar y continuar',
      cancelButtonText: 'No, solo subir archivo',
      customClass: {
        confirmButton: 'bg-[#FEE000] text-[#404141] font-bold px-4 py-2 rounded hover:bg-yellow-400',
        cancelButton: 'bg-[#404141] text-white font-bold px-4 py-2 rounded hover:bg-gray-800',
        actions: 'flex gap-3 justify-center'
      },
      buttonsStyling: false,
      allowOutsideClick: false
    });



    if (resultado.isConfirmed) {
      const link = document.createElement('a');
      link.href = urlPlantilla;
      link.download = 'plantilla.xlsx';
      link.click();
      document.body.removeChild(link);
    }
    archivoInput.removeEventListener('click', handler);
    archivoInput.click();
    setTimeout(() => {
      archivoInput.addEventListener('click', handler);
    }, 50);
  });
const formValidador = document.getElementById("formValidador");
const loader = document.getElementById("loader");
const contenido = document.getElementById("resultadoContenido");
const modalResultado = document.getElementById("resultadoModal");

formValidador.addEventListener("submit", async function (e) {
  e.preventDefault();

  // Mostrar loader desde el inicio
  loader.classList.remove("hidden");
  contenido.classList.add("hidden");

  // Asegurar que el modal esté abierto mientras carga
  modalResultado.checked = true;

  const formData = new FormData(formValidador);

  try {
    const resp = await fetch("/validar", {
      method: "POST",
      body: formData
    });

    const datos = await resp.json();

    if (datos.error) {
      loader.classList.add("hidden");
      Swal.fire({
        icon: "error",
        title: "Error",
        text: datos.error,
      });
      modalResultado.checked = false; // Cerrar modal si hay error
      return;
    }

    // Llenar datos
    document.getElementById("resEAN").textContent = datos.ean ?? "—";
    document.getElementById("resDescripcion").textContent = datos.descripcion ?? "—";
    document.getElementById("resCategoria").textContent = datos.categoria ?? "—";
    document.getElementById("resVidaUtil").textContent = datos.dias_vida_util ?? "—";
    document.getElementById("resBloqueo").textContent = datos.fecha_bloqueo ?? "—";
    document.getElementById("resConcepto").textContent = datos.concepto_bloqueo ?? "—";
    document.getElementById("resObservacion").textContent = datos.observacion ?? "—";

    loader.classList.add("hidden");
    contenido.classList.remove("hidden");

  } catch (err) {
    loader.classList.add("hidden");
    modalResultado.checked = false;
    Swal.fire({
      icon: "error",
      title: "Error de conexión",
      text: err.message,
    });
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