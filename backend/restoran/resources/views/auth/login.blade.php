<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Staff - Resto App</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">

        <div class="bg-blue-600 p-8 text-center">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                <i class="ph ph-user-circle text-5xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white tracking-wide">STAFF LOGIN</h2>
            <p class="text-blue-100 text-sm mt-1">Masuk untuk mengakses sistem</p>
        </div>

        <div class="p-8 pt-10">
            @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
                <div class="flex items-start">
                    <i class="ph ph-warning-circle text-red-500 text-xl mr-2"></i>
                    <div>
                        <p class="text-sm text-red-700 font-bold">Gagal Masuk</p>
                        <ul class="text-xs text-red-600 list-disc list-inside mt-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-envelope text-gray-400 text-xl"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-gray-700 font-medium placeholder-gray-400"
                            placeholder="nama@resto.com">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-lock-key text-gray-400 text-xl"></i>
                        </div>
                        <input type="password" name="password" required
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-gray-700 font-medium placeholder-gray-400"
                            placeholder="••••••">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    MASUK SISTEM <i class="ph ph-arrow-right font-bold"></i>
                </button>
            </form>

            <div class="mt-8 text-center border-t pt-6">
                <a href="{{ route('order.index') }}" class="text-sm text-gray-400 hover:text-blue-600 font-medium flex items-center justify-center gap-2 transition">
                    <i class="ph ph-arrow-left"></i> Kembali ke Menu Pelanggan
                </a>
            </div>
        </div>
    </div>

</body>

</html>