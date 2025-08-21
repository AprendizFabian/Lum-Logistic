<!-- Encabezado con título y botón -->
<div class="flex justify-between items-center mb-8 flex-wrap gap-4 px-6 pt-4">
  <h1 class="text-3xl font-bold flex items-center gap-2">
    <i class="fas fa-users text-[#FEDF00]"></i> Lista de Usuarios
  </h1>
<a href="/usuarios?agregar=1"
     class="bg-[#404141] hover:bg-[#2f2f2f] text-[#FEDF00] font-semibold px-4 py-2 rounded-xl shadow-lg flex items-center gap-2 transition">
    <i class="fas fa-user-plus"></i> Agregar Usuario o tienda
  </a>
</div>

<!-- Grid combinado -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-6">

  <!-- Usuarios -->
  <?php foreach ($usuariosPaginados as $usuario): ?>
    <div
      class="bg-white border border-[#e5e5e5] rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition duration-300">
      <div class="bg-[#FEDF00] h-20 flex items-center justify-center relative">
        <img class="w-20 h-20 rounded-full border-4 border-white shadow-md absolute -bottom-10 object-cover"
          src="https://static.vecteezy.com/system/resources/previews/008/302/557/non_2x/eps10-yellow-user-solid-icon-or-logo-in-simple-flat-trendy-modern-style-isolated-on-white-background-free-vector.jpg"
          alt="Usuario">
      </div>
      <div class="pt-12 pb-4 px-4 text-center">
        <h3 class="text-lg font-bold text-[#404141]"><?= htmlspecialchars($usuario['username']) ?></h3>
        <p class="text-sm text-gray-600">Rol: <?= htmlspecialchars($usuario['rol']) ?></p>
      </div>
      <div class="flex justify-center gap-3 pb-4">
        <a href="/usuarios?editar=<?= $usuario['id_user'] ?>"
          class="bg-[#404141] hover:bg-[#2f2f2f] text-[#FEDF00] px-3 py-1 rounded-md text-sm shadow-sm transition flex items-center gap-1">
          <i class="fas fa-edit"></i> Editar
        </a>
        <a href="/usuarios?eliminar=<?= $usuario['id_user'] ?>"
          class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm shadow-sm transition flex items-center gap-1">
          <i class="fas fa-trash"></i> Eliminar
        </a>
      </div>
    </div>
  <?php endforeach; ?>

  <!-- Tiendas -->
  <?php foreach (($tiendasPaginadas ?? []) as $tienda): ?>
    <div
      class="bg-white border border-[#e5e5e5] rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition duration-300">
      <div class="bg-[#FEDF00] h-20 flex items-center justify-center relative">
        <img class="w-20 h-20 rounded-full border-4 border-white shadow-md absolute -bottom-10 object-cover"
          src="https://cdn-icons-png.flaticon.com/512/891/891462.png"
          alt="Tienda">
      </div>
      <div class="pt-12 pb-4 px-4 text-center">
        <h3 class="text-lg font-bold text-[#404141]"><?= htmlspecialchars($tienda['store_name'] ?? 'Sin nombre') ?></h3>
        <p class="text-sm text-gray-600">Rol: <?= htmlspecialchars($tienda['rol'] ?? 'Sin correo') ?></p>
      </div>
      <div class="flex justify-center gap-3 pb-4">
        <a href="/tiendas?editar=<?= $tienda['id_store'] ?>"
          class="bg-[#404141] hover:bg-[#2f2f2f] text-[#FEDF00] px-3 py-1 rounded-md text-sm shadow-sm transition flex items-center gap-1">
          <i class="fas fa-edit"></i> Editar
        </a>
        <a href="/tiendas?eliminar=<?= $tienda['id_store'] ?>"
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
        <!-- ID real en DB -->
        <input type="hidden" name="id_user" value="<?= $usuarioAEditar['id_user'] ?>">

        <div class="mb-4">
          <label class="label font-semibold">Usuario</label>
          <input type="text" name="username" 
                 value="<?= htmlspecialchars($usuarioAEditar['username']) ?>"
                 class="input input-bordered w-full text-[#404141]" required>
        </div>

        <div class="mb-4">
          <label class="label font-semibold">Email</label>
          <input type="email" name="email" 
                 value="<?= htmlspecialchars($usuarioAEditar['email']) ?>"
                 class="input input-bordered w-full text-[#404141]" required>
        </div>

        <div class="mb-4">
          <label class="label font-semibold">Rol</label>
          <select name="id_role" class="select select-bordered w-full text-[#404141]" required>
            <option value="2" <?= $usuarioAEditar['id_role'] == 2 ? 'selected' : '' ?>>Usuario</option>
            <option value="1" <?= $usuarioAEditar['id_role'] == 1 ? 'selected' : '' ?>>Administrador</option>
            <option value="1" <?= $usuarioAEditar['id_role'] == 3 ? 'selected' : '' ?>>Tienda</option>
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
        ¿Estás seguro de eliminar al usuario 
        <strong><?= htmlspecialchars($usuarioAEliminar['username']) ?></strong>?
      </p>
      <form method="POST" action="/eliminar">
        <!-- ✅ ahora id_user -->
        <input type="hidden" name="id_user" value="<?= $usuarioAEliminar['id_user'] ?>">
        <div class="modal-action">
          <button type="submit" class="btn bg-red-600 text-white hover:bg-red-700">Eliminar</button>
          <a href="/usuarios" class="btn">Cancelar</a>
        </div>
      </form>
    </div>
  </dialog>
