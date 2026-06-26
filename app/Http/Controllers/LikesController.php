<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\Comment;
use App\Models\CommentLike;

/**
 * 點讚 Controller
 * ============================================================
 * 4 個 action：對 article / comment 各一組 like / unlike。
 *
 *   POST   /articles/{article}/like  → likeArticle
 *   DELETE /articles/{article}/like  → unlikeArticle
 *   POST   /comments/{comment}/like  → likeComment
 *   DELETE /comments/{comment}/like  → unlikeComment
 *
 * 都是 idempotent：
 *   - like 用 firstOrCreate：已讚過直接回現有 row，不會撞 UNIQUE KEY
 *   - unlike 用 where + delete：沒讚過 delete 0 列也算成功
 *
 * 全部用 form POST + redirect()->back()，回到原頁面（沿用本專案風格，不走 AJAX）。
 */
class LikesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ============================================================
    // Article
    // ============================================================
    public function likeArticle(Article $article)
    {
        ArticleLike::firstOrCreate([
            'article_id' => $article->id,
            'user_id'    => auth()->id(),
        ]);
        return back();
    }

    public function unlikeArticle(Article $article)
    {
        ArticleLike::where('article_id', $article->id)
            ->where('user_id', auth()->id())
            ->delete();
        return back();
    }

    // ============================================================
    // Comment
    // ============================================================
    public function likeComment(Comment $comment)
    {
        CommentLike::firstOrCreate([
            'comment_id' => $comment->id,
            'user_id'    => auth()->id(),
        ]);
        return back();
    }

    public function unlikeComment(Comment $comment)
    {
        CommentLike::where('comment_id', $comment->id)
            ->where('user_id', auth()->id())
            ->delete();
        return back();
    }
}
