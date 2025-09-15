<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6 px-6 pt-6">
    <h1 class="text-3xl font-bold flex items-center gap-3 text-[#404141]">
        <i class="fas fa-box text-[#FEDF00] text-4xl"></i>
        <?= htmlspecialchars($title) ?>
    </h1>

    <div class="flex gap-4 items-center w-full lg:w-auto justify-between lg:justify-end">
        <form method="GET"
            class="flex flex-col sm:flex-row flex-wrap items-center gap-3 bg-white p-3 rounded-2xl shadow-md border border-gray-200 w-full lg:w-auto">
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-sm bg-[#404141] text-white hover:bg-[#2f2f2f] rounded-lg">
                    <i class="fa-solid fa-download"></i>
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
            <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                placeholder="Buscar por EAN o nombre..."
                class="flex-1 px-4 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FEDF00] text-[#404141] bg-[#f9f9f9] border border-[#ccc] transition" />
            <button type="submit"
                class="px-6 py-2 bg-[#404141] text-white font-medium rounded-xl hover:bg-[#2f2f2f] transition duration-200 shadow flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass"></i> Buscar
            </button>
        </form>
    </div>
    <div class="flex items-center gap-3 flex-wrap">
        <a href="/date/dateJuliana"
            class="px-4 py-2 bg-[#404141] text-white rounded-lg text-sm hover:bg-[#2f2f2f] flex items-center gap-2">
            <i class="fa-solid fa-upload"></i> Cargue Individual
        </a>
        <a href="/Masivo"
            class="px-4 py-2 bg-[#FEDF00] text-[#404141] rounded-lg text-sm hover:bg-yellow-500 flex items-center gap-2">
            <i class="fa-solid fa-file-import"></i> Carga Masiva
        </a>
    </div>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 px-6">
    <?php if (empty($datesPaginated['items'])): ?>
        <div class="col-span-full text-center mt-10 p-6 bg-red-100 text-red-600 rounded-xl shadow-md">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            No se encontró la fecha.
        </div>
    <?php else: ?>
        <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($datesPaginated['items'] as $row): ?>
                <?php
                ?>
                <div
                    class="bg-white rounded-2xl shadow-lg border border-[#e5e5e5] hover:shadow-2xl hover:-translate-y-1 transition duration-300 p-6 flex flex-col gap-4 animate-fadeIn">
                    <p class="font-bold text-lg flex items-center gap-2">
                        <i class="fa-solid fa-barcode text-[#FEDF00]"></i> <?= htmlspecialchars($row[0]) ?>
                    </p>
                    <p class="text-base flex items-center gap-2">
                        <i class="fa-solid fa-calendar text-[#FEDF00]"></i>
                        Fecha vencimiento: <span class="font-semibold"><?= htmlspecialchars($row[1]) ?></span>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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