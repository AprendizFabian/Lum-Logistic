<div class="max-w-2xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4"><?= htmlspecialchars($title) ?></h2>

    <div class="space-y-3">
        <p><strong>ID:</strong> <?= htmlspecialchars($usuario['idusuarios']) ?></p>
        <p><strong>Usuario:</strong> <?= htmlspecialchars($usuario['usuario']) ?></p>
        <p><strong>Rol:</strong> <?= htmlspecialchars($usuario['rol_id_rol']) == 1 ? 'Administrador' : 'Usuario' ?></p>
    </div>

    <div class="mt-6">
        <a href="/usuarios" class="text-blue-500 hover:underline">← Volver a la lista de usuarios</a>
    </div>
</div>