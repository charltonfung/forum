<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * 給文章塞一些留言，每篇 0-4 則，分散在 3 個 user
     */
    public function run()
    {
        $commentPool = [
            '推一個！',
            '哈哈太實用了',
            '感謝分享，學到新東西',
            '同感，我也有類似經驗',
            '請問可以再詳細一點嗎？',
            '已經試過了，真的有效',
            '收藏起來',
            '完全同意這個觀點',
            '原來如此，難怪',
            '+1 持續關注',
            '這個我之前也踩過坑',
            '推推',
            '我也想知道答案',
            '不錯不錯',
            '寫得很清楚',
        ];

        $articles = Article::all();

        foreach ($articles as $article) {
            // 每篇 0-4 則留言
            $commentCount = rand(0, 4);
            for ($i = 0; $i < $commentCount; $i++) {
                $authorId = rand(1, 3);
                // 留言時間在文章發表之後、現在之前
                $createdAt = $article->created_at->copy()
                    ->addMinutes(rand(30, 60 * 24 * 3));   // 文章發表後 30 分鐘 ~ 3 天內
                if ($createdAt->isFuture()) {
                    $createdAt = now()->subMinutes(rand(1, 60));
                }

                Comment::create([
                    'article_id' => $article->id,
                    'user_id'    => $authorId,
                    'content'    => $commentPool[array_rand($commentPool)],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}
