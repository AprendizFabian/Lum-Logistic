<div class="max-w-md mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-yellow-400">
    <div class="bg-yellow-400 p-6 flex flex-col items-center">
        <!-- Imagen de usuario predeterminada -->
        <img class="w-24 h-24 rounded-full border-4 border-white shadow-md" 
             src="https://cdn-icons-png.flaticon.com/512/149/149071.png" 
             alt="Usuario">
        <h2 class="mt-4 text-2xl font-bold text-gray-800">
            <?= $_SESSION['user']['type'] === 'user' 
                ? htmlspecialchars($usuario['username'] ?? 'Usuario') 
                : htmlspecialchars($usuario['store_name'] ?? 'Tienda') ?>
        </h2>
        <p class="text-gray-700 italic">
            <?= $_SESSION['user']['type'] === 'user' 
                ? htmlspecialchars($usuario['rol'] ?? 'Rol') 
                : 'Tienda registrada' ?>
        </p>
    </div>

    <div class="p-6 space-y-3">
        <?php if ($_SESSION['user']['type'] === 'user'): ?>
            <p class="flex items-center">
                <span class="font-semibold text-yellow-500 mr-2">📧</span> 
                <?= htmlspecialchars($usuario['email'] ?? '---') ?>
            </p>

        <?php elseif ($_SESSION['user']['type'] === 'store'): ?>
            <p class="flex items-center">
                <span class="font-semibold text-yellow-500 mr-2">📍</span> 
                <?= htmlspecialchars($usuario['store_address'] ?? '---') ?>
            </p>
            <p class="flex items-center">
                <span class="font-semibold text-yellow-500 mr-2">📧</span> 
                <?= htmlspecialchars($usuario['store_email'] ?? '---') ?>
            </p>
        <?php endif; ?>
    </div>
</div>
