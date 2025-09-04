<section class="px-6 py-10 max-w-screen-md mx-auto text-[#404141]">
  <h1 class="text-2xl font-bold flex items-center gap-2 mb-6">
    <i class="fa-solid fa-boxes-stacked text-[#FEDF00]"></i>
    Subir Stock
  </h1>

  <!-- Formulario para subir archivo -->
  <form method="POST" action="/stock/subir" enctype="multipart/form-data" 
        class="bg-white shadow-md rounded-xl p-6 space-y-4 border border-gray-200">
    
    <!-- Input de archivo -->
    <div>
      <label class="block font-semibold mb-2">Archivo CSV</label>
      <input type="file" name="archivo_stock" accept=".csv"
             class="file-input file-input-bordered w-full text-[#404141]" required>
    </div>

    <!-- Ayuda -->
    <p class="text-sm text-gray-500">
      Formato esperado en el CSV: <br>
      <code>id_store, sync_id, stock, chipperprice</code>
    </p>

    <!-- Botón -->
    <button type="submit"
      class="px-6 py-2 bg-[#404141] text-[#FEDF00] font-semibold rounded-lg hover:bg-[#2f2f2f] transition">
      Subir archivo
    </button>
  </form>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($errores)): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Errores en la carga',
      html: `<?= implode("<br>", $errores) ?>`,
      confirmButtonColor: '#404141'
    });
  </script>
<?php elseif (isset($procesados) && $procesados > 0): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Carga exitosa',
      text: 'Se procesaron <?= $procesados ?> registros correctamente.',
      confirmButtonColor: '#404141'
    });
  </script>
<?php endif; ?>
