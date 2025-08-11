<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <main class="flex-1 w-full">
            <section class="w-full max-w-7xl mx-auto px-4 py-10">

                <!-- Top bar: botón y búsqueda -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">


                    <form method="GET" action="" class="w-full sm:w-auto flex flex-wrap gap-2">
                        <input type="text" name="search" placeholder="Buscar vida útil..."
                            class="flex-1 min-w-[200px] border border-gray-400 rounded-lg px-4 py-2 text-base shadow-sm"
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />
                        <button type="submit"
                            class="px-4 py-2 bg-black text-white rounded-xl hover:bg-gray-800 transition">
                            Buscar
                        </button>
                    </form>

                </div>
                <?php if (empty($sheetData['data'])): ?>
                    <p class="text-gray-500">No hay datos disponibles.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($sheetData['data'] as $row): ?>
                            <div
                                class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition break-words border border-gray-100">
                                <p class="font-bold text-base mb-2">🔹 Nombre: <?= htmlspecialchars($row[0] ?? 'Sin nombre') ?>
                                </p>
                                <p class="font-bold text-base mb-2">
                                    🔙 Salida-merma: <?= htmlspecialchars($row[1] ?? '-') ?>,
                                    <?= htmlspecialchars($row[2] ?? '-') ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Paginación -->
                <?php if ($sheetData['totalPages'] > 1): ?>
                    <div class="mt-8 flex flex-wrap justify-center items-center gap-2">
                        <?php if ($sheetData['currentPage'] > 1): ?>
                            <a href="?page=<?= $sheetData['currentPage'] - 1 ?><?= $sheetData['searchParam'] ?>"
                                class="px-4 py-2 border rounded-lg">Anterior</a>
                        <?php endif; ?>

                        <?php for ($i = $sheetData['startPage']; $i <= $sheetData['endPage']; $i++): ?>
                            <a href="?page=<?= $i ?><?= $sheetData['searchParam'] ?>"
                                class="px-4 py-2 border rounded-lg <?= $sheetData['currentPage'] == $i ? 'bg-black text-white' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($sheetData['currentPage'] < $sheetData['totalPages']): ?>
                            <a href="?page=<?= $sheetData['currentPage'] + 1 ?><?= $sheetData['searchParam'] ?>"
                                class="px-4 py-2 border rounded-lg">Siguiente</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </section>
        </main>
    </div>
</body>