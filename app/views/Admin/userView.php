<!-- Encabezado con título y botón -->
<div class="flex justify-between items-center mb-8 flex-wrap gap-4 px-6 pt-4">
  <h1 class="text-3xl font-bold flex items-center gap-2">
    <i class="fas fa-users text-[#FEDF00]"></i> Lista de Usuarios
  </h1>
  <a href="/agregar"
    class="bg-[#404141] hover:bg-[#2f2f2f] text-[#FEDF00] font-semibold px-4 py-2 rounded-xl shadow-lg flex items-center gap-2 transition">
    <i class="fas fa-user-plus"></i> Agregar Usuario
  </a>
</div>

<!-- Cards de usuarios -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-6">
  <?php foreach ($usuariosPaginados as $usuario): ?>
    <div
      class="bg-white border border-[#e5e5e5] rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition duration-300">
      <!-- Encabezado decorativo -->
      <div class="bg-[#FEDF00] h-20 flex items-center justify-center relative">
        <img class="w-20 h-20 rounded-full border-4 border-white shadow-md absolute -bottom-10 object-cover"
          src="https://static.vecteezy.com/system/resources/previews/008/302/557/non_2x/eps10-yellow-user-solid-icon-or-logo-in-simple-flat-trendy-modern-style-isolated-on-white-background-free-vector.jpg"
          alt="Foto de usuario">
      </div>

      <!-- Info usuario -->
      <div class="pt-12 pb-4 px-4 text-center">
        <h3 class="text-lg font-bold text-[#404141]"><?= htmlspecialchars($usuario['usuario']) ?></h3>
        <p class="text-sm text-gray-600"><?= htmlspecialchars($usuario['rol']) ?></p>
      </div>

      <!-- Acciones -->
      <div class="flex justify-center gap-3 pb-4">
        <a href="/usuarios?editar=<?= $usuario['idusuarios'] ?>"
          class="bg-[#404141] hover:bg-[#2f2f2f] text-[#FEDF00] px-3 py-1 rounded-md text-sm shadow-sm transition flex items-center gap-1">
          <i class="fas fa-edit"></i> Editar
        </a>
        <a href="/usuarios?eliminar=<?= $usuario['idusuarios'] ?>"
          class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm shadow-sm transition flex items-center gap-1">
          <i class="fas fa-trash"></i> Eliminar
        </a>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Modal Edición -->
<?php if ($usuarioAEditar): ?>
  <dialog open class="modal modal-open">
    <div class="modal-box bg-white text-[#404141]">
      <h3 class="font-bold mb-4 text-2xl">Editar Usuario</h3>
      <form method="POST" action="/editar">
        <input type="hidden" name="id" value="<?= $usuarioAEditar['idusuarios'] ?>">

        <div class="mb-4">
          <label class="label font-semibold">Usuario</label>
          <input type="text" name="usuario" value="<?= htmlspecialchars($usuarioAEditar['usuario']) ?>"
            class="input input-bordered w-full text-[#404141]" required>
        </div>

        <div class="mb-4">
          <label class="label font-semibold">Rol</label>
          <select name="rol" class="select select-bordered w-full text-[#404141]" required>
            <option value="2" <?= $usuarioAEditar['rol_id_rol'] == 2 ? 'selected' : '' ?>>Usuario</option>
            <option value="1" <?= $usuarioAEditar['rol_id_rol'] == 1 ? 'selected' : '' ?>>Administrador</option>
          </select>
        </div>

        <div class="modal-action">
          <button type="submit"
            class="px-5 py-2 bg-[#FEDF00] text-[#404141] font-semibold rounded-lg hover:bg-yellow-400 transition">
            Guardar
          </button>
          <a href="/usuarios" class="btn">Cancelar</a>
        </div>
      </form>
    </div>
  </dialog>
<?php endif; ?>
<!-- Modal Eliminación -->
<?php if ($usuarioAEliminar): ?>
  <dialog open class="modal modal-open">
    <div class="modal-box">
      <h3 class="font-bold text-lg text-[#404141]">¿Eliminar usuario?</h3>
      <p class="py-2 text-gray-600">
        ¿Estás seguro de eliminar al usuario <strong><?= htmlspecialchars($usuarioAEliminar['usuario']) ?></strong>?
      </p>
      <form method="POST" action="/eliminar">
        <input type="hidden" name="id" value="<?= $usuarioAEliminar['idusuarios'] ?>">
        <div class="modal-action">
          <button type="submit" class="btn bg-red-600 text-white hover:bg-red-700">Eliminar</button>
          <a href="/usuarios" class="btn">Cancelar</a>
        </div>
      </form>
    </div>
  </dialog>
<?php endif; ?>