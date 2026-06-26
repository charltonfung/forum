@extends('layouts.article')

@section('main')
<div class="max-w-3xl mx-auto">

    {{-- 返回連結 --}}
    <div class="mb-4">
        <a href="{{ route('root') }}" class="text-gray-700 hover:text-gray-900 text-sm">
            ← 回文章列表
        </a>
    </div>

    {{-- ============================================================
        個人資料卡
        ============================================================ --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center mb-4">
            {{-- 簡單的字母頭像（取 name 第一個字） --}}
            <div class="w-16 h-16 rounded-full bg-blue-500 text-white flex items-center justify-center text-2xl font-bold mr-4">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h1>
                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
            </div>
            {{-- 編輯會員資料 → Jetstream 內建的 /user/profile --}}
            <a href="{{ url('/user/profile') }}"
               class="px-3 py-1.5 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 text-sm">
                編輯會員資料
            </a>
        </div>

        <div class="text-xs text-gray-500 pt-3 border-t border-gray-100">
            加入時間：{{ auth()->user()->created_at }}
        </div>
    </div>

    {{-- ============================================================
        個人文章列表卡
        ============================================================ --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">
                我的文章
                <span class="text-gray-500 text-sm font-normal">（共 {{ $articles->total() }} 篇）</span>
            </h2>
            <a href="{{ route('articles.create') }}"
               class="px-3 py-1.5 rounded bg-green-500 text-white text-sm hover:bg-green-600">
                + 發表文章
            </a>
        </div>

        {{-- 文章列表 --}}
        @forelse($articles as $article)
            <div class="py-4 border-b border-gray-100 last:border-b-0">
                <h3 class="font-semibold mb-1">
                    <a href="{{ route('articles.show', $article) }}"
                       class="text-gray-800 hover:text-blue-600">
                        {{ $article->title }}
                    </a>
                </h3>

                <div class="flex items-center text-xs text-gray-500 mb-2" style="gap: 12px;">
                    <span>{{ $article->created_at }}</span>
                    <span>♡ {{ $article->likes_count }}</span>
                    <span>💬 {{ $article->comments_count }}</span>
                </div>

                <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                    {{ \Illuminate\Support\Str::limit($article->content, 120) }}
                </p>

                <div class="flex items-center" style="gap: 8px;">
                    <a href="{{ route('articles.edit', $article) }}"
                       class="text-xs px-2 py-1 rounded bg-blue-500 text-white hover:bg-blue-600">
                        編輯
                    </a>
                    <form action="{{ route('articles.destroy', $article) }}" method="post"
                          onsubmit="return confirm('確定要刪除這篇文章嗎？');">
                        @csrf
                        @method('delete')
                        <button type="submit" class="text-xs px-2 py-1 rounded bg-gray-300 text-gray-700 hover:bg-gray-400">
                            刪除
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <p class="mb-3">還沒有發表過任何文章</p>
                <a href="{{ route('articles.create') }}"
                   class="inline-block px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600">
                    寫第一篇文章
                </a>
            </div>
        @endforelse

        {{-- 分頁（永遠顯示，含「共 N 筆」+ 上下頁按鈕） --}}
        @include('partials._pagination', ['paginator' => $articles])
    </div>

</div>
@endsection
