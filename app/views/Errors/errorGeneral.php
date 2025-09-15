<section class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-lg rounded-2xl p-8 max-w-lg text-center">
        <h1 class="text-6xl font-extrabold text-red-500 mb-4">¡Oops!</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-2"><?= $title ?? 'Error' ?></h2>
        <p class="text-gray-600 mb-6">
            <?= htmlspecialchars($message ?? 'Algo salió mal, por favor intenta más tarde.') ?>
        </p>
        <a href="/" 
           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-xl shadow hover:bg-blue-700 transition">
           Volver al inicio
        </a>
    </div>
</section>
