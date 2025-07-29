<section class="p-6">
    <!-- Barra superior con botón Agregar y buscador -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <!-- Botón Agregar amarillo -->
        <button 
            class="px-6 py-2 bg-yellow-400 text-black rounded-xl hover:bg-yellow-500 transition flex items-center gap-2 order-1 md:order-none"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Agregar Nueva
        </button>

        <!-- Buscador -->
        <form method="GET" class="flex items-center gap-4 w-full md:w-1/2 order-3 md:order-none">
            <input 
                type="text" 
                name="buscar" 
                value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>" 
                placeholder="Buscar por EAN o Fecha..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-black text-black bg-white"
            >
            <button 
                type="submit" 
                class="px-4 py-2 bg-black text-white rounded-xl hover:bg-gray-800 transition"
            >
                Buscar
            </button>
        </form>
    </div>

    <?php
        $buscar = strtolower($_GET['buscar'] ?? '');
        $datosFiltrados = [];

        // Filtrar datos si hay búsqueda
        foreach ($fechasData['data'] as $row) {
            $textoFila = strtolower(implode(' ', $row));
            if ($buscar && strpos($textoFila, $buscar) === false) {
                continue;
            }
            $datosFiltrados[] = $row;
        }

        // Paginación
        $currentPage = max(1, intval($_GET['page'] ?? 1));
        $perPage = 9; // Mostrar 3 columnas x 3 filas
        $totalItems = count($datosFiltrados);
        $totalPages = max(1, ceil($totalItems / $perPage));
        $start = ($currentPage - 1) * $perPage;
        $paginados = array_slice($datosFiltrados, $start, $perPage);
        
        // Dividir en grupos de 3 para las filas
        $grupos = array_chunk($paginados, 3);
    ?>

    <!-- Grid de 3 columnas -->
    <div class="space-y-6">
        <?php if (count($paginados) === 0): ?>
            <p class="text-center text-gray-500 py-10">No se encontraron resultados.</p>
        <?php else: ?>
            <?php foreach ($grupos as $grupo): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($grupo as $row): ?>
                        <?php
                            $ean = htmlspecialchars($row[0] ?? 'Sin EAN');
                            $fecha = htmlspecialchars($row[1] ?? 'Sin fecha');
                        ?>
                        <div class="bg-white text-black p-8 rounded-xl shadow-lg border border-gray-6 hover:shadow-xl transition">
                            <p class="font-bold text-base mb-2">🔹 EAN: <?= $ean ?></p>
                            <p class="font-bold text-base mb-2">📅 Fecha vencimiento: <?= $fecha ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Paginación -->
    <div class="mt-6 flex justify-center items-center gap-4">
   <?php if ($fechasData['totalPages'] > 1): ?>
    <div class="mt-8 flex justify-center gap-2">
        <?php if ($fechasData['currentPage'] > 1): ?>
            <a href="?page=<?= $fechasData['currentPage'] - 1 ?><?= $fechasData['searchParam'] ?>" 
               class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                ← Anterior
            </a>
        <?php endif; ?>

        <?php for ($i = max(1, $fechasData['currentPage'] - 2); $i <= min($fechasData['totalPages'], $fechasData['currentPage'] + 2); $i++): ?>
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
    </div>
    
</section>