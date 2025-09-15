<?php if ($membersPaginated['totalPages'] > 1): ?>
    <nav class="flex justify-center items-center gap-2 flex-wrap">
        <?php if ($membersPaginated['page'] > 1): ?>
            <a href="?page=<?= $membersPaginated['page'] - 1 ?>"
                class="px-4 py-2 rounded-xl shadow-md transition text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-[#404141] flex items-center gap-1">
                <i class="fas fa-chevron-left"></i> Anterior
            </a>
        <?php endif; ?>

        <?php foreach ($membersPaginated['pages'] as $p): ?>
            <?php if ($p === "..."): ?>
                <span class="px-3 py-2 text-gray-400 font-semibold">...</span>
            <?php else: ?>
                <a href="?page=<?= $p ?>" class="px-4 py-2 rounded-xl shadow-md transition text-sm font-semibold
                  <?= $p == $membersPaginated['page']
                      ? 'bg-[#404141] text-[#FEDF00]'
                      : 'bg-gray-200 hover:bg-gray-300 text-[#404141]' ?>">
                    <?= $p ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($membersPaginated['page'] < $membersPaginated['totalPages']): ?>
            <a href="?page=<?= $membersPaginated['page'] + 1 ?>"
                class="px-4 py-2 rounded-xl shadow-md transition text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-[#404141] flex items-center gap-1">
                Siguiente <i class="fas fa-chevron-right"></i>
            </a>
        <?php endif; ?>
    </nav>
<?php endif; ?>