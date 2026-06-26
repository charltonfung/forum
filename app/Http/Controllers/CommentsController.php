<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

/**
 * 留言 Controller
 * ============================================================
 * 沿用 ArticlesController 的風格：純 form POST + redirect + session flash。
 *
 *   POST   /articles/{article}/comments  → store
 *   DELETE /comments/{comment}           → destroy
 *
 * 列表（list）不另外做：留言已嵌在 articles/show.blade 內透過 $article->comments 直接取。
 */
class CommentsController extends Controller
{
    public function __construct()
    {
        // 留言必須登入
        $this->middleware('auth');
    }

    public function store(Request $request, Article $article)
    {
        $data = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $article->comments()->create([
            'content' => $data['content'],
            'user_id' => auth()->id(),
        ]);

        // 回到文章內頁 + flash 訊息
        return redirect()
            ->route('articles.show', $article)
            ->with('notice', '留言成功！');
    }

    public function destroy(Comment $comment)
    {
        // 權限檢查：只有留言作者可刪
        if ($comment->user_id !== auth()->id()) {
            abort(403, '沒有權限刪除此留言');
        }

        $articleId = $comment->article_id;
        $comment->delete();  // SoftDeletes 自動處理

        return redirect()
            ->route('articles.show', $articleId)
            ->with('notice', '留言已刪除！');
    }
}
