<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SDN Ketintang II</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F7F7F7] min-h-screen flex flex-col">

    {{-- Topbar --}}
    <div class="bg-primary px-8 py-4 flex items-center gap-3">
        <span class="text-white font-bold text-lg">Classroom</span>
        <span class="text-primary-light text-xs">•</span>
        <span class="text-primary-light text-sm">SDN Ketintang Surabaya</span>
    </div>

    {{-- Content --}}
    <div class="flex-1 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-sm">

            {{-- Header Card --}}
            <div class="bg-primary rounded-2xl shadow-md px-8 py-8 text-center mb-4">
                <div class="text-4xl mb-3">👋</div>
                <h1 class="text-white text-2xl font-bold">Halo, Selamat Datang!</h1>
                <p class="text-primary-light text-sm mt-1">Masuk untuk mulai belajar hari ini</p>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-2xl shadow-md px-8 py-8">

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-5">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-800 mb-2">Email/Username</label>
                        <input
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            placeholder="Masukkan Email atau Username kamu ..."
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-800 mb-2">Kata Sandi</label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan Kata Sandi kamu ..."
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-xl transition-colors duration-200 text-sm tracking-wide"
                    >
                        Masuk Sekarang
                    </button>
                </form>

                <p class="text-center text-xs text-gray-400 mt-5">
                    <span class="text-primary underline cursor-default">Lupa kata sandi?</span> Hubungi gurumu ya!
                </p>
            </div>

        </div>
    </div>

</body>
</html>
