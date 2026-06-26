@extends('layouts.article')

@section('main')
<div class="max-w-3xl mx-auto">

    {{-- ============================================================
        站名 Hero
        ============================================================ --}}
    <div class="text-center mb-6">
        <h1 class="text-5xl font-bold text-gray-800 tracking-wider">FREE TALK</h1>
        <p class="text-gray-600 mt-1 text-sm">自由暢談區</p>
    </div>

    {{-- Session flash 訊息 --}}
    @if(session()->has('notice'))
        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session()->get('notice') }}
        </div>
    @endif

    {{-- ============================================================
        Header + 文章列表（共用一張大白卡）
        ============================================================ --}}
    <div class="bg-white rounded-lg shadow">

        {{-- Header --}}
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">
                最新文章
                <span class="text-gray-500 text-sm font-normal">（共 {{ $articles->total() }} 篇）</span>
            </h2>
            @auth
                <a href="{{ route('articles.create') }}"
                   class="px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600 text-sm">
                    + 發表文章
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600 text-sm">
                    登入後發文
                </a>
            @endauth
        </div>

        {{-- 文章列表（每篇是一個 row，用底線分隔） --}}
        @forelse($articles as $article)
            <div class="p-4 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">
                <h3 class="text-lg font-semibold mb-1">
                    <a href="{{ route('articles.show', $article) }}"
                       class="text-gray-800 hover:text-blue-600">
                        {{ $article->title }}
                    </a>
                </h3>

                <div class="flex items-center text-xs text-gray-500 mb-2 flex-wrap" style="gap: 10px;">
                    <span>{{ $article->user->name }}</span>
                    <span>·</span>
                    <span>{{ $article->created_at }}</span>
                    <span class="text-red-500">♡ {{ $article->likes_count }}</span>
                    <span>💬 {{ $article->comments_count }}</span>
                </div>

                <p class="text-sm text-gray-600 mb-3 leading-relaxed">
                    {{ \Illuminate\Support\Str::limit($article->content, 120) }}
                </p>

                {{-- 只有作者才看得到編輯 / 刪除 --}}
                @auth
                    @if(auth()->id() === $article->user_id)
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
                    @endif
                @endauth
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">
                <p class="mb-3">還沒有任何文章，搶第一篇吧！</p>
                @auth
                    <a href="{{ route('articles.create') }}"
                       class="inline-block px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600">
                        寫第一篇文章
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-block px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">
                        登入後發文
                    </a>
                @endauth
            </div>
        @endforelse
    </div>

    {{-- 分頁 --}}
    @if($articles->hasPages())
        <div class="mt-4">
            {{ $articles->links() }}
        </div>
    @endif

</div>
@endsection
