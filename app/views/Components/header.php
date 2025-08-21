<?php
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}

$nombre_usuario = htmlspecialchars($_SESSION['user']['username'] ?? 'Usuario');
$rol = $_SESSION['user']['id_role'] ?? 0;
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside id="sidebar"
        class="bg-neutral text-neutral-content w-64 p-6 space-y-6 fixed top-0 left-0 bottom-0 z-50 transform transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full overflow-y-auto">

        <!-- Logo -->
        <div class="flex items-center gap-3 mb-6">
            <img src="/images/cropped-favicon-lum-192x192.png" alt="Logo" class="w-10 h-10 rounded-md">
            <a href="/catalogo" class="text-xl font-bold hover:text-warning transition">LUM Logistic</a>
        </div>

        <!-- Menú principal -->
        <h1 class="text-2xl font-bold border-b border-gray-600 pb-4">Menú</h1>
        <nav class="menu text-lg space-y-2">
            <li><a href="/catalogo" class="hover:text-warning"><i class="fa-solid fa-boxes-stacked"></i> Catálogo</a>
            </li>
            <li><a href="/vida-util" class="hover:text-warning"><i class="fa-solid fa-clock"></i> Vida Útil</a>
            </li>

            <?php if ($rol == 1): ?>
                <li><a href="/usuarios" class="hover:text-warning"><i class="fa-solid fa-users"></i> Usuarios</a></li>
            <?php endif; ?>

            <li><a href="/fecha" class="hover:text-warning"><i class="fa-solid fa-calendar"></i> Fechas</a></li>
            <li><a href="/Masivo" class="hover:text-warning"><i class="fa-solid fa-check"></i> Validador</a></li>
        </nav>

        <!-- Menú usuario -->
        <div class="border-t border-gray-600 pt-4">
            <details class="w-full">
                <summary
                    class="flex items-center gap-2 px-3 py-2 cursor-pointer hover:bg-warning hover:text-black rounded-lg transition-colors">
                    <i class="fa-solid fa-user"></i>
                    <span><?= $nombre_usuario ?></span>
                    <i class="fa-solid fa-caret-down ml-auto"></i>
                </summary>
                <ul class="mt-2 pl-6 space-y-2 text-base">
                    <li>
                        <a href="/usuario" class="flex items-center gap-2 hover:text-warning transition">
                            <i class="fa-solid fa-id-badge"></i> Mi Perfil
                        </a>
                    </li>
                    <li>
                        <a href="/logout" class="flex items-center gap-2 text-error hover:text-red-400 transition">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </details>
        </div>
    </aside>

    <!-- Botón tipo pestaña -->
    <button id="sidebarToggle"
        class="fixed top-1/2 -translate-y-1/2 left-0 z-50 bg-neutral text-neutral-content p-2 rounded-r-lg shadow-lg hover:bg-warning hover:text-black transition block lg:hidden">
        <i id="sidebarIcon" class="fa-solid fa-arrow-right"></i>
    </button>
    <!-- Contenido -->
    <main id="mainContent" class="flex-1 p-2 lg:ml-64 transition-all duration-300 overflow-y-auto">
        <?php if (isset($view_path))
            require_once $view_path; ?>
    </main>
</div>

<!-- JS toggle -->
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
        window.location.href = '/logout';
      });
    }, tiempoExpira);
  }

  // 🔹 Reinicia el contador con actividad del usuario
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