<header
    class="navbar w-full max-w-[1200px] min-h-[15vh] mx-auto my-2 p-3 bg-[#404141] text-[#ffffff] shadow-xl font-bold rounded-xl">
    <div class="navbar-start my-2">
        <div class="dropdown">
            <div tabindex="0" role="button"
                class="btn btn-ghost lg:hidden text-[#ffffff] hover:text-[#FFD700] transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
            <ul tabindex="0"
                class="menu menu-sm dropdown-content bg-[#2d3748] rounded-box z-10 mt-3 w-52 p-2 shadow-lg border border-gray-700">
                <li><a class="text-[#ffffff] hover:bg-gray-700 hover:text-[#FFD700] transition-colors duration-300"
                        href="#hero-section">Inicio</a></li>
                <li><a class="text-[#ffffff] hover:bg-gray-700 hover:text-[#FFD700] transition-colors duration-300"
                        href="#services-section">Servicios</a></li>
                <li><a class="text-[#ffffff] hover:bg-gray-700 hover:text-[#FFD700] transition-colors duration-300"
                        href="#about-section">Sobre Nosotros</a></li>
                <li><a class="text-[#ffffff] hover:bg-gray-700 hover:text-[#FFD700] transition-colors duration-300"
                        href="#contact-section">Contacto</a></li>
                <li class="mt-2"><a
                        class="btn btn-block bg-[#FFD700] text-[#1a202c] font-bold hover:bg-yellow-500 transition-colors duration-300"
                        href="#">Iniciar Sesión</a></li>
                <li class="mt-2"><a
                        class="btn btn-block border border-[#FFD700] text-[#FFD700] hover:bg-[#FFD700] hover:text-[#1a202c] transition-all duration-300"
                        href="#">Registrarse</a></li>
            </ul>
        </div>

        <div class="flex flex-row items-center gap-3">
            <img src="images/cropped-favicon-lum-192x192.png" alt="LUM Logistic Logo" class="size-10 md:size-12">
            <a href="#"
                class="text-[#ffffff] text-xl md:text-2xl hover:text-[#FFD700] transition-colors duration-300 tracking-wide">
                LUM Logistic
            </a>
        </div>
    </div>

    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 gap-2">
            <li><a class="text-[#ffffff] text-lg hover:text-[#FFD700] transition-colors duration-300 px-4 py-2 rounded-md"
                    href="/">Inicio</a></li>
            <li><a class="text-[#ffffff] text-lg hover:text-[#FFD700] transition-colors duration-300 px-4 py-2 rounded-md"
                    href="#">Servicios</a></li>
            <li><a class="text-[#ffffff] text-lg hover:text-[#FFD700] transition-colors duration-300 px-4 py-2 rounded-md"
                    href="#">Sobre Nosotros</a></li>
        </ul>
    </div>

    <div class="navbar-end gap-3 hidden lg:flex">
        <a class="btn w-full max-w-[130px] bg-[#FFD700] text-[#1a202c] font-bold hover:bg-yellow-500 border-none transition-colors duration-300 shadow-md hover:shadow-lg"
            href="/login">
            Iniciar Sesión
        </a>
        <a class="btn w-full max-w-[130px] border border-[#FFD700] bg-transparent text-[#FFD700] font-bold hover:bg-[#FFD700] hover:text-[#1a202c] transition-all duration-300 shadow-md hover:shadow-lg"
            href="/fecha-juliana">
            Registrarse
        </a>
    </div>
</header>