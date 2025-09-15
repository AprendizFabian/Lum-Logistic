<?php if (isset($_SESSION['auth'])): ?>
    <div class="flex min-h-screen">
        <aside id="sidebar"
            class="bg-neutral text-neutral-content w-64 p-6 space-y-6 fixed top-0 left-0 bottom-0 z-50 transform transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full overflow-y-auto">
            <div class="flex items-center gap-3 mb-6">
                <img src="/images/cropped-favicon-lum-192x192.png" alt="Logo" class="w-10 h-10 rounded-md">
                <a href="/catalogo" class="text-xl font-bold hover:text-warning transition">LUM Logistic</a>
            </div>

            <!-- Menú principal -->
            <h1 class="text-2xl font-bold border-b border-gray-600 pb-4">Menú</h1>
            <nav class="menu text-lg space-y-2">
                <li><a href="/products/" class="hover:text-warning"><i class="fa-solid fa-boxes-stacked"></i> Catálogo</a>
                </li>
                <li><a href="/products/shelfLife" class="hover:text-warning"><i class="fa-solid fa-clock"></i> Vida Útil</a>
                </li>

                <?php if ($_SESSION['auth']['id_role'] == 1): ?>
                    <li><a href="/users/" class="hover:text-warning"><i class="fa-solid fa-users"></i> Usuarios</a></li>
                    <li><a href="/stock" class="hover:text-warning"><i class="fa-solid fa-upload"></i></i> Stock tiendas</a>
                    </li>
                <?php endif; ?>

                <li><a href="/dates/" class="hover:text-warning"><i class="fa-solid fa-calendar"></i> Fechas</a></li>
            </nav>

            <div class="border-t border-gray-600 pt-4">
                <details class="w-full">
                    <summary
                        class="flex items-center gap-2 px-3 py-2 cursor-pointer hover:bg-warning hover:text-black rounded-lg transition-colors">
                        <i class="fa-solid fa-user"></i>
                        <span><?= $_SESSION['auth']['username'] ?></span>
                        <i class="fa-solid fa-caret-down ml-auto"></i>
                    </summary>
                    <ul class="mt-2 pl-6 space-y-2 text-base">
                        <li>
                            <a href="/users/user" class="flex items-center gap-2 hover:text-warning transition">
                                <i class="fa-solid fa-id-badge"></i> Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a href="/auth/logout" class="flex items-center gap-2 text-error hover:text-red-400 transition">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </details>
            </div>
        </aside>
        <button id="sidebarToggle"
            class="fixed top-1/2 -translate-y-1/2 left-0 z-50 bg-neutral text-neutral-content p-2 rounded-r-lg shadow-lg hover:bg-warning hover:text-black transition block lg:hidden">
            <i id="sidebarIcon" class="fa-solid fa-arrow-right"></i>
        </button>
        <main id="mainContent" class="flex-1 p-2 lg:ml-64 transition-all duration-300 overflow-y-auto">
            <?php if (isset($view_path))
                require_once $view_path; ?>
        </main>
    </div>
<?php else: ?>

    <header class="navbar w-full min-h-[15vh] p-3 bg-[#404141] text-white shadow-2xl font-bold">
        <div class="navbar-start my-2">
            <div class="dropdown">
                <div tabindex="0" role="button"
                    class="btn btn-ghost lg:hidden text-white hover:text-[#FFD700] transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-[#2d3748] rounded-box z-10 mt-3 w-52 p-2 shadow-lg border border-gray-700">
                    <li><a href="#hero-section" class="text-white hover:bg-gray-700 hover:text-[#FFD700]">Inicio</a></li>
                    <li><a href="#services-section" class="text-white hover:bg-gray-700 hover:text-[#FFD700]">Servicios</a>
                </ul>
            </div>

            <div class="flex flex-row items-center gap-3">
                <img src="/images/cropped-favicon-lum-192x192.png" alt="LUM Logistic Logo" class="size-10 md:size-12">
                <a href="#" class="text-white text-xl md:text-2xl hover:text-[#FFD700] transition-colors">LUM Logistic</a>
            </div>
        </div>

        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1 gap-2">
                <li><a href="/" class="text-white text-lg hover:text-[#FFD700] px-4 py-2 rounded-md">Inicio</a></li>
                <li><a href="#servicios" class="text-white text-lg hover:text-[#FFD700] px-4 py-2 rounded-md">Servicios</a>
                </li>
                </li>
            </ul>
        </div>

        <div class="navbar-end gap-3 hidden lg:flex items-center">
            <div class="flex">
                <a href="/auth/login" class="bg-[#FFD700] text-black p-2.5 rounded-md">Iniciar Sesion</a>
            </div>
        </div>
    </header>

<?php endif; ?>

<script>
    const tiempoExpira = 60 * 60 * 1000;
    let timeout;

    function resetTimer() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            Swal.fire({
                icon: 'warning',
                title: 'Sesión expirada',
                text: 'Tu sesión ha expirado por inactividad, volveras a la pagina de inicio.',
                confirmButtonText: 'Aceptar',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.location.href = '/auth/logout';
            });
        }, tiempoExpira);
    }

    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll = resetTimer;
    document.onclick = resetTimer;
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarIcon = document.getElementById('sidebarIcon');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebarIcon.classList.remove('fa-arrow-left');
            sidebarIcon.classList.add('fa-arrow-right');
        } else {
            sidebarIcon.classList.remove('fa-arrow-right');
            sidebarIcon.classList.add('fa-arrow-left');
        }
    });
</script>