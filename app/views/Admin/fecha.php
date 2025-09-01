<section class="px-6 py-10 max-w-screen-xl mx-auto text-[#404141] space-y-6">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 relative">
        <!-- Título -->
        <h1 class="text-3xl font-bold flex items-center gap-3">
            <i class="fa-solid fa-barcode text-[#FEDF00] text-2xl"></i>
            Fechas de vencimiento
        </h1>
        <div class="absolute top-2 right-5">
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-sm bg-[#404141] text-white hover:bg-[#2f2f2f] rounded-lg">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </label>
                <ul tabindex="0"
                    class="dropdown-content menu p-2 shadow-lg bg-white rounded-xl w-56 border border-gray-200 space-y-1">
                    <li>
                        <a href="https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=pdf&gid=416131206"
                            target="_blank" class="flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-red-500"></i> Descargar Bloqueos HOY PDF
                        </a>
                    </li>
                    <li>
                        <a href="https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=xlsx&gid=416131206"
                            class="flex items-center gap-2">
                            <i class="fa-solid fa-file-excel text-green-500"></i> Descargar Bloqueos HOY Excel
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <hr class="border-gray-300">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <form method="GET" class="flex items-center gap-2">
            <input type="text" name="buscar" value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
                placeholder="Buscar por EAN o Fecha..."
                class="w-64 px-4 py-2 text-sm rounded-lg border border-gray-300 bg-white shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FEDF00]">

            <button type="submit"
                class="px-4 py-2 bg-[#404141] text-white rounded-lg text-sm hover:bg-[#2f2f2f] flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass"></i> Buscar
            </button>
        </form>
        <div class="flex items-center gap-3 flex-wrap">
            <a href="/fecha-juliana"
                class="px-4 py-2 bg-[#404141] text-white rounded-lg text-sm hover:bg-[#2f2f2f] flex items-center gap-2">
                <i class="fa-solid fa-upload"></i> Cargue Individual
            </a>
            <a href="/Masivo"
                class="px-4 py-2 bg-[#FEDF00] text-[#404141] rounded-lg text-sm hover:bg-yellow-500 flex items-center gap-2">
                <i class="fa-solid fa-file-import"></i> Carga Masiva
            </a>
        </div>
    </div>
    <?php if (empty($fechasData['data'])): ?>
        <div class="col-span-full text-center mt-10 p-6 bg-red-100 text-red-600 rounded-xl shadow-md">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            No se encontró la fecha.
        </div>
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