<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restoran RasaNyaman')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

    <nav class="bg-white shadow-sm h-16 flex items-center justify-between px-6 fixed top-0 w-full z-20">

        <div class="flex items-center gap-4">
            <div class="font-black text-xl tracking-wider text-gray-900">
                RESTORAN <span class="text-blue-600">RasaNyaman</span>
            </div>
            <div class="h-6 w-px bg-gray-300 mx-2"></div>
            <h1 class="text-lg font-bold text-gray-700">@yield('header', 'Halaman')</h1>
        </div>

        <div class="flex items-center gap-4">

            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase 
                {{ Auth::user()->role == 'chef' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                {{ Auth::user()->role }}
            </span>

            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ date('d M Y') }}</p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Logout">
                    <i class="ph ph-sign-out text-2xl"></i>
                </button>
            </form>
        </div>
    </nav>

    <main class="flex-1 mt-16 p-6 overflow-y-auto">

        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 1500,
                showConfirmButton: false
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
            });
        </script>
        @endif

        @yield('content')
    </main>

</body>

</html>