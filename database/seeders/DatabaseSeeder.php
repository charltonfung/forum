<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * 一次跑完所有 seeder
     * 順序很重要：user → article → comment → like（後者依賴前者）
     *
     * 執行：php artisan db:seed
     * 全重來：php artisan migrate:fresh --seed
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ArticleSeeder::class,
            CommentSeeder::class,
            LikeSeeder::class,
        ]);
    }
}
