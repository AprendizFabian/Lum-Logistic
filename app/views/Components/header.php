<?php
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}

$nombre_usuario = htmlspecialchars($_SESSION['user']['usuario'] ?? 'Usuario');
$rol = $_SESSION['user']['rol_id_rol'] ?? 0;
?>

<header
    class="fixed top-0 left-0 right-0 z-50 w-full h-[15vh] bg-[#404141] text-white flex items-center justify-between px-4 shadow-2xl">
    <div class="flex items-center gap-4">
        <button id="toggleSidebar" class="lg:hidden text-white hover:text-[#FFD700] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
            </svg>
        </button>
        <div class="flex items-center gap-3">
            <img src="/images/cropped-favicon-lum-192x192.png" alt="Logo" class="size-10 md:size-12">
            <a href="/catalogo" class="text-white text-xl md:text-2xl hover:text-[#FFD700]">LUM Logistic</a>
        </div>
    </div>

    <!-- Usuario con menú -->
    <!-- Usuario con menú -->
    <div class="relative block">
        <button id="userMenuButton" class="flex items-center gap-2 hover:text-yellow-400 focus:outline-none">
            <i class="fa-solid fa-user"></i>
            <span class="hidden sm:inline"><?= $nombre_usuario ?></span>
        </button>
        <div id="userDropdown" class="absolute right-0 mt-2 w-40 bg-white text-black rounded-md shadow-lg hidden">
            <a href="/usuario" class="block px-4 py-2 hover:bg-gray-200"><i class="fa-solid fa-user"></i> Ver
                perfil</a>
            <a href="/logout" class="block px-4 py-2 hover:bg-gray-200"><i
                    class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</a>
        </div>
    </div>

</header>

<div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

<div class="pt-[15vh] flex min-h-screen">
    <aside id="sidebar"
        class="bg-[#404141] text-white w-64 p-6 space-y-6 fixed top-[15vh] left-0 bottom-0 z-50 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out">
        <h1 class="text-2xl font-bold border-b pb-4">Menú</h1>
        <nav class="flex flex-col gap-4 text-lg">
            <a href="/catalogo" class="flex items-center gap-3 hover:text-yellow-400"><i
                    class="fa-solid fa-boxes-stacked"></i></i><span>Catálogo</span></a>
            <a href="/vida-util" class="flex items-center gap-3 hover:text-yellow-400"><i class="fa-solid fa-clock"></i>
                <span>Vida Útil</span></a>

            <?php if ($rol == 1): ?>
                <a href="/usuarios" class="flex items-center gap-3 hover:text-yellow-400"><i
                        class="fa-solid fa-users"></i><span>Usuarios</span></a>
            <?php endif; ?>

            <a href="/fecha" class="flex items-center gap-3 hover:text-yellow-400"><i
                    class="fa-solid fa-calendar"></i><span>Fechas</span></a>
            <a href="/fecha-juliana" class="flex items-center gap-3 hover:text-yellow-400"><i
                    class="fa-solid fa-check"></i><span>Validador</span></a>
        </nav>
    </aside>

    <!-- CONTENIDO -->
    <main class="flex-5 px-auto py-1">
        <?php if (isset($view_path))
            require_once $view_path; ?>
    </main>
</div>

<!-- ALERTA + CIERRE AUTOMÁTICO POR INACTIVIDAD -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let tiempoInactividad = 60 * 60 * 1000; // 1 minuto
    let temporizador;

    function cerrarSesionPorInactividad() {
        Swal.fire({
            title: 'Sesión cerrada',
            text: 'Tu sesión ha expirado por inactividad.',
            icon: 'info',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '/logout'; // Ruta de cierre de sesión
        });
    }

    function reiniciarTemporizador() {
        clearTimeout(temporizador);
        temporizador = setTimeout(cerrarSesionPorInactividad, tiempoInactividad);
    }

    // Detecta actividad del usuario
    ['click', 'mousemove', 'keydown', 'scroll'].forEach(evt => {
        window.addEventListener(evt, reiniciarTemporizador);
    });

    reiniciarTemporizador(); // Iniciar al cargar
</script>
<script>
    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const userMenuButton = document.getElementById('userMenuButton');
    const userDropdown = document.getElementById('userDropdown');

    toggleSidebar.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    });

    backdrop.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
    });

    userMenuButton.addEventListener('click', () => {
        userDropdown.classList.toggle('hidden');
    });

    window.addEventListener('click', function (e) {
        if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.add('hidden');
        }
    });
</script>