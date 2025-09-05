<div class="max-w-2xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4"><?= htmlspecialchars($title) ?></h2>

    <div class="space-y-3">
        <p><?= htmlspecialchars($_SESSION['auth']['id']) ?></p>
        <?= var_dump($_SESSION['auth']) ?>
        <p><strong>Usuario:</strong> <?= htmlspecialchars($_SESSION['auth']['username']) ?></p>
        <p><strong>Rol:</strong> <?= htmlspecialchars($_SESSION['auth']['id_role']) == 1 ? 'Administrador' : 'Usuario' ?></p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($_SESSION['auth']['email']) ?></p>
    </div>

    <div class="mt-6">
        <a href="/catalogo" class="text-blue-500 hover:underline">← Volver a la lista de usuarios</a>
    </div>
</div>