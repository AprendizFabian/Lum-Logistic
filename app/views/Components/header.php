<header
    class="navbar w-full  min-h-[15vh]  p-3 bg-[#404141] text-[#ffffff] shadow-2xl font-bold ">
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
                    href="/fecha-juliana">Validador</a></li>
            <li><a class="text-[#ffffff] text-lg hover:text-[#FFD700] transition-colors duration-300 px-4 py-2 rounded-md"
                    href="/login">Sobre Nosotros</a></li>
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