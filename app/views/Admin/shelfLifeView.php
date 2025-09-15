<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6 px-6 pt-6">
    <h1 class="text-3xl font-bold flex items-center gap-3 text-[#404141]">
        <i class="fas fa-shield text-[#FEDF00] text-4xl"></i>
        <?= htmlspecialchars($title) ?>
    </h1>

    <div class="flex gap-4 items-center w-full lg:w-auto justify-between lg:justify-end">
        <form method="GET"
            class="flex flex-col sm:flex-row flex-wrap items-stretch gap-3 bg-white p-3 rounded-2xl shadow-md border border-gray-200 w-full lg:w-auto">
            <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                placeholder="Buscar vida útil..."
                class="flex-1 px-4 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FEDF00] text-[#404141] bg-[#f9f9f9] border border-[#ccc] transition" />
            <button type="submit"
                class="px-6 py-2 bg-[#404141] text-white font-medium rounded-xl hover:bg-[#2f2f2f] transition duration-200 shadow flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass"></i> Buscar
            </button>
        </form>
    </div>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 px-6">
    <?php if (empty($shelfLifePaginated['items'])): ?>
        <div class="col-span-full text-center mt-12 p-8 bg-red-50 text-red-600 rounded-2xl shadow-md">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            No se encontró ningún producto.
        </div>
    <?php endif; ?>

    <?php foreach ($shelfLifePaginated['items'] as $shelfLife): ?>
        <div
            class="bg-white rounded-2xl shadow-lg border border-[#e5e5e5] hover:shadow-2xl hover:-translate-y-1 transition duration-300 p-6 flex flex-col gap-3 animate-fadeIn">
            <p class="font-bold text-lg flex items-center gap-2">
                <i class="fa-solid fa-user text-[#FEDF00]"></i>
                <?= htmlspecialchars($shelfLife['concept'] ?? 'Sin nombre') ?>
            </p>
            <p class="text-[#404141] text-base flex items-center gap-2">
                <i class="fa-solid fa-calendar text-[#FEDF00]"></i>
                Salida-merma: <span class="font-semibold"><?= htmlspecialchars($shelfLife['duration'] . " DIAS" ?? '-') ?>
            </p>
        </div>
    <?php endforeach; ?>
</div>

<div class="flex justify-center gap-2 my-5">
    <?php if ($shelfLifePaginated['totalPages'] > 1): ?>
        <nav class="flex justify-center items-center gap-2 flex-wrap">
            <?php if ($shelfLifePaginated['page'] > 1): ?>
                <a href="?page=<?= $shelfLifePaginated['page'] - 1 ?>"
                    class="px-4 py-2 rounded-xl shadow-md transition text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-[#404141] flex items-center gap-1">
                    <i class="fas fa-chevron-left"></i> Anterior
                </a>
            <?php endif; ?>

            <?php foreach ($shelfLifePaginated['pages'] as $p): ?>
                <?php if ($p === "..."): ?>
                    <span class="px-3 py-2 text-gray-400 font-semibold">...</span>
                <?php else: ?>
                    <a href="?page=<?= $p ?>" class="px-4 py-2 rounded-xl shadow-md transition text-sm font-semibold
                  <?= $p == $shelfLifePaginated['page']
                      ? 'bg-[#404141] text-[#FEDF00]'
                      : 'bg-gray-200 hover:bg-gray-300 text-[#404141]' ?>">
                        <?= $p ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($shelfLifePaginated['page'] < $shelfLifePaginated['totalPages']): ?>
                <a href="?page=<?= $shelfLifePaginated['page'] + 1 ?>"
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