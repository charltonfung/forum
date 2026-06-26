<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>FREE TALK</title>
    <style>
        /* Alpine.js 還沒載入完成前，先藏起 x-cloak 標記的元素，避免下拉選單閃一下 */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-400 min-h-screen flex flex-col">

    {{-- ============================================================
        頂部 nav bar
        ============================================================
        - 左：「FREE TALK」logo，點擊回首頁
        - 右：
            未登入 → 登入 / 註冊
            已登入 → 使用者名稱 + 頭像（點擊展開下拉，含用戶中心 / 編輯會員資料 / 登出）
    --}}
    <nav class="bg-white shadow">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800 tracking-wide hover:text-blue-600">
                FREE TALK
            </a>

            @if (Route::has('login'))
                @auth
                    {{-- ============================================================
                        使用者下拉選單（Alpine.js 控制 open 狀態）
                        ============================================================
                        x-data            宣告元件區域變數（這裡只有 open）
                        @click.away       點選單外面就關掉
                        x-show            根據 open true/false 顯示
                        x-cloak           頁面 load 完之前先藏起（搭配上面的 CSS）
                        x-transition      開關動畫
                    --}}
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open"
                                class="flex items-center text-sm text-gray-700 hover:text-blue-600 focus:outline-none"
                                style="gap: 6px;">
                            {{-- 字母頭像 --}}
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-500 text-white text-xs font-bold">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </span>
                            <span class="font-medium">{{ auth()->user()->name }}</span>
                            {{-- 下拉箭頭 --}}
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        {{-- 下拉選單面板 --}}
                        <div x-show="open"
                             x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 py-1 z-50">

                            <a href="{{ url('/user_profile') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                用戶中心
                            </a>
                            <a href="{{ url('/user/profile') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                編輯會員資料
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    登出
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center" style="gap: 8px;">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-blue-600 px-2">
                            登入
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-blue-600 px-2">
                                註冊
                            </a>
                        @endif
                    </div>
                @endauth
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
