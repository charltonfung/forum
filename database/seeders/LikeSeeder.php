<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * 給每篇文章 / 每則留言隨機塞 0-3 個讚
     * 用 firstOrCreate 確保不撞 UNIQUE KEY（同一人不會讚兩次）
     */
    public function run()
    {
        $userIds = [1, 2, 3];

        // 文章讚
        foreach (Article::all() as $article) {
            $likers = collect($userIds)->shuffle()->take(rand(0, 3));
            foreach ($likers as $userId) {
                ArticleLike::firstOrCreate([
                    'article_id' => $article->id,
                    'user_id'    => $userId,
                ]);
            }
        }

        // 留言讚
        foreach (Comment::all() as $comment) {
            $likers = collect($userIds)->shuffle()->take(rand(0, 2));
            foreach ($likers as $userId) {
                CommentLike::firstOrCreate([
                    'comment_id' => $comment->id,
                    'user_id'    => $userId,
                ]);
            }
        }
    }
}
