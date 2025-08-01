<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <main class="flex-1 flex flex-col w-full">
            <section class="flex-1 px-4 py-10 bg-gray-100">
                <!-- Top bar con botón y búsqueda -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            
                 <form method="GET" class="flex items-center gap-4 w-full md:w-1/2">
            <input 
                type="text" 
                name="search" 
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
                placeholder="search por EAN o Fecha..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-black text-black bg-white"
            >
            <button 
                type="submit" 
                class="px-4 py-2 bg-black text-white rounded-xl hover:bg-gray-800 transition"
            >
                buscar
            </button>
        </form>
                </div>

                <!-- Grid de productos -->
                <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 max-w-screen-xl mx-auto w-full">
                    <?php foreach ($sheetData['data'] as $index => $row): ?>
                        <?php 
                            $imageUrl = !empty($row[3]) 
                                ? htmlspecialchars($row[3]) 
                                : 'https://lum.com.co/wp-content/uploads/2022/07/Logo-LUM-transparente-positivo.png';
                        ?>
                        <div class="bg-white rounded-xl shadow-md border border-gray-300 hover:shadow-xl transition duration-300 flex flex-col min-w-[270px] w-full">
                            <!-- Imagen -->
                            <div class="h-48 bg-gray-100 flex items-center justify-center rounded-t-xl overflow-hidden">
                                <img 
                                    src="<?= $imageUrl ?>" 
                                    alt="Producto" 
                                    class="object-contain h-full w-full"
                                    onerror="this.onerror=null; this.src='https://lum.com.co/wp-content/uploads/2022/07/Logo-LUM-transparente-positivo.png';"
                                >
                            </div>

                            <!-- Contenido -->
                            <div class="p-5 flex flex-col gap-2 flex-grow">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    <?= htmlspecialchars($row[2] ?? 'Sin descripción') ?>
                                </h3>
                                <p class="text-sm text-gray-700"><strong>Sync ID:</strong> <?= htmlspecialchars($row[0] ?? '-') ?></p>
                                <p class="text-sm text-gray-700"><strong>EAN:</strong> <?= htmlspecialchars($row[1] ?? '-') ?></p>
                                <p class="text-xs text-gray-500 mt-auto"><strong>Registro #</strong> <?= ($sheetData['perPage'] * ($sheetData['currentPage'] - 1)) + $index + 1 ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginación -->
                <?php if ($sheetData['totalPages'] > 1): ?>
                    <div class="mt-8 flex flex-wrap justify-center items-center gap-2">
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
