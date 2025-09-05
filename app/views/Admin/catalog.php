<body class="bg-[#f9f9f9] text-[#404141] font-sans">
  <div class="flex min-h-screen">
    <main class="flex-1 flex flex-col w-full">
      <section class="flex-1 px-6 py-10 max-w-screen-xl mx-auto">
        <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
          <h1 class="text-4xl font-bold flex items-center gap-2">
            <i class="fa-solid fa-box-open text-[#FEDF00]"></i> Catálogo
          </h1>

          <form method="GET"
            class="flex items-center gap-3 w-full md:w-1/2 bg-white p-3 rounded-2xl shadow-lg border border-[#e5e5e5]">
            <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
              placeholder="Buscar por EAN o nombre..."
              class="flex-1 px-4 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FEDF00] text-[#404141] bg-[#f9f9f9] border border-[#ccc] transition" />
            <button type="submit"
              class="px-5 py-2 bg-[#404141] text-white font-medium rounded-xl hover:bg-[#2f2f2f] transition duration-200 shadow flex items-center gap-2">
              <i class="fa-solid fa-magnifying-glass"></i> Buscar
            </button>
          </form>
        </div>

        <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          <?php if (empty($productos)): ?>
            <div class="col-span-full text-center mt-10 p-6 bg-red-100 text-red-600 rounded-xl shadow-md">
              <i class="fa-solid fa-triangle-exclamation mr-2"></i>
              No se encontró el producto.
            </div>
          <?php endif; ?>

          <?php foreach ($productos as $producto): ?>
            <?php
              $imageUrl = !empty($producto['image_url'])
                ? htmlspecialchars($producto['image_url'])
                : 'https://lum.com.co/wp-content/uploads/2022/07/Logo-LUM-transparente-positivo.png';
            ?>
            <div
              class="bg-white rounded-2xl shadow-lg border border-[#e5e5e5] hover:shadow-2xl hover:-translate-y-1 transition duration-300 flex flex-col overflow-hidden animate-fadeIn">

              <div class="bg-[#f0f0f0] flex items-center justify-center h-48 relative">
                <img src="<?= $imageUrl ?>" alt="Producto" class="object-contain h-full w-full p-4"
                  onerror="this.onerror=null; this.src='https://lum.com.co/wp-content/uploads/2022/07/Logo-LUM-transparente-positivo.png';">
              </div>
              <div class="p-5 flex flex-col gap-3 flex-grow">
                <h3 class="text-lg font-semibold text-[#404141] line-clamp-3">
                  <?= htmlspecialchars($producto['description'] ?? 'Sin descripción') ?>
                </h3>
                <p class="text-sm text-[#666] flex items-center gap-1">
                  <i class="fa-solid fa-barcode"></i>
                  <strong>EAN:</strong> <?= htmlspecialchars($producto['ean'] ?? '-') ?>
                </p>
                <span class="inline-block px-3 py-1 text-xs font-semibold text-[#404141] bg-[#FEDF00] rounded-full w-fit shadow">
                  <i class="fa-solid fa-tag mr-1"></i>
                  <?= htmlspecialchars($producto['shelf_life_concept'] ?? 'General') ?>
                </span>
                <?php if (isset($producto['total_stock'])): ?>
                  <p class="text-sm text-[#404141]">
                    <i class="fa-solid fa-boxes-stacked mr-1"></i>
                    <strong>Stock:</strong> <?= (int) $producto['total_stock'] ?>
                  </p>
                  <p class="text-sm text-[#404141]">
                    <i class="fa-solid fa-dollar-sign mr-1"></i>
                    <strong>Chipperprice:</strong>
                    <?= $producto['chipperprice'] !== null ? (int) $producto['chipperprice'] : '—' ?>
                  </p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <?php if ($totalPages > 1): ?>
          <div class="mt-10 flex flex-wrap justify-center gap-2">
            <?php if ($page > 1): ?>
              <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>"
                class="px-4 py-2 border border-[#404141] text-[#404141] rounded-full flex items-center gap-2 hover:bg-[#FEDF00] hover:text-[#404141] transition">
                <i class="fa-solid fa-arrow-left"></i> Anterior
              </a>
            <?php endif; ?>
            <?php
              $rango = 2;
              $start = max(1, $page - $rango);
              $end   = min($totalPages, $page + $rango);
              if ($start > 1) echo '<span class="px-3 py-2">...</span>';
              for ($i = $start; $i <= $end; $i++):
            ?>
              <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
                class="px-4 py-2 border rounded-full transition font-semibold
                  <?= $page == $i
                      ? 'bg-[#404141] text-white border-[#404141]'
                      : 'border-[#ccc] text-[#404141] hover:bg-[#FEDF00] hover:border-[#FEDF00]' ?>">
                <?= $i ?>
              </a>
            <?php endfor;
              if ($end < $totalPages) echo '<span class="px-3 py-2">...</span>';
            ?>
            <?php if ($page < $totalPages): ?>
              <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>"
                class="px-4 py-2 border border-[#404141] text-[#404141] rounded-full flex items-center gap-2 hover:bg-[#FEDF00] hover:text-[#404141] transition">
                Siguiente <i class="fa-solid fa-arrow-right"></i>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </section>
    </main>
  </div>
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.5s ease-in-out; }
  </style>
</body>
