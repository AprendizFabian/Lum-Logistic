<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6 px-6 pt-6">
  <h1 class="text-3xl font-bold flex items-center gap-3 text-[#404141]">
    <i class="fas fa-box text-[#FEDF00] text-4xl"></i>
    <?= htmlspecialchars($title) ?>
  </h1>

  <div class="flex gap-4 items-center w-full lg:w-auto justify-between lg:justify-end">
    <form method="GET"
      class="flex flex-col sm:flex-row flex-wrap items-stretch gap-3 bg-white p-3 rounded-2xl shadow-md border border-gray-200 w-full lg:w-auto">
      <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
        placeholder="Buscar por EAN o nombre..."
        class="flex-1 px-4 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FEDF00] text-[#404141] bg-[#f9f9f9] border border-[#ccc] transition" />
      <button type="submit"
        class="px-6 py-2 bg-[#404141] text-white font-medium rounded-xl hover:bg-[#2f2f2f] transition duration-200 shadow flex items-center gap-2">
        <i class="fa-solid fa-magnifying-glass"></i> Buscar
      </button>
    </form>
  </div>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 px-6">
  <?php if (empty($productsPaginated['items'])): ?>
    <div class="col-span-full text-center mt-12 p-8 bg-red-50 text-red-600 rounded-2xl shadow-md">
      <i class="fa-solid fa-triangle-exclamation mr-2"></i>
      No se encontró ningún producto.
    </div>
  <?php endif; ?>

  <?php foreach ($productsPaginated['items'] as $product): ?>
    <?php
    $imageUrl = !empty($product['image_url'])
      ? htmlspecialchars($product['image_url'])
      : 'https://lum.com.co/wp-content/uploads/2022/07/Logo-LUM-transparente-positivo.png';
    ?>
    <div
      class="card border border-gray-200 rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transition duration-300 bg-white overflow-hidden">
      <div class="flex mx-auto items-center justify-center size-55 relative">
        <img src="<?= $imageUrl ?>" alt="Producto" class="object-contain h-full w-full p-6"
          onerror="this.onerror=null; this.src='https://lum.com.co/wp-content/uploads/2022/07/Logo-LUM-transparente-positivo.png';">
      </div>
      <div class="p-5 flex flex-col flex-grow">
        <h3 class="text-lg font-bold text-[#404141] line-clamp-2 mb-2">
          <?= htmlspecialchars($product['description'] ?? 'Sin descripción') ?>
        </h3>
        <p class="text-sm text-[#666] flex items-center gap-2 mb-1">
          <i class="fa-solid fa-barcode text-[#999]"></i>
          <span><strong>EAN:</strong> <?= htmlspecialchars($product['ean'] ?? '-') ?></span>
        </p>
        <span
          class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-[#404141] bg-[#FEDF00] rounded-full w-fit shadow-sm my-3">
          <i class="fa-solid fa-tag"></i>
          <?= htmlspecialchars($product['shelf_life_concept'] ?? 'General') ?>
        </span>
        <?php if (isset($product['total_stock'])): ?>
          <div class="mt-auto flex justify-between items-center">
            <div class="space-y-1 text-sm">
              <p class="text-[#404141] flex items-center gap-1">
                <i class="fa-solid fa-boxes-stacked text-[#14519A]"></i>
                <strong>Stock:</strong> <?= (int) $product['total_stock'] ?>
              </p>
              <p class="text-[#404141] flex items-center gap-1">
                <i class="fa-solid fa-dollar-sign text-green-600"></i>
                <strong>Chipperprice:</strong>
                <?= $product['chipperprice'] !== null ? number_format($product['chipperprice'], 0, ',', '.') : '—' ?>
              </p>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="flex justify-center gap-2 my-5">
  <?php if ($productsPaginated['totalPages'] > 1): ?>
    <nav class="flex justify-center items-center gap-2 flex-wrap">
      <?php if ($productsPaginated['page'] > 1): ?>
        <a href="?page=<?= $productsPaginated['page'] - 1 ?>"
          class="px-4 py-2 rounded-xl shadow-md transition text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-[#404141] flex items-center gap-1">
          <i class="fas fa-chevron-left"></i> Anterior
        </a>
      <?php endif; ?>

      <?php foreach ($productsPaginated['pages'] as $p): ?>
        <?php if ($p === "..."): ?>
          <span class="px-3 py-2 text-gray-400 font-semibold">...</span>
        <?php else: ?>
          <a href="?page=<?= $p ?>" class="px-4 py-2 rounded-xl shadow-md transition text-sm font-semibold
                  <?= $p == $productsPaginated['page']
                    ? 'bg-[#404141] text-[#FEDF00]'
                    : 'bg-gray-200 hover:bg-gray-300 text-[#404141]' ?>">
            <?= $p ?>
          </a>
        <?php endif; ?>
      <?php endforeach; ?>

      <?php if ($productsPaginated['page'] < $productsPaginated['totalPages']): ?>
        <a href="?page=<?= $productsPaginated['page'] + 1 ?>"
          class="px-4 py-2 rounded-xl shadow-md transition text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-[#404141] flex items-center gap-1">
          Siguiente <i class="fas fa-chevron-right"></i>
        </a>
      <?php endif; ?>
    </nav>
  <?php endif; ?>
</div>

<style>
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fadeIn {
    animation: fadeIn 0.5s ease-in-out;
  }
</style>