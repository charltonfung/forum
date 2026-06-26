{{-- ============================================================
    通用分頁元件
    ============================================================
    用法：@include('partials._pagination', ['paginator' => $articles])

    特性：
      - 永遠顯示（即使只有 1 頁、或 0 筆資料），跟 Laravel 預設 links() 行為不同
      - 顯示「共 N 筆、第 X / Y 頁」資訊
      - 上一頁 / 下一頁 按鈕，到頂 / 到底時變灰色 disabled
    ============================================================ --}}
<div class="px-4 py-3 border-t border-gray-200 flex items-center justify-between text-sm">
    <div class="text-gray-500">
        共 {{ $paginator->total() }} 筆，第 {{ $paginator->currentPage() }} / {{ max($paginator->lastPage(), 1) }} 頁
    </div>

    <div class="flex items-center" style="gap: 4px;">
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 rounded bg-gray-100 text-gray-400 cursor-not-allowed">← 上一頁</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="px-3 py-1 rounded bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                ← 上一頁
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="px-3 py-1 rounded bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                下一頁 →
            </a>
        @else
            <span class="px-3 py-1 rounded bg-gray-100 text-gray-400 cursor-not-allowed">下一頁 →</span>
        @endif
    </div>
</div>
