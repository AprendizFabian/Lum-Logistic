<div
    class="card border border-gray-200 rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transition duration-300 bg-white overflow-hidden">

    <div class="bg-[#FEDF00] h-24 flex items-center justify-center relative">
        <div
            class="w-20 h-20 rounded-full border-4 border-white shadow-md absolute -bottom-10 flex items-center justify-center bg-white">
            <?php if ($member['type'] === 'store'): ?>
                <i class="fas fa-store text-4xl text-[#404141]"></i>
            <?php else: ?>
                <i class="fas fa-user text-4xl text-[#404141]"></i>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body items-center text-center pt-12 space-y-2">
        <h3 class="text-lg font-bold text-[#404141]"><?= htmlspecialchars($member['username']) ?></h3>

        <p class="text-sm text-gray-600 flex items-center gap-2">
            <i class="fas fa-envelope text-[#404141]"></i>
            <?= htmlspecialchars($member['email'] ?? 'Sin correo') ?>
        </p>

        <div class="flex gap-3">
            <span
                class="px-3 py-1 text-xs font-semibold rounded-full 
                <?= ['Administrador' => 'bg-red-100 text-red-700', 'Usuario' => 'bg-blue-100 text-blue-700'][$member['rol']] ?? 'bg-orange-100 text-orange-700' ?>">
                <i class="fas fa-user-tag"></i> <?= htmlspecialchars($member['rol']) ?>
            </span>
            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                <?= $member['status'] === 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <i class="fas fa-user-tag"></i>
                <?= $member['status'] === 1 ? 'Activo' : 'Inactivo' ?>
            </span>
        </div>
    </div>

    <div class="card-actions justify-center gap-3 pb-6">
        <button
            class="btn btn-sm bg-[#404141] hover:bg-[#2f2f2f] text-[#FEDF00] border-0 rounded-lg shadow-md flex items-center gap-2 px-4"
            onclick="openEditModal(this)" data-id="<?= $member['id'] ?>"
            data-username="<?= htmlspecialchars($member['username']) ?>"
            data-email="<?= htmlspecialchars($member['email']) ?>" data-rol="<?= $member['id_role'] ?>"
            data-type="<?= htmlspecialchars($member['type']) ?>"
            data-address="<?= htmlspecialchars($member['address'] ?? '') ?>"
            data-city="<?= htmlspecialchars($member['city_id'] ?? '') ?>">
            <i class="fas fa-edit"></i> Editar
        </button>

        <form method="POST" action="/users/changeStatus" class="form-activar">
            <?php if ($member['type'] === 'user'): ?>
                <input type="hidden" name="id_user" value="<?= $member['id'] ?>">
                <input type="hidden" name="type" value="user">
            <?php elseif ($member['type'] === 'store'): ?>
                <input type="hidden" name="id_store" value="<?= $member['id'] ?>">
                <input type="hidden" name="type" value="store">
            <?php endif; ?>

            <input type="hidden" name="status" value="<?= $member['status'] == 1 ? 0 : 1 ?>">

            <button type="submit"
                class="btn btn-sm px-4 py-2 rounded-lg text-sm shadow-md transition flex items-center gap-2
                <?= $member['status'] == 1 ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' ?>">
                <i class="fas <?= $member['status'] == 1 ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                <?= $member['status'] == 1 ? 'Desactivar' : 'Activar' ?>
            </button>
        </form>
    </div>
</div>