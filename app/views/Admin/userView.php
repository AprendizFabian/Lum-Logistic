<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 p-6">
    <?php foreach ($usuariosPaginados as $usuario): ?>
        <div class="relative bg-white shadow-lg rounded-2xl overflow-hidden">
            <!-- Fondo azul inclinado -->
            <div class="absolute top-0 left-0 w-full h-32 bg-yellow-200 transform -skew-y-6 origin-top-left z-0"></div>

            <!-- Imagen de perfil -->
            <div class="relative flex justify-center z-10 mt-8">
                <img class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md" src="https://static.vecteezy.com/system/resources/previews/008/302/557/non_2x/eps10-yellow-user-solid-icon-or-logo-in-simple-flat-trendy-modern-style-isolated-on-white-background-free-vector.jpg" alt="Foto de usuario">
            </div>

            <!-- Info del usuario -->
            <div class="relative z-10 text-center px-4 py-6 mt-2">
                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($usuario['usuario']) ?></h3>
                <p class="text-sm text-black-300 font-medium"><?= htmlspecialchars($usuario['rol']) ?></p>
            </div>

            <!-- Footer opcional con redes -->
            <div class="flex justify-center gap-4 pb-4">
           
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
    $currentPage = (int)$page;
    $totalPages = (int)$totalPages;
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $currentPage + 2);
    $searchParam = ''; 
?>

<?php if ($totalPages > 1): ?>
    <div class="mt-8 flex flex-wrap justify-center items-center gap-2">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage - 1 ?><?= $searchParam ?>" class="px-4 py-2 border rounded-lg hover:bg-gray-200">Anterior</a>
        <?php endif; ?>

        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <a href="?page=<?= $i ?><?= $searchParam ?>"
               class="px-4 py-2 border rounded-lg <?= $currentPage == $i ? 'bg-black text-white' : 'hover:bg-gray-100' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?= $currentPage + 1 ?><?= $searchParam ?>" class="px-4 py-2 border rounded-lg hover:bg-gray-200">Siguiente</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
