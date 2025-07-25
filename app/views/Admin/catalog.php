<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <aside class="bg-[#404141] text-white w-64 p-6 space-y-6">
            <h1 class="text-2xl font-bold border-b pb-4">Mi CRM</h1>
            <nav class="flex flex-col bg-[#404141] gap-4 text-lg">
                <a href="index.html" class="flex items-center gap-3 hover:text-yellow-400 transition">
                    📦 <span>Catálogo</span>
                </a>
                <a href="vida_util.html" class="flex items-center gap-3 hover:text-yellow-400 transition">
                    ⏳ <span>Vida Útil</span>
                </a>
                <a href="tabla_usuarios.html" class="flex items-center gap-3 hover:text-yellow-400 transition">
                    👥 <span>Usuarios</span>
                </a>
                <a href="configuracion.html" class="flex items-center gap-3 hover:text-yellow-400 transition">
                    ⚙️ <span>Configuración</span>
                </a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col">
            <section class="flex-1 p-10 bg-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <button class="bg-yellow-400 hover:bg-yellow-500 text-black px-5 py-2 rounded-lg font-medium transition" onclick="document.getElementById('modalAgregar').showModal()">
                        ➕ Agregar nuevo
                    </button>
                    <form method="GET" action="">
                        <input type="text" name="search" placeholder="Buscar..." class="border border-gray-300 rounded-lg px-4 py-2 w-72 shadow-sm focus:ring-yellow-400 focus:border-yellow-400" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />
                        <button type="submit" class="hidden">Search</button> </form>
                </div>

                <div class="overflow-x-auto rounded-lg shadow-md">
                    <?php
                    $startIndex = ($sheetData['currentPage'] - 1) * $sheetData['perPage'];

                    // Placeholder for your actual PHP search filtering logic.
                    // This section should filter $sheetData['data'] based on $_GET['search']
                    // and then update $sheetData['total'] accordingly.
                    /*
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $searchTerm = strtolower($_GET['search']);
                        $originalData = /* ... your full unfiltered data source ... * /; // You'd load all data here first
                        $filteredData = [];
                        foreach ($originalData as $row) {
                            if (
                                stripos($row[0] ?? '', $searchTerm) !== false || // Syncid
                                stripos($row[1] ?? '', $searchTerm) !== false || // EAN
                                stripos($row[2] ?? '', $searchTerm) !== false    // Descripción
                            ) {
                                $filteredData[] = $row;
                            }
                        }
                        $sheetData['data'] = array_slice($filteredData, $startIndex, $sheetData['perPage']); // Get only current page's data
                        $sheetData['total'] = count($filteredData); // Update total for pagination
                        // If you're loading paginated data directly from a DB, the search would be part of the SQL query.
                    } else {
                        // Load paginated data without search
                        // $sheetData['data'] = array_slice(/* ... your full data source ... * /, $startIndex, $sheetData['perPage']);
                        // $sheetData['total'] = /* ... total count of your data source ... * /;
                    }
                    */

                    $totalPages = ceil($sheetData['total'] / $sheetData['perPage']);
                    $currentPage = $sheetData['currentPage'];
                    $searchParam = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
                    ?>

                    <table class="w-full bg-white">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="p-4 border-b w-16 text-left text-gray-600 font-semibold rounded-tl-lg">id</th>
                                <th class="p-4 border-b text-left text-gray-600 font-semibold">Syncid</th>
                                <th class="p-4 border-b text-left text-gray-600 font-semibold">EAN</th>
                                <th class="p-4 border-b text-left text-gray-600 font-semibold rounded-tr-lg">Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($sheetData['data'])): ?>
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-gray-500">No hay datos disponibles.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($sheetData['data'] as $index => $row): ?>
                                    <tr class="hover:bg-gray-50 text-base border-b last:border-b-0">
                                        <td class="p-4 text-center font-medium text-gray-600">
                                            <?= $startIndex + $index + 1 ?>
                                        </td>
                                        <td class="p-4"><?= htmlspecialchars($row[0] ?? '') ?></td>
                                        <td class="p-4"><?= htmlspecialchars($row[1] ?? '') ?></td>
                                        <td class="p-4"><?= htmlspecialchars($row[2] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="mt-8 flex justify-center items-center space-x-2">

                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?><?= $searchParam ?>"
                               class="px-4 py-2 border rounded-lg font-medium bg-white text-gray-700 hover:bg-gray-100 transition duration-200 ease-in-out">
                                &laquo; Anterior
                            </a>
                        <?php endif; ?>

                        <?php
                        // Determine the start and end page numbers to display
                        $numPagesToShow = 5; // How many page numbers to show at once
                        $startPage = max(1, $currentPage - floor($numPagesToShow / 2));
                        $endPage = min($totalPages, $startPage + $numPagesToShow - 1);

                        // Adjust startPage if we're near the end
                        if ($endPage - $startPage + 1 < $numPagesToShow) {
                            $startPage = max(1, $endPage - $numPagesToShow + 1);
                        }
                        ?>

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <a href="?page=<?= $i ?><?= $searchParam ?>"
                               class="px-4 py-2 border rounded-lg font-medium
                               <?= $currentPage == $i ? 'bg-black text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100' ?>
                               transition duration-200 ease-in-out">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?><?= $searchParam ?>"
                               class="px-4 py-2 border rounded-lg font-medium bg-white text-gray-700 hover:bg-gray-100 transition duration-200 ease-in-out">
                                Siguiente &raquo;
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                </section>
        </main>
    </div>

    <dialog id="modalAgregar" class="w-full max-w-md rounded-lg shadow-xl backdrop:bg-black backdrop:bg-opacity-50">
        <form method="dialog" class="bg-white p-6 rounded space-y-4">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Agregar Vida Útil</h3>

            <div>
                <label for="id_vida_util" class="block text-sm mb-1 text-gray-700">ID Vida Útil</label>
                <input type="text" id="id_vida_util" class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="concepto" class="block text-sm mb-1 text-gray-700">Concepto</label>
                <input type="text" id="concepto" class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="tiempo" class="block text-sm mb-1 text-gray-700">Tiempo</label>
                <input type="text" id="tiempo" class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 ease-in-out">Guardar</button>
                <button type="button" onclick="document.getElementById('modalAgregar').close()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium transition duration-200 ease-in-out">Cancelar</button>
            </div>
        </form>
    </dialog>
</body>