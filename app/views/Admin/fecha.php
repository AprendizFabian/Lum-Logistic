<section class="p-6">

    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">

        <form method="GET" class="flex items-center gap-4 w-full md:w-1/2">
            <input type="text" name="buscar" value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
                placeholder="buscar por EAN o Fecha..."
                class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-black text-black bg-white">
            <button type="submit" class="px-4 py-2 bg-black text-white rounded-xl hover:bg-gray-800 transition">
                buscar
            </button>
        </form>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto w-full px-4">
        <?php if (empty($fechasData['data'])): ?>
            <p class="text-center col-span-full text-gray-500 py-10">No se encontraron resultados.</p>
        <?php else: ?>
            <?php foreach ($fechasData['data'] as $index => $row): ?>
                <?php
                $ean = htmlspecialchars($row[0] ?? 'Sin EAN');
                $fecha = htmlspecialchars($row[1] ?? 'Sin fecha');
                ?>
                <div
                    class="bg-white text-black px-6 py-8 rounded-xl shadow-md border border-gray-200 hover:shadow-xl transition flex flex-col min-h-[160px]">
                    <p class="font-bold text-base mb-4">🔹 EAN: <?= $ean ?></p>
                    <p class="font-bold text-base mt-auto">📅 Fecha vencimiento: <?= $fecha ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Paginación -->
    <?php if ($fechasData['totalPages'] > 1): ?>
        <div class="mt-8 flex justify-center gap-2 flex-wrap">
            <?php if ($fechasData['currentPage'] > 1): ?>
                <a href="?page=<?= $fechasData['currentPage'] - 1 ?><?= $fechasData['searchParam'] ?>"
                    class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                    ← Anterior
                </a>
            <?php endif; ?>

            <?php for ($i = $fechasData['startPage']; $i <= $fechasData['endPage']; $i++): ?>
                <a href="?page=<?= $i ?><?= $fechasData['searchParam'] ?>"
                    class="px-4 py-2 rounded-lg <?= $fechasData['currentPage'] == $i ? 'bg-black text-white' : 'bg-gray-100 hover:bg-gray-200' ?> transition">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($fechasData['currentPage'] < $fechasData['totalPages']): ?>
                <a href="?page=<?= $fechasData['currentPage'] + 1 ?><?= $fechasData['searchParam'] ?>"
                    class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                    Siguiente →
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>