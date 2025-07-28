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
                </li>
                <li><a href="#about-section" class="text-white hover:bg-gray-700 hover:text-[#FFD700]">Sobre
                        Nosotros</a></li>
                <li><a href="#contact-section" class="text-white hover:bg-gray-700 hover:text-[#FFD700]">Contacto</a>
                </li>
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
            <li><a href="/fecha-juliana"
                    class="text-white text-lg hover:text-[#FFD700] px-4 py-2 rounded-md">Validador</a></li>
            <li><a href="/login" class="text-white text-lg hover:text-[#FFD700] px-4 py-2 rounded-md">Sobre Nosotros</a>
            </li>
        </ul>
    </div>

    <div class="navbar-end gap-3 hidden lg:flex items-center">
        <div class="flex items-center gap-2 text-white font-semibold cursor-pointer hover:text-yellow-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                <path
                    d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
            </svg>
            <span>Usuario</span>
        </div>
    </div>
</header>

<section class="w-full">
    <div class="hero min-h-[85vh] max-w-[1200px] mx-auto px-6 py-10">
        <div class="hero-content flex flex-col lg:flex-row-reverse items-center gap-10 lg:gap-20">
            <img src="/images/LUM-home-img-2.png" alt="Imagen logística"
                class="hidden lg:block max-w-[450px] transition-transform duration-300 hover:scale-[1.02]" />
            <div class="flex flex-col gap-6 text-center lg:text-left">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-neutral leading-tight">
                    LUM <span class="text-[#FFD700]">Logistic</span>
                </h1>
                <p class="text-base sm:text-lg text-gray-600 leading-relaxed max-w-2xl">
                    Somos el aliado logístico para su compañía. Optimizamos costos y mejoramos eficiencias mediante
                    modelos
                    colaborativos en nuestros centros de acopio. Conectamos múltiples clientes y canales a través de
                    tecnología
                    desarrollada in-house.
                </p>
                <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-full bg-[#FFD700] text-black text-sm font-medium shadow hover:scale-105 transition">
                        Somos LUM
                    </span>
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-full bg-[#FFD700] text-black text-sm font-medium shadow hover:scale-105 transition">
                        Innovación Logística
                    </span>
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-full border border-[#FFD700] text-[#FFD700] text-sm font-medium hover:bg-[#FFD700] hover:text-black transition">
                        Tecnología In-house
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="w-full bg-base-100 py-10 px-4">
    <div class="max-w-[1200px] mx-auto space-y-16">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-neutral mb-10">
            Nuestros <span class="text-warning">Servicios</span>
        </h2>
        <div class="flex flex-col lg:flex-row items-center gap-8 group">
            <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                alt="Gestión de Inventario"
                class="w-full lg:w-1/2 h-64 object-cover rounded-2xl shadow-md group-hover:scale-[1.02] transition-transform duration-300" />
            <div class="lg:w-1/2 space-y-4">
                <h3 class="text-2xl font-semibold text-neutral">Gestión de Inventario</h3>
                <p class="text-base text-gray-600">
                    Control y organización eficiente de tus productos en tiempo real. Optimiza tu logística y reduce
                    errores operativos.
                </p>
                <div class="flex gap-2">
                    <span class="badge badge-outline badge-warning">Logística</span>
                    <span class="badge badge-outline">Almacenamiento</span>
                </div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row-reverse items-center gap-8 group">
            <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                alt="Distribución Nacional"
                class="w-full lg:w-1/2 h-64 object-cover rounded-2xl shadow-md group-hover:scale-[1.02] transition-transform duration-300" />
            <div class="lg:w-1/2 space-y-4">
                <h3 class="text-2xl font-semibold text-neutral">Distribución Nacional</h3>
                <p class="text-base text-gray-600">
                    Entregas seguras y a tiempo en todo el país. Conectamos tu producto con tu cliente sin
                    intermediarios.
                </p>
                <div class="flex gap-2">
                    <span class="badge badge-outline badge-warning">Transporte</span>
                    <span class="badge badge-outline">Colombia</span>
                </div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row items-center gap-8 group">
            <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                alt="Tecnología In-house"
                class="w-full lg:w-1/2 h-64 object-cover rounded-2xl shadow-md group-hover:scale-[1.02] transition-transform duration-300" />
            <div class="lg:w-1/2 space-y-4">
                <h3 class="text-2xl font-semibold text-neutral">Tecnología In-house</h3>
                <p class="text-base text-gray-600">
                    Creamos plataformas digitales a medida para ayudarte a monitorear y tomar decisiones en tiempo real.
                </p>
                <div class="flex gap-2">
                    <span class="badge badge-outline badge-warning">Software</span>
                    <span class="badge badge-outline">Automatización</span>
                </div>
            </div>
        </div>
    </div>
</section>