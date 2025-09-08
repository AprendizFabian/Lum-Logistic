<?php if ($membersPaginated['page'] > 1): ?>
    <a href="?page=<?= $membersPaginated['page'] - 1 ?>"
        class="px-4 py-2 rounded-lg shadow-md transition text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-[#404141] flex items-center gap-1">
        <i class="fas fa-chevron-left"></i> Anterior
    </a>
<?php endif; ?>

<?php for ($i = 1; $i <= $membersPaginated['totalPages']; $i++): ?>
    <a href="?page=<?= $i ?>"
        class="px-4 py-2 rounded-lg shadow-md transition text-sm font-semibold 
        <?= $i == $membersPaginated['page'] ? 'bg-[#404141] text-[#FEDF00]' : 'bg-gray-200 hover:bg-gray-300 text-[#404141]' ?>">
        <?= $i ?>
    </a>
<?php endfor; ?>

<?php if ($membersPaginated['page'] < $membersPaginated['totalPages']): ?>
    <a href="?page=<?= $membersPaginated['page'] + 1 ?>"
        class="px-4 py-2 rounded-lg shadow-md transition text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-[#404141] flex items-center gap-1">
        Siguiente <i class="fas fa-chevron-right"></i>
    </a>
<?php endif; ?>