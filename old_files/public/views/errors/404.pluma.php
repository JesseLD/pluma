<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>404 Not Found</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-center flex items-center justify-center min-h-screen p-6">
  <div class="max-w-xl">
    <h1 class="text-6xl font-bold text-red-500 mb-4">404</h1>
    <p class="text-lg text-gray-700">
      {{ message ?? 'The page you are looking for could not be found.' }}
    </p>
    <a href="/" class="mt-6 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      Back to Home
    </a>

    {{ '<script>alert("XSS")</script>' }}

  </div>
</body>

</html>