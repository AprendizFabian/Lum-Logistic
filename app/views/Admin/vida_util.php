<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <main >
          <section class="flex-1 p-10 bg-gray-100">
     <body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <main class="flex-1">
            <section class="w-full max-w-7xl mx-auto px-4 py-6">
                <div class="flex justify-between mb-6">
                <button class="bg-yellow-400 hover:bg-yellow-500 text-black px-5 py-2 rounded-lg font-medium transition" onclick="document.getElementById('modalAgregar').showModal()">➕ Agregar nuevo</button>
                    
                    <form method="GET" action="">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Buscar vida útil..." 
                            class="border border-gray-400 rounded-lg px-4 py-2 w-72 text-base"
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
                        />
                    </form>
                </div>

                <?php if (empty($sheetData['data'])): ?>
                    <p class="text-gray-500">No hay datos disponibles.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                     <?php foreach ($sheetData['data'] as $row): ?>
                        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition        break-words border border-gray-10">
                           <p class="font-bold text-base mb-2">🔹 Nombre: <?= htmlspecialchars($row[0] ?? 'Sin nombre') ?></p>
                            <p class="font-bold text-base mb-2">
                              🔙 Salida-merma: <?= htmlspecialchars($row[1] ?? '-'), htmlspecialchars($row [2] ?? '-') ?>
                              &nbsp;&nbsp;&nbsp;
                            </p>
                          </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

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

</body>
