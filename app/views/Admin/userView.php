<div class="flex justify-end px-6 pt-4">
  <a href="/agregar" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">➕ Agregar Usuario</a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-6">
  <?php foreach ($usuariosPaginados as $usuario): ?>
    <div class="relative bg-white shadow-lg rounded-2xl overflow-hidden">
      <div class="absolute top-0 left-0 w-full h-32 bg-yellow-200 transform -skew-y-6 origin-top-left z-0"></div>
      <div class="relative flex justify-center z-10 mt-8">
        <img class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md"
          src="https://static.vecteezy.com/system/resources/previews/008/302/557/non_2x/eps10-yellow-user-solid-icon-or-logo-in-simple-flat-trendy-modern-style-isolated-on-white-background-free-vector.jpg"
          alt="Foto de usuario">
      </div>
      <div class="relative z-10 text-center px-4 py-6 mt-2">
        <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($usuario['usuario']) ?></h3>
        <p class="text-sm text-black-500 font-medium"><?= htmlspecialchars($usuario['rol']) ?></p>
      </div>
      <div class="relative z-10 flex justify-center gap-4 pb-4">
        <a href="/usuarios?editar=<?= $usuario['idusuarios'] ?>"
          class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">✏️ Editar</a>
        <a href="/usuarios?eliminar=<?= $usuario['idusuarios'] ?>"
          class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">🗑️ Eliminar</a>

      </div>
    </div>
  <?php endforeach; ?>
</div>


<!-- Modal de Edición -->
<?php if ($usuarioAEditar): ?>
  <dialog open class="modal modal-open">
    <div class="modal-box">
      <h3 class="font-bold text-white mb-4">Editar Usuario</h3>
      <form method="POST" action="/editar">
        <input type="hidden" name="id" value="<?= $usuarioAEditar['idusuarios'] ?>">

        <div class="mb-4">
          <label class="label">Usuario</label>
          <input type="text" name="usuario" value="<?= htmlspecialchars($usuarioAEditar['usuario']) ?>"
            class="input input-bordered w-full" required>
        </div>

        <div class="mb-4">
          <label class="label">Rol</label>
          <select name="rol" class="select select-bordered w-full" required>
            <option value="2" <?= $usuarioAEditar['rol_id_rol'] == 2 ? 'selected' : '' ?>>Usuario</option>
            <option value="1" <?= $usuarioAEditar['rol_id_rol'] == 1 ? 'selected' : '' ?>>Administrador</option>
          </select>
        </div>

        <div class="modal-action">
          <button type="submit" class="btn btn-success">Guardar</button>
          <a href="/usuarios" class="btn">Cancelar</a>
        </div>
      </form>
    </div>
  </dialog>
<?php endif; ?>


<!-- Modal de Confirmación de Eliminación -->
<?php if ($usuarioAEliminar): ?>
  <dialog open class="modal modal-open">
    <div class="modal-box">
      <h3 class="font-bold text-lg">¿Eliminar usuario?</h3>
      <p class="py-2 text-gray-600">¿Estás seguro de eliminar al usuario
        <strong><?= htmlspecialchars($usuarioAEliminar['usuario']) ?></strong>?
      </p>
      <form method="POST" action="/eliminar">
        <input type="hidden" name="id" value="<?= $usuarioAEliminar['idusuarios'] ?>">
        <div class="modal-action">
          <button type="submit" class="btn btn-error">Eliminar</button>
          <a href="/usuarios" class="btn">Cancelar</a>
        </div>
      </form>
    </div>
  </dialog>
<?php endif; ?>

<!-- Paginación -->
<?php if ($totalPages > 1): ?>
  <?php
  $currentPage = $page;
  $startPage = max(1, $currentPage - 2);
  $endPage = min($totalPages, $startPage + 4);
  ?>
  <div class="mt-8 flex flex-wrap justify-center items-center gap-2">
    <?php if ($currentPage > 1): ?>
      <a href="?page=<?= $currentPage - 1 ?>" class="px-4 py-2 border rounded-lg hover:bg-gray-200">Anterior</a>
    <?php endif; ?>

    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
      <a href="?page=<?= $i ?>"
        class="px-4 py-2 border rounded-lg <?= $currentPage == $i ? 'bg-black text-white' : 'hover:bg-gray-100' ?>">
        <?= $i ?>
      </a>
    <?php endfor; ?>

    <?php if ($currentPage < $totalPages): ?>
      <a href="?page=<?= $currentPage + 1 ?>" class="px-4 py-2 border rounded-lg hover:bg-gray-200">Siguiente</a>
    <?php endif; ?>
  </div>
<?php endif; ?>