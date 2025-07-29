       <header class="navbar w-full min-h-[15vh] p-3 bg-[#404141] text-white shadow-2xl font-bold">
            <div class="navbar-start my-2">
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn btn-ghost lg:hidden text-white hover:text-[#FFD700] transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                        </svg>
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-[#2d3748] rounded-box z-10 mt-3 w-52 p-2 shadow-lg border border-gray-700">
                        <li><a href="#hero-section" class="text-white hover:bg-gray-700 hover:text-[#FFD700]">Inicio</a></li>
                        <li><a href="#services-section" class="text-white hover:bg-gray-700 hover:text-[#FFD700]">Servicios</a></li>
                        <li><a href="#about-section" class="text-white hover:bg-gray-700 hover:text-[#FFD700]">Sobre Nosotros</a></li>
                        <li><a href="#contact-section" class="text-white hover:bg-gray-700 hover:text-[#FFD700]">Contacto</a></li>
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
                    <li><a href="/fecha-juliana" class="text-white text-lg hover:text-[#FFD700] px-4 py-2 rounded-md">Validad</a></li>
                    <li><a href="/login" class="text-white text-lg hover:text-[#FFD700] px-4 py-2 rounded-md">Sobre Nosotros</a></li>
                </ul>
            </div>

            <div class="navbar-end gap-3 hidden lg:flex items-center">
                <div class="flex items-center gap-2 text-white font-semibold cursor-pointer hover:text-yellow-400 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                    </svg>
                    <span>Usuario</span>
                </div>
            </div>
        </header>
        <div class="flex flex-1">
            <aside class="bg-[#404141] text-white w-64 p-6 space-y-6">
                <h1 class="text-2xl font-bold border-b pb-4"></h1>
                <nav class="flex flex-col gap-4 text-lg">
                    <a href="/catalogo" class="flex items-center gap-3 hover:text-yellow-400 transition">📦 <span>Catálogo</span></a>
                    <a href="/vida-util" class="flex items-center gap-3 hover:text-yellow-400 transition">⏳ <span>Vida Útil</span></a>
                    <a href="/usuarios" class="flex items-center gap-3 hover:text-yellow-400 transition">👥 <span>Usuarios</span></a>
                    <a href="/fecha" class="flex items-center gap-3 hover:text-yellow-400 transition">⚙️ <span>Fechas</span></a>
                </nav>
            </aside>
            <main class="">
                <?php if (isset($view_path)) require_once $view_path; ?>
            </main>
        </div>
    </div>
</body>
</html>