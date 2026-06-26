<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>FREE TALK</title>
</head>
<body class="bg-gray-400 min-h-screen flex flex-col">

    {{-- ============================================================
        頂部 nav bar
        ============================================================
        - 左：「FREE TALK」logo，點擊回首頁
        - 右：登入狀態切換（用戶中心 / 登出  vs  登入 / 註冊）
    --}}
    <nav class="bg-white shadow">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800 tracking-wide hover:text-blue-600">
                FREE TALK
            </a>

            @if (Route::has('login'))
                <div class="flex items-center" style="gap: 8px;">
                    @auth
                        <a href="{{ url('/user_profile') }}" class="text-sm text-gray-700 hover:text-blue-600 px-2">
                            用戶中心
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-700 hover:text-red-600 px-2">
                                登出
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-blue-600 px-2">
                            登入
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-blue-600 px-2">
                                註冊
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    {{-- flex-1 讓 main 撐開高度，把 footer 推到頁面底部
         （配合 body 的 min-h-screen + flex flex-col，這就是「sticky footer」模式） --}}
    <main class="m-4 flex-1">
        @yield('main')
    </main>

    {{-- ============================================================
        底部 footer bar
        ============================================================ --}}
    <footer class="bg-gray-800 text-gray-300 text-center text-xs py-3">
        Made by Jay Fung · &copy; 2021
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
