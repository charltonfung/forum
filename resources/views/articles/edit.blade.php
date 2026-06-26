@extends('layouts.article')

@section('main')
<div class="max-w-3xl mx-auto">
    {{-- 返回連結 --}}
    <div class="mb-4">
        <a href="{{ route('articles.show', $article) }}" class="text-gray-700 hover:text-gray-900 text-sm">
            ← 回文章
        </a>
    </div>

    {{-- 主卡片 --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">編輯文章</h1>

        {{-- 驗證錯誤 --}}
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('articles.update', $article) }}" method="post">
            @csrf
            @method('patch')

            {{-- 標題：old() 優先，沒有再 fallback 原值（驗證失敗時不會被吃掉使用者輸入） --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">標題</label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $article->title) }}"
                       maxlength="255"
                       autofocus
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
            </div>

            {{-- 內文 --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    內文 <span class="text-gray-500 font-normal text-xs">（至少 10 字）</span>
                </label>
                <textarea name="content"
                          rows="12"
                          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">{{ old('content', $article->content) }}</textarea>
            </div>

            {{-- 按鈕列 --}}
            <div class="flex items-center justify-end" style="gap: 8px;">
                <a href="{{ route('articles.show', $article) }}"
                   class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">
                    取消
                </a>
                <button type="submit"
                        class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">
                    更新文章
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
