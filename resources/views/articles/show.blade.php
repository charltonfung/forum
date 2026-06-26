@extends('layouts.article')

@section('main')
<div class="max-w-3xl mx-auto">

    {{-- 返回連結 --}}
    <div class="mb-4">
        <a href="{{ route('root') }}" class="text-gray-700 hover:text-gray-900 text-sm">
            ← 回文章列表
        </a>
    </div>

    {{-- Session flash 訊息 --}}
    @if(session()->has('notice'))
        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session()->get('notice') }}
        </div>
    @endif

    {{-- ============================================================
        文章主體卡片
        ============================================================ --}}
    <article class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $article->title }}</h1>
        <div class="text-sm text-gray-500 mb-6 pb-4 border-b border-gray-200">
            <span>{{ $article->user->name }}</span>
            <span class="mx-1">·</span>
            <span>{{ $article->created_at }}</span>
        </div>

        <div class="text-gray-800 leading-relaxed whitespace-pre-wrap text-base">{{ $article->content }}</div>

        {{-- 文章底部操作列 --}}
        <div class="mt-6 pt-4 border-t border-gray-200 flex items-center" style="gap: 8px;">
            @auth
                @if($article->likedBy(auth()->user()))
                    <form action="{{ route('articles.unlike', $article) }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600 text-sm">
                            ♥ 已讚 {{ $article->likes()->count() }}
                        </button>
                    </form>
                @else
                    <form action="{{ route('articles.like', $article) }}" method="post">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded bg-white border border-red-400 text-red-500 hover:bg-red-50 text-sm">
                            ♡ 讚 {{ $article->likes()->count() }}
                        </button>
                    </form>
                @endif
            @else
                <span class="px-4 py-2 rounded bg-gray-100 text-gray-500 text-sm">
                    ♡ 讚 {{ $article->likes()->count() }}
                </span>
            @endauth

            {{-- 作者才能編輯 / 刪除 --}}
            @auth
                @if(auth()->id() === $article->user_id)
                    <a href="{{ route('articles.edit', $article) }}"
                       class="ml-auto px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600 text-sm">
                        編輯
                    </a>
                    <form action="{{ route('articles.destroy', $article) }}" method="post"
                          onsubmit="return confirm('確定要刪除這篇文章嗎？');">
                        @csrf
                        @method('delete')
                        <button type="submit" class="px-4 py-2 rounded bg-gray-300 text-gray-700 hover:bg-gray-400 text-sm">
                            刪除
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    </article>

    {{-- ============================================================
        留言區卡片
        ============================================================ --}}
    <section class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
            留言
            <span class="text-gray-500 text-sm font-normal">（{{ $article->comments->count() }}）</span>
        </h2>

        {{-- 留言列表 --}}
        @forelse($article->comments as $comment)
            <div class="py-4 border-b border-gray-100 last:border-b-0">
                {{-- 留言頭部：作者 / 時間 / 刪除 --}}
                <div class="flex items-center mb-2">
                    <span class="font-semibold text-gray-800 text-sm">{{ $comment->user->name }}</span>
                    <span class="ml-2 text-xs text-gray-500">{{ $comment->created_at }}</span>

                    @auth
                        @if(auth()->id() === $comment->user_id)
                            <form action="{{ route('comments.destroy', $comment) }}" method="post" class="ml-auto"
                                  onsubmit="return confirm('確定刪除這則留言？');">
                                @csrf
                                @method('delete')
                                <button type="submit" class="text-red-500 text-xs hover:underline">
                                    刪除
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>

                {{-- 留言內容 --}}
                <p class="text-gray-700 whitespace-pre-wrap mb-2 text-sm leading-relaxed">{{ $comment->content }}</p>

                {{-- 留言點讚 --}}
                <div>
                    @auth
                        @if($comment->likedBy(auth()->user()))
                            <form action="{{ route('comments.unlike', $comment) }}" method="post" class="inline">
                                @csrf
                                @method('delete')
                                <button type="submit" class="text-xs px-2 py-1 rounded bg-red-500 text-white hover:bg-red-600">
                                    ♥ {{ $comment->likes()->count() }}
                                </button>
                            </form>
                        @else
                            <form action="{{ route('comments.like', $comment) }}" method="post" class="inline">
                                @csrf
                                <button type="submit" class="text-xs px-2 py-1 rounded bg-white border border-red-400 text-red-500 hover:bg-red-50">
                                    ♡ {{ $comment->likes()->count() }}
                                </button>
                            </form>
                        @endif
                    @else
                        <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-500">
                            ♡ {{ $comment->likes()->count() }}
                        </span>
                    @endauth
                </div>
            </div>
        @empty
            <p class="text-gray-500 italic text-sm py-2">還沒有留言，搶頭香！</p>
        @endforelse

        {{-- 發新留言 --}}
        <div class="mt-6 pt-4 border-t border-gray-200">
            @auth
                @if($errors->any())
                    <div class="mb-3 p-3 bg-red-100 border border-red-300 text-red-700 rounded text-sm">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('comments.store', $article) }}" method="post">
                    @csrf
                    <textarea name="content"
                              rows="3"
                              maxlength="2000"
                              placeholder="寫下你的留言…"
                              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent">{{ old('content') }}</textarea>
                    <div class="mt-2 flex justify-end">
                        <button type="submit" class="px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600 text-sm">
                            送出留言
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-4 text-gray-600 text-sm bg-gray-50 rounded">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">登入</a>
                    後即可留言
                </div>
            @endauth
        </div>
    </section>

</div>
@endsection
