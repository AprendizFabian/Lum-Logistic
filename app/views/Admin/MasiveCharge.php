<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between border-b border-gray-300 pb-3 mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
      <i class="fas fa-file-upload text-yellow-400"></i>
      Cargue Masivo
    </h1>
    <a href="/fecha"
      class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
      <i class="fas fa-arrow-left"></i> Volver
    </a>
  </div>
  <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-[#FEE000]">
    <h2 class="text-2xl font-semibold text-[#404141] mb-4 flex items-center gap-2">
      <i class="fas fa-upload text-[#FEE000]"></i> Subir Archivo
      <span class="text-sm text-gray-500">(solo archivos en formato .csv)</span>
    </h2>
    <form id="formCargueMasivo" enctype="multipart/form-data">
      <?php if ($_SESSION['auth']['type'] === 'user'): ?>
        <div class="mb-4">
          <label class="block text-gray-700 font-semibold mb-4">Selecciona la tienda en la que estas antes de subir el
            archivo</label>
          <select name="id_store" class="select select-bordered w-full text-[#404141]" required>
            <option value="">-- Selecciona una tienda --</option>
            <?php foreach ($tiendas as $t): ?>
              <option value="<?= $t['id'] ?>">
                <?= htmlspecialchars($t['username']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php else: ?>
        <input type="hidden" name="id_store" value="<?= $_SESSION['auth']['id'] ?>">
      <?php endif; ?>
      <div id="dropArea"
        class="flex flex-col items-center justify-center border-2 border-dashed border-gray-400 rounded-xl p-8 text-center cursor-pointer transition hover:border-[#FEE000] hover:bg-yellow-50 mb-4">
        <i class="fas fa-cloud-upload-alt text-6xl text-gray-500 mb-3"></i>
        <p class="text-gray-600 mb-1">Arrastra tu archivo aquí o haz clic para seleccionarlo</p>
        <p id="fileName" class="text-sm text-gray-500 italic"></p>
        <input type="file" id="archivoMasivo" name="archivo" accept=".csv" class="hidden">
      </div>

      <button type="submit"
        class="w-full bg-[#404141] hover:bg-black text-white font-semibold py-2 rounded-lg transition">
        <i class="fas fa-check-circle mr-2"></i> Subir archivo
      </button>
    </form>
  </div>
</div>
<script>
  const formCargueMasivo = document.getElementById("formCargueMasivo");
  const archivoInput = document.getElementById('archivoMasivo');
  const fileNameDisplay = document.getElementById('fileName');
  const dropArea = document.getElementById('dropArea');
  const urlPlantilla = '/templates/plantilla.xlsx';
  archivoInput.addEventListener('click', async function handler(e) {
    e.preventDefault();
    const resultado = await Swal.fire({
      title: 'Recuerda descargar la plantilla',
      text: '¿Deseas descargarla antes de subir el archivo?',
      icon: 'info',
      showCancelButton: true,
      confirmButtonText: 'Sí, descargar',
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
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      return;
    }

    if (resultado.dismiss === Swal.DismissReason.cancel) {
      archivoInput.removeEventListener('click', handler);
      archivoInput.click();
      setTimeout(() => archivoInput.addEventListener('click', handler), 50);
    }
  });

  archivoInput.addEventListener('change', () => {
    if (archivoInput.files.length > 0) {
      const file = archivoInput.files[0];
      const tiposPermitidos = [
        "application/vnd.ms-excel",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "text/csv"
      ];

      if (!tiposPermitidos.includes(file.type)) {
        Swal.fire({
          icon: 'error',
          title: 'Formato no permitido',
          text: 'Solo se aceptan archivos .csv',
          confirmButtonColor: '#d33'
        });
        archivoInput.value = "";
        fileNameDisplay.textContent = "";
        return;
      }

      fileNameDisplay.textContent = file.name;
    }
  });

  dropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropArea.classList.add('border-[#FEE000]', 'bg-yellow-50');
  });

  dropArea.addEventListener('dragleave', () => {
    dropArea.classList.remove('border-[#FEE000]', 'bg-yellow-50');
  });

  dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dropArea.classList.remove('border-[#FEE000]', 'bg-yellow-50');
    if (e.dataTransfer.files.length > 0) {
      const file = e.dataTransfer.files[0];
      const tiposPermitidos = [
        "application/vnd.ms-excel",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "text/csv"
      ];
      if (!tiposPermitidos.includes(file.type)) {
        Swal.fire({
          icon: 'error',
          title: 'Formato no permitido',
          text: 'Solo se aceptan archivos .csv ',
          confirmButtonColor: '#d33'
        });
        return;
      }
      archivoInput.files = e.dataTransfer.files;
      fileNameDisplay.textContent = file.name;
    }
  });
  formCargueMasivo.addEventListener("submit", async e => {
    e.preventDefault();
    if (!archivoInput.files.length) {
      archivoInput.click();
      return;
    }
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

      if (datos.error) {
        Swal.fire({
          icon: 'error',
          title: 'Error en el cargue',
          text: datos.error,
          confirmButtonColor: '#d33'
        });
      } else {
        const icono = datos.errores?.length ? 'error' : 'success';
        const titulo = icono === 'success' ? 'Cargue completado' : 'Cargue Fallido';
        const erroresHTML = datos.errores?.length
          ? `<p style="margin-bottom: 8px; font-weight: bold; color: #d9534f;">⚠️ Errores encontrados:</p>
           <div style="max-height: 250px; overflow-y: auto; text-align: left; font-size: 14px; border: 1px solid #ccc; padding: 0; border-radius: 5px; background-color: #fff8f8;">
             ${datos.errores.map((e, i) => `
               <div style="padding: 8px 10px; border-bottom: ${i < datos.errores.length - 1 ? '1px solid #eee' : 'none'}; display: flex; align-items: flex-start;">
                 <span style="color: #d9534f; margin-right: 6px;">❌</span>
                 <span>${e}</span>
               </div>
             `).join('')}
           </div>`
          : '';
        const mensajeHTML = `<p>${datos.mensaje || 'Cargue realizado con éxito'} | Insertados: ${datos.insertados ?? 0}</p>${erroresHTML}`;
        Swal.fire({
          icon: icono,
          title: titulo,
          html: mensajeHTML,
          customClass: {
            confirmButton: 'bg-[#FEE000] text-[#404141] font-bold px-4 py-2 rounded hover:bg-yellow-400'
          },
          buttonsStyling: false
        });
      }
      archivoInput.value = "";
      fileNameDisplay.textContent = "";
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Error inesperado',
        text: err.message,
        confirmButtonColor: '#d33'
      });
      archivoInput.value = "";
      fileNameDisplay.textContent = "";
    }
  });
</script>