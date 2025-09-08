<div
    class="card border border-[#e5e5e5] rounded-2xl shadow-lg hover:shadow-2xl hover:-translate-y-1 transition duration-300 bg-white">
    <div class="bg-[#FEDF00] h-24 flex items-center justify-center relative">
        <?php if ($member['type'] === 'store'): ?>
            <img class="w-20 h-20 rounded-full border-4 border-white shadow-md absolute -bottom-10 object-cover"
                src="https://cdn-icons-png.flaticon.com/512/891/891462.png" alt="Tienda">
        <?php else: ?>
            <img class="w-20 h-20 rounded-full border-4 border-white shadow-md absolute -bottom-10 object-cover"
                src="https://static.vecteezy.com/system/resources/previews/008/302/557/non_2x/eps10-yellow-user-solid-icon-or-logo-in-simple-flat-trendy-modern-style-isolated-on-white-background-free-vector.jpg"
                alt="Usuario">
        <?php endif; ?>
    </div>
    <div class="card-body items-center text-center pt-12">
        <h3 class="text-xl font-bold text-[#404141]"><?= htmlspecialchars($member['username']) ?></h3>
        <p class="text-sm text-gray-600 flex items-center gap-2">
            <i class="fas fa-envelope text-[#404141]"></i> <?= htmlspecialchars($member['email'] ?? 'Sin correo') ?>
        </p>
        <p class="text-sm text-gray-600 flex items-center gap-2">
            <i class="fas fa-user-tag text-[#404141]"></i> <?= htmlspecialchars($member['rol']) ?>
        </p>
        <p class="mt-2">
            <?php if ($member['status'] == 1): ?>
                <span
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold shadow">
                    <i class="fas fa-check-circle"></i> Activo
                </span>
            <?php else: ?>
                <span
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold shadow">
                    <i class="fas fa-times-circle"></i> Inactivo
                </span>
            <?php endif; ?>
        </p>
    </div>
    <div class="card-actions justify-center gap-3 pb-6">
        <button
            class="btn btn-sm bg-[#404141] hover:bg-[#2f2f2f] text-[#FEDF00] border-0 rounded-lg shadow-md flex items-center gap-2"
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
                class="btn btn-sm btn-estado px-3 py-1 rounded-md text-sm shadow-sm transition flex items-center gap-1
            <?= $member['status'] == 1 ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' ?>">
                <i class="fas <?= $member['status'] == 1 ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                <?= $member['status'] == 1 ? 'Desactivar' : 'Activar' ?>
            </button>
        </form>
    </div>
</div>