<?php endif; ?>
<!-- Modal Agregar Usuario o Tienda -->
<?php if (isset($_GET['agregar'])): ?>
<dialog open class="modal modal-open">
  <div class="modal-box bg-white text-[#404141] w-full max-w-lg">
    <h3 class="font-bold mb-4 text-2xl">Agregar Usuario o Tienda</h3>
    
    <form method="POST" id="agregarForm" action="/agregar">
      <!-- Campos para usuarios -->
      <div id="userFields">
        <div class="mb-4">
          <label class="label font-semibold">Usuario</label>
          <input type="text" name="username" placeholder="Nombre de usuario"
                 class="input input-bordered w-full text-[#404141]">
        </div>

        <div class="mb-4">
          <label class="label font-semibold">Email</label>
          <input type="email" name="email" placeholder="correo@ejemplo.com"
                 class="input input-bordered w-full text-[#404141]">
        </div>

        <div class="mb-4">
          <label class="label font-semibold">Contraseña</label>
          <input type="password" name="password" placeholder="********"
                 class="input input-bordered w-full text-[#404141]">
        </div>
      </div>

      <!-- Campos para tiendas -->
      <div id="storeFields" style="display:none;">
        <div class="mb-4">
          <label class="label font-semibold">Nombre Tienda</label>
          <input type="text" name="store_name" placeholder="Ej: Mi Tienda"
                 class="input input-bordered w-full text-[#404141]">
        </div>

        <div class="mb-4">
          <label class="label font-semibold">Dirección</label>
          <input type="text" name="store_address" placeholder="Calle 123"
                 class="input input-bordered w-full text-[#404141]">
        </div>

        <div class="mb-4">
          <label class="label font-semibold">Email Tienda</label>
          <input type="email" name="store_email" placeholder="tienda@ejemplo.com"
                 class="input input-bordered w-full text-[#404141]">
        </div>

        <div class="mb-4">
          <label class="label font-semibold">Contraseña</label>
          <input type="password" name="store_password" placeholder="********"
                 class="input input-bordered w-full text-[#404141]">
        </div>
      </div>

      <!-- Select rol -->
      <div class="mb-4">
        <label class="label font-semibold">Rol</label>
        <select id="roleSelect" name="id_role" class="select select-bordered w-full text-[#404141]" required>
          <option value="2">Usuario</option>
          <option value="1">Administrador</option>
          <option value="3">Tienda</option>
        </select>
      </div>

      <div class="modal-action flex justify-end gap-2">
        <button type="submit"
          class="px-5 py-2 bg-[#FEDF00] text-[#404141] font-semibold rounded-lg hover:bg-yellow-400 transition">
          Agregar
        </button>
        <a href="/usuarios" class="px-5 py-2 bg-gray-300 text-[#404141] font-semibold rounded-lg hover:bg-gray-400 transition">
          Cancelar
        </a>
      </div>
    </form>
  </div>
</dialog>

<script>
  const roleSelect = document.getElementById('roleSelect');
  const userFields = document.getElementById('userFields');
  const storeFields = document.getElementById('storeFields');
  const form = document.getElementById('agregarForm');

  roleSelect.addEventListener('change', () => {
    if (roleSelect.value === "3") {
      // Cambiar a formulario de tienda
      userFields.style.display = "none";
      storeFields.style.display = "block";
      form.action = "/agregarT";
    } else {
      // Formulario de usuario
      userFields.style.display = "block";
      storeFields.style.display = "none";
      form.action = "/agregar";
    }
  });
</script>
<?php endif; ?>