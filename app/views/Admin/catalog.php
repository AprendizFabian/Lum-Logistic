<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <main class="flex-1 flex flex-col">
            <section class="flex-1 p-10 bg-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <button class="bg-yellow-400 hover:bg-yellow-500 text-black px-5 py-2 rounded-lg font-medium transition" onclick="document.getElementById('modalAgregar').showModal()">➕ Agregar nuevo</button>
                    <form method="GET" action="">
                        <input type="text" name="search" placeholder="Buscar..." class="border border-gray-300 rounded-lg px-4 py-2 w-72 shadow-sm" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />
                        <button type="submit" class="hidden">Buscar</button>
                    </form>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($sheetData['data'] as $index => $row): ?>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 hover:shadow-xl transition duration-300 flex flex-col">
            <div class="h-48 bg-gray-100 flex items-center justify-center rounded-t-xl overflow-hidden">
                <img src="https://via.placeholder.com/150" alt="Producto" class="object-contain h-full">
            </div>

            <div class="p-5 flex flex-col gap-2 flex-grow">
                <h3 class="text-xl font-semibold text-gray-900"><?= htmlspecialchars($row[2] ?? 'Sin descripción') ?></h3>
                <p class="text-sm text-gray-700"><strong>Sync ID:</strong> <?= htmlspecialchars($row[0] ?? '-') ?></p>
                <p class="text-sm text-gray-700"><strong>EAN:</strong> <?= htmlspecialchars($row[1] ?? '-') ?></p>
                <p class="text-xs text-gray-500 mt-auto"><strong>Registro #</strong> <?= ($sheetData['perPage'] * ($sheetData['currentPage'] - 1)) + $index + 1 ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

                <?php if ($sheetData['totalPages'] > 1): ?>
                    <div class="mt-8 flex justify-center items-center space-x-2">
                        <?php if ($sheetData['currentPage'] > 1): ?>
                            <a href="?page=<?= $sheetData['currentPage'] - 1 ?><?= $sheetData['searchParam'] ?>" class="px-4 py-2 border rounded-lg">Anterior</a>
                        <?php endif; ?>
                        <?php for ($i = $sheetData['startPage']; $i <= $sheetData['endPage']; $i++): ?>
                            <a href="?page=<?= $i ?><?= $sheetData['searchParam'] ?>" class="px-4 py-2 border rounded-lg <?= $sheetData['currentPage'] == $i ? 'bg-black text-white' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>
                        <?php if ($sheetData['currentPage'] < $sheetData['totalPages']): ?>
                            <a href="?page=<?= $sheetData['currentPage'] + 1 ?><?= $sheetData['searchParam'] ?>" class="px-4 py-2 border rounded-lg">Siguiente</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
