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
                        class="select select-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]">
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
                    class="select select-bordered w-full text-[#404141] rounded-xl focus:ring-2 focus:ring-[#FEDF00]"
                    required>
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