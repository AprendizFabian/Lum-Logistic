<body class="bg-[#f9f9f9] text-[#404141] font-sans">
    <div class="flex min-h-screen">
        <main class="flex-1 flex flex-col w-full">
            <section class="flex-1 px-6 py-10 max-w-screen-xl mx-3">

                <!-- Barra de búsqueda -->
                <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
                    <h1 class="text-4xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-database text-[#FEDF00]"></i> Vida Útil
                    </h1>
                    <form method="GET" action=""
                        class="flex items-center gap-3 w-full md:w-1/2 bg-white p-3 rounded-2xl shadow-lg border border-[#e5e5e5]">
                        <input type="text" name="search" placeholder="Buscar vida útil..."
                            class="flex-1 px-4 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FEDF00] text-[#404141] bg-[#f9f9f9] border border-[#ccc] transition"
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />
                        <button type="submit"
                            class="px-5 py-2 bg-[#404141] text-white font-medium rounded-xl hover:bg-[#2f2f2f] transition duration-200 shadow flex items-center gap-2">
                            <i class="fa-solid fa-magnifying-glass"></i> Buscar
                        </button>
                    </form>
                </div>

                <!-- Datos -->
                <?php if (empty($sheetData['data'])): ?>
                    <p class="text-gray-500 text-center">No hay datos disponibles.</p>
                <?php else: ?>
                    <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($sheetData['data'] as $row): ?>
                            <div
                                class="bg-white rounded-2xl shadow-lg border border-[#e5e5e5] hover:shadow-2xl hover:-translate-y-1 transition duration-300 p-6 flex flex-col gap-3 animate-fadeIn">
                                <p class="font-bold text-lg flex items-center gap-2">
                                    <i class="fa-solid fa-user text-[#FEDF00]"></i>
                                    <?= htmlspecialchars($row[0] ?? 'Sin nombre') ?>
                                </p>
                                <p class="text-[#404141] text-base flex items-center gap-2">
                                    <i class="fa-solid fa-calendar text-[#FEDF00]"></i>
                                    Salida-merma: <span class="font-semibold"><?= htmlspecialchars($row[1] ?? '-') ?>,
                                        <?= htmlspecialchars($row[2] ?? '-') ?></span>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Paginación -->
                <?php if ($sheetData['totalPages'] > 1): ?>
                    <div class="mt-10 flex flex-wrap justify-center gap-2">
                        <?php if ($sheetData['currentPage'] > 1): ?>
                            <a href="?page=<?= $sheetData['currentPage'] - 1 ?><?= $sheetData['searchParam'] ?>"
                                class="px-4 py-2 border border-[#404141] text-[#404141] rounded-full flex items-center gap-2 hover:bg-[#FEDF00] hover:text-[#404141] transition">
                                <i class="fa-solid fa-arrow-left"></i> Anterior
                            </a>
                        <?php endif; ?>

                        <?php for ($i = $sheetData['startPage']; $i <= $sheetData['endPage']; $i++): ?>
                            <a href="?page=<?= $i ?><?= $sheetData['searchParam'] ?>" class="px-4 py-2 border rounded-full transition font-semibold
                                <?= $sheetData['currentPage'] == $i
                                    ? 'bg-[#404141] text-white border-[#404141]'
                                    : 'border-[#ccc] text-[#404141] hover:bg-[#FEDF00] hover:border-[#FEDF00]' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($sheetData['currentPage'] < $sheetData['totalPages']): ?>
                            <a href="?page=<?= $sheetData['currentPage'] + 1 ?><?= $sheetData['searchParam'] ?>"
                                class="px-4 py-2 border border-[#404141] text-[#404141] rounded-full flex items-center gap-2 hover:bg-[#FEDF00] hover:text-[#404141] transition">
                                Siguiente <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        </main>
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
</body> 