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
                    class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]"
                    required>
            </div>

            <div>
                <label class="label font-semibold">Email</label>
                <input type="email" name="email" id="modalEmail"
                    class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]"
                    required>
            </div>

            <div class="hidden" id="addressField">
                <label class="label font-semibold">Dirección</label>
                <input type="text" name="store_address" id="modalAddress"
                    class="input input-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]">
            </div>

            <div class="hidden" id="cityField">
                <label class="label font-semibold">Ciudad</label>
                <select name="city_id" id="modalCity"
                    class="select select-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]"
                    required>
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