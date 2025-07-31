<div class="container mx-auto py-10 px-6 max-w-7xl">
  <div class="text-center mb-10">
    <h1 class="text-4xl font-bold text-black mb-2">Validador de Productos</h1>
    <p class="text-sm text-gray-600">Herramientas para validar fechas, productos y descargar reportes</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white border border-gray-200 rounded-xl shadow p-6">
      <div class="flex items-center gap-3 mb-5">
        <span class="bg-[#4a69bd] text-white p-2 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
        </span>
        <h2 class="text-xl font-semibold text-gray-800">Validar Producto</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-sm text-gray-700 mb-1">Código EAN</label>
          <input type="text" class="w-full p-2 text-sm border rounded-md focus:ring-2 focus:ring-[#4a69bd]" placeholder="123456789012">
        </div>
        <div>
          <label class="block text-sm text-gray-700 mb-1">Fecha Vencimiento</label>
          <input type="date" class="w-full p-2 text-sm border rounded-md focus:ring-2 focus:ring-[#4a69bd]">
        </div>
        <div class="flex items-end">
          <button class="w-full bg-[#4a69bd] text-white py-2 text-sm rounded-md hover:bg-[#3b57a2] transition font-medium">
            Calcular
          </button>
        </div>
      </div>
  
      <!-- Resultados de solo salida -->
      <div class="grid grid-cols-3 gap-4 text-center mt-6">
        <div class="bg-gray-100 py-3 px-2 rounded-md shadow-sm">
          <p class="text-xs text-gray-500">Fecha Bloqueo</p>
          <div class="text-base font-semibold text-gray-800" id="fechaBloqueo">--/--/----</div>
        </div>
        <div class="bg-gray-100 py-3 px-2 rounded-md shadow-sm">
          <p class="text-xs text-gray-500">Días Vida Útil</p>
          <div class="text-base font-semibold text-gray-800" id="diasVidaUtil">-- días</div>
        </div>
        <div class="bg-gray-100 py-3 px-2 rounded-md shadow-sm">
          <p class="text-xs text-gray-500">Estado</p>
          <div class="text-base font-semibold text-green-500" id="estadoProducto">Vigente</div>
        </div>
      </div>
    </div>

    <!-- Conversor Juliano -->
    <div class="bg-white border border-gray-200 rounded-xl shadow p-6">
      <div class="flex items-center gap-3 mb-5">
        <span class="bg-[#4a69bd] text-white p-2 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
          </svg>
        </span>
        <h2 class="text-xl font-semibold text-gray-800">Conversor Juliano</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
        <div class="md:col-span-3">
          <label class="block text-sm text-gray-700 mb-1">Fecha Juliana</label>
          <input id="julianaInput" type="text" class="w-full p-2 text-sm border rounded-md focus:ring-2 focus:ring-[#4a69bd]" placeholder="Ej: 24198">
        </div>
        <div class="flex items-end">
          <button onclick="convertirJuliana()" class="w-full bg-[#4a69bd] text-white py-2 text-sm rounded-md hover:bg-[#3b57a2] transition font-medium">
            Convertir
          </button>
        </div>
      </div>

      <div id="resultadoJuliana" class="mt-4 bg-gray-100 p-3 rounded-md hidden">
        <p class="text-xs text-gray-500">Resultado:</p>
        <p class="text-base font-medium" id="fechaConvertida">DD/MM/AAAA</p>
      </div>
    </div>
  </div>

 <h1 class="text-3xl font-semibold text-gray-800 text-center mt-10">Descarga de Bloqueos </h1>
  <!-- Descargas -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
    <div class="bg-white border border-gray-200 rounded-xl shadow p-5">
      <h3 class="text-sm font-semibold text-gray-800 mb-3">Bloqueos de hoy</h3>
      <div class="space-y-2">
        <a href="https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=xlsx&gid=416131206" 
           download="bloqueos-hoy.xlsx"
           class="block bg-[#44bd32] text-white py-2 text-sm rounded-md hover:bg-[#3aa62a] transition text-center font-medium">
          Descargar Excel (.xlsx)
        </a>
        <a href="https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=pdf&gid=416131206" 
           download="bloqueos-hoy.pdf"
           class="block bg-[#e84118] text-white py-2 text-sm rounded-md hover:bg-[#d13813] transition text-center font-medium">
          Descargar PDF (.pdf)
        </a>
      </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow p-5">
      <h3 class="text-sm font-semibold text-gray-800 mb-3">Próximos 7 días</h3>
      <div class="space-y-2">
        <a href="https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=xlsx&gid=115512917" 
           download="proximos-7dias.xlsx"
           class="block bg-[#44bd32] text-white py-2 text-sm rounded-md hover:bg-[#3aa62a] transition text-center font-medium">
          Descargar Excel (.xlsx)
        </a>
        <a href="https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=pdf&gid=115512917" 
           download="proximos-7dias.pdf"
           class="block bg-[#e84118] text-white py-2 text-sm rounded-md hover:bg-[#d13813] transition text-center font-medium">
          Descargar PDF (.pdf)
        </a>
      </div>
    </div>
  </div>
</div>

<script>
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
