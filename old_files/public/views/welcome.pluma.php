<?php


extend('app');
section('content');
set_title('Welcome');

?>

<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 text-center px-4">
    <img src="<?= asset('images/pluma.svg') ?>" alt="Pluma Logo" class="w-24 h-auto mb-6 pointer-events-none">

    <h1 class="text-3xl font-semibold text-gray-800">Welcome to <span class="text-blue-600">Pluma Framework</span></h1>

    <p class="text-gray-600 mt-2 text-lg">
        A simple, elegant mini PHP framework for small and fast web apps.
    </p>

    <a href="/docs" class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
        📘 Read the Documentation
    </a>
</div>
<?php endSection(); ?>