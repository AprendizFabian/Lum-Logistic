<div class="flex justify-between items-center mb-10 flex-wrap gap-4 px-6 pt-6">
  <h1 class="text-3xl font-bold flex items-center gap-3 text-[#404141]">
    <i class="fas fa-users text-[#FEDF00] text-4xl"></i> <?= htmlspecialchars($title) ?>
  </h1>

  <button type="button" onclick="document.getElementById('agregarUsuarioModal').showModal()"
    class="group flex items-center gap-2 bg-[#404141] hover:bg-[#2f2f2f] text-[#FEDF00] border-0 rounded-full shadow-md px-4 py-2 transition-all duration-300 overflow-hidden w-12 hover:w-60">
    <i class="fas fa-user-plus text-lg"></i>
    <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">Agregar Usuario o
      Tienda</span>
  </button>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 px-6">
  <?php foreach ($membersPaginated['items'] as $member): ?>
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
  <?php endforeach; ?>
</div>

<div class="flex justify-center gap-2 my-8">
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
</div>

<dialog class="modal" id="agregarUsuarioModal">
  <div class="modal-box bg-white text-[#404141] rounded-2xl shadow-xl p-6 w-full max-w-2xl">
    <div class="flex items-center gap-3 mb-4">
      <i class="fas fa-user-plus text-[#FEDF00] text-xl"></i>
      <h3 class="font-bold text-2xl">Agregar Usuario o Tienda</h3>
    </div>

    <form method="POST" id="agregarForm" action="/users/addMember" class="space-y-4">
      <div id="userFields" class="space-y-4">
        <div>
          <label class="label font-semibold"><i class="fas fa-user"></i>Usuario</label>
          <input type="text" name="username" placeholder="Nombre de usuario"
            class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]">
        </div>

        <div>
          <label class="label font-semibold"><i class="fas fa-envelope"></i>Email</label>
          <input type="email" name="email" placeholder="correo@ejemplo.com"
            class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]">
        </div>

        <div>
          <label class="label font-semibold"><i class="fas fa-lock"></i>Contraseña</label>
          <input type="password" name="password" placeholder="********"
            class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]">
        </div>
      </div>

      <div id="storeFields" class="hidden space-y-4">
        <div>
          <label class="label font-semibold"><i class="fas fa-store"></i>Nombre Tienda</label>
          <input type="text" name="store_name" placeholder="Ej: Mi Tienda"
            class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]">
        </div>

        <div>
          <label class="label font-semibold">Ciudad</label>
          <select name="city_id" id="modalCity"
            class="select select-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]" required>
            <option value="">Seleccione una ciudad</option>
            <?php foreach ($cities as $city): ?>
              <option value="<?= htmlspecialchars($city['id_city']) ?>" name="city_id">
                <?= htmlspecialchars($city['city_name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="label font-semibold"><i class="fas fa-map-marker-alt"></i>Dirección</label>
          <input type="text" name="store_address" placeholder="Calle 123"
            class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]">
        </div>

        <div>
          <label class="label font-semibold"><i class="fas fa-envelope"></i>Email Tienda</label>
          <input type="email" name="store_email" placeholder="tienda@ejemplo.com"
            class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]">
        </div>

        <div>
          <label class="label font-semibold"><i class="fas fa-lock"></i>Contraseña</label>
          <input type="password" name="password" placeholder="********"
            class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]">
        </div>
      </div>

      <div>
        <label class="label font-semibold"><i class="fas fa-user-shield"></i>Rol</label>
        <select id="roleSelect" name="id_role"
          class="select select-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]" required>
          <option value="1">Administrador</option>
          <option value="2">Usuario</option>
          <option value="3">Tienda</option>
        </select>
      </div>

      <div class="modal-action flex justify-end gap-2">
        <button type="submit"
          class="px-5 py-2 bg-[#FEDF00] text-[#404141] font-semibold rounded-xl hover:bg-yellow-400 transition shadow-md flex items-center gap-2">
          <i class="fas fa-plus-circle"></i> Agregar
        </button>
        <a href="/users/"
          class="px-5 py-2 bg-gray-200 text-[#404141] font-semibold rounded-xl hover:bg-gray-300 transition shadow-md flex items-center gap-2">
          <i class="fas fa-times"></i> Cancelar
        </a>
      </div>
    </form>
  </div>
</dialog>

