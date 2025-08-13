<section class="px-6 py-10 max-w-screen-xl mx-auto text-[#404141]">

    <!-- Barra de búsqueda -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <h1 class="text-3xl font-bold flex items-center gap-2">
            <i class="fa-solid fa-barcode text-[#FEDF00]"></i> Fechas de Vencimiento
        </h1>
        <form method="GET"
            class="flex items-center gap-3 w-full md:w-1/2 bg-white p-3 rounded-2xl shadow-lg border border-[#e5e5e5]">
            <input type="text" name="buscar" value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
                placeholder="Buscar por EAN o Fecha..."
                class="flex-1 px-4 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FEDF00] text-[#404141] bg-[#f9f9f9] border border-[#ccc] transition">
            <button type="submit"
                class="px-5 py-2 bg-[#404141] text-white font-medium rounded-xl hover:bg-[#2f2f2f] transition duration-200 shadow flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass"></i> Buscar
            </button>
        </form>
    </div>

    <!-- Grid de datos -->
    <?php if (empty($fechasData['data'])): ?>
        <p class="text-center text-gray-500 py-10">No se encontraron resultados.</p>
    <?php else: ?>
        <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($fechasData['data'] as $row): ?>
                <?php
                $ean = htmlspecialchars($row[0] ?? 'Sin EAN');
                $fecha = htmlspecialchars($row[1] ?? 'Sin fecha');
                ?>
                <div
                    class="bg-white rounded-2xl shadow-lg border border-[#e5e5e5] hover:shadow-2xl hover:-translate-y-1 transition duration-300 p-6 flex flex-col gap-4 animate-fadeIn">
                    <p class="font-bold text-lg flex items-center gap-2">
                        <i class="fa-solid fa-barcode text-[#FEDF00]"></i> <?= $ean ?>
                    </p>
                    <p class="text-base flex items-center gap-2">
                        <i class="fa-solid fa-calendar text-[#FEDF00]"></i>
                        Fecha vencimiento: <span class="font-semibold"><?= $fecha ?></span>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Paginación -->
    <?php if ($fechasData['totalPages'] > 1): ?>
        <div class="mt-10 flex flex-wrap justify-center gap-2">
            <?php if ($fechasData['currentPage'] > 1): ?>
                <a href="?page=<?= $fechasData['currentPage'] - 1 ?><?= $fechasData['searchParam'] ?>"
                    class="px-4 py-2 border border-[#404141] text-[#404141] rounded-full flex items-center gap-2 hover:bg-[#FEDF00] hover:text-[#404141] transition">
                    <i class="fa-solid fa-arrow-left"></i> Anterior
                </a>
            <?php endif; ?>

            <?php for ($i = $fechasData['startPage']; $i <= $fechasData['endPage']; $i++): ?>
                <a href="?page=<?= $i ?><?= $fechasData['searchParam'] ?>" class="px-4 py-2 border rounded-full transition font-semibold
                    <?= $fechasData['currentPage'] == $i
                        ? 'bg-[#404141] text-white border-[#404141]'
                        : 'border-[#ccc] text-[#404141] hover:bg-[#FEDF00] hover:border-[#FEDF00]' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($fechasData['currentPage'] < $fechasData['totalPages']): ?>
                <a href="?page=<?= $fechasData['currentPage'] + 1 ?><?= $fechasData['searchParam'] ?>"
                    class="px-4 py-2 border border-[#404141] text-[#404141] rounded-full flex items-center gap-2 hover:bg-[#FEDF00] hover:text-[#404141] transition">
                    Siguiente <i class="fa-solid fa-arrow-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</section>

<!-- Animación -->
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