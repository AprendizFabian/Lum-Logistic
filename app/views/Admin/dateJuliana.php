<div class="container mx-auto py-10 px-4">
  <h2 class="text-center text-3xl font-bold mb-10">Validador de Productos</h2>

  <div class="bg-white border border-[#dcdde1] rounded-xl p-6 mb-8 shadow-md">
    <h5 class="text-xl font-semibold mb-4">Validar Producto</h5>
    <form>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div>
          <label for="ean" class="block text-sm font-medium text-gray-700 mb-1">Código EAN</label>
          <input type="text"
            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a69bd]"
            id="ean" placeholder="Ingrese el código EAN">
        </div>
        <div>
          <label for="fecha_vencimiento" class="block text-sm font-medium text-gray-700 mb-1">Fecha de
            Vencimiento</label>
          <input type="date"
            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a69bd]"
            id="fecha_vencimiento">
        </div>
        <div class="flex items-end">
          <button type="button"
            class="w-full bg-[#4a69bd] text-white py-2 px-4 rounded-lg hover:bg-[#3b57a2] transition duration-300 ease-in-out shadow-md"
            onclick="document.getElementById('modalResultado').classList.remove('hidden'); document.getElementById('modalResultado').classList.add('flex');">Calcular</button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-[#dcdde1] rounded-xl p-4 shadow-sm">
          <strong class="block text-sm font-semibold">Fecha de Bloqueo:</strong>
          <p class="text-gray-500 text-sm">DD/MM/AAAA</p>
        </div>
        <div class="bg-white border border-[#dcdde1] rounded-xl p-4 shadow-sm">
          <strong class="block text-sm font-semibold">Días de Vida Útil:</strong>
          <p class="text-gray-500 text-sm">-- días</p>
        </div>
        <div class="bg-white border border-[#dcdde1] rounded-xl p-4 shadow-sm">
          <strong class="block text-sm font-semibold">Estado del Producto:</strong>
          <p class="text-green-500 text-sm font-medium">Vigente</p>
        </div>
      </div>
    </form>
  </div>

  <div class="bg-white border border-[#dcdde1] rounded-xl p-6 mb-8 shadow-md">
    <h5 class="text-xl font-semibold mb-4">Conversor de Fecha Juliana</h5>
    <form method="POST" action="/convertir-fecha">
      <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center">
        <div class="col-span-1 md:col-span-3">
          <input type="text"
            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a69bd]"
            name="fecha_juliana" placeholder="Ingrese fecha Juliana (Ej: 24198)" required>
        </div>
        <div class="col-span-1 md:col-span-1">
          <button
            class="w-full bg-[#4a69bd] text-white py-2 px-4 rounded-lg hover:bg-[#3b57a2] transition duration-300 ease-in-out shadow-md"
            type="submit">Convertir</button>
        </div>
        <div class="col-span-1 md:col-span-2">
          <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["fecha_juliana"])): ?>
            <?php
            function convertirJuliana($fechaJuliana)
            {
              if (!preg_match('/^\d{5}$/', $fechaJuliana))
                return 'Formato inválido';
              $año = substr($fechaJuliana, 0, 2);
              $diaDelAño = substr($fechaJuliana, 2);
              $añoFull = ($año < 50) ? '20' . $año : '19' . $año;
              $fecha = DateTime::createFromFormat('Y z', "$añoFull " . ($diaDelAño - 1));
              return $fecha ? $fecha->format('d/m/Y') : 'Formato inválido';
            }
            $resultado = convertirJuliana($_POST["fecha_juliana"]);
            ?>
            <div class="bg-white border border-[#dcdde1] rounded-xl p-3 shadow-sm">
              <strong class="block text-sm font-semibold">Resultado:</strong>
              <p class="text-sm <?= ($resultado === 'Formato inválido') ? 'text-[#e84118]' : 'text-gray-500' ?>">
                <?= $resultado ?>
              </p>
            </div>
          <?php else: ?>
            <div class="bg-white border border-[#dcdde1] rounded-xl p-3 shadow-sm">
              <strong class="block text-sm font-semibold">Resultado:</strong>
              <p class="text-gray-500 text-sm">DD/MM/AAAA</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </form>
  </div>

  <div class="text-center mb-8">
    <h2 class="text-2xl font-bold mb-4">Descargar bloqueos de hoy</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-lg mx-auto">
      <div>
        <button
          onclick="window.location.href='https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=xlsx&gid=416131206'"
          class="w-full bg-[#44bd32] text-white py-3 px-6 rounded-lg hover:opacity-90 transition duration-300 ease-in-out shadow-md">
          📥 Bloqueos de hoy (.xls)
        </button>
      </div>
      <div>
        <button
          onclick="window.location.href='https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=pdf&gid=416131206'"
          class="w-full bg-[#e84118] text-white py-3 px-6 rounded-lg hover:opacity-90 transition duration-300 ease-in-out shadow-md">
          📥 Bloqueos de hoy (.pdf)
        </button>
      </div>
    </div>
  </div>

  <div class="text-center mb-8">
    <h2 class="text-2xl font-bold mb-4">Descargar próximos de la semana</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-lg mx-auto">
      <div>
        <button
          onclick="window.location.href='https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=xlsx&gid=115512917'"
          class="w-full bg-[#44bd32] text-white py-3 px-6 rounded-lg hover:opacity-90 transition duration-300 ease-in-out shadow-md">
          📥 Descargar bloqueos de próxima semana (.xls)
        </button>
      </div>
      <div>
        <button
          onclick="window.location.href='https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=pdf&gid=115512917'"
          class="w-full bg-[#e84118] text-white py-3 px-6 rounded-lg hover:opacity-90 transition duration-300 ease-in-out shadow-md">
          📥 Descargar bloqueos de la próxima semana (.pdf)
        </button>
      </div>
    </div>
  </div>

  <div id="modalResultado" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
      <div class="flex justify-between items-center border-b pb-3 mb-4">
        <h5 class="text-xl font-bold">Resultado del Producto</h5>
        <button type="button" class="text-gray-400 hover:text-gray-600"
          onclick="document.getElementById('modalResultado').classList.add('hidden'); document.getElementById('modalResultado').classList.remove('flex');">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      <div class="space-y-2">
        <p><strong class="font-semibold">EAN:</strong> 0123456789123</p>
        <p><strong class="font-semibold">Fecha de Vencimiento:</strong> 25/12/2025</p>
        <p><strong class="font-semibold">Fecha de Bloqueo:</strong> 10/12/2025</p>
        <p><strong class="font-semibold">Días de Vida Útil:</strong> 90</p>
        <p><strong class="font-semibold">Estado:</strong> <span class="text-green-500">Vigente</span></p>
      </div>
      <div class="flex justify-end mt-6">
        <button type="button"
          class="bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition duration-300 ease-in-out"
          onclick="document.getElementById('modalResultado').classList.add('hidden'); document.getElementById('modalResultado').classList.remove('flex');">Cerrar</button>
      </div>
    </div>
  </div>
</div>