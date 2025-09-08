<div class="max-w-2xl mx-auto mt-10">
    <!-- Card -->
    <div class="card bg-white shadow-xl rounded-2xl border border-gray-100">
        <!-- Header -->
        <div class="card-body">
            <h2 class="card-title text-3xl font-bold text-[#14519A] flex items-center gap-3">
                <i class="fas fa-user-circle text-4xl text-[#14519A]"></i>
                <?= htmlspecialchars($title) ?>
            </h2>
            <div class="mt-6 space-y-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user text-gray-500 w-6"></i>
                    <span class="text-gray-700 font-medium">Usuario:</span>
                    <span class="text-gray-900"><?= htmlspecialchars($_SESSION['auth']['username']) ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-shield text-gray-500 w-6"></i>
                    <span class="text-gray-700 font-medium">Rol:</span>
                    <span class="badge <?= $_SESSION['auth']['id_role'] == 1 ? 'badge-primary' : 'badge-secondary' ?>">
                        <?= $_SESSION['auth']['id_role'] == 1 ? 'Administrador' : 'Usuario' ?>
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-envelope text-gray-500 w-6"></i>
                    <span class="text-gray-700 font-medium">Correo:</span>
                    <span class="text-gray-900"><?= htmlspecialchars($_SESSION['auth']['email']) ?></span>
                </div>
            </div>
            <div class="mt-8">
                <a href="/catalogo" class="btn btn-outline btn-primary gap-2">
                    <i class="fas fa-arrow-left"></i> Volver al Catalogo
                </a>
            </div>
        </div>
    </div>
</div>