<dialog class="modal" id="editarUsuarioModal">
  <div class="modal-box bg-white text-[#404141] rounded-2xl shadow-xl p-6 w-full max-w-2xl">
    <div class="flex items-center gap-3 mb-4">
      <i class="fas fa-user-edit text-[#FEDF00] text-xl"></i>
      <h3 id="modalTitle" class="font-bold text-2xl">Editar Usuario</h3>
    </div>

    <form method="POST" action="/users/editMember" class="space-y-4">
      <input type="hidden" name="id_user" id="modalUserId">
      <input type="hidden" name="id_store" id="modalStoreId">

      <div>
        <label class="label font-semibold">Usuario</label>
        <input type="text" name="username" id="modalUsername"
          class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]" required>
      </div>

      <div>
        <label class="label font-semibold">Email</label>
        <input type="email" name="email" id="modalEmail"
          class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]" required>
      </div>

      <div class="hidden" id="addressField">
        <label class="label font-semibold">Dirección</label>
        <input type="text" name="store_address" id="modalAddress"
          class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]">
      </div>

      <div class="hidden" id="cityField">
        <label class="label font-semibold">Ciudad</label>
        <select name="city_id" id="modalCity"
          class="select select-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]" required>
          <option value="">Seleccione una ciudad</option>
          <?php foreach ($cities as $city): ?>
            <option value="<?= htmlspecialchars($city['id_city']) ?>" <?= isset($member['city_id']) && $member['city_id'] == $city['id_city'] ? 'selected' : '' ?> name="city_id">
              <?= htmlspecialchars($city['city_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="label font-semibold">Rol</label>
        <select name="id_role" id="modalRol"
          class="select select-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]">
          <option value="1">Administrador</option>
          <option value="2">Usuario</option>
          <option value="3">Tienda</option>
        </select>
      </div>

      <div class="modal-action flex justify-end gap-2">
        <button type="submit"
          class="px-5 py-2 bg-[#FEDF00] text-[#404141] font-semibold rounded-xl hover:bg-yellow-400 transition shadow-md flex items-center gap-2">
          <i class="fas fa-save"></i> Guardar
        </button>
        <button type="button" class="btn rounded-xl bg-gray-200 text-[#404141] hover:bg-gray-300"
          onclick="document.getElementById('editarUsuarioModal').close()">
          <i class="fas fa-times"></i> Cancelar
        </button>
      </div>
    </form>
  </div>
</dialog>

<script>
  function openEditModal(button) {
    const modal = document.getElementById("editarUsuarioModal");

    const id = button.dataset.id;
    const username = button.dataset.username;
    const email = button.dataset.email;
    const rol = button.dataset.rol;
    const type = button.dataset.type;
    const address = button.dataset.address;
    const city = button.dataset.city;

    document.getElementById("modalUserId").value = id;
    document.getElementById("modalUsername").value = username;
    document.getElementById("modalEmail").value = email;
    document.getElementById("modalRol").value = rol;

    if (type === "store") {
      document.getElementById("modalTitle").innerText = "Editar Tienda";
      document.getElementById("addressField").classList.remove("hidden");
      document.getElementById("cityField").classList.remove("hidden");
      document.getElementById("modalAddress").value = address;
      document.getElementById("modalCity").value = city;

      document.getElementById("modalUserId").value = "";
      document.getElementById("modalStoreId").value = id;
    } else {
      document.getElementById("modalTitle").innerText = "Editar Usuario";
      document.getElementById("addressField").classList.add("hidden");

      document.getElementById("modalUserId").value = id;
      document.getElementById("modalStoreId").value = "";
    }

    modal.showModal();
  }

  document.getElementById("roleSelect").addEventListener("change", function () {
    const roleSelect = document.getElementById('roleSelect');
    const userFields = document.getElementById("userFields");
    const storeFields = document.getElementById("storeFields");

    if (this.value === "3") {
      storeFields.classList.remove("hidden");
      userFields.classList.add("hidden");
      userFields.querySelectorAll('input').forEach(i => i.disabled = true);
      storeFields.querySelectorAll('input', 'select').forEach(i => i.disabled = false);
    } else {
      userFields.classList.remove("hidden");
      storeFields.classList.add("hidden");
      storeFields.querySelectorAll('input, select').forEach(i => i.disabled = true);
      userFields.querySelectorAll('input').forEach(i => i.disabled = false);
    }
  });

  roleSelect.dispatchEvent(new Event('change'));
</script